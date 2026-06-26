@extends('layouts.admin')

@section('title', 'Tambah Permission')
@section('page_title', 'Tambah Permission')
@section('page_subtitle', 'Buat permission baru untuk modul tertentu')

@section('content')
    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @include('admin.permissions._form')
    </form>
@endsection
