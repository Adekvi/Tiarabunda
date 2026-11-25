<?php

namespace App\Providers;

use App\Models\Informasi\Berita;
use App\Models\Informasi\Faq;
use App\Models\Informasi\Kegiatan;
use App\Models\Landing\Popup;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use App\Observers\Landing\Informasi\Berita\BeritaObserver;
use App\Observers\Landing\Informasi\Faq\FaqObserver;
use App\Observers\Landing\Informasi\Kegiatan\KegiatanObserver;
use App\Observers\Landing\Pendidikan\Program\FakultasObserver;
use App\Observers\Landing\Pendidikan\Program\ProdiObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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
        // IP asli pengunjung di belakang Cloudflare/proxy (lihat config/security.php)
        if ($proxies = config('security.trusted_proxies')) {
            TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }

        // Hanya dijalankan pada layout utama saat membuka landing utama (/)
        View::composer('components.landing.layout.app', function ($view) {
            if (!request()->routeIs('landing') && !request()->is('/')) {
                $view->with('welcomePopup', null);
                return;
            }

            $slides = Cache::rememberForever(Popup::CACHE_KEY, function () {
                return Popup::aktif()
                    ->select([
                        'id',
                        'kategori',
                        'badge',
                        'judul',
                        'deskripsi',
                        'gambar',
                        'teks_tombol',
                        'url_tombol',
                        'buka_tab_baru',
                        'teks_sekunder',
                        'url_sekunder',
                        'urutan',
                    ])
                    ->get()
                    ->map(function ($item) {
                        $imgUrl = null;
                        if (!empty($item->gambar)) {
                            $val = trim($item->gambar);
                            if (
                                str_starts_with($val, 'http://') ||
                                str_starts_with($val, 'https://') ||
                                str_starts_with($val, '/')
                            ) {
                                $imgUrl = $val;
                            } elseif (
                                str_starts_with($val, 'storage/') ||
                                str_starts_with($val, 'landing/') ||
                                str_starts_with($val, 'assets/')
                            ) {
                                $imgUrl = asset($val);
                            } else {
                                $imgUrl = asset('storage/' . ltrim($val, '/'));
                            }
                        }

                        return [
                            'id'            => $item->id,
                            'kategori'      => $item->kategori ?: 'info',
                            'badge'         => $item->badge ?: 'Informasi Kampus',
                            'judul'         => $item->judul,
                            'deskripsi'     => $item->deskripsi,
                            'gambar'        => $imgUrl,
                            'teks_tombol'   => $item->teks_tombol,
                            'url_tombol'    => $item->url_tombol,
                            'buka_tab_baru' => (bool) $item->buka_tab_baru,
                            'teks_sekunder' => $item->teks_sekunder,
                            'url_sekunder'  => $item->url_sekunder,
                        ];
                    })
                    ->values()
                    ->all();
            });

            $view->with('welcomePopup', $slides);
        });

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
