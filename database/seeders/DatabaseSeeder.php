<?php

namespace Database\Seeders;

// use App\Models\User;

use Database\Seeders\Auth\UserSeeder;
use Database\Seeders\Landing\Informasi\FaqSeeder;
use Database\Seeders\Landing\Layanan\FasilitasSeeder;
use Database\Seeders\Landing\Layanan\KemahaSeeder;
use Database\Seeders\Landing\Layanan\PerpusSeeder;
use Database\Seeders\Landing\Pendidikan\AkreditasiSeeder;
use Database\Seeders\Landing\Pendidikan\BeasiswaSeeder;
use Database\Seeders\Landing\Pendidikan\FakultasSeeder;
use Database\Seeders\Landing\Pendidikan\PmbSeeder;
use Database\Seeders\Landing\Pendidikan\ProdiSeeder;
use Database\Seeders\Landing\Profil\PimpinanSeeder;
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

            // Landing Profil
            PimpinanSeeder::class,

            // Landing Pendidikan
            AkreditasiSeeder::class,
            FakultasSeeder::class,
            ProdiSeeder::class,
            PmbSeeder::class,
            BeasiswaSeeder::class,

            // Landing Layanan
            FasilitasSeeder::class,
            PerpusSeeder::class,
            KemahaSeeder::class,

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
