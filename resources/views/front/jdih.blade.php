@extends('layouts.front')
@section('title', 'Beranda')

@section('content')
{{-- Hero Section --}}
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Jaringan Dokumentasi dan Informasi Hukum</h1>
        <p class="lead mb-4 opacity-75">Provinsi Kepulauan Riau</p>

        {{-- Search Box --}}
        <div class="search-box">
            <form action="{{ route('front.jdih.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari dokumen hukum..."
                           value="{{ request('q') }}" id="searchInput" autocomplete="off">
                    <button class="btn btn-warning fw-semibold" type="submit">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
                <div id="suggestions" class="bg-white rounded shadow-sm mt-1 d-none" style="position:relative; z-index:10;"></div>
            </form>
        </div>

        <div class="mt-3">
            <a href="{{ route('front.jdih.advanced-search') }}" class="text-white text-decoration-none small opacity-75">
                <i class="bi bi-sliders me-1"></i> Pencarian Lanjutan
            </a>
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="stat-card bg-white rounded p-3">
                    <h3 class="fw-bold text-primary mb-1">{{ number_format($totalDocs) }}</h3>
                    <small class="text-muted">Dokumen Hukum</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-white rounded p-3">
                    <h3 class="fw-bold text-primary mb-1">{{ $totalTypes }}</h3>
                    <small class="text-muted">Jenis Dokumen</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card bg-white rounded p-3">
                    <h3 class="fw-bold text-primary mb-1">{{ $totalCategories }}</h3>
                    <small class="text-muted">Kategori</small>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Documents --}}
@if($featuredDocs->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h6 class="text-uppercase fw-bold text-muted" style="letter-spacing: 3px; font-size: 0.8rem;">Unggulan</h6>
            <h2 class="fw-bold">Dokumen Hukum Terbaru</h2>
        </div>
        <div class="row g-4">
            @foreach($featuredDocs as $doc)
            <div class="col-md-4">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="badge bg-info mb-2">{{ $doc->type->code }}</span>
                        @if($doc->category)
                            <span class="badge bg-light text-dark border mb-2">{{ $doc->category->name }}</span>
                        @endif
                        <h6 class="fw-bold mb-2">
                            <a href="{{ route('front.jdih.show', $doc->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($doc->title, 100) }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $doc->published_at?->translatedFormat('d M Y') ?? $doc->year }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Thematic Browsing --}}
@if($themes->isNotEmpty())
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h6 class="text-uppercase fw-bold text-muted" style="letter-spacing: 3px; font-size: 0.8rem;">Telusuri</h6>
            <h2 class="fw-bold">Berdasarkan Tematik</h2>
        </div>
        <div class="row g-3">
            @foreach($themes as $theme)
            <div class="col-md-3 col-6">
                <a href="{{ route('front.jdih.theme-show', $theme->slug) }}" class="text-decoration-none">
                    <div class="card theme-card card-hover border-0 shadow-sm h-100" style="border-left-color: {{ $theme->color ?? '#2563eb' }} !important;">
                        <div class="card-body p-3">
                            <i class="{{ $theme->icon ?? 'bi-tag' }} fs-4 mb-2 d-block" style="color: {{ $theme->color ?? '#2563eb' }};"></i>
                            <h6 class="fw-semibold text-dark mb-1">{{ $theme->name }}</h6>
                            <small class="text-muted">{{ $theme->documents_count }} dokumen</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Latest Documents --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h6 class="text-uppercase fw-bold text-muted" style="letter-spacing: 3px; font-size: 0.8rem;">Terbaru</h6>
            <h2 class="fw-bold">Dokumen Terbaru</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestDocs as $doc)
                    <tr>
                        <td>
                            <a href="{{ route('front.jdih.show', $doc->slug) }}" class="text-decoration-none fw-semibold">
                                {{ Str::limit($doc->title, 80) }}
                            </a>
                        </td>
                        <td><span class="badge bg-info">{{ $doc->type->code }}</span></td>
                        <td>{{ $doc->category?->name ?? '—' }}</td>
                        <td>{{ $doc->year }}</td>
                        <td>{{ $doc->published_at?->translatedFormat('d M Y') ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada dokumen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Browse by Type --}}
@if($types->isNotEmpty())
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Jenis Dokumen</h2>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($types as $type)
            <div class="col-md-2 col-4">
                <a href="{{ route('front.jdih.type-show', $type->code) }}" class="text-decoration-none">
                    <div class="card card-hover border-0 shadow-sm text-center p-3 h-100">
                        <h5 class="fw-bold text-primary mb-1">{{ $type->code }}</h5>
                        <small class="text-muted">{{ $type->name }}</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('searchInput');
    const suggestions = document.getElementById('suggestions');
    let timeout;

    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const q = this.value.trim();

        if (q.length < 2) {
            suggestions.classList.add('d-none');
            return;
        }

        timeout = setTimeout(() => {
            fetch(`{{ route('front.jdih.suggest') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.length === 0) {
                        suggestions.classList.add('d-none');
                        return;
                    }
                    let html = '<div class="list-group list-group-flush">';
                    data.forEach(doc => {
                        html += `<a href="${doc.url}" class="list-group-item list-group-item-action">
                            <strong>${doc.title}</strong><br>
                            <small class="text-muted">No. ${doc.number}/${doc.year}</small>
                        </a>`;
                    });
                    html += '</div>';
                    suggestions.innerHTML = html;
                    suggestions.classList.remove('d-none');
                });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.classList.add('d-none');
        }
    });
});
</script>
@endsection
