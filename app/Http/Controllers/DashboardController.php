<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\RespondentScore;
use App\Models\RespondentAnswer;
use Illuminate\Support\Facades\Auth;
use App\Models\RespondentAnswerChildren;
use App\Models\WorkUnit;

class DashboardController extends Controller
{
    public function getProcessedData($id_arr = [])
    {
        $units = WorkUnit::query()
            ->with('user.score')
            ->when(count($id_arr) > 0, function($query) use ($id_arr) {
                $query->whereIn('id', $id_arr);
            })
            ->whereNot('category','PUSAT')
            ->get();

        $units_count               = $units->count();
        $units_count_by_category   = $units->countBy('category');

        $registered  = $units->filter(function ($unit) {
            return $unit->user->count() > 0;
        });
        $registered_count               = $registered->count();
        $registered_count_by_category   = $registered->countBy('category');
        
        $submitted  = $registered->filter(function ($unit) {
            return $unit->user->first()->score->is_done_filling === 1;
        });
        $submitted_count                = $submitted->count();
        $submitted_count_by_category    = $submitted->countBy('category');

        $scored  = $submitted->filter(function ($unit) {
            return $unit->user->first()->score->is_done_scoring === 1;
        });
        $scored_count               = $scored->count();
        $scored_count_by_category   = $scored->countBy('category');

        $processed_data = [
            'units_count'                   => $units_count,
            'units_count_by_category'       => $units_count_by_category,

            'registered'                    => $registered,
            'registered_count'              => $registered_count,
            'registered_count_by_category'  => $registered_count_by_category,

            'submitted'                     => $submitted,
            'submitted_count'               => $submitted_count,
            'submitted_count_by_category'   => $submitted_count_by_category,

            'scored'                        => $scored,
            'scored_count'                  => $scored_count,
            'scored_count_by_category'      => $scored_count_by_category,
        ];

        return $processed_data;
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
            return view('pages.dashboard.respondent',
                compact(
                    'submission',
                    'questions',
                    'answered_count'
                ));
        }
        /* JURY */ 
        else if ($user->role === "JURY") {
            $responses      = RespondentScore::with('respondent.work_unit')->where('jury_id', Auth::user()->id)->get();
            $work_units     = $responses->pluck('respondent.work_unit_id')->toArray();
            $processed_data = $this->getProcessedData($work_units);
            return view('pages.dashboard.jury',compact('processed_data', 'work_units'));
        }
        /* ADMIN */  
        else if ($user->role === "ADMIN") {
            $highest_score_responses = RespondentScore::query()
                ->with('respondent.work_unit')
                ->orderByDesc('total_score')
                ->limit(5)
                ->get();

            $processed_data = $this->getProcessedData();
            // dd($processed_data);
            
            return view('pages.dashboard.admin', compact('processed_data','highest_score_responses'));
        } else {
            abort(404);
        }
    }
}
