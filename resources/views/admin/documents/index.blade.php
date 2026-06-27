@extends('layouts.admin')
@section('title', 'Dokumen Hukum')
@section('page_title', 'Dokumen Hukum')

@section('page_actions')
    <a href="{{ route('admin.documents.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="feather-plus"></i> Tambah Dokumen
    </a>
@endsection

@section('content')
    {{-- Filter Bar --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, nomor, TEU..."
                           value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <select name="type_id" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ $typeId == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="berlaku" {{ $status === 'berlaku' ? 'selected' : '' }}>Berlaku</option>
                        <option value="dicabut" {{ $status === 'dicabut' ? 'selected' : '' }}>Dicabut</option>
                        <option value="tidak_berlaku" {{ $status === 'tidak_berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="year" class="form-select">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="feather-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Judul</th>
                            <th>Nomor</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Lampiran</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $item)
                        <tr>
                            <td class="ps-4">{{ ($documents->currentPage() - 1) * $documents->perPage() + $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('admin.documents.show', $item) }}" class="fw-semibold text-decoration-none">
                                    {{ Str::limit($item->title, 80) }}
                                </a>
                            </td>
                            <td>{{ $item->number }}</td>
                            <td><span class="badge bg-info">{{ $item->type->code }}</span></td>
                            <td>{{ $item->category?->name ?? '—' }}</td>
                            <td>{{ $item->year }}</td>
                            <td>
                                @if($item->status === 'berlaku')
                                    <span class="badge bg-success">Berlaku</span>
                                @elseif($item->status === 'dicabut')
                                    <span class="badge bg-danger">Dicabut</span>
                                @else
                                    <span class="badge bg-warning text-dark">Tidak Berlaku</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $item->attachments->count() }}</span></td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.documents.show', $item) }}" class="btn btn-sm btn-light border" title="Lihat">
                                    <i class="feather-eye text-info"></i>
                                </a>
                                <a href="{{ route('admin.documents.edit', $item) }}" class="btn btn-sm btn-light border" title="Edit">
                                    <i class="feather-edit text-warning"></i>
                                </a>
                                <form action="{{ route('admin.documents.destroy', $item) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus dokumen ini? Semua lampiran juga akan dihapus.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border" title="Hapus">
                                        <i class="feather-trash-2 text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="feather-file-text fs-2 d-block mb-2 opacity-25"></i>
                                Belum ada dokumen hukum.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $documents->links() }}</div>
    </div>
@endsection
