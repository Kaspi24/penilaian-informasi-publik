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
        // $questions = Question::query()
        //     ->leftJoin('respondent_answer', function ($join) {
        //         $join->on('questions.id', '=', 'respondent_answer.question_id')
        //             ->where('respondent_answer.respondent_id', '=', Auth::user()->id);
        //     })
        //     // ->with(['children' => function ($q) {
        //     //     $q->leftJoin('respondent_answer_children', function ($join) {
        //     //         $join->on('question_children.id', '=', 'respondent_answer_children.question_children_id')
        //     //             ->where('respondent_answer_children.respondent_id', '=', Auth::user()->id);
        //     //     });
        //     // }])
        //     ->where('questions.nth_indicator','I')
        //     ->where('questions.category','PELAPORAN')
        //     ->get();
        // $questions->load(['children' => function ($q) {
        //     $q->leftJoin('respondent_answer_children', function ($join) {
        //         $join->on('question_children.id', '=', 'respondent_answer_children.question_children_id')
        //             ->where('respondent_answer_children.respondent_id', '=', Auth::user()->id);
        //     });
        // }]);
        // dd($questions);

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

            // $all_questions = Question::query()
            //     ->select(
            //         // 'questions.*',
            //         'questions.id                   as id',
            //         'questions.nth_indicator        as nth_indicator',
            //         'questions.category             as category',
            //         'questions.text                 as text',
            //         'questions.details              as details',
            //         'respondent_answer.answer       as answer',
            //         'respondent_answer.attachment   as attachment'
            //     )
            //     ->leftJoin('respondent_answer', function ($join) {
            //         $join->on('questions.id', '=', 'respondent_answer.question_id')
            //             ->where('respondent_answer.respondent_id', '=', Auth::user()->id);
            //     })
            //     ->with(['children' => function ($q) {
            //         $q->select(
            //                 'question_children.*',
            //                 'respondent_answer_children.answer       as child_answer',
            //                 'respondent_answer_children.attachment   as child_attachment'
            //             )
            //             ->leftJoin('respondent_answer_children', function ($join) {
            //             $join->on('question_children.id', '=', 'respondent_answer_children.question_children_id')
            //                 ->where('respondent_answer_children.respondent_id', '=', Auth::user()->id);
            //         });
            //     }])
            //     ->where('questions.nth_indicator',$indicator)
            //     ->get();

            
            $indicators["INDIKATOR ".$indicator] = $all_questions->groupBy('category');
        }
        $aaasdasdasd = Question::find(23);
        $asdasdasd = RespondentAnswerChildren::find(9);
        // dd($indicators["INDIKATOR II"]["DIUMUMKAN BERKALA"],$asdasdasd,$aaasdasdasd);
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
        return Response::json($respondent_answer);
    }

    public function updateAnswerChild(Request $request)
    {
        $respondent_answer_child  = RespondentAnswerChildren::query()
            ->where('question_children_id', $request->question_id)
            ->where('respondent_id', Auth::user()->id)
            ->first();
        
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
        return Response::json($respondent_answer_child);
    }
}
