@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
# Cordial saludo líder de proceso {{ $data->name }}

Las necesidades de inversión y/o funcionamiento han sido aprobada. Por favor ingrese con usuario y contraseña.

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
