<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Contracts\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_empty()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api')->get(
            'api/cart',
            ['Accept' => 'application/json']
        );
        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK, 'data' => []]);
    }

    public function test_index()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'users_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')->get(
            'api/cart',
            ['Accept' => 'application/json']
        );
        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK]);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'qty',
                    'product',
                    'user',
                ]
            ]
        ]);
    }
}
