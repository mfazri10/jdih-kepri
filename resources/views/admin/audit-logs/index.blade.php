@extends('layouts.admin')
@section('title', 'Audit Trail')
@section('page_title', 'Audit Trail')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Aksi</th>
                        <th>Tipe</th>
                        <th>ID</th>
                        <th>IP</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">{{ $log->user?->name ?? 'System' }}</td>
                        <td>
                            @if($log->action === 'create') <span class="badge bg-success">Create</span>
                            @elseif($log->action === 'update') <span class="badge bg-warning text-dark">Update</span>
                            @else <span class="badge bg-danger">Delete</span> @endif
                        </td>
                        <td>{{ class_basename($log->auditable_type) }}</td>
                        <td>{{ $log->auditable_id }}</td>
                        <td><small>{{ $log->ip_address }}</small></td>
                        <td>{{ $log->created_at?->translatedFormat('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada log.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $logs->links() }}</div>
</div>
@endsection
