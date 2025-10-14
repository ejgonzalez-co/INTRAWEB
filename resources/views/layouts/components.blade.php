@extends('layouts.default')

@section('title', trans('components'))

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')

    <h1 class="page-header text-center m-b-40 m-t-30">{!! $dataModule->title !!}</h1>

    <div class="row row-space-10 justify-content-center">
        @foreach($dataModule->components as $component)
        <div class="col-md-2 p-30">
            <a href="{{ $component->redirect }}" class="btn rounded-25 widget-card widget-card-rounded square m-b-5">
                <div class="widget-card-cover" style="background-size: 80%; background-image: url(..{!! ($component->img) !!})">
                <!--<div class="widget-card-cover">-->
                    <!--<img class="img-responsive" src="{!! url($component->img) !!}">-->
                </div>
            </a>
            <div class="f-s-17 text-black-transparent-6 f-w-700 text-black p-t-2 text-center">{!! $component->name !!}</div>
        </div>
        @endforeach
    </div>

@endsection

