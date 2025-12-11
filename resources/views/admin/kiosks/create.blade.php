@extends('admin.layouts.admin')
@section('title', 'Nouveau Kiosque')
@section('page-title', 'Ajouter un Kiosque')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.kiosks.store') }}" method="POST">
            @include('admin.kiosks._form')
        </form>
    </div>
</div>
@endsection