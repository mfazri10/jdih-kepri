@extends('layouts.front')
@section('title', 'Tematik: ' . $theme->name)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.jdih') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('front.jdih.themes') }}" class="text-decoration-none">Tematik</a></li>
                <li class="breadcrumb-item active">{{ $theme->name }}</li>
            </ol>
        </nav>

        <div class="d-flex align-items-center mb-4">
            <i class="{{ $theme->icon ?? 'bi-tag' }} fs-3 me-3" style="color: {{ $theme->color ?? '#2563eb' }};"></i>
            <div>
                <h2 class="fw-bold mb-0">{{ $theme->name }}</h2>
                @if($theme->description)
                    <p class="text-muted mb-0">{{ $theme->description }}</p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                @forelse($documents as $doc)
                <div class="card border-0 shadow-sm mb-3 card-hover">
                    <div class="card-body p-4">
                        <span class="badge bg-info mb-2">{{ $doc->type->code }}</span>
                        <h5 class="fw-bold mb-2">
                            <a href="{{ route('front.jdih.show', $doc->slug) }}" class="text-decoration-none text-dark">
                                {{ $doc->title }}
                            </a>
                        </h5>
                        <small class="text-muted">
                            No. {{ $doc->number }}/{{ $doc->year }}
                            @if($doc->category) &mdash; {{ $doc->category->name }} @endif
                            <span class="ms-3"><i class="bi bi-calendar me-1"></i>{{ $doc->published_at?->translatedFormat('d M Y') }}</span>
                        </small>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada dokumen dalam tematik ini.</p>
                </div>
                @endforelse

                <div class="mt-4">{{ $documents->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
