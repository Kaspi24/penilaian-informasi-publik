<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WorkUnitSeeder::class,
            QuestionSeeder::class,
            QuestionChildrenSeeder::class,
            UserSeeder::class,
            LandingPageContentSeeder::class
        ]);
    }
}
