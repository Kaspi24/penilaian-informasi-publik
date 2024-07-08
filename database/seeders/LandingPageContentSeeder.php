<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageContent;
use Illuminate\Support\Facades\File;

class LandingPageContentSeeder extends Seeder
{
    public function run(): void
    {

        LandingPageContent::create([   
            'year'          => 2024,
            'name'          => 'Beranda',
            'nth_sequence'  => 1,
            'image'         => 'Landing-Page-Contents/1.jpg',
            'is_visible'    => true,
        ]);
        LandingPageContent::create([   
            'year'          => 2024,
            'name'          => 'Tentang',
            'nth_sequence'  => 2,
            'image'         => 'Landing-Page-Contents/2.jpg',
            'is_visible'    => true,
        ]);
        LandingPageContent::create([   
            'year'          => 2024,
            'name'          => 'Jadwal',
            'nth_sequence'  => 3,
            'image'         => 'Landing-Page-Contents/3.jpg',
            'is_visible'    => true,
        ]);
        LandingPageContent::create([   
            'year'          => 2024,
            'name'          => 'Penilaian',
            'nth_sequence'  => 4,
            'image'         => 'Landing-Page-Contents/4.jpg',
            'is_visible'    => true,
        ]);
    }
}
