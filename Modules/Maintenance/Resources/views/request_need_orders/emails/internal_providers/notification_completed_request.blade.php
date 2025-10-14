@component('mail::layout')
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

Cordial saludo Administrador,

Nos complace informarle que el proveedor interno {{ $data["rol_asignado_nombre"] }} ha completado una solicitud de producto o servicio con el número {{ $data["consecutivo"] }}. Esta solicitud está vinculada a la identificación de necesidad número {{ $data["request_consecutive"] }}. Para continuar con la gestión de esta solicitud, por favor ingrese al módulo de Identificación de Necesidades y proceda con el proceso correspondiente.
   
<strong>Observación realizada por el proveedor interno:</strong> {{ $data["internal_provider_observation"] }}

   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent


