@extends('layouts.admin')

@section('title', 'Tambah Role')
@section('page_title', 'Tambah Role')
@section('page_subtitle', 'Buat role baru dan tetapkan permission')

@section('content')
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @include('admin.roles._form')
    </form>
@endsection
