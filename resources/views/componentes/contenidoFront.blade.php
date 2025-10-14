@extends('front.layouts.header')
@section('contenido')
@btnCerrarSesion

{{ $slot }}

@endsection