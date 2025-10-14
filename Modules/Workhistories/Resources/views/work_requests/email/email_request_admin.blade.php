@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

# Cordial saludo {{ $data->name }},

   El funcionario {{ $data->history->users->name }} ha enviado una solicitud para consultar la hoja de vida del funcionario {{ $data->history->user_name }}, que pertenece a  un funcionario {{ $data->history->state }}. Por favor ingresar con usuario y contraseña para aprobar o cancelar la solicitud. Intraepa

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