<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'images' => $this->faker->imageUrl(),
            'code' => $this->faker->randomDigit(2, 50, 1000),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}
