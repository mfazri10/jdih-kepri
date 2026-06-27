<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'JDIH Kepri - Jaringan Dokumentasi dan Informasi Hukum Kepulauan Riau')">
    <title>@yield('title', 'JDIH Kepri') | JDIH Kepri</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Feather Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    @yield('styles')

    <style>
        :root {
            --jdih-primary: #1e3a5f;
            --jdih-secondary: #2563eb;
            --jdih-accent: #f59e0b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #333;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--jdih-primary) 0%, var(--jdih-secondary) 100%);
            color: white;
            padding: 4rem 0;
        }

        .search-box {
            max-width: 600px;
            margin: 0 auto;
        }

        .search-box .form-control {
            border-radius: 50px 0 0 50px;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border: none;
        }

        .search-box .btn {
            border-radius: 0 50px 50px 0;
            padding: 0.75rem 2rem;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .theme-card {
            border-left: 4px solid var(--jdih-secondary);
        }

        .stat-card {
            border-top: 3px solid var(--jdih-accent);
        }

        .footer {
            background: var(--jdih-primary);
            color: rgba(255,255,255,0.8);
        }

        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .footer a:hover {
            color: white;
        }

        .badge-status-berlaku { background: #059669; }
        .badge-status-dicabut { background: #dc2626; }
        .badge-status-tidak_berlaku { background: #d97706; }
    </style>
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--jdih-primary);">
        <div class="container">
            <a class="navbar-brand" href="{{ route('front.jdih') }}">
                <i class="bi bi-scale me-2"></i>JDIH Kepri
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('front.jdih') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('front.jdih.search') }}">Cari Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('front.jdih.themes') }}">Telusur Tematik</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Layanan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('front.jdih.consultations') }}">Konsultasi Hukum</a></li>
                            <li><a class="dropdown-item" href="{{ route('front.jdih.hearings') }}">Public Hearing</a></li>
                            <li><a class="dropdown-item" href="{{ route('front.jdih.info-requests') }}">Permintaan Informasi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="text-white mb-3"><i class="bi bi-scale me-2"></i>JDIH Kepri</h5>
                    <p>Jaringan Dokumentasi dan Informasi Hukum Provinsi Kepulauan Riau. Akses mudah, informasi hukum pasti.</p>
                </div>
                <div class="col-lg-2">
                    <h6 class="text-white mb-3">Navigasi</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('front.jdih') }}">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('front.jdih.search') }}">Cari Dokumen</a></li>
                        <li class="mb-2"><a href="{{ route('front.jdih.themes') }}">Tematik</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white mb-3">Layanan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('front.jdih.consultations') }}">Konsultasi Hukum</a></li>
                        <li class="mb-2"><a href="{{ route('front.jdih.hearings') }}">Public Hearing</a></li>
                        <li class="mb-2"><a href="{{ route('front.jdih.info-requests') }}">Permintaan Informasi</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-white mb-3">Kontak</h6>
                    <p><i class="bi bi-geo-alt me-2"></i>Tanjungpinang, Kepulauan Riau</p>
                    <p><i class="bi bi-envelope me-2"></i>jdih@kepriprov.go.id</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <small>&copy; {{ date('Y') }} JDIH Provinsi Kepulauan Riau. All rights reserved.</small>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>feather.replace();</script>

    {{-- Accessibility Toolbar --}}
    <x-accessibility-toolbar />

    @yield('scripts')
</body>
</html>
