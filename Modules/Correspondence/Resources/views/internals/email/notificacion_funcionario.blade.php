@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Reciba un cordial saludo {!! isset($data['recipient']) ? $data['recipient'] : 'Funcionario' !!}. <br>
   {!! $data['mensaje'] !!}. <br>
   <a href="{{ config('app.url') }}{!! $data['link'] ?? '' !!}" title="Continúe gestionando su correspondencia en Intraweb">Gestione su correspondencia en Intraweb</a>

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje.
      @endcomponent
   @endslot
@endcomponent
