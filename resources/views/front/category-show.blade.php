@extends('layouts.front')
@section('title', 'Kategori: ' . $category->name)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.jdih') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>

        <h2 class="fw-bold mb-4">
            <i class="{{ $category->icon ?? 'bi-folder' }} me-2"></i>{{ $category->name }}
        </h2>

        @forelse($documents as $doc)
        <div class="card border-0 shadow-sm mb-3 card-hover">
            <div class="card-body p-4">
                <span class="badge bg-info mb-2">{{ $doc->type->code }}</span>
                <h5 class="fw-bold mb-2">
                    <a href="{{ route('front.jdih.show', $doc->slug) }}" class="text-decoration-none text-dark">{{ $doc->title }}</a>
                </h5>
                <small class="text-muted">No. {{ $doc->number }}/{{ $doc->year }}</small>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <p class="text-muted">Belum ada dokumen dalam kategori ini.</p>
        </div>
        @endforelse

        <div class="mt-4">{{ $documents->links() }}</div>
    </div>
</section>
@endsection
