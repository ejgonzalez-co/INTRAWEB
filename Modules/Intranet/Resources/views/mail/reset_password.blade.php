@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   <h1>Hola {{ $data["nameUser"] ?? 'Usuario' }},</h1>
   <p>Recibimos una solicitud para restablecer la contraseña de su cuenta en Intraweb. Si usted solicitó este cambio, haga clic en el botón de abajo para restablecer su contraseña.</p>


   <a href="{{ url('password/reset', $data['token']) }}">Reestablecer contraseña.</a>


   <p>Este enlace para restablecer la contraseña es válido por 60 minutos. Si no solicitó un cambio de contraseña, puede ignorar este correo electrónico.</p>
   <p>Si tiene problemas para hacer clic en el botón "Restablecer contraseña", copie y pegue la siguiente URL en su navegador web:</p>
   <p><a href="{{ url('password/reset', $data['token']) }}">{{ url('password/reset', $data['token']) }}</a></p>
   
   <p>Gracias,</p>
   <p>El equipo de Intraweb</p>
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
      @endcomponent
   @endslot
@endcomponent
