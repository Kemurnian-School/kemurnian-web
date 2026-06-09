<?php

namespace Database\Factories;

use App\Models\Link;
use App\Enums\SchoolGroup;
use App\Enums\SchoolLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $level = fake()->randomElement(SchoolLevel::cases());
        $group = fake()->randomElement(SchoolGroup::cases());

        return [
            'name' => 'Admin WA ' . strtoupper($level->value),
            'url' => 'https://wa.me/628' . fake()->numerify('#########'),
            'school_group' => $group,
            'school_level' => $level,
        ];
    }
}
