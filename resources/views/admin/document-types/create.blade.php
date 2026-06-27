@extends('layouts.admin')
@section('title', 'Tambah Jenis Dokumen')
@section('page_title', 'Tambah Jenis Dokumen')

@section('content')
<form action="{{ route('admin.document-types.store') }}" method="POST">
    @csrf
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('admin.document-types._form')
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="feather-save me-1"></i> Simpan
            </button>
            <a href="{{ route('admin.document-types.index') }}" class="btn btn-light border px-4">Batal</a>
        </div>
    </div>
</form>
@endsection
