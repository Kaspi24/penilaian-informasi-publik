<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Question;
use App\Models\WorkUnit;
use App\Models\RespondentScore;
use Illuminate\Database\Seeder;
use App\Models\RespondentAnswer;
use App\Models\RespondentAnswerChildren;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'username'      => 'superadmin',
            'name'          => 'Admin',
            'work_unit_id'  => 1,
            'email'         => 'admin@example.com',
            'role'          => UserRole::ADMIN,
        ]);
        User::factory()->create([
            'username'      => 'juri1',
            'name'          => 'Juri I',
            'work_unit_id'  => 1,
            'email'         => 'juri1@example.com',
            'role'          => UserRole::JURY,
        ]);
        User::factory()->create([
            'username'      => 'juri2',
            'name'          => 'Juri II',
            'work_unit_id'  => 1,
            'email'         => 'juri2@example.com',
            'role'          => UserRole::JURY,
        ]);
        User::factory()->create([
            'username'      => 'juri3',
            'name'          => 'Juri III',
            'work_unit_id'  => 1,
            'email'         => 'juri3@example.com',
            'role'          => UserRole::JURY,
        ]);
        
        $work_units = WorkUnit::whereNot('category','PUSAT')->get();
        $questions  = Question::with('children')->get();

        foreach ($work_units as $work_unit) {
            $number = rand(0,1);
            $user = User::factory()->create([
                'username'      => fake()->userName(),
                'work_unit_id'  => $work_unit->id,
                'role'          => UserRole::RESPONDENT,
                'email'         => fake()->safeEmail(),
                'name'          => $number === 0 ? null : fake()->name(),
                'phone'         => $number === 0 ? null : fake()->numerify('+6281 ##-###-####'),
                'whatsapp'      => $number === 0 ? null : fake()->numerify('+6281 ##-###-####'),
            ]);

            if ($number === 1) {
                $work_unit->update([   
                    'head_name' => fake()->name(),
                    'email'     => fake()->safeEmail(),
                    'phone'     => fake()->numerify('+6281 ##-###-####')
                ]);
            }
            
            foreach ($questions as $question) {
                $respondent_answer = RespondentAnswer::create([
                    'respondent_id' => $user->id,
                    'question_id'   => $question->id
                ]);
                
                foreach ($question->children as $question_child) {
                    RespondentAnswerChildren::create([
                        'respondent_answer_id'  => $respondent_answer->id,
                        'respondent_id'         => $user->id,
                        'question_children_id'  => $question_child->id,
                        'question_id'           => $question->id
                    ]);
                }
            }
            RespondentScore::create([
                'respondent_id'         => $user->id
            ]);
        }
    }
}
