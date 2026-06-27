@extends('layouts.admin')
@section('title', 'Kategori Dokumen')
@section('page_title', 'Kategori Dokumen')

@section('page_actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="feather-plus"></i> Tambah Kategori
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Slug</th>
                            <th>Induk</th>
                            <th>Icon</th>
                            <th>Dokumen</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $item)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $item->name }}</td>
                            <td><code>{{ $item->slug }}</code></td>
                            <td>{{ $item->parent?->name ?? '—' }}</td>
                            <td><i class="{{ $item->icon ?? 'bi-folder' }}"></i></td>
                            <td><span class="badge bg-primary">{{ $item->documents_count }}</span></td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.categories.edit', $item) }}" class="btn btn-sm btn-light border">
                                    <i class="feather-edit text-warning"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $item) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border">
                                        <i class="feather-trash-2 text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="feather-folder fs-2 d-block mb-2 opacity-25"></i>
                                Belum ada kategori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $categories->links() }}</div>
    </div>
@endsection
