<?php

namespace App\Providers;

use App\Models\Informasi\Berita;
use App\Models\Informasi\Faq;
use App\Models\Informasi\Kegiatan;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use App\Observers\Landing\Informasi\Berita\BeritaObserver;
use App\Observers\Landing\Informasi\Faq\FaqObserver;
use App\Observers\Landing\Informasi\Kegiatan\KegiatanObserver;
use App\Observers\Landing\Pendidikan\Program\FakultasObserver;
use App\Observers\Landing\Pendidikan\Program\ProdiObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        Gate::define('access-superadmin', fn($user) => $user->isSuperAdmin());
        Gate::define('access-admin', fn($user) => $user->isAdmin());

        Paginator::useBootstrapFive();

        Paginator::defaultView('superadmin.konten.layanan.fasilitas.partials.pagination');
        Paginator::defaultSimpleView('superadmin.konten.layanan.fasilitas.partials.pagination');

        // Landing Pendidikan
        Fakultas::observe(FakultasObserver::class);
        Prodi::observe(ProdiObserver::class);

        // Landing Informasi
        Faq::observe(FaqObserver::class);
        Berita::observe(BeritaObserver::class);
        Kegiatan::observe(KegiatanObserver::class);
    }
}
