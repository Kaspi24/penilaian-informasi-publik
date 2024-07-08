<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\RespondentScore;
use App\Models\QuestionChildren;
use App\Models\RespondentAnswer;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SetJuryRequest;
use App\Models\RespondentAnswerChildren;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Collection;

class QuestionnaireController extends Controller
{
    public function load_respondent_question_answers($respondent_id)
    {
        $indicators = new Collection();
        $all_indicators = Question::select(DB::raw('DISTINCT(nth_indicator) AS indicator'))->pluck('indicator')->toArray();
        foreach ($all_indicators as $indicator) {
            $all_questions = Question::query()
                ->select(
                    'questions.id                   AS id',
                    'questions.*',
                    'respondent_answer.id               AS respondent_answer_id',
                    'respondent_answer.answer           AS answer',
                    'respondent_answer.attachment       AS attachment',
                    'respondent_answer.score            AS score',
                    'respondent_answer.updated_by       AS updated_by',
                    'respondent_answer.updated_by_name  AS updated_by_name'
                )
                ->with(['children' => function ($q) use ($respondent_id) {
                    $q->leftJoin('respondent_answer_children', function ($join) use ($respondent_id){
                        $join->on('question_children.id', '=', 'respondent_answer_children.question_children_id')
                            ->where('respondent_answer_children.respondent_id', '=', $respondent_id);
                    });
                }])
                ->leftJoin('respondent_answer', function ($join) use ($respondent_id) {
                    $join->on('questions.id', '=', 'respondent_answer.question_id')
                        ->where('respondent_answer.respondent_id', '=', $respondent_id);
                })
                ->where('questions.nth_indicator',$indicator)
                ->get();
            $indicators["INDIKATOR ".$indicator] = $all_questions->groupBy('category');
        }
        return $indicators;
    }

    public function index()
    {
        $user = Auth::user();
        /* RESPONDENT */
        if ($user->role === "RESPONDENT") {
            $submission     = RespondentScore::firstWhere('respondent_id',$user->id);
            $questions      = Question::with('children')->get();
            $answered_count = 0;
            foreach ($questions as $question) {
                if ($question->children->count() > 0) {
                    $answered_children_count = RespondentAnswerChildren::query()
                        ->where('respondent_id',$user->id)
                        ->whereIn('question_children_id',$question->children->pluck('id')->toArray())
                        ->whereNotNull('answer')
                        ->count();
                    ($answered_children_count === $question->children->count()) && $answered_count++;
                } else {
                    $respondent_question = RespondentAnswer::where('respondent_id',$user->id)->where('question_id',$question->id)->first(); 
                    ($respondent_question->answer !== null) && $answered_count++;
                }
            }
            return view('pages.questionnaire.index',
                compact(
                    'submission',
                    'questions',
                    'answered_count'
                ));
        }
        /* JURY */ 
        else if ($user->role === "JURY") {
            $juries         = User::where('role','JURY')->get();
            return view('pages.questionnaire.jury-index',compact('juries'));
        }
        /* ADMIN */  
        else if ($user->role === "SUPERADMIN" || $user->role === "ADMIN") {
            $juries         = User::where('role','JURY')->get();
            return view('pages.questionnaire.admin-index', compact('juries'));
        } else {
            abort(404);
        }
    }

    public function start()
    {
        $indicators = $this->load_respondent_question_answers(Auth::user()->id);
        return view('pages.questionnaire.start',
            compact(
                'indicators'
            ));
    }

    public function updateAnswer(Request $request)
    {
        $respondent_answer  = RespondentAnswer::query()
            ->where('question_id', $request->question_id)
            ->where('respondent_id', Auth::user()->id)
            ->first();
        if($respondent_answer) {
            try {
                if ($request->answer == 1 || $request->answer == true) {
                    $respondent_answer->update([
                        'answer'        => 1,
                        'attachment'    => $request->attachment
                    ]);
                } else {
                    $respondent_answer->update([
                        'answer'        => 0,
                        'attachment'    => null
                    ]);
                }
            } catch (\Exception $exception) {
                return Response::json($exception);
            } catch (\Error $error) {
                return Response::json($error);
            }
            $reloaded_questions = $this->load_respondent_question_answers(Auth::user()->id);
            return Response::json($reloaded_questions);
        }
    }

    public function updateAnswerChild(Request $request)
    {
        $respondent_answer_child  = RespondentAnswerChildren::query()
            ->where('question_children_id', $request->question_id)
            ->where('respondent_id', Auth::user()->id)
            ->first();
        
        if($respondent_answer_child) {
            try {
                if ($request->answer == 1 || $request->answer == true) {
                    $respondent_answer_child->update([
                        'answer'        => 1,
                        'attachment'    => $request->attachment
                    ]);
                } else {
                    $respondent_answer_child->update([
                        'answer'        => 0,
                        'attachment'    => null
                    ]);
                }
            } catch (\Exception $exception) {
                return Response::json($exception);
            } catch (\Error $error) {
                return Response::json($error);
            }
            $reloaded_questions = $this->load_respondent_question_answers(Auth::user()->id);
            return Response::json($reloaded_questions);
        }
    }

    public function submitResponse(Request $request)
    {
        $admin      = User::firstWhere('role','ADMIN');
        $questions  = Question::with('children')->get();
        foreach ($questions as $question) {
            if($question->children->count() === 0) {
                $respondent_answer = RespondentAnswer::query()
                    ->where('respondent_id', Auth::user()->id)
                    ->where('question_id', $question->id)
                    ->first();
                if(!$respondent_answer->answer) {
                    $respondent_answer->update([
                        'answer'            => 0,
                        'updated_by'        => $admin->id,
                        'updated_by_name'   => $admin->name
                    ]);
                } else if ($respondent_answer->answer === 0) {
                    $respondent_answer->update([
                        'updated_by'        => $admin->id,
                        'updated_by_name'   => $admin->name
                    ]);
                }
            } else {
                $all_empty = true;
                foreach ($question->children as $question_child) {
                    $respondent_answer_child = RespondentAnswerChildren::query()
                        ->where('respondent_id', Auth::user()->id)
                        ->where('question_children_id', $question_child->id)
                        ->first();
                    if(!$respondent_answer_child->answer) {
                        $respondent_answer_child->update(['answer' => 0]);
                    } else {
                        $all_empty = false;
                    }
                }
                if($all_empty) {
                    $respondent_answer = RespondentAnswer::query()
                        ->where('respondent_id', Auth::user()->id)
                        ->where('question_id', $question->id)
                        ->first();
                    $respondent_answer->update([
                        'updated_by'        => $admin->id,
                        'updated_by_name'   => $admin->name
                    ]);
                }
            }
        }
        $submission     = RespondentScore::firstWhere('respondent_id',Auth::user()->id);
        $submission->update(['is_done_filling' => true]);
        return Redirect::route('questionnaire.index')->with('success', 'Tanggapan anda telah dikirim!');
    }

    public function evaluate($respondent_id)
    {
        $respondent = User::with(['work_unit','answers'])->firstWhere('id',$respondent_id);
        $indicators = $this->load_respondent_question_answers($respondent_id);
        foreach ($indicators as $indicator => $categories) {
            foreach ($categories as $category => $questions) {
                foreach ($questions as $question) {
                    $question->audits = Audit::query()
                        ->select('audits.*','users.name')
                        ->leftJoin('users', 'audits.user_id','=','users.id')
                        ->where('auditable_id',$question->respondent_answer_id)
                        ->whereNot('user_id',$respondent_id)
                        ->get();
                }
            }
        }
        // dd($indicators["INDIKATOR I"]["PPID"][0]);
        $submission = RespondentScore::firstWhere('respondent_id',$respondent_id);
        $juries         = User::where('role','JURY')->get();
        if($submission->is_done_filling && (Auth::user()->role === 'SUPERADMIN' || Auth::user()->role === 'ADMIN' || (Auth::user()->role === 'JURY' && $submission->jury_id === Auth::user()->id))){
            return view('pages.questionnaire.evaluate', compact('respondent', 'indicators', 'submission', 'juries'));
        } else {
            if (Auth::user()->role === 'SUPERADMIN' || Auth::user()->role === 'ADMIN' || (Auth::user()->role === 'JURY' && $submission->jury_id === Auth::user()->id)) {
                return Redirect::route('questionnaire.index')->with('failed', 'Tanggapan repsonden yang coba anda nilai belum selesai/dikirimkan!');
            } else {
                abort(404);
            }
        }
    }
    
    public function setJury(Request $request, $respondent_id)
    {
        $submission = RespondentScore::firstWhere('respondent_id', $respondent_id);
        if($submission) {
            try {
                $submission->update([
                    'jury_id' => $request->jury_id
                ]);
            } catch (\Exception $exception) {
                return Response::json($exception);
            } catch (\Error $error) {
                return Response::json($error);
            }
            return Response::json([
                '200'   => 'OKE',
                'RESP'  =>  $respondent_id,
                'JURY'  =>  $request->jury_id
            ]);
        }
    }

    public function updateScore(Request $request, $respondent_id)
    {
        $respondent_answer  = RespondentAnswer::query()
            ->where('question_id', $request->question_id)
            ->where('respondent_id', $respondent_id)
            ->first();

        if($respondent_answer) {
            try {
                $respondent_answer->update([
                    'score'             => $request->score,
                    'updated_by'        => Auth::user()->id,
                    'updated_by_name'   => Auth::user()->name,
                ]);
            } catch (\Exception $exception) {
                return Response::json($exception);
            } catch (\Error $error) {
                return Response::json($error);
            }
            $reloaded_questions = $this->load_respondent_question_answers($respondent_id);
            return Response::json($reloaded_questions);
        }
    }

    public function submitScore(Request $request, $respondent_id)
    {
        $respondent     = User::with(['work_unit','answers'])->firstWhere('id',$respondent_id);
        $indicators     = $this->load_respondent_question_answers($respondent_id);
        $submission     = RespondentScore::firstWhere('respondent_id',$respondent_id);        
        $total_score    = 0;

        foreach ($indicators as $indicator => $categories) {
            foreach ($categories as $category => $questions) {
                foreach ($questions as $question) {
                    if ($question->updated_by) {
                        $total_score += $question->score;
                    } else {
                        $respondent_answer = RespondentAnswer::query()
                            ->where('question_id',$question->id)
                            ->where('respondent_id',$respondent->id)
                            ->first();
                        $respondent_answer->update([
                            'score'             => $question->good_enough,
                            'updated_by'        => Auth::user()->id,
                            'updated_by_name'   => Auth::user()->name,
                        ]);
                        $total_score += $question->good_enough;
                    }
                }
            }
        }
        $submission->update([
            'total_score'       => $total_score,
            'updated_by'        => Auth::user()->id,
            'updated_by_name'   => Auth::user()->name,
            'is_done_scoring'   => true
        ]);
        return Redirect::route('questionnaire.index')->with('success', 'Penilaian telah disimpan!');
    }

    public function showScore($respondent_id)
    {
        $respondent = User::find($respondent_id);
        $submission = RespondentScore::firstWhere('respondent_id',$respondent->id);
        $indicators = $this->load_respondent_question_answers($respondent->id);

        if(
            (Auth::user()->role === 'RESPONDENT' && Auth::user()->id === $respondent->id) 
            ||
            (Auth::user()->role === 'JURY' && Auth::user()->id === $submission->jury_id)
            || 
            Auth::user()->role === 'SUPERADMIN' 
            ||
            Auth::user()->role === 'ADMIN'
        ) {
            return view('pages.questionnaire.show', compact('respondent', 'indicators', 'submission'));
        } else {
            abort(404);
        }
    }
}