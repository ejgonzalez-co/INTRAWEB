@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         <img style="width: 120px;float: left;" src="https://app.intraepa.gov.co/assets/img/logo_epa.png" alt="{{ config('app.name') }} Logo">
         <h2 style="padding-right: 120px;">{{ config('app.name') }}</h2>
      @endcomponent
   @endslot
   
# Cordial saludo líder de proceso {{ $data->name }}

Su solicitud de modificación y/o adicción ha sido aprobada. Por favor ingresar con usuario y contraseña, realizar los ajuste y enviar nuevamente a revisión y su posterior aprobación.

   @component('mail::button', ['url' => config('app.url')])
   Ingrese aquí.
   @endcomponent


   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
