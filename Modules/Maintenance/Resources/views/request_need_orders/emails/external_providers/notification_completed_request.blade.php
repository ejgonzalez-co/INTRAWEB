@component('mail::layout')
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

Cordial saludo Administrador,

Nos complace informarle que el proveedor externo {{ $data["external_provider_name"] }} ha completado una solicitud de producto o servicio con el número {{ $data["request_order_consecutive"] }}. Esta solicitud está vinculada a la identificación de necesidad número {{ $data["consecutivo"] }}. Para continuar con la gestión de esta solicitud, por favor ingrese al módulo de Identificación de Necesidades y proceda con el proceso correspondiente.
      
<strong>Observación realizada por el proveedor externo:</strong> {{ $data["external_provider_observation"] }}

   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent


