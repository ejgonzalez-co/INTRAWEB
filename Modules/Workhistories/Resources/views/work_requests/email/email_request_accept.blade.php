@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

# Cordial saludo {{ $data->name }},

   El área de Talento Humano ha aprobado la solicitud para consultar la hoja de vida del funcionario  {{ $data->history->user_name }}, con la siguiente observación: {{ $data->history->answer }}, con el rango de tiempo {{ $data->history->date_start }} y {{ $data->history->date_final }}. Por favor ingresar con usuario y contraseña para realizar la consulta pertinente.

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