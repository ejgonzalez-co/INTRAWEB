@contenidoBackend

@section('migas')
<li class="breadcrumb-item"><a href="{{url('usuarios')}}">Usuarios</a></li>
<li class="breadcrumb-item active">Editar usuario</li>
@endsection

<div class="card">
   <h5 class="card-header">Editar usuario del sistema</h5>
   <div class="card-body">

      {!! Form::model($usuario, ['route' => ['usuarios.update', $usuario->id],
         'method' => 'PUT']) !!}
         
         @include('backend.gestionUsuario.usuario.formularios.editar')
         
      {!! Form::close() !!}
   </div>
</div>
@endcontenidoBackend