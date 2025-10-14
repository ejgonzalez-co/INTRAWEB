@component('mail::layout')
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

Cordial saludo, Administrador,

Le escribimos para informarle que la solicitud de producto o servicio {{ $data["consecutivo"] }}, relacionada con la identificación de necesidad #{{ $data["request_consecutive"] }}, aún no ha sido completada por el proveedor interno, {{ $data["rol_asignado_nombre"] }}.
   
<strong>Observación realizada por el proveedor interno:</strong> {{ $data["internal_provider_observation"] }}

   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent