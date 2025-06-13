<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slide>
 */
class SlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'images' => $this->faker->imageUrl(),
            'description' => $this->faker->optional()->paragraph(),
            'link' => $this->faker->word(),
            'status' => $this->faker->randomDigit(1, 0),
        ];
    }
}
