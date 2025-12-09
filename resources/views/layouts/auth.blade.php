<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpus-JTI')</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Auth Styling -->
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card fade-in">
            <div class="auth-header">
                <h1>
                    <i class="fas fa-book-open logo-icon"></i>
                    {{ config('app.name', 'Perpus-JTI') }}
                </h1>
                <p class="mb-0 opacity-90 mt-2" style="font-weight: 400; font-size: 1rem;">
                    @yield('subtitle', '')
                </p>
            </div>
            <div class="auth-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('form')
            </div>
        </div>

        <div class="text-center mt-4 text-muted" style="font-size: 0.8rem; font-weight: 400;">
            &copy; {{ date('Y') }} Perpus-JTI — Sistem Manajemen Perpustakaan
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>