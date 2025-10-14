@component('mail::layout')
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot


   Cordial saludo Administrador,<br>
   Se le informa que el {{ $data->bodega }} ha completado una operación de entrada 
   correspondiente a la solicitud de producto o servicio #{{ $data->consecutivo }}, la cual está vinculada a la identificación de necesidad # {{ $data->solicitudPrincipal->consecutivo }}.<br> 
   Para dar continuidad a la gestión de esta solicitud, le instamos a ingresar y proceder con el proceso correspondiente.

   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent


