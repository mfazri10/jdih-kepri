@extends('layouts.admin')
@section('title', 'Public Hearing')
@section('page_title', 'Public Hearing')

@section('page_actions')
    <a href="{{ route('admin.hearings.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="feather-plus"></i> Tambah Hearing
    </a>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Masukan</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hearings as $item)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ Str::limit($item->title, 60) }}</td>
                        <td>{{ $item->start_date->translatedFormat('d M Y') }} - {{ $item->end_date->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($item->status === 'open') <span class="badge bg-success">Buka</span>
                            @elseif($item->status === 'closed') <span class="badge bg-danger">Tutup</span>
                            @else <span class="badge bg-secondary">Arsip</span> @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $item->submissions_count }}</span></td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.hearings.show', $item) }}" class="btn btn-sm btn-light border"><i class="feather-eye text-info"></i></a>
                            <a href="{{ route('admin.hearings.edit', $item) }}" class="btn btn-sm btn-light border"><i class="feather-edit text-warning"></i></a>
                            <form action="{{ route('admin.hearings.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light border"><i class="feather-trash-2 text-danger"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada public hearing.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $hearings->links() }}</div>
</div>
@endsection
