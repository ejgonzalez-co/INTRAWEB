@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

  
   # Reciba un cordial saludo  {{ $data['name'] }}
   La Dirección TI de la EPA le ha asignado una nueva solicitud para su gestión. Por favor, ingrese con sus credenciales. Tenga en cuenta que las solicitudes tienen tiempos de vencimiento, por lo que se recomienda atenderlas lo antes posible."
   @component('mail::button', ['url' => config('app.url')])
   Ingrese aquí para gestionar la solicitud.
   @endcomponent
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
