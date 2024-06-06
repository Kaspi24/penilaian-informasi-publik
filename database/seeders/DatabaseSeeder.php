<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WorkUnitSeeder::class,
            QuestionSeeder::class,
            QuestionChildrenSeeder::class
        ]);

        User::factory()->create([
            'username'      => 'superadmin',
            'name'          => 'Admin',
            'work_unit_id'  => 1,
            'email'         => 'admin@example.com',
            'role'          => 'ADMIN',
        ]);
        User::factory()->create([
            'username'      => 'juri1',
            'name'          => 'Juri I',
            'work_unit_id'  => 1,
            'email'         => 'juri1@example.com',
            'role'          => 'JURY',
        ]);
        User::factory()->create([
            'username'      => 'juri2',
            'name'          => 'Juri II',
            'work_unit_id'  => 1,
            'email'         => 'juri2@example.com',
            'role'          => 'JURY',
        ]);
        User::factory()->create([
            'username'      => 'juri3',
            'name'          => 'Juri III',
            'work_unit_id'  => 1,
            'email'         => 'juri3@example.com',
            'role'          => 'JURY',
        ]);
    }
}
