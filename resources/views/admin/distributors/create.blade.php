@extends('admin.layouts.admin')
@section('title', 'Nouveau Distributeur')
@section('page-title', 'Nouveau Distributeur')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.distributors.store') }}" method="POST">
            @include('admin.distributors._form')
        </form>
    </div>
</div>
@endsection