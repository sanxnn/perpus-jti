@extends('layouts.auth')

@section('title', 'Login — Perpus-JTI')
@section('subtitle', 'Silakan masuk ke akun Anda')

@section('form')
  <form method="POST" action="{{ route('aksi.login') }}">
    @csrf
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
      </div>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">
      <i class="fas fa-sign-in-alt me-2"></i>Masuk
    </button>
  </form>

  <div class="footer-links mt-3">
    <i class="fas fa-user-plus me-1"></i>Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
  </div>
@endsection