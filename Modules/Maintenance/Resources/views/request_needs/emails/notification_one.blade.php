@component('mail::layout')
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
{{-- @php
    dd($data);
@endphp --}}

    Estimado Líder de Proceso {{ $data->users->name }},<br>
    La solicitud de identificación de necesidad {{ $data->consecutivo }} ha sido cancelada con las siguiente observación:<br> {{ $data->observacion_fin }}.<br> Para cualquier pregunta o aclaración, le solicitamos ponerse en contacto con la Jefatura de Mantenimientos.


   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent


