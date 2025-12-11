@extends('admin.layouts.admin')
@section('title', 'Nouvelle École')
@section('page-title', 'Nouvelle École')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.schools.store') }}" method="POST">
            @include('admin.schools._form')
        </form>
    </div>
</div>
@endsection