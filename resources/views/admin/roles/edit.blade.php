@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')
@section('page_subtitle', 'Perbarui nama role dan permission')

@section('content')
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @include('admin.roles._form')
    </form>
@endsection
