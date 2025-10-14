@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
# Cordial saludo {{ $data->name }}

El líder de proceso {{ $data->name_process }} ha enviado las necesidades de inversión y/o funcionamiento para su revisión. Por favor ingrese con usuario y contraseña.

   @component('mail::button', ['url' => config('app.url')])
   Ingrese aquí para gestionar las necesidades.
   @endcomponent


   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
