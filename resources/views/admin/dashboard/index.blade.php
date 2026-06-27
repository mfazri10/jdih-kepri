@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan JDIH Kepri')

@section('content')
    {{-- JDIH Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xxl-3">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-12 text-muted text-uppercase">Total Dokumen</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['documents']) }}</div>
                            <small class="text-muted">{{ $stats['documents_active'] }} berlaku</small>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                            <i class="feather-file-text"></i>
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
                            <div class="fs-12 text-muted text-uppercase">Total Views</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['total_views']) }}</div>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-success text-success">
                            <i class="feather-eye"></i>
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
                            <div class="fs-12 text-muted text-uppercase">Total Downloads</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['total_downloads']) }}</div>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                            <i class="feather-download"></i>
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
                            <div class="fs-12 text-muted text-uppercase">Konsultasi Pending</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['consultations_pending']) }}</div>
                            @if($stats['feedbacks_new'] > 0)
                                <small class="text-danger">{{ $stats['feedbacks_new'] }} feedback baru</small>
                            @endif
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                            <i class="feather-message-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stretch stretch-full">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-primary mb-1">{{ $stats['categories'] }}</h3>
                    <small class="text-muted">Kategori Dokumen</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stretch stretch-full">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-primary mb-1">{{ $stats['types'] }}</h3>
                    <small class="text-muted">Jenis Dokumen</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stretch stretch-full">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-primary mb-1">{{ $stats['users'] }}</h3>
                    <small class="text-muted">Users</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent Documents --}}
        <div class="col-lg-6">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Dokumen Terbaru</h5>
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr><th>Judul</th><th>Jenis</th><th>Tahun</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($recentDocuments as $doc)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.documents.show', $doc) }}" class="fw-semibold text-decoration-none">
                                                {{ Str::limit($doc->title, 50) }}
                                            </a>
                                        </td>
                                        <td><span class="badge bg-info">{{ $doc->type->code }}</span></td>
                                        <td>{{ $doc->year }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">Belum ada dokumen.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Role Summary --}}
        <div class="col-lg-6">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Role Summary</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr><th>Role</th><th>Permissions</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td class="fw-semibold">{{ $role->name }}</td>
                                        <td>{{ $role->permissions_count }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">Belum ada role.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
