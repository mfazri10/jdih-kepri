@extends('layouts.front')
@section('title', $document->title)

@section('content')
<section class="py-5">
    <div class="container">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.jdih') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('front.jdih.search') }}" class="text-decoration-none">Dokumen</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($document->title, 50) }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 mb-3">
                            <span class="badge bg-info">{{ $document->type->name }}</span>
                            @if($document->category)
                                <span class="badge bg-light text-dark border">{{ $document->category->name }}</span>
                            @endif
                            @if($document->status === 'berlaku')
                                <span class="badge badge-status-berlaku">Berlaku</span>
                            @elseif($document->status === 'dicabut')
                                <span class="badge badge-status-dicabut">Dicabut</span>
                            @else
                                <span class="badge badge-status-tidak_berlaku">Tidak Berlaku</span>
                            @endif
                        </div>

                        <h3 class="fw-bold mb-3">{{ $document->title }}</h3>

                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width:200px;">Nomor</td>
                                <td><strong>{{ $document->number }} / {{ $document->year }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tajuk Entri Utama</td>
                                <td>{{ $document->teu }}</td>
                            </tr>
                            @if($document->subject)
                            <tr><td class="text-muted">Subjek Hukum</td><td>{{ $document->subject }}</td></tr>
                            @endif
                            @if($document->source)
                            <tr><td class="text-muted">Sumber</td><td>{{ $document->source }}</td></tr>
                            @endif
                            @if($document->signatory)
                            <tr><td class="text-muted">Penandatangan</td><td>{{ $document->signatory }}</td></tr>
                            @endif
                            @if($document->place)
                            <tr><td class="text-muted">Tempat Terbit</td><td>{{ $document->place }}</td></tr>
                            @endif
                            @if($document->date_set)
                            <tr><td class="text-muted">Tanggal Penetapan</td><td>{{ $document->date_set->translatedFormat('d F Y') }}</td></tr>
                            @endif
                            @if($document->date_publish)
                            <tr><td class="text-muted">Tanggal Pengundangan</td><td>{{ $document->date_publish->translatedFormat('d F Y') }}</td></tr>
                            @endif
                            @if($document->date_effective)
                            <tr><td class="text-muted">Tanggal Berlaku</td><td>{{ $document->date_effective->translatedFormat('d F Y') }}</td></tr>
                            @endif
                        </table>

                        @if($document->abstract)
                        <hr>
                        <h6 class="fw-semibold mb-2">Abstrak</h6>
                        <p>{{ $document->abstract }}</p>
                        @endif
                    </div>
                </div>

                {{-- Attachments --}}
                @if($document->attachments->isNotEmpty())
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-paperclip me-1"></i> Lampiran ({{ $document->attachments->count() }})</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($document->attachments as $att)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                                <strong>{{ $att->original_name }}</strong>
                                <small class="text-muted ms-2">({{ $att->formatted_size }})</small>
                            </div>
                            <a href="{{ route('front.jdih.download', [$document->slug, $att->id]) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Full Text --}}
                @if($document->full_text)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold">Isi Dokumen</h6>
                    </div>
                    <div class="card-body">
                        <pre style="white-space: pre-wrap; font-size: 0.9rem;">{{ $document->full_text }}</pre>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Meta --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Dilihat</span>
                            <strong>{{ number_format($document->views_count) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Diunduh</span>
                            <strong>{{ number_format($document->downloads_count) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Bahasa</span>
                            <span>{{ strtoupper($document->language) }}</span>
                        </div>
                        @if($document->published_at)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Dipublikasi</span>
                            <small>{{ $document->published_at->translatedFormat('d M Y') }}</small>
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
                            <a href="{{ route('front.jdih.theme-show', $theme->slug) }}" class="badge text-decoration-none me-1 mb-1"
                               style="background: {{ $theme->color ?? '#6c757d' }};">
                                {{ $theme->name }}
                            </a>
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

                {{-- Related Documents --}}
                @if($relatedDocs->isNotEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold">Dokumen Terkait</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($relatedDocs as $related)
                        <a href="{{ route('front.jdih.show', $related->slug) }}"
                           class="list-group-item list-group-item-action">
                            <span class="badge bg-info mb-1">{{ $related->type->code }}</span>
                            <br>
                            <small class="fw-semibold">{{ Str::limit($related->title, 80) }}</small>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
