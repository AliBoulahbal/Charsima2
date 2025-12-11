@extends('admin.layouts.admin')
@section('title', 'Nouvelle Livraison')
@section('page-title', 'Nouvelle Livraison')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.deliveries.store') }}" method="POST">
            @include('admin.deliveries._form')
        </form>
    </div>
</div>
@endsection