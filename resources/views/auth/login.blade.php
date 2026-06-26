@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="text-center">
        <h2 class="fs-20 fw-bolder mb-2 text-dark">Login</h2>
        <h4 class="fs-13 fw-bold mb-3 text-secondary">Masuk ke panel admin</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2 px-3 mb-3 text-center d-flex align-items-center justify-content-center gap-2"
            role="alert"
            style="border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(40, 167, 69, 0.2); background-color: rgba(40, 167, 69, 0.05); color: #28a745;">
            <i class="feather-check-circle" style="font-size: 1rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger py-2 px-3 mb-3 text-center d-flex align-items-center justify-content-center gap-2"
            role="alert"
            style="border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(220, 53, 69, 0.2); background-color: rgba(220, 53, 69, 0.05); color: #dc3545;">
            <i class="feather-alert-circle" style="font-size: 1rem;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3 text-center d-flex align-items-center justify-content-center gap-2"
            role="alert"
            style="border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(220, 53, 69, 0.2); background-color: rgba(220, 53, 69, 0.05); color: #dc3545;">
            <i class="feather-alert-circle" style="font-size: 1rem;"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST" class="w-100">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="Email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3 password-container">
            <input type="password" name="password" id="passwordInput"
                class="form-control @error('password') is-invalid @enderror" placeholder="Password" required
                style="padding-right: 45px;">
            <button type="button" class="password-toggle" onclick="togglePasswordVisibility()">
                <i class="feather-eye" id="togglePasswordIcon" style="font-size: 1.15rem;"></i>
            </button>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember" value="1">
                <label class="form-check-label c-pointer" for="rememberMe">Remember me</label>
            </div>
            <div>
                <a href="{{ route('password.request') }}" class="fs-12 text-primary fw-semibold" style="text-decoration: none;">Lupa Password?</a>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-lg btn-primary w-100">Login</button>
        </div>
    </form>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('feather-eye');
                toggleIcon.classList.add('feather-eye-off');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('feather-eye-off');
                toggleIcon.classList.add('feather-eye');
            }
        }
    </script>
@endsection
