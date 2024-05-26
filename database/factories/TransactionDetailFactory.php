<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionDetail>
 */
class TransactionDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $product = Product::factory()->create();
        $qty = fake()->randomDigit();
        return [
            'transactions_id' => Transaction::factory()->create()->id,
            'products_id' => $product->id,
            'qty' => $qty,
            'total' => $qty * $product->price
        ];
    }
}
