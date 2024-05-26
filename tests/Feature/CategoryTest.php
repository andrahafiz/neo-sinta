<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Contracts\Response;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
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
            'api/category',
            ['Accept' => 'application/json']
        );
        $response->assertNoContent();
    }

    public function test_index()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'api')->get(
            'api/category',
            ['Accept' => 'application/json']
        );
        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK]);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'slug',
                    'category',
                ]
            ],
            'meta' => [
                'total',
                'perPage',
                'currentPage',
                'lastPage',
            ],
        ]);
    }

    public function test_index_filter()
    {
        $user = User::factory()->create();
        Category::factory()->create(
            [
                'category' => 'category',
                'slug' => 'category'
            ]
        );

        Category::factory()->create(
            [
                'category' => 'rice',
                'slug' => 'rice'
            ]
        );

        $response = $this->actingAs($user, 'api')->get(
            'api/category?search=category',
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK]);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'slug',
                    'category',
                ]
            ],
            'meta' => [
                'total',
                'perPage',
                'currentPage',
                'lastPage',
            ],
        ]);
        $response->assertJson([
            'message' => Response::MESSAGE_OK,
            'meta'    => [
                'total'       => 1,
                'perPage'     => 15,
                'currentPage' => 1,
                'lastPage'    => 1
            ]
        ]);
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->post(
            'api/category/',
            [
                'name' => 'category',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'slug',
                'category',
            ]
        ]);
    }

    public function test_store_error_validation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->post(
            'api/category',
            ['name' => ''],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create();
        $product =  Product::factory()->create(['categories_id' => $category->id]);

        $response = $this->actingAs($user, 'api')->get(
            'api/category/' . $category->slug,
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'slug',
                'category',
                'products'
            ]
        ]);
    }

    public function test_show_not_found()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create(
            ['slug' => 'category']
        );

        $response = $this->actingAs($user, 'api')->get(
            'api/category/' . $this->faker()->slug(),
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_NOT_FOUND);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create();

        $response = $this->actingAs($user, 'api')->put(
            'api/category/' . $category->slug,
            ['name' => 'edit category'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_OK);
        $this->assertDatabaseHas(Category::class, [
            'category' => 'edit category'
        ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'slug',
                'category',
            ]
        ]);
    }

    public function test_update_not_found_data()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create([
            'slug' => 'category'
        ]);

        $response = $this->actingAs($user, 'api')->put(
            'api/category/' . $this->faker()->slug(),
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_NOT_FOUND);
    }

    public function test_update_error_validation()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create();

        $response = $this->actingAs($user, 'api')->put(
            'api/category/' . $category->slug,
            ['name' => ''],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
    }
    public function test_delete()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create();

        $response = $this->actingAs($user, 'api')->delete(
            'api/category/' . $category->slug,
            ['Accept' => 'application/json']
        );
        $response->assertNoContent();
        $response->assertStatus(Response::STATUS_NO_CONTENT);
        $this->assertDatabaseMissing(Category::class, [
            'slug' => $category->slug
        ]);
    }

    public function test_delete_data_not_found()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create();

        $response = $this->actingAs($user, 'api')->delete(
            'api/category/' . $this->faker()->slug(),
            ['Accept' => 'application/json']
        );
        $response->assertNotFound();
        $response->assertStatus(Response::STATUS_NOT_FOUND);
    }

    public function test_delete_data_with_product()
    {
        $user = User::factory()->create();
        $category =  Category::factory()->create();
        $product = Product::factory()->create([
            'categories_id' => $category->id
        ]);

        $response = $this->actingAs($user, 'api')->delete(
            'api/category/' . $category->slug,
            ['Accept' => 'application/json']
        );
        $response->assertStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => Response::MESSAGE_UNPROCESSABLE_ENTITY,
            'data'    => [
                "message" => "Data tidak bisa dihapus karena berkaitan dengan data lainnya"
            ]
        ]);
    }
}
