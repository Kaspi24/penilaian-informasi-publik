<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionChildren;
use Illuminate\Http\Request;
use App\Models\RespondentAnswer;
use App\Models\RespondentAnswerChildren;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Collection;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questions = Question::with('children')->get();
        $answer_count   = RespondentAnswer::query()
            ->where('respondent_id', Auth::user()->id)
            ->whereNotNull('answer')
            ->count();
        
        return view('pages.questionnaire.index',
            compact(
                'answer_count'
            ));
    }

    public function start()
    {
        $indicators = new Collection();
        $all_indicators = Question::select(DB::raw('DISTINCT(nth_indicator) AS indicator'))->pluck('indicator')->toArray();
        foreach ($all_indicators as $indicator) {
            $all_questions = Question::query()
                ->leftJoin('respondent_answer', function ($join) {
                    $join->on('questions.id', '=', 'respondent_answer.question_id')
                        ->where('respondent_answer.respondent_id', '=', Auth::user()->id);
                })
                ->with(['children' => function ($q) {
                    $q->leftJoin('respondent_answer_children', function ($join) {
                        $join->on('question_children.id', '=', 'respondent_answer_children.question_children_id')
                            ->where('respondent_answer_children.respondent_id', '=', Auth::user()->id);
                    });
                }])
                ->where('questions.nth_indicator',$indicator)
                ->get();
            $indicators["INDIKATOR ".$indicator] = $all_questions->groupBy('category');
        }
        // dd($indicators["INDIKATOR II"]["DIUMUMKAN BERKALA"]);
        return view('pages.questionnaire.start',
            compact(
                'indicators'
            ));
    }

    public function reload_questions()
    {
        $indicators = new Collection();
        $all_indicators = Question::select(DB::raw('DISTINCT(nth_indicator) AS indicator'))->pluck('indicator')->toArray();
        foreach ($all_indicators as $indicator) {
            $all_questions = Question::query()
                ->leftJoin('respondent_answer', function ($join) {
                    $join->on('questions.id', '=', 'respondent_answer.question_id')
                        ->where('respondent_answer.respondent_id', '=', Auth::user()->id);
                })
                ->with(['children' => function ($q) {
                    $q->leftJoin('respondent_answer_children', function ($join) {
                        $join->on('question_children.id', '=', 'respondent_answer_children.question_children_id')
                            ->where('respondent_answer_children.respondent_id', '=', Auth::user()->id);
                    });
                }])
                ->where('questions.nth_indicator',$indicator)
                ->get();
            $indicators["INDIKATOR ".$indicator] = $all_questions->groupBy('category');
        }
        return $indicators;
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
            $reloaded_questions = $this->reload_questions();
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
            $reloaded_questions = $this->reload_questions();
            return Response::json($reloaded_questions);
        }
    }
}
