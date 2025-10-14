@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
# Reciba un cordial saludo ingeniero {{ $data->name }}

El caso con número {{ $data['request']['id'] }}, fue enviado para su revisión luego de una devolución al funcionario. 
Ingrese al sistema para poder conocer los detalles enviados.

   @component('mail::button', ['url' => config('app.url')])
      Ingrese al sistema de Mesa de ayuda para mayor información.
   @endcomponent

   
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
