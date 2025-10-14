@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

  
   # Reciba un cordial saludo  {{ $data['assigned_user_name'] }}
   La solicitud con consecutivo  {{$data['id']}} está próxima a vencer. Por favor, ingrese al sistema y atenderla lo antes posible."

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
