@extends('layouts.admin')

@section('title', 'Menu Admin')
@section('page_title', 'Setting Menu')
@section('page_subtitle', 'Kelola navigasi sidebar berbasis database')

@section('page_actions')
    @can('menus.create')
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="feather-plus"></i>
            <span>Tambah Menu</span>
        </a>
    @endcan
@endsection

@section('content')
    <x-table.table 
        title="Daftar Menu Sidebar"
        :headers="[
            'Info Menu',
            'URL / Route',
            'Induk',
            'Permission',
            ['label' => 'Status', 'align' => 'center'],
            'Aksi'
        ]"
        :records="$menus"
        :searchRoute="route('admin.menus.index')"
        :searchValue="$search"
        searchPlaceholder="Cari nama menu..."
    >
        @foreach ($menus as $menu)
            <tr>
                <td class="ps-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-text avatar-sm bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="{{ $menu->ikon ?: 'feather-menu' }} fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark fw-semibold">{{ $menu->nama_menu }}</h6>
                            <div class="fs-13 text-muted mt-1">{{ $menu->nama_fitur ?: 'Tanpa Fitur' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="fs-14 fw-medium text-dark">{{ $menu->alamat_url ?: '-' }}</div>
                    <div class="fs-12 text-muted">{{ $menu->route_name ?: 'Tanpa Route' }}</div>
                </td>
                <td>
                    @if ($menu->parent)
                        <span class="badge bg-light text-dark border px-2 py-1">
                            <i class="feather-corner-up-left fs-12 me-1"></i>
                            {{ $menu->parent->nama_menu }}
                        </span>
                    @else
                        <span class="text-muted fs-13">Menu Utama</span>
                    @endif
                </td>
                <td>
                    <span class="fs-13 {{ $menu->permission_name ? 'text-dark' : 'text-muted' }}">
                        {{ $menu->permission_name ?: 'Publik/Semua' }}
                    </span>
                </td>
                <td class="text-center">
                    @if ($menu->is_active)
                        <span class="badge bg-success text-white rounded-pill px-3 py-1 shadow-sm">Aktif</span>
                    @else
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1 shadow-sm">Nonaktif</span>
                    @endif
                </td>
                <td class="pe-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        @can('menus.update')
                            <a href="{{ route('admin.menus.edit', $menu) }}"
                                class="btn btn-sm btn-light border d-flex align-items-center gap-1">
                                <i class="feather-edit fs-12 text-warning"></i> Edit
                            </a>
                        @endcan
                        @can('menus.delete')
                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
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
