<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Membuat akun awal. Password TIDAK ditulis di kode:
     * - diambil dari env SEED_SUPERADMIN_PASSWORD / SEED_ADMIN_PASSWORD bila ada,
     * - jika tidak, dibuat acak dan ditampilkan SEKALI di terminal.
     * Akun yang sudah ada tidak disentuh (password tidak pernah ditimpa ulang).
     */
    public function run(): void
    {
        $accounts = [
            ['Super Admin', 'superadmin@tiarabunda.ac.id', 'superadmin', 'SEED_SUPERADMIN_PASSWORD'],
            ['Admin Konten', 'admin@tiarabunda.ac.id', 'admin', 'SEED_ADMIN_PASSWORD'],
        ];

        foreach ($accounts as [$name, $email, $role, $envKey]) {
            if (User::query()->where('email', $email)->exists()) {
                $this->command?->info("Lewati {$email} (sudah ada).");
                continue;
            }

            $password = env($envKey) ?: Str::password(20);

            User::query()->create([
                'name'              => $name,
                'email'             => $email,
                'password'          => Hash::make($password),
                'role'              => $role,
                'email_verified_at' => now(),
            ]);

            if (! env($envKey)) {
                $this->command?->warn("Password {$email}: {$password}  <- catat sekarang, tidak ditampilkan lagi.");
            }
        }
    }
}
