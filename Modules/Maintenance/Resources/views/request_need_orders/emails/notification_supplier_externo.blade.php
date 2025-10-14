@component('mail::layout')
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
@php
 if (gettype($data) === "array") {
      $data = json_decode(json_encode($data));
   }
    $array =  explode('-', $data->proveedor_nombre);
    $name =  $array[2];
@endphp

Estimado {{ $name  }},<br>
Es un placer saludarle cordialmente. La Jefatura de Mantenimientos de la EPA le ha asignado una Solicitud de productos o servicios con el consecutivo <strong>{{ $data->consecutivo }} </strong>, la cual está bajo su gestión.<br><br>
Para revisar el listado de órdenes pendientes, le invitamos a ingresar al siguiente 
<a href="{{ url('/login-outside-vendor/') }}" target="_blank">Link</a><br>
Descargar Orden:  <a href="{{ url('/maintenance/vig-gr-r-026-to-provider/'. $data->id_order ) }}">Ver orden de compra</a><br><br>
Agradecemos de antemano su colaboración y pronta atención a esta solicitud.<br>


   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent


