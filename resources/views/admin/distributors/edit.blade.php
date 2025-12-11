@extends('admin.layouts.admin')
@section('title', 'Modifier Distributeur')
@section('page-title', 'Modifier Distributeur')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.distributors.update', $distributor) }}" method="POST">
            @method('PUT')
            @include('admin.distributors._form')
        </form>
    </div>
</div>
@endsection