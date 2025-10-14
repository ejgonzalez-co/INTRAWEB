@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ $config->modelNames->human }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @@include('{{ $config->prefixes->getViewPrefixForInclude() }}{{ $config->modelNames->snakePlural }}.show_fields')
                    <a href="@{{ route('{{ $config->prefixes->getRoutePrefixWith('.') }}{{ $config->modelNames->dashedPlural }}.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
