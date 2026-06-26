@extends('layouts.admin')

@section('title', 'Edit Permission')
@section('page_title', 'Edit Permission')
@section('page_subtitle', 'Perbarui nama permission')

@section('content')
    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
        @include('admin.permissions._form')
    </form>
@endsection
