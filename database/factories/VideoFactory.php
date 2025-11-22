<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $thumbnail = '/images/' . fake()->numberBetween(1, 15) . '.webp';
        // $filename = '/images/' . fake()->numberBetween(1, 2) . '.mp4';

        return [
            // House Info
            'title' => fake()->jobTitle(),
            'thumbnail' => $thumbnail,
            'external_link' => 'https://cdn.videy.co/6iMolLRA1.mp4',
            'status' => fake()->randomElement(['published', 'draft']),
            'user_id' => 1, // admin

            // Tracking
            'views_count' => fake()->numberBetween(30000, 2000000),

            // Date
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
