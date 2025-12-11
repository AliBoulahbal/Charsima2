@extends('admin.layouts.admin')
@section('title', 'Nouvel Utilisateur')
@section('page-title', 'Nouvel Utilisateur')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @include('admin.users._form')
        </form>
    </div>
</div>
@endsection