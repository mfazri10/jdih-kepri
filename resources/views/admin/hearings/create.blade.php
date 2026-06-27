@extends('layouts.admin')
@section('title', 'Tambah Public Hearing')
@section('page_title', 'Tambah Public Hearing')

@section('content')
<form action="{{ route('admin.hearings.store') }}" method="POST">
    @csrf
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
            @include('admin.hearings._form')
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="feather-save me-1"></i> Simpan</button>
            <a href="{{ route('admin.hearings.index') }}" class="btn btn-light border px-4">Batal</a>
        </div>
    </div>
</form>
@endsection
