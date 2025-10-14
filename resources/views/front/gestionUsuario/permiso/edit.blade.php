@extends('administrator.layouts.header')
@section('contenido')
@section('migas')
    <li class="breadcrumb-item"><a href="{{url('permisos')}}">permisos</a></li>
    <li class="breadcrumb-item active">Editar permiso</li>
@endsection
<div class="card" id="listadoLineas">
	<div class="card-header">
		<h3 class="card-title">Editar permiso del sistema</h3>
	</div>
	<!-- /.card-header -->
	<div class="card-body">
        {!! Form::model($permiso, ['route' => ['permisos.update', $permiso->id],
                    'method' => 'PUT']) !!}
            @include('administrator.permisos.partials.form')
        {!! Form::close() !!}
	</div>
	<!-- /.card-body -->
	<div class="card-footer clearfix">
	</div>
</div>
@endsection