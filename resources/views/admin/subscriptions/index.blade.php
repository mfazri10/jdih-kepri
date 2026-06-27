@extends('layouts.admin')
@section('title', 'Langganan Notifikasi')
@section('page_title', 'Langganan Notifikasi')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Email</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Channel</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $item)
                    <tr>
                        <td class="ps-4">{{ $item->email }}</td>
                        <td>{{ $item->category?->name ?? 'Semua' }}</td>
                        <td>{{ $item->type?->code ?? 'Semua' }}</td>
                        <td><span class="badge bg-info">{{ $item->channel }}</span></td>
                        <td>
                            @if($item->is_active) <span class="badge bg-success">Aktif</span>
                            @else <span class="badge bg-secondary">Nonaktif</span> @endif
                        </td>
                        <td class="pe-4 text-end">
                            <form action="{{ route('admin.subscriptions.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light border"><i class="feather-trash-2 text-danger"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada langganan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $subscriptions->links() }}</div>
</div>
@endsection
