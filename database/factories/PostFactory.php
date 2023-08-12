<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => fake()->text(),
            'preview_text' => fake()->text(300),
            'title' => fake()->sentence(5),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }

    public function noActive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false
            ];
        });
    }
}
