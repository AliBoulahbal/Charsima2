@extends('admin.layouts.admin')
@section('title', 'Modifier Utilisateur')
@section('page-title', 'Modifier Utilisateur')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @method('PUT')
            @include('admin.users._form')
        </form>
    </div>
</div>
@endsection