<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = Video::factory(1000)->create();

        // Category
        $categoryIds = Category::all()->pluck('id');

        foreach ($videos as $video) {
            // Assign 1–3 random categories per video
            $video->categories()->attach(
                $categoryIds->random(rand(1, 3))->toArray()
            );
        }
    }
}
