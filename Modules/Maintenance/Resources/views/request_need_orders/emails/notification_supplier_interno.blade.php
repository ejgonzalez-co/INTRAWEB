@component('mail::layout')
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot


    Estimado {{ $data->rol_asignado_nombre  }},<br>
    Es un placer saludarle cordialmente. La Jefatura de Mantenimientos de la EPA le ha asignado una Solicitud de productos o servicios con el consecutivo <strong>{{ $data->consecutivo }} </strong>, la cual está bajo su gestión.<br><br>
    Le informamos que puede revisar el listado de órdenes pendientes accediendo a la Intraepa. Para facilitar el proceso, le proporcionamos el siguiente <a href= '{{ ('https://intraepa.gov.co/') }}' target="_blank">Link</a> <br><br>
    Agradecemos de antemano su colaboración y pronta atención a esta solicitud.<br>


   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent


