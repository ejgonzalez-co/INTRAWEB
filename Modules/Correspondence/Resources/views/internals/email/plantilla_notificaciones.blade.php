@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado/a {{ $data["name"] ?? 'Funcionario' }},

   {!! $data['mensaje'] !!}

   {{-- Unsubscribe Section --}}
   <p>Si desea dejar de recibir estas notificaciones, puede hacerlo <a href="{{ config('app.url') . '/cancel-subscription?c='.encrypt($data['mail']) }}" title="Cancelar suscripción">aquí</a>.</p>
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
       Nota: Si tiene problemas para acceder al enlace, copie y péguelo en la barra de direcciones de su navegador. Este correo es informativo; por favor, no responda a este mensaje.
      @endcomponent
   @endslot
@endcomponent
