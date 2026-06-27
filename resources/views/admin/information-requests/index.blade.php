@extends('layouts.admin')
@section('title', 'Permintaan Informasi')
@section('page_title', 'Permintaan Informasi Hukum')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No. Register</th>
                        <th>Nama</th>
                        <th>Subjek</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Batas</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $item)
                    <tr>
                        <td class="ps-4"><code>{{ $item->register_number }}</code></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Str::limit($item->subject, 40) }}</td>
                        <td><span class="badge bg-info">{{ $item->request_type }}</span></td>
                        <td>
                            @if($item->status === 'pending') <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($item->status === 'processing') <span class="badge bg-info">Proses</span>
                            @elseif($item->status === 'fulfilled') <span class="badge bg-success">Selesai</span>
                            @else <span class="badge bg-danger">Ditolak</span> @endif
                        </td>
                        <td>{{ $item->due_date?->translatedFormat('d M Y') ?? '—' }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.information-requests.show', $item) }}" class="btn btn-sm btn-light border"><i class="feather-eye text-info"></i></a>
                            <form action="{{ route('admin.information-requests.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light border"><i class="feather-trash-2 text-danger"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada permintaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $requests->links() }}</div>
</div>
@endsection
