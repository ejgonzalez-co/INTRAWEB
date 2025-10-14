@correo(['bgTitulo'=>'#17a2b8', 'colorTitulo'=>'#FFFFFF'])
   @slot('titulo')
      <h2>Creación de cuenta</h2>
   @endslot

   <h1>Hola, {{$datosUsuario['nombre']}}</h1>
   <p>Recuerde que los datos para ingresar al sistema son:</p>
   <br>
   <p>Usuario: {{$datosUsuario['username']}}</p>
   <p>Contraseña: {{$datosUsuario['password']}}</p>
   <br>
   <p>Ir {{ config('app.name') }}: <a href="{{config('app.url')}}">{{config('app.url')}}</a></p>
@endcorreo