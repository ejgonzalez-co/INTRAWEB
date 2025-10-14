@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         <img style="width: 120px;float: left;" src="https://app.intraepa.gov.co/assets/img/logo_epa.png" alt="{{ config('app.name') }} Logo">
         <h2 style="padding-right: 120px;">{{ config('app.name') }}</h2>
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
