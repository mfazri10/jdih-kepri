@extends('layouts.admin')

@section('title', 'Permissions')
@section('page_title', 'Manajemen Permission')
@section('page_subtitle', 'Kelola izin granular untuk setiap modul')

@section('page_actions')
    @can('permissions.create')
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="feather-plus"></i>
            <span>Tambah Permission</span>
        </a>
    @endcan
@endsection

@section('content')
    <x-table.table 
        title="Daftar Hak Akses"
        :headers="['Informasi Izin', 'Modul Terkait', 'Aksi']"
        :records="$permissions"
        :searchRoute="route('admin.permissions.index')"
        :searchValue="$search"
        searchPlaceholder="Cari nama izin..."
    >
        @foreach ($permissions as $permission)
            <tr>
                <td class="ps-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-text avatar-sm bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="feather-key fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark fw-semibold text-capitalize">{{ str_replace(['.', '_'], ' ', $permission->name) }}</h6>
                            <div class="fs-13 text-muted mt-1">{{ $permission->name }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-light text-dark border px-3 py-1 rounded-pill text-capitalize">
                        {{ str($permission->name)->before('.')->headline() }}
                    </span>
                </td>
                <td class="pe-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        @can('permissions.update')
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-light border d-flex align-items-center gap-1">
                                <i class="feather-edit fs-12 text-warning"></i> Edit
                            </a>
                        @endcan
                        @can('permissions.delete')
                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus izin ini secara permanen?')">
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
