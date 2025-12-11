@extends('admin.layouts.admin')
@section('title', 'Modifier Kiosque')
@section('page-title', 'Modifier Kiosque: ' . $kiosk->name)

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.kiosks.update', $kiosk) }}" method="POST">
            @include('admin.kiosks._form')
        </form>
    </div>
</div>
@endsection