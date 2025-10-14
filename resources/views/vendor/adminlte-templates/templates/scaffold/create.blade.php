@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ $config->modelNames->human }}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    @{!! Form::open(['route' => '{{ $config->prefixes->getRoutePrefixWith('.') }}{{ $config->modelNames->dashedPlural }}.store']) !!}

                        @@include('{{ $config->prefixes->getViewPrefixForInclude() }}{{ $config->modelNames->snakePlural }}.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
