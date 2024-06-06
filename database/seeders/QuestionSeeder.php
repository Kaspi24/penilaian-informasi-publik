<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = File::get(database_path('json/questions.json'));
        $questions = json_decode($questions);

        foreach ($questions as $question) {
            Question::updateOrCreate(
                [
                    'id'            => $question->id
                ],
                [   
                    "text"          => $question->text,
                    "nth_indicator" => $question->nth_indicator,
                    "category"      => $question->category,
                    "details"       => $question->details,
                    "ref_text"      => $question->ref_text,
                    "ref_address"   => $question->ref_address,
                    "very_good"     => $question->very_good,
                    "good"          => $question->good,
                    "good_enough"   => $question->good_enough,
                    "less_good"     => $question->less_good
                ]
            );
        }
    }
}
