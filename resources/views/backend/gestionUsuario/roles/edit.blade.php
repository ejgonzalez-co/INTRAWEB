@extends('backend.layouts.header')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Roles</div>

                <div class="panel-body">      

                <form action="{{ route('roles.update',$role->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @include('backend.gestionUsuario.roles.partials.form')
                    </form>

                        
                        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection