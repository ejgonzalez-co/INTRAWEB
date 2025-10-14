@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
# Cordial saludo líder de proceso {{ $data->name }}

El área de Presupuesto ha devuelto sus necesidades de funcionamiento y/o inversión para que realice ajustes pertinentes. Por favor ingrese con usuario y contraseña y envíe nuevamente a revisión.

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
