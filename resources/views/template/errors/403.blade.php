@extends('layouts.admin')
@section('title', '403 — Akses Ditolak')
@section('page-title', 'Akses Ditolak')
@section('content')<div class="error-page"><div class="error-code">403</div><div class="error-emoji">🔒</div><h1>Akses tidak diizinkan</h1><p>Anda tidak memiliki izin untuk mengakses halaman ini.</p><a class="btn btn-primary" href="{{ route('superadmin.dashboard') }}">Kembali ke Dashboard</a></div>@endsection
