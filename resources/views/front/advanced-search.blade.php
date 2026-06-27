@extends('layouts.front')
@section('title', 'Pencarian Lanjutan')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4"><i class="bi bi-search me-2"></i>Pencarian Lanjutan</h2>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('front.jdih.search') }}">
                    {{-- Kata Kunci --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kata Kunci</label>
                        <input type="text" name="q" class="form-control form-control-lg"
                               placeholder="Masukkan kata kunci pencarian..." autofocus>
                        <div class="form-text">Cari berdasarkan judul, abstrak, TEU, atau nomor dokumen.</div>
                    </div>

                    <hr class="my-4">

                    {{-- Filter Utama --}}
                    <h6 class="fw-semibold mb-3"><i class="bi bi-funnel me-1"></i> Filter Dokumen</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Jenis Dokumen</label>
                            <select name="type_id" class="form-select">
                                <option value="">Semua Jenis</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun</label>
                            <select name="year" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter Tambahan --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="berlaku">Berlaku</option>
                                <option value="dicabut">Dicabut</option>
                                <option value="tidak_berlaku">Tidak Berlaku</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Terbit Dari</label>
                            <input type="date" name="date_from" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Terbit Sampai</label>
                            <input type="date" name="date_to" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Urutan</label>
                            <select name="sort" class="form-select">
                                <option value="relevance">Relevansi</option>
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="title">Judul A-Z</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search me-1"></i> Cari Dokumen
                        </button>
                        <a href="{{ route('front.jdih.search') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                        <a href="{{ route('front.jdih') }}" class="btn btn-outline-dark ms-auto">
                            <i class="bi bi-house me-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
