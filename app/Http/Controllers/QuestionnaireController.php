<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\RespondentAnswer;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questions = Question::with('children')->get();
        $respondent_answer_count   = RespondentAnswer::query()
            ->where('respondent_id', Auth::user()->id)
            ->whereNotNull('answer')
            ->count();

        // dd($respondent_answer_count);
        // dd($questions);
        return view('pages.questionnaire.index');
    }
}
