<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'users_id' => User::factory()->create()->id,
            'total_price' => fake()->numberBetween(10000, 1000000),
            'money' => fake()->numberBetween(10000, 1000000),
            'change' => fake()->numberBetween(10000, 1000000),
            'created_at' => fake()->dateTimeBetween('-1 month')
        ];
    }
}
