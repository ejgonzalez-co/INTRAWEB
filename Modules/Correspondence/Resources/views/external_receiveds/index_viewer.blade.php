@extends('layouts.default')

@section('title', trans('External Correspondence Received'))

@section('section_img', '/assets/img/components/intranet_poll.png')



@section('content')

<viewer-public execute-url-axios-validar="/correspondence/validar-correspondence-received-code?c={{ $code }}" ></viewer-attachement>

@endsection