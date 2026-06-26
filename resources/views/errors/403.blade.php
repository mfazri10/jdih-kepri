<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 | {{ config('app.name', 'DPRD Kepri') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
</head>

<body class="bg-gray-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mt-5">
                    <div class="card-body p-5 text-center">
                        <div class="display-5 fw-bold text-danger mb-3">403</div>
                        <h1 class="h3 mb-3">Akses Ditolak</h1>
                        <p class="text-muted mb-4">{{ $message ?? 'Anda tidak memiliki izin untuk membuka halaman ini.' }}</p>
                        <a href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}" class="btn btn-primary">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
