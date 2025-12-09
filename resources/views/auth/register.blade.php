@extends('layouts.auth')

@section('title', 'Daftar — Perpus-JTI')
@section('subtitle', 'Buat akun anggota perpustakaan')

@section('form')
    <form method="POST" action="{{ route('aksi.register') }}">
        @csrf

        <!-- Baris 1: Nama & Email -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                </div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Baris 2: NIM & No HP -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="npm" class="form-label">NIM/NIP</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                    <input type="text" name="npm" id="npm" class="form-control @error('npm') is-invalid @enderror" value="{{ old('npm') }}" required>
                </div>
                @error('npm')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">No. HP</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>
        </div>

        <!-- Baris 3: Prodi & Password -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="department" class="form-label">Program Studi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                    <input type="text" name="department" id="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', 'Teknik Informatika') }}" required>
                </div>
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required minlength="6">
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="fas fa-user-plus me-2"></i> Daftar Akun
        </button>
    </form>

    <div class="footer-links mt-4">
        <a href="{{ route('login') }}" class="text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Sudah punya akun? Masuk
        </a>
    </div>
@endsection