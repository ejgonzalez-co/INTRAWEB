@extends('administrator.layouts.header')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Permiso</div>

                <div class="panel-body">                                        
                    <p><strong>Nombre</strong>     {{ $permiso->name }}</p>
                    <p><strong>Slug</strong>       {{ $permiso->slug }}</p>
                    <p><strong>Descripción</strong>  {{ $permiso->description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection