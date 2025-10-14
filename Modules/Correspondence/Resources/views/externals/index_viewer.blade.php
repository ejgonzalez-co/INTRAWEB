@extends('layouts.default')

@section('title', 'Correspondencia Enviada')

@section('section_img', '/assets/img/components/intranet_poll.png')



@section('content')

<viewer-public execute-url-axios-validar="/correspondence/validar-correspondence-external-code?c={{ $code }}" ></viewer-attachement>

@endsection