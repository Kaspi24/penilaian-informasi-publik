<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index()
    {
        $indicators = new Collection();
        $all_indicators = Question::select(DB::raw('DISTINCT(nth_indicator) AS indicator'))->pluck('indicator')->toArray();
        
        foreach ($all_indicators as $indicator) {
            $all_questions = Question::with('children')->where('nth_indicator',$indicator)->get();
            $indicators["INDIKATOR ".$indicator] = $all_questions->groupBy('category');
        }
        
        return view('pages.admin.question.index', compact('indicators'));
    }
}
