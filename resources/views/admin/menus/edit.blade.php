@extends('layouts.admin')

@section('title', 'Edit Menu')
@section('page_title', 'Edit Menu')
@section('page_subtitle', 'Perbarui data dan relasi permission untuk menu')

@section('content')
    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
        @include('admin.menus._form')
    </form>
@endsection
