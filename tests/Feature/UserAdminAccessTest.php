<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Contracts\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAdminAccessTest extends TestCase
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

    public function test_index()
    {
        $user = User::factory()->create(['roles' => 'ADMIN']);

        User::factory(50, [
            'roles' => 'ADMIN'
        ])->create();

        $response = $this->actingAs($user, 'api')->get(
            'api/user',
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK]);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'username',
                    'email',
                    'emailVerifiedAt',
                    'photo',
                    'roles',
                    'createdAt',
                    'updatedAt',
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
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $user1 = User::factory(15, [
            'roles' => 'KARYAWAN'
        ])->create();

        $user2 = User::factory(15, [
            'roles' => 'ADMIN'
        ])->create();

        $response = $this->actingAs($user, 'api')->get(
            'api/user?roles=ADMIN',
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK]);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'username',
                    'email',
                    'emailVerifiedAt',
                    'photo',
                    'roles',
                    'createdAt',
                    'updatedAt',
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
                'total'       => 16,
                'perPage'     => 15,
                'currentPage' => 1,
                'lastPage'    => 2
            ]
        ]);
    }

    public function test_store()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->post(
            'api/user/',
            [
                "name" => 'New User',
                "username" => $this->faker()->name(),
                "email" => $this->faker()->email(),
                "password" =>  $this->faker()->password(8),
                "phone_number" => (string) $this->faker()->randomNumber(),
                'image'     => UploadedFile::fake()->create('user.jpg'),
                "address" => $this->faker()->address(),
                "roles" => "KARYAWAN",
            ],
            ['Accept' => 'application/json']
        );

        $this->assertDatabaseHas(User::class, [
            'name' => 'New User'
        ]);
        $response->assertStatus(Response::STATUS_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'username',
                'email',
                'emailVerifiedAt',
                'photo',
                'roles',
                'createdAt',
                'updatedAt',
            ]
        ]);
    }

    public function test_store_error_validation()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->post(
            'api/user/',
            [
                "name" => $this->faker()->name(),
                "username" => $this->faker()->name(),
                "email" => $this->faker()->email(),
                "password" =>  $this->faker()->password(3, 3),
                "phone_number" => (string) $this->faker()->randomNumber(),
                'image'     => UploadedFile::fake()->create('user.jpg'),
                "address" => $this->faker()->address(),
                "roles" => "KARYAWAN",
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
    }

    public function test_show()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->get(
            'api/user/' . $user->id,
            ['Accept' => 'application/json']
        );
        $response->assertOk();
        $response->assertJson(['message' => Response::MESSAGE_OK]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'username',
                'email',
                'emailVerifiedAt',
                'photo',
                'roles',
                'createdAt',
                'updatedAt',
            ]
        ]);
    }

    public function test_show_not_found()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->get(
            'api/user/' . $this->faker()->randomNumber(),
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_NOT_FOUND);
    }

    public function test_update()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->put(
            'api/user/' . $user->id,
            ['name' => 'edit user'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_OK);
        $this->assertDatabaseHas(User::class, [
            'id'   => $user->id,
            'name' => 'edit user'
        ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'username',
                'email',
                'emailVerifiedAt',
                'photo',
                'roles',
                'createdAt',
                'updatedAt',
            ]
        ]);
    }

    public function test_update_not_found_data()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->put(
            'api/user/' . $this->faker()->randomNumber(),
            ['name' => 'edit user'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_NOT_FOUND);
    }

    public function test_update_error_validation()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->put(
            'api/user/' . $user->id,
            ['roles' => 'admin'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::STATUS_UNPROCESSABLE_ENTITY);
    }

    public function test_delete()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->delete(
            'api/user/' . $user->id,
            ['Accept' => 'application/json']
        );
        $response->assertNoContent();
        $response->assertStatus(Response::STATUS_NO_CONTENT);
        $this->assertSoftDeleted(User::class, [
            'id' => $user->id
        ]);
    }

    public function test_delete_data_not_found()
    {
        $user = User::factory()->create([
            'roles' => 'ADMIN'
        ]);

        $response = $this->actingAs($user, 'api')->delete(
            'api/user/' . $this->faker()->randomNumber(),
            ['Accept' => 'application/json']
        );

        $response->assertNotFound();
        $response->assertStatus(Response::STATUS_NOT_FOUND);
    }
}
