@component('mail::layout')
   {{-- Header --}}
 
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

  
   # Reciba un cordial saludo  {{ $data['admin']['name'] }}
   El proveedor {{$data['continue']['assigned_user_name']}} ha cambiado el estado de la solicitud con consecutivo {{ $data['request']['id'] }} a 'Pre-Cierre'. Para finalizar el proceso, por favor ingrese al sistema, valide la solicitud y proceda a su cierre."

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
