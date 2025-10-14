@extends('front.layouts.header')
@section('contenido')
<div class="container-fluid contenido">
    @include('alerts.warning')
    @include('alerts.success')
    @include('alerts.error')
    {{ $slot }}
</div>
@endsection