@extends('layouts.front')
@section('title', 'Cari Dokumen')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4"><i class="bi bi-search me-2"></i>Cari Dokumen Hukum</h2>

        {{-- Search Form --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('front.jdih.search') }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="q" class="form-control" placeholder="Kata kunci..."
                                   value="{{ $query }}">
                        </div>
                        <div class="col-md-2">
                            <select name="type_id" class="form-select">
                                <option value="">Semua Jenis</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ $typeId == $type->id ? 'selected' : '' }}>{{ $type->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Cari</button>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-2">
                            <select name="year" class="form-select form-select-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="berlaku" {{ $status === 'berlaku' ? 'selected' : '' }}>Berlaku</option>
                                <option value="dicabut" {{ $status === 'dicabut' ? 'selected' : '' }}>Dicabut</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}" placeholder="Dari">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}" placeholder="Sampai">
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('front.jdih.search') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Results --}}
        <div class="row">
            <div class="col-lg-9">
                @if($query)
                    <p class="text-muted mb-3">Ditemukan <strong>{{ $documents->total() }}</strong> dokumen untuk "<strong>{{ $query }}</strong>"</p>
                @endif

                @forelse($documents as $doc)
                <div class="card border-0 shadow-sm mb-3 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge bg-info">{{ $doc->type->code }}</span>
                            @if($doc->category)
                                <span class="badge bg-light text-dark border">{{ $doc->category->name }}</span>
                            @endif
                            @if($doc->status === 'berlaku')
                                <span class="badge badge-status-berlaku">Berlaku</span>
                            @elseif($doc->status === 'dicabut')
                                <span class="badge badge-status-dicabut">Dicabut</span>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-2">
                            <a href="{{ route('front.jdih.show', $doc->slug) }}" class="text-decoration-none text-dark">
                                {{ $doc->title }}
                            </a>
                        </h5>
                        <p class="text-muted mb-2 small">No. {{ $doc->number }}/{{ $doc->year }} &mdash; {{ $doc->teu }}</p>
                        @if($doc->abstract)
                            <p class="mb-2 small">{{ Str::limit($doc->abstract, 200) }}</p>
                        @endif
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i> {{ $doc->published_at?->translatedFormat('d M Y') ?? $doc->year }}
                            <span class="ms-3"><i class="bi bi-eye me-1"></i> {{ $doc->views_count }}</span>
                        </small>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted d-block mb-3 opacity-25"></i>
                    <h5 class="text-muted">Tidak ada dokumen ditemukan</h5>
                    <p class="text-muted">Coba gunakan kata kunci yang berbeda atau kurangi filter.</p>
                </div>
                @endforelse

                <div class="mt-4">{{ $documents->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-3">
                @if($popularSearches->isNotEmpty())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-fire me-1"></i> Pencarian Populer</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($popularSearches as $ps)
                        <a href="{{ route('front.jdih.search', ['q' => $ps->query]) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between">
                            <span>{{ $ps->query }}</span>
                            <small class="text-muted">{{ $ps->hit_count }}x</small>
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
