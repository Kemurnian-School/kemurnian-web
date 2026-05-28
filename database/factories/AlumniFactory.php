<?php

namespace Database\Factories;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Universities;
use App\Models\JobTitles;

/**
 * @extends Factory<Alumni>
 */
class AlumniFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'graduation_year' => fake()->numberBetween(2000, 2024),
            'motto' => fake()->sentence(),
            'university_id' => Universities::inRandomOrder()->value('id'),
            'job_title_id' => JobTitles::inRandomOrder()->value('id'),
        ];
    }
}
