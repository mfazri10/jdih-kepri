@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="text-center">
        <h2 class="fs-20 fw-bolder mb-2 text-dark">Reset Password</h2>
        <h4 class="fs-13 fw-bold mb-3 text-secondary">Silakan masukkan password baru Anda.</h4>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3 text-center d-flex align-items-center justify-content-center gap-2"
            role="alert"
            style="border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(220, 53, 69, 0.2); background-color: rgba(220, 53, 69, 0.05); color: #dc3545;">
            <i class="feather-alert-circle" style="font-size: 1rem;"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('password.store') }}" method="POST" class="w-100">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="Email Akun" value="{{ old('email', $email) }}" required readonly>
        </div>

        <div class="mb-3 password-container">
            <input type="password" name="password" id="passwordInput"
                class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru" required
                style="padding-right: 45px;">
            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('passwordInput', 'togglePasswordIcon')">
                <i class="feather-eye" id="togglePasswordIcon" style="font-size: 1.15rem;"></i>
            </button>
        </div>

        <div class="mb-3 password-container">
            <input type="password" name="password_confirmation" id="passwordConfirmInput"
                class="form-control" placeholder="Konfirmasi Password Baru" required
                style="padding-right: 45px;">
            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('passwordConfirmInput', 'toggleConfirmIcon')">
                <i class="feather-eye" id="toggleConfirmIcon" style="font-size: 1.15rem;"></i>
            </button>
        </div>

        <div>
            <button type="submit" class="btn btn-lg btn-primary w-100">Simpan Password Baru</button>
        </div>
    </form>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

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
