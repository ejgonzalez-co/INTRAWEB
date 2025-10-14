@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado/a {{ $data["name"] ?? 'Funcionario' }},

   {!! $data['mensaje'] !!}


   <a href="{{ config('app.url') }}/correspondence/externals?qder={{ !empty($data['id_encrypted']) ? $data['id_encrypted'] : '' }}" title="Gestionar solicitud">Gestionar solicitud</a>

   <p>Si desea marcar este correo como leído, puede hacerlo aquí: <br>👉<a style="text-align: center; font-size: 15px;"href="{{ config('app.url') }}/correspondence/watch-archives?id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}"> Haga clic aquí para marcar este correo electrónico como leído.</a></p>

   <br />

   {{-- Unsubscribe Section --}}
   <small>Si desea dejar de recibir estas notificaciones, puede hacerlo <a href="{{ config('app.url') . '/cancel-subscription?c='.encrypt($data['mail']) }}" title="Cancelar suscripción">aquí</a>.</small>
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Importante: Si no puede acceder al enlace directamente, copie y pegue la URL en la barra de direcciones de su navegador web.
         Este correo es de tipo informativo, por lo tanto, le pedimos no responder a este mensaje.
      @endcomponent
   @endslot
@endcomponent
