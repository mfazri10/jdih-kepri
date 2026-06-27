@extends('layouts.front')
@section('title', 'Telusur Tematik')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold text-muted" style="letter-spacing: 3px; font-size: 0.8rem;">Telusuri</h6>
            <h2 class="fw-bold">Berdasarkan Tematik</h2>
            <p class="text-muted">Temukan dokumen hukum berdasarkan topik/tema tertentu</p>
        </div>

        <div class="row g-4">
            @forelse($themes as $theme)
            <div class="col-md-4">
                <a href="{{ route('front.jdih.theme-show', $theme->slug) }}" class="text-decoration-none">
                    <div class="card theme-card card-hover border-0 shadow-sm h-100" style="border-left-color: {{ $theme->color ?? '#2563eb' }} !important;">
                        <div class="card-body p-4">
                            <i class="{{ $theme->icon ?? 'bi-tag' }} fs-3 mb-3 d-block" style="color: {{ $theme->color ?? '#2563eb' }};"></i>
                            <h5 class="fw-bold text-dark mb-2">{{ $theme->name }}</h5>
                            @if($theme->description)
                                <p class="text-muted small mb-2">{{ Str::limit($theme->description, 120) }}</p>
                            @endif
                            <span class="badge bg-primary">{{ $theme->documents_count }} dokumen</span>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-tag fs-1 text-muted d-block mb-3 opacity-25"></i>
                <h5 class="text-muted">Belum ada tematik tersedia.</h5>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
