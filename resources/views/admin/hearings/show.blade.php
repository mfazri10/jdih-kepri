@extends('layouts.admin')
@section('title', 'Detail Public Hearing')
@section('page_title', 'Detail Public Hearing')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">{{ $hearing->title }}</h4>
                <p>{{ $hearing->description }}</p>
                @if($hearing->document_draft)
                    <p><strong>Draft:</strong> <a href="{{ $hearing->document_draft }}" target="_blank">{{ $hearing->document_draft }}</a></p>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Masukan Publik ({{ $hearing->submissions->count() }})</h6>
            </div>
            <div class="card-body p-0">
                @forelse($hearing->submissions as $sub)
                <div class="border-bottom p-3">
                    <div class="d-flex justify-content-between mb-1">
                        <strong>{{ $sub->name }}</strong>
                        @if($sub->status === 'pending') <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($sub->status === 'reviewed') <span class="badge bg-info">Ditinjau</span>
                        @elseif($sub->status === 'accepted') <span class="badge bg-success">Diterima</span>
                        @else <span class="badge bg-danger">Ditolak</span> @endif
                    </div>
                    <small class="text-muted">{{ $sub->institution ?? $sub->email }}</small>
                    <p class="mt-2 mb-0">{{ $sub->opinion }}</p>
                </div>
                @empty
                <p class="text-center text-muted py-4">Belum ada masukan.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    @if($hearing->status === 'open') <span class="badge bg-success">Buka</span>
                    @elseif($hearing->status === 'closed') <span class="badge bg-danger">Tutup</span>
                    @else <span class="badge bg-secondary">Arsip</span> @endif
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Mulai</span>
                    <small>{{ $hearing->start_date->translatedFormat('d M Y') }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Selesai</span>
                    <small>{{ $hearing->end_date->translatedFormat('d M Y') }}</small>
                </div>
                @if($hearing->location)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Lokasi</span>
                    <small>{{ $hearing->location }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
