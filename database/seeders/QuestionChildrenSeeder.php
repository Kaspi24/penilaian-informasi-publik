<?php

namespace Database\Seeders;

use App\Models\QuestionChildren;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class QuestionChildrenSeeder extends Seeder
{
    public function run(): void
    {
        $question_childrens = File::get(database_path('json/questions_children.json'));
        $question_childrens = json_decode($question_childrens);

        foreach ($question_childrens as $question_child) {
            QuestionChildren::updateOrCreate(
                [
                    'id'            => $question_child->id
                ],
                [   
                    "question_id"   => $question_child->question_id,
                    "text"          => $question_child->text
                ]
            );
        }
    }
}
