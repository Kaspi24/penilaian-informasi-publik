<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\RespondentScore;
use App\Models\QuestionChildren;
use App\Models\RespondentAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RespondentAnswerChildren;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Collection;

class QuestionnaireController extends Controller
{
    public function load_respondent_question_answers()
    {
        $indicators = new Collection();
        $all_indicators = Question::select(DB::raw('DISTINCT(nth_indicator) AS indicator'))->pluck('indicator')->toArray();
        foreach ($all_indicators as $indicator) {
            $all_questions = Question::query()
                ->select(
                    'questions.id                   AS id',
                    'questions.*',
                    'respondent_answer.answer       AS answer',
                    'respondent_answer.attachment   AS attachment',
                    'respondent_answer.score        AS score'
                )
                ->with(['children' => function ($q) {
                    $q->leftJoin('respondent_answer_children', function ($join) {
                        $join->on('question_children.id', '=', 'respondent_answer_children.question_children_id')
                            ->where('respondent_answer_children.respondent_id', '=', Auth::user()->id);
                    });
                }])
                ->leftJoin('respondent_answer', function ($join) {
                    $join->on('questions.id', '=', 'respondent_answer.question_id')
                        ->where('respondent_answer.respondent_id', '=', Auth::user()->id);
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
            return view('pages.questionnaire.jury-index');
        }
        /* ADMIN */  
        else if ($user->role === "ADMIN") {
            $juries         = User::where('role','JURY')->get();
            return view('pages.questionnaire.admin-index', compact('juries'));
        } else {
            abort(404);
        }
    }

    public function start()
    {
        $indicators = $this->load_respondent_question_answers();
        // dd($indicators["INDIKATOR II"]["DIUMUMKAN BERKALA"]);
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
            $reloaded_questions = $this->load_respondent_question_answers();
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
            $reloaded_questions = $this->load_respondent_question_answers();
            return Response::json($reloaded_questions);
        }
    }

    public function submitResponse(Request $request)
    {
        $questions = Question::with('children')->get();
        foreach ($questions as $question) {
            if($question->children->count() === 0) {
                $respondent_answer = RespondentAnswer::query()
                    ->where('respondent_id', Auth::user()->id)
                    ->where('question_id', $question->id)
                    ->first();
                if(!$respondent_answer->answer) {
                    $respondent_answer->update(['answer' => 0]);
                }
            } else {
                foreach ($question->children as $question_child) {
                    $respondent_answer_child = RespondentAnswerChildren::query()
                        ->where('respondent_id', Auth::user()->id)
                        ->where('question_children_id', $question_child->id)
                        ->first();
                    if(!$respondent_answer_child->answer) {
                        $respondent_answer_child->update(['answer' => 0]);
                    }
                }
            }
        }
        $submission     = RespondentScore::firstWhere('respondent_id',Auth::user()->id);
        $submission->update(['is_done_filling' => true]);
        return Redirect::route('questionnaire.index')->with('success', 'Tanggapan anda telah dikirim!');
    }
}
