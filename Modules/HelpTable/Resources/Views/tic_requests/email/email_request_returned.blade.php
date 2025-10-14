@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
# Reciba un cordial saludo, {{ $data->name ?? 'N/A' }}

El caso con número {{ $data['request']['id'] }}, fue devuelto por el administrador para su verificación,  con la siguiente justificación: "<strong>{{ $data['continue']['tracing'] }}</strong>". Por favor verifique la observación generada por el administrador y complete los datos requeridos para que su solicitud pueda ser escalada y atendida.

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
