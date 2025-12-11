@extends('admin.layouts.admin')
@section('title', 'Modifier École')
@section('page-title', 'Modifier École')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.schools.update', $school) }}" method="POST">
            @method('PUT')
            @include('admin.schools._form')
        </form>
    </div>
</div>
@endsection