@extends('layouts.admin')

@section('title', 'Roles')
@section('page_title', 'Manajemen Role')
@section('page_subtitle', 'Kelola role dan mapping permission')

@section('page_actions')
    @can('roles.create')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="feather-plus"></i>
            <span>Tambah Role</span>
        </a>
    @endcan
@endsection

@section('content')
    <x-table.table 
        title="Daftar Role Hak Akses"
        :headers="['Informasi Role', 'Bobot Akses', 'Aksi']"
        :records="$roles"
        :searchRoute="route('admin.roles.index')"
        :searchValue="$search"
        searchPlaceholder="Cari tipe role..."
    >
        @foreach ($roles as $role)
            <tr>
                <td class="ps-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-text avatar-sm bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="feather-star fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark fw-semibold text-capitalize">{{ str_replace('-', ' ', $role->name) }}</h6>
                            <div class="fs-13 text-muted mt-1">Level Otorisasi</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">
                        <i class="feather-key fs-12 me-1 text-muted"></i> {{ $role->permissions_count }} Tipe Set
                    </span>
                </td>
                <td class="pe-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        @can('roles.update')
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-light border d-flex align-items-center gap-1">
                                <i class="feather-edit fs-12 text-warning"></i> Edit
                            </a>
                        @endcan
                        @can('roles.delete')
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini secara permanen?')">
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
