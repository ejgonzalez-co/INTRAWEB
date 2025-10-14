@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

      # Reciba un cordial saludo  {!! isset($data['recipient']) ? $data['recipient'] : 'Ciudadano.'  !!}  <br>
   {!!  $data['mensaje']  !!}


   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje.
      @endcomponent
   @endslot
@endcomponent
