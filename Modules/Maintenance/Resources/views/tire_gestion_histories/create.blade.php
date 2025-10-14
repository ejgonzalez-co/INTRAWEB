@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tire Gestion History
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'tireGestionHistories.store']) !!}

                        @include('tire_gestion_histories.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
