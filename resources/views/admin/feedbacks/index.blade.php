@extends('layouts.admin')
@section('title', 'Umpan Balik')
@section('page_title', 'Umpan Balik & Survei')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama</th>
                        <th>Tipe</th>
                        <th>Subjek</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $item)
                    <tr>
                        <td class="ps-4">{{ $item->name ?? $item->user?->name ?? 'Anonim' }}</td>
                        <td>
                            @if($item->type === 'saran') <span class="badge bg-info">Saran</span>
                            @elseif($item->type === 'masalah') <span class="badge bg-danger">Masalah</span>
                            @else <span class="badge bg-success">Pujian</span> @endif
                        </td>
                        <td>{{ Str::limit($item->subject, 40) }}</td>
                        <td>
                            @if($item->rating)
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $item->rating ? '-fill text-warning' : '' }}"></i>
                                @endfor
                            @else — @endif
                        </td>
                        <td>
                            @if($item->status === 'new') <span class="badge bg-warning text-dark">Baru</span>
                            @elseif($item->status === 'read') <span class="badge bg-info">Dibaca</span>
                            @else <span class="badge bg-success">Selesai</span> @endif
                        </td>
                        <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.feedbacks.show', $item) }}" class="btn btn-sm btn-light border"><i class="feather-eye text-info"></i></a>
                            <form action="{{ route('admin.feedbacks.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light border"><i class="feather-trash-2 text-danger"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada feedback.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $feedbacks->links() }}</div>
</div>
@endsection
