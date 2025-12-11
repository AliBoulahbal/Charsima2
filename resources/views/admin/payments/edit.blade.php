@extends('admin.layouts.admin')
@section('title', 'Modifier Paiement')
@section('page-title', 'Modifier Paiement')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
            @method('PUT')
            @include('admin.payments._form')
        </form>
    </div>
</div>
@endsection