<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name'              => 'ridho',
            'username'          => 'ridho',
            'email'             => 'ridho@gmail.com',
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'    => Str::random(10),
            'phone_number'      => '0877231244312',
            'address'           => 'Rumbai, Pekanbaru',
            'photo'             => 'avatar.jpg',
            'roles'             => 'ADMIN'
        ]);

        $mahasiswa = Mahasiswa::create([
            'name'              => 'mahasiswa',
            'nim'               => '12345',
            'email'             => 'mahasiswa@gmail.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        \App\Models\User::factory(count: 2)->create();
        \App\Models\Product::factory(count: 1)->create();

        $role_mahasiswa = Role::create(['name' => 'mahasiswa', 'guard_name' => 'mahasiswa-api']);

        $mahasiswa->assignRole(['mahasiswa']);

        // $dosen = Lecture::create([
        //     'name'              => 'dosen',
        //     'email'             => 'dosen@gmail.com',
        //     'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ]);

        $role_dosen     = Role::create(['name' => 'dosen', 'guard_name' => 'dosen-api']);
        $role_kaprodi   = Role::create(['name' => 'kaprodi', 'guard_name' => 'kaprodi-api']);
    }
}
