@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div class="text-center">
        <h2 class="fs-20 fw-bolder mb-2 text-dark">Lupa Password</h2>
        <h4 class="fs-13 fw-bold mb-3 text-secondary">Kami akan mengirimkan email tautan untuk menyetel ulang password Anda.</h4>
    </div>

    @if (session('status'))
        <div class="alert alert-success py-2 px-3 mb-3 text-center d-flex align-items-center justify-content-center gap-2"
            role="alert"
            style="border-radius: 8px; font-size: 0.8rem; border: 1px solid rgba(40, 167, 69, 0.2); background-color: rgba(40, 167, 69, 0.05); color: #28a745;">
            <i class="feather-check-circle" style="font-size: 1rem;"></i>
            <span>{{ session('status') }}</span>
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

    <form action="{{ route('password.email') }}" method="POST" class="w-100">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="Email Akun" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-lg btn-primary w-100">Kirim Link Reset</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('login') }}" class="fs-13 fw-semibold text-secondary hover-primary d-inline-flex align-items-center gap-1" style="text-decoration: none;">
            <i class="feather-arrow-left fs-14"></i> Kembali ke Halaman Login
        </a>
    </div>
@endsection
