@extends('layouts.admin')

@section('title', 'Tambah Menu')
@section('page_title', 'Tambah Menu')
@section('page_subtitle', 'Buat menu baru untuk sidebar admin')

@section('content')
    <form action="{{ route('admin.menus.store') }}" method="POST">
        @include('admin.menus._form')
    </form>
@endsection
