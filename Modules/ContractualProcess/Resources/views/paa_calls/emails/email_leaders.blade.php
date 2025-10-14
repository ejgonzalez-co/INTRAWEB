@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         <img style="width: 120px;float: left;" src="https://app.intraepa.gov.co/assets/img/logo_epa.png" alt="{{ config('app.name') }} Logo">
         <h2 style="padding-right: 120px;">{{ config('app.name') }}</h2>
      @endcomponent
   @endslot
   
# Cordial saludo Líder de proceso {{ $data->name_process }}

El área de Gestión Recursos ha abierto la convocatoría para el Plan Anual de Adquisiciones {{ $data['call']['validity'] }}. Por favor ingrese con usuario y contraseña e inicie el proceso de enviar sus necesidades de inversión y/o funcionamiento.

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
