<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Panel admin {{ config('app.name', 'DPRD Kepri') }}">
    <title>@yield('title', 'Login') | {{ config('app.name', 'DPRD Kepri') }}</title>
    @include('layouts.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/feather.min.css') }}">

    <style>
        body.auth-centered-body {
            background: linear-gradient(135deg, #0d1b3e 0%, #1a2e5a 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        .auth-card-container {
            width: 100%;
            max-width: 440px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control {
            border-radius: 8px !important;
            padding: 0.75rem 1rem !important;
            border: 1px solid #ced4da !important;
            height: auto !important;
        }

        .form-control:focus {
            border-color: #c8a84e !important;
            box-shadow: 0 0 0 0.2rem rgba(200, 168, 78, 0.25) !important;
        }

        .btn-primary {
            background-color: #1a2e5a !important;
            border-color: #1a2e5a !important;
            padding: 0.75rem !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #0d1b3e !important;
            border-color: #0d1b3e !important;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            padding: 0;
        }

        .password-toggle:hover {
            color: #1a2e5a;
        }
    </style>
</head>

<body class="auth-centered-body">
    <div class="auth-card-container">
        <div class="auth-card p-4 p-sm-5">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="Logo"
                    style="width: 50px; height: 50px; object-fit: contain;">
            </div>
            @yield('content')
        </div>
    </div>

    @include('layouts.footer', ['is_auth' => true])
</body>

</html>
