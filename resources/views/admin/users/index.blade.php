@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Manajemen User')
@section('page_subtitle', 'Kelola akun, role, dan direct permission')

@section('page_actions')
    @can('users.create')
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="feather-plus"></i>
            <span>Tambah User</span>
        </a>
    @endcan
@endsection

@section('content')
    <x-table.table 
        title="Daftar Pengguna"
        :headers="['User Info', 'Role', 'Direct Permission', 'Status', 'Aksi']"
        :records="$users"
        :searchRoute="route('admin.users.index')"
        :searchValue="$search"
        searchPlaceholder="Cari nama atau email..."
    >
        @foreach ($users as $user)
            <tr>
                <td class="ps-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-text avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark fw-semibold">{{ $user->name }}</h6>
                            <div class="fs-13 text-muted">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($user->roles->isEmpty())
                        <span class="badge bg-light text-secondary border">-</span>
                    @else
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 shadow-sm">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td>
                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">
                        <i class="feather-key fs-12 me-1 text-muted"></i> {{ $user->permissions->count() }} Set
                    </span>
                </td>
                <td>
                    @if ($user->is_active)
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 11px;">Aktif</span>
                    @else
                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 11px;">Nonaktif</span>
                    @endif
                </td>
                <td class="pe-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        @can('users.update')
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-light border d-flex align-items-center gap-1">
                                <i class="feather-edit fs-12 text-warning"></i> Edit
                            </a>
                        @endcan
                        @can('users.delete')
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border d-flex align-items-center gap-1">
                                    <i class="feather-trash-2 fs-12 text-danger"></i> Hapus
                                </button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table.table>
@endsection
