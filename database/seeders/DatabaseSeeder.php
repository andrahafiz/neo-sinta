<?php

namespace Database\Seeders;

use App\Models\Lecture;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
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
        Artisan::call('passport:install', ['--force' => true]);

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

        $mahasiswa1 = Mahasiswa::create([
            'name'              => 'mahasiswa',
            'nim'               => '1111',
            'email'             => 'mahasiswa@gmail.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        $mahasiswa2 = Mahasiswa::create([
            'name'              => 'mahasiswa2',
            'nim'               => '22222',
            'email'             => 'mahasiswa2@gmail.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        \App\Models\User::factory(count: 2)->create();

        $role_mahasiswa = Role::create(['name' => 'mahasiswa', 'guard_name' => 'mahasiswa-guard']);

        $mahasiswa1->assignRole(['mahasiswa']);
        $mahasiswa2->assignRole(['mahasiswa']);

        $dosen = Lecture::create([
            'name'              => 'dosen',
            'nip'               => '54321',
            'email'             => 'dosen@gmail.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $dosen1 = Lecture::create([
            'name'              => 'dosen pem1',
            'nip'               => '543211111',
            'email'             => 'dosenpem1@gmail.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $dosen2 = Lecture::create([
            'name'              => 'dosen pem2',
            'nip'               => '54322222',
            'email'             => 'dosenpem2@gmail.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $kaprodi = Lecture::create([
            'name'              => 'kaprodi',
            'nip'               => '99999',
            'email'             => 'kaprod@gmail.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $role_dosen             = Role::create(['name' => 'dosen', 'guard_name' => 'dosen-guard']);
        $role_kaprodi           = Role::create(['name' => 'kaprodi', 'guard_name' => 'dosen-guard']);
        $role_dosen_pembimbing  = Role::create(['name' => 'dosen_pembimbing', 'guard_name' => 'dosen-guard']);

        $dosen->assignRole(['dosen']);
        $dosen1->assignRole(['dosen', 'dosen_pembimbing']);
        $dosen2->assignRole(['dosen', 'dosen_pembimbing']);
        $kaprodi->assignRole(['dosen', 'kaprodi']);
    }
}
