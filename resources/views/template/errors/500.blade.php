@extends('layouts.admin')
@section('title', '500 — Gangguan Server')
@section('page-title', 'Gangguan Server')
@section('content')<div class="error-page"><div class="error-code">500</div><div class="error-emoji">⚙</div><h1>Terjadi gangguan server</h1><p>Silakan coba beberapa saat lagi. Jika masalah berlanjut, hubungi administrator sistem.</p><a class="btn btn-primary" href="{{ route('superadmin.dashboard') }}">Kembali ke Dashboard</a></div>@endsection
