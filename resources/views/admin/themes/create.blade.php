@extends('layouts.admin')
@section('title', 'Tambah Tematik')
@section('page_title', 'Tambah Tematik')

@section('content')
<form action="{{ route('admin.themes.store') }}" method="POST">
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
            @include('admin.themes._form')
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="feather-save me-1"></i> Simpan</button>
            <a href="{{ route('admin.themes.index') }}" class="btn btn-light border px-4">Batal</a>
        </div>
    </div>
</form>
@endsection
