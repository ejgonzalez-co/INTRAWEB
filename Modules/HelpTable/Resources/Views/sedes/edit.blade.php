@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Sede
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($sede, ['route' => ['sedes.update', $sede->id], 'method' => 'patch']) !!}

                        @include('sedes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection