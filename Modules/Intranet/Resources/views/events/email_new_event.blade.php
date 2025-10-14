@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
# Cordial saludo {{ $data->name }}

Tiene una invitación para evento de {{  $data->event->title }}, el cual será realizado el {{  $data->event->initial_date." ".$data->event->initial_hour }}.
{{-- ¿Asistirás? --}}

   {{-- <table>
      <tr>
         <tr>
            <td>¿Asistirás al evento?</td>
         </tr>
         <td>
            @component('mail::button', ['url' => config('app.url')."/intranet/confirm-attendance-event/". $data->event->id."/1"])
            Sí
            @endcomponent
         </td>
         <td>
            @component('mail::button', ['url' => config('app.url')."/intranet/confirm-attendance-event/". $data->event->id."/2"])
            No
            @endcomponent
         </td>
      </tr>
   </table> --}}


   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
