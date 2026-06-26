@extends('layouts.admin')

@section('title', 'Log Login')
@section('page_title', 'Audit Log Login')
@section('page_subtitle', 'Histori akses dan deteksi proteksi keamanan autentikasi')

@section('content')
    <x-table.table 
        title="Riwayat Percobaan Login"
        :headers="['User / Email', 'IP Address', 'User Agent', 'Status', 'Waktu']"
        :records="$logs"
    >
        <x-slot name="headerActions">
            <form class="d-flex flex-column flex-sm-row align-items-sm-center gap-2" method="GET">
                <div class="input-group input-group-sm">
                    <select class="form-select border" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Sukses</option>
                        <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="deactivated" {{ $status === 'deactivated' ? 'selected' : '' }}>Nonaktif/Diblokir</option>
                    </select>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="feather-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ $search }}" placeholder="Cari email..." style="min-width: 200px;">
                    <button class="btn btn-primary px-3" type="submit">Filter</button>
                    @if($search !== '' || $status !== '')
                        <a href="{{ route('admin.login-logs.index') }}" class="btn btn-light border">Reset</a>
                    @endif
                </div>
            </form>
        </x-slot>

        @foreach ($logs as $log)
            <tr>
                <td class="ps-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-text avatar-sm {{ $log->status === 'success' ? 'bg-success' : ($log->status === 'deactivated' ? 'bg-warning text-dark' : 'bg-danger') }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-weight: 700;">
                            {{ strtoupper(substr($log->email, 0, 1)) }}
                        </div>
                        <div>
                            @if($log->user)
                                <h6 class="mb-0 text-dark fw-semibold">{{ $log->user->name }}</h6>
                            @else
                                <h6 class="mb-0 text-secondary fw-semibold">Unknown Account</h6>
                            @endif
                            <div class="fs-12 text-muted">{{ $log->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="font-monospace text-dark fs-13">{{ $log->ip_address ?: '-' }}</span>
                </td>
                <td>
                    <div class="text-truncate fs-12 text-secondary" style="max-width: 350px;" title="{{ $log->user_agent }}">
                        {{ $log->user_agent ?: '-' }}
                    </div>
                </td>
                <td>
                    @if ($log->status === 'success')
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 11px;">Sukses</span>
                    @elseif ($log->status === 'deactivated')
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 11px;">Nonaktif</span>
                    @else
                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 11px;">Gagal</span>
                    @endif
                </td>
                <td class="pe-4 text-secondary fs-13">
                    {{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->format('d M Y H:i:s') . ' WIB' : '-' }}
                </td>
            </tr>
        @endforeach
    </x-table.table>
@endsection
