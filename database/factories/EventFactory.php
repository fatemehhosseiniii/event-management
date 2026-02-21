<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $capacity = fake()->numberBetween(1, 100);
        
        return [
            'uuid' => Str::uuid(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(3),
            'capacity' => $capacity,
            'free_capacity' => $capacity,
            // start_date is today or 1-2 days before today
            'start_date' => $startDate = now()->subDays(fake()->numberBetween(0, 2))->setTime(
                fake()->numberBetween(0,23),
                fake()->numberBetween(0,59),
                fake()->numberBetween(0,59)
            ),
            // end_date is 2-4 days after start_date
            'end_date' => (clone $startDate)->addDays(fake()->numberBetween(2, 4))->setTime(
                fake()->numberBetween(0,23),
                fake()->numberBetween(0,59),
                fake()->numberBetween(0,59)
            ),
            'creator_id' => User::where('is_admin', true)->first()?->id,
            'is_active' => fake()->boolean(),
        ];
    }
}
