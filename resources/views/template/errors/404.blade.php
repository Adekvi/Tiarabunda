@extends('layouts.admin')
@section('title', '404 — Halaman Tidak Ditemukan')
@section('page-title', 'Halaman Tidak Ditemukan')
@section('content')<div class="error-page"><div class="error-code">404</div><div class="error-emoji">🧭</div><h1>Halaman tidak ditemukan</h1><p>Alamat yang Anda buka mungkin sudah dipindahkan atau tidak tersedia.</p><a class="btn btn-primary" href="{{ route('superadmin.dashboard') }}">Kembali ke Dashboard</a></div>@endsection
