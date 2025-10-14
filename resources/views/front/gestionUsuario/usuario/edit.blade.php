@contenidoFront
<br>
@section('migas')
<li class="breadcrumb-item"><a href="{{url('usuarios')}}">Usuarios</a></li>
<li class="breadcrumb-item active">Editar usuario</li>
@endsection

@include('alerts.warning')
@include('alerts.success')
@include('alerts.error')
<div class="card">
   <h5 class="card-header">Editar usuario del sistema</h5>
   <div class="card-body">

      {!! Form::model($usuario, ['route' => ['usuarios.update', $usuario->id],
         'method' => 'PUT']) !!}
         
         @include('front.gestionUsuario.usuario.formas.editar')
         
      {!! Form::close() !!}
   </div>
</div>
@endcontenidoFront