@extends('layouts.admin')
@section('title', 'Jenis Dokumen')
@section('page_title', 'Jenis Dokumen')

@section('page_actions')
    <a href="{{ route('admin.document-types.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="feather-plus"></i> Tambah Jenis
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Kode</th>
                            <th>Nama Jenis</th>
                            <th>Induk</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentTypes as $item)
                        <tr>
                            <td class="ps-4"><code>{{ $item->code }}</code></td>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td>{{ $item->parent?->name ?? '—' }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.document-types.edit', $item) }}"
                                   class="btn btn-sm btn-light border">
                                    <i class="feather-edit text-warning"></i>
                                </a>
                                <form action="{{ route('admin.document-types.destroy', $item) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus jenis dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border">
                                        <i class="feather-trash-2 text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="feather-layers fs-2 d-block mb-2 opacity-25"></i>
                                Belum ada jenis dokumen.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $documentTypes->links() }}</div>
    </div>
@endsection
