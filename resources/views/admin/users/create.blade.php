@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page_title', 'Tambah User')
@section('page_subtitle', 'Buat akun baru dan tetapkan role')

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST">
        @include('admin.users._form')
    </form>
@endsection
