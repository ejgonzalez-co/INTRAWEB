@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

# Cordial saludo {{ $data->name }},

El área de Talento Humano ha CANCELADO  la solicitud para consultar la hoja de vida del funcionario  {{ $data->history->user_name }}, con la siguiente justificación: {{ $data->history->answer }}. Por favor ingresar con usuario y contraseña.

   @component('mail::button', ['url' => config('app.url_joomla')])
   Historial de hojas de vida
   @endcomponent

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent