@extends('layouts.admin')
@section('title', 'Konsultasi Hukum')
@section('page_title', 'Konsultasi Hukum')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama</th>
                        <th>Subjek</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $item)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $item->name }}</td>
                        <td>{{ Str::limit($item->subject, 50) }}</td>
                        <td>
                            @if($item->status === 'pending') <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($item->status === 'answered') <span class="badge bg-success">Dijawab</span>
                            @else <span class="badge bg-secondary">Ditutup</span> @endif
                        </td>
                        <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.consultations.show', $item) }}" class="btn btn-sm btn-light border"><i class="feather-eye text-info"></i></a>
                            <form action="{{ route('admin.consultations.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light border"><i class="feather-trash-2 text-danger"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada konsultasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $consultations->links() }}</div>
</div>
@endsection
