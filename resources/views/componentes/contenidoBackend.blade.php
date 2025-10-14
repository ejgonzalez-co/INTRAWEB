@extends('backend.layouts.header')
@section('contenido')
@btnCerrarSesion
@include('alerts.success')
@include('alerts.warning')
@include('alerts.error')
{{ $slot }}

@endsection