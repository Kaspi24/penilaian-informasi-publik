<?php

namespace Database\Seeders;

use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkUnitSeeder extends Seeder
{
    public function run(): void
    {
        $work_units = File::get(database_path('json/work_units.json'));
        $work_units = json_decode($work_units);

        foreach ($work_units as $work_unit) {
            WorkUnit::updateOrCreate(
            [
                'id' => $work_unit->id
            ],
            [   
                'name'      => $work_unit->name,
                'category'  => $work_unit->category
            ]);
        }
    }
}
