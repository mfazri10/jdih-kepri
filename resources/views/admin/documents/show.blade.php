@extends('layouts.admin')
@section('title', 'Detail Dokumen')
@section('page_title', 'Detail Dokumen')

@section('page_actions')
    <a href="{{ route('admin.documents.edit', $document) }}" class="btn btn-warning d-flex align-items-center gap-2">
        <i class="feather-edit"></i> Edit
    </a>
@endsection

@section('content')
<div class="row g-4">
    {{-- Main Info --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h4 class="fw-bold mb-3">{{ $document->title }}</h4>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor</small>
                        <strong>{{ $document->number }} / {{ $document->year }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Jenis</small>
                        <span class="badge bg-info">{{ $document->type->name }} ({{ $document->type->code }})</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Kategori</small>
                        {{ $document->category?->name ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        @if($document->status === 'berlaku')
                            <span class="badge bg-success">Berlaku</span>
                        @elseif($document->status === 'dicabut')
                            <span class="badge bg-danger">Dicabut</span>
                        @else
                            <span class="badge bg-warning text-dark">Tidak Berlaku</span>
                        @endif
                    </div>
                </div>

                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 200px;">Tajuk Entri Utama</td>
                        <td>{{ $document->teu }}</td>
                    </tr>
                    @if($document->subject)
                    <tr>
                        <td class="text-muted">Subjek Hukum</td>
                        <td>{{ $document->subject }}</td>
                    </tr>
                    @endif
                    @if($document->source)
                    <tr>
                        <td class="text-muted">Sumber</td>
                        <td>{{ $document->source }}</td>
                    </tr>
                    @endif
                    @if($document->signatory)
                    <tr>
                        <td class="text-muted">Penandatangan</td>
                        <td>{{ $document->signatory }}</td>
                    </tr>
                    @endif
                    @if($document->place)
                    <tr>
                        <td class="text-muted">Tempat Terbit</td>
                        <td>{{ $document->place }}</td>
                    </tr>
                    @endif
                    @if($document->date_set)
                    <tr>
                        <td class="text-muted">Tanggal Penetapan</td>
                        <td>{{ $document->date_set->translatedFormat('d F Y') }}</td>
                    </tr>
                    @endif
                    @if($document->date_publish)
                    <tr>
                        <td class="text-muted">Tanggal Pengundangan</td>
                        <td>{{ $document->date_publish->translatedFormat('d F Y') }}</td>
                    </tr>
                    @endif
                    @if($document->date_effective)
                    <tr>
                        <td class="text-muted">Tanggal Berlaku</td>
                        <td>{{ $document->date_effective->translatedFormat('d F Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Bahasa</td>
                        <td>{{ strtoupper($document->language) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Abstract --}}
        @if($document->abstract)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Abstrak / Ringkasan</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $document->abstract }}</p>
            </div>
        </div>
        @endif

        {{-- Attachments --}}
        @if($document->attachments->isNotEmpty())
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">
                    <i class="feather-paperclip me-1"></i> Lampiran ({{ $document->attachments->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($document->attachments as $attachment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="feather-file me-2"></i>
                            <a href="{{ $attachment->file_url }}" target="_blank">{{ $attachment->original_name }}</a>
                            <small class="text-muted ms-2">({{ $attachment->formatted_size }})</small>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <small class="text-muted">{{ $attachment->download_count }}x download</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Full Text --}}
        @if($document->full_text)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Full Text</h6>
            </div>
            <div class="card-body">
                <pre style="white-space: pre-wrap; font-size: 0.9rem;">{{ $document->full_text }}</pre>
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Stats --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Statistik</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Views</span>
                    <strong>{{ number_format($document->views_count) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Downloads</span>
                    <strong>{{ number_format($document->downloads_count) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Unggulan</span>
                    @if($document->is_featured)
                        <span class="badge bg-primary">Ya</span>
                    @else
                        <span class="badge bg-secondary">Tidak</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Dibuat</span>
                    <small>{{ $document->created_at->translatedFormat('d M Y H:i') }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Diperbarui</span>
                    <small>{{ $document->updated_at->translatedFormat('d M Y H:i') }}</small>
                </div>
            </div>
        </div>

        {{-- Creator/Editor --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Riwayat</h6>
            </div>
            <div class="card-body">
                @if($document->creator)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Dibuat oleh</span>
                    <span>{{ $document->creator->name }}</span>
                </div>
                @endif
                @if($document->editor)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Diperbarui oleh</span>
                    <span>{{ $document->editor->name }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Themes --}}
        @if($document->themes->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Tematik</h6>
            </div>
            <div class="card-body">
                @foreach($document->themes as $theme)
                    <span class="badge me-1 mb-1" style="background: {{ $theme->color ?? '#6c757d' }};">
                        {{ $theme->name }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Tags --}}
        @if($document->tags->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Tag</h6>
            </div>
            <div class="card-body">
                @foreach($document->tags as $tag)
                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Published --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold">Publikasi</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    @if($document->published_at && $document->published_at->lte(now()))
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </div>
                @if($document->published_at)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Published at</span>
                    <small>{{ $document->published_at->translatedFormat('d M Y H:i') }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
