@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
# Cordial saludo funcionario

Tiene una invitación para contestar la encuesta: {{  $data->title }}, la cual estará habilitada entre el {{  $data->date_open }} y {{  $data->date_close }}.
<br>
<b><i>{{ $data->observations }}</i></b>
   <table>
      <tr>
         <tr>
            <td>¿Quiere contestar la encuesta?</td>
         </tr>
         <td>
            @component('mail::button', ['url' => config('app.url')."/intranet/polls"])
            Entra a la intranet con tu usuario y contraseña
            @endcomponent
         </td>
      </tr>
   </table>


   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
