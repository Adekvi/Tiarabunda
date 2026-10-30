<!doctype html>
<html lang="id" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — {{ config('app.name', 'Universitas Tiara Bunda') }}</title>
    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
</head>

<body class="auth-body">
    <main class="auth-layout">
        <section class="auth-brand-panel">
            <a class="brand brand-light" href="/">
                <span class="brand-copy">
                    <strong>UNIVERSITAS TIARA BUNDA</strong>
                    <small>Tiara Bunda Univercity</small>
                </span>
            </a>
            <div class="auth-promo">
                <span class="eyebrow">ADMINISTRATION PORTAL</span>
                <h1>Kelola kampus.
                    <br>Bangun masa depan.
                </h1>
                <p>Panel terpusat untuk mengelola konten, informasi akademik, dan penerimaan mahasiswa.</p>
                <div class="auth-decoration">🎓</div>
            </div>
            <small class="auth-copyright">© {{ date('Y') }} Universitas Tiara Bunda</small>
        </section>
        <section class="auth-form-panel">
            <form class="auth-form" method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="auth-mobile-brand">UNIVERSITAS TIARA BUNDA</div>
                <span class="eyebrow">SELAMAT DATANG KEMBALI</span>
                <h2>Masuk ke akun Anda</h2>
                <p class="muted">Masukkan akun administrator untuk melanjutkan.</p>
                @if ($errors->any())
                    <div class="notice notice-warning">
                        {{ $errors->first() }}
                    </div>
                @endif
                <label class="field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required
                        placeholder="nama@tiarabunda.ac.id">
                </label>
                <label class="field">
                    <span>Kata sandi</span>
                    <input type="password" name="password" autocomplete="current-password" required
                        placeholder="Masukkan kata sandi">
                </label>
                <div class="auth-inline">
                    <label class="check-label">
                        <input type="checkbox" name="remember"> Ingat
                        saya</label>
                    <a href="#">Lupa kata sandi?</a>
                </div>
                <button class="btn btn-primary btn-block" type="submit">Masuk ke Dashboard →</button>
            </form>
        </section>
    </main>
</body>

</html>
