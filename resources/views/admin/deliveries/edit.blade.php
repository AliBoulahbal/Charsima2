@extends('admin.layouts.admin')
@section('title', 'Modifier Livraison')
@section('page-title', 'Modifier Livraison')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.deliveries.update', $delivery) }}" method="POST">
            @method('PUT')
            @include('admin.deliveries._form')
        </form>
    </div>
</div>
@endsection