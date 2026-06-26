@extends('layouts.admin')

@section('title', 'Edit User')
@section('page_title', 'Edit User')
@section('page_subtitle', 'Perbarui detail profil, role, dan hak akses eksklusif')

@section('content')
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @include('admin.users._form')
    </form>

    <div class="card stretch stretch-full border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="card-title fw-semibold mb-0">Effective Permission (Total Akses Tersedia)</h6>
        </div>
        <div class="card-body py-4">
            <div class="d-flex flex-wrap gap-2">
                @forelse ($effectivePermissions as $permission)
                    <span class="badge bg-light text-dark border p-2 text-capitalize">{{ str_replace(['_', '.'], ' ', $permission) }}</span>
                @empty
                    <span class="text-muted fs-13">User ini belum memiliki permission apapun yang berlaku (Kosong dari role maupun direct permission).</span>
                @endforelse
            </div>
        </div>
    </div>
@endsection
