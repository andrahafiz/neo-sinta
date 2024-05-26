<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'name_product' => fake()->word(),
            'slug' =>  Str::slug(fake()->unique()->word()),
            'categories_id' => Category::factory()->create()->id,
            'description' => fake()->paragraph(),
            'stock' => fake()->randomNumber(2),
            'price' => fake()->numberBetween(10000, 1000000),
            'image' => fake()->filePath()
        ];
    }
}
