<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AdminAkun extends Command
{
    protected $signature = 'admin:akun
        {email : Email akun admin}
        {--nama= : Nama tampilan (dipakai hanya saat membuat akun baru)}
        {--role=superadmin : superadmin atau admin}';

    protected $description = 'Buat akun admin baru atau reset password akun yang ada (password lewat prompt tersembunyi)';

    public function handle(): int
    {
        $email = Str::lower(trim((string) $this->argument('email')));
        $role = (string) $this->option('role');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->components->error('Format email tidak valid.');
            return self::FAILURE;
        }

        if (! in_array($role, ['superadmin', 'admin'], true)) {
            $this->components->error('Role harus "superadmin" atau "admin".');
            return self::FAILURE;
        }

        $password = (string) $this->secret('Password baru (min. 12 karakter, huruf besar/kecil, angka, simbol)');
        $confirm = (string) $this->secret('Ulangi password');

        $validator = Validator::make(
            ['password' => $password, 'password_confirmation' => $confirm],
            ['password' => ['required', 'confirmed', 'max:72', Password::min(12)->mixedCase()->numbers()->symbols()]],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->components->error($message);
            }
            return self::FAILURE;
        }

        $user = User::query()->where('email', $email)->first();

        if ($user) {
            $user->forceFill([
                'password'       => Hash::make($password),
                'role'           => $role,
                'remember_token' => Str::random(60),   // mematikan "ingat saya" lama
            ])->save();

            // Paksa logout semua sesi aktif akun ini.
            if (config('session.driver') === 'database') {
                DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
            }

            $this->components->info("Password {$email} diperbarui dan semua sesi lamanya dihapus.");
        } else {
            (new User)->forceFill([
                'name'              => $this->option('nama') ?: Str::headline(Str::before($email, '@')),
                'email'             => $email,
                'password'          => Hash::make($password),
                'role'              => $role,
                'email_verified_at' => now(),
            ])->save();

            $this->components->info("Akun {$role} {$email} dibuat.");
        }

        RateLimiter::clear('login-email|' . sha1($email));
        $this->line('Jika login masih terkunci (percobaan gagal sebelumnya), jalankan: php artisan cache:clear');

        return self::SUCCESS;
    }
}
