@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan implementasi RBAC dan navigasi admin')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xxl-3">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-12 text-muted text-uppercase">Roles</div>
                            <div class="fs-3 fw-bold">{{ $stats['roles'] }}</div>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                            <i class="feather-award"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-12 text-muted text-uppercase">Permissions</div>
                            <div class="fs-3 fw-bold">{{ $stats['permissions'] }}</div>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-success text-success">
                            <i class="feather-key"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-12 text-muted text-uppercase">Users</div>
                            <div class="fs-3 fw-bold">{{ $stats['users'] }}</div>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                            <i class="feather-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-12 text-muted text-uppercase">Menus</div>
                            <div class="fs-3 fw-bold">{{ $stats['menus'] }}</div>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                            <i class="feather-menu"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Role Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Permission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td class="fw-semibold">{{ $role->name }}</td>
                                        <td>{{ $role->permissions_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Belum ada role.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">User Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $user)
                                    <tr>
                                        <td class="fw-semibold">{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->getRoleNames()->join(', ') ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Menu Seeded</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($menus as $menu)
                            <div class="col-md-6 col-xl-3">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="avatar-text avatar-md bg-light text-primary">
                                            <i class="{{ $menu->ikon ?: 'feather-circle' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $menu->nama_menu }}</div>
                                            <div class="fs-12 text-muted">{{ $menu->nama_fitur ?: 'Tanpa fitur' }}</div>
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted">{{ $menu->permission_name ?: 'Tanpa permission khusus' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
