<?php

namespace Database\Seeders;

// use App\Models\User;

use Database\Seeders\Auth\UserSeeder;
use Database\Seeders\Landing\Informasi\FaqSeeder;
use Database\Seeders\Landing\Layanan\FasilitasSeeder;
use Database\Seeders\Landing\Pendidikan\FakultasSeeder;
use Database\Seeders\Landing\Pendidikan\ProdiSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,

            // Landing Pendidikan
            FakultasSeeder::class,
            ProdiSeeder::class,

            // Landing Layanan
            FasilitasSeeder::class,

            // Landing Informasi
            FaqSeeder::class,

        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
