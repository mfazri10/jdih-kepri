@extends('layouts.admin')
@section('title', 'Tag Dokumen')
@section('page_title', 'Tag Dokumen')

@section('page_actions')
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="feather-plus"></i> Tambah Tag
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
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $item)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $item->name }}</td>
                            <td><code>{{ $item->slug }}</code></td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.tags.edit', $item) }}" class="btn btn-sm btn-light border">
                                    <i class="feather-edit text-warning"></i>
                                </a>
                                <form action="{{ route('admin.tags.destroy', $item) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus tag ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border">
                                        <i class="feather-trash-2 text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="feather-bookmark fs-2 d-block mb-2 opacity-25"></i>
                                Belum ada tag.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $tags->links() }}</div>
    </div>
@endsection
