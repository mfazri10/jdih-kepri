@extends('layouts.admin')
@section('title', 'Tematik Dokumen')
@section('page_title', 'Tematik Dokumen')

@section('page_actions')
    <a href="{{ route('admin.themes.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="feather-plus"></i> Tambah Tematik
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Warna</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Icon</th>
                            <th>Dokumen</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($themes as $item)
                        <tr>
                            <td class="ps-4">
                                <span style="display:inline-block;width:24px;height:24px;border-radius:4px;background:{{ $item->color ?? '#6c757d' }};"></span>
                            </td>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td><code>{{ $item->slug }}</code></td>
                            <td><i class="{{ $item->icon ?? 'bi-tag' }}"></i></td>
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
                                <a href="{{ route('admin.themes.edit', $item) }}" class="btn btn-sm btn-light border">
                                    <i class="feather-edit text-warning"></i>
                                </a>
                                <form action="{{ route('admin.themes.destroy', $item) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus tematik ini?')">
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
                                <i class="feather-tag fs-2 d-block mb-2 opacity-25"></i>
                                Belum ada tematik.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $themes->links() }}</div>
    </div>
@endsection
