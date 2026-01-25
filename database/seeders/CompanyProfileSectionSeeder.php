<?php

namespace Database\Seeders;

use App\Models\CompanyProfileSection;
use Illuminate\Database\Seeder;

class CompanyProfileSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['slug' => 'home', 'title' => 'Home'],
            ['slug' => 'about', 'title' => 'About'],
            ['slug' => 'services', 'title' => 'Services'],
            ['slug' => 'news', 'title' => 'News'],
            ['slug' => 'team', 'title' => 'Team'],
        ];

        foreach ($sections as $section) {
            CompanyProfileSection::firstOrCreate(
                ['slug' => $section['slug']],
                ['title' => $section['title']]
            );
        }
    }
}
