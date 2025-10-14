@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado/a {{ $data["nombre_funcionario"] ?? 'Funcionario' }},

   <p>Le informamos que su acceso como <strong>{{ $data["nombre_rol"] }}</strong> en el sistema <strong>Intraweb</strong> ha sido revocado. A partir de ahora, ya no podrá acceder a las funcionalidades asociadas a este rol.</p>

   <br />

   <p>Si considera que esto es un error o requiere asistencia, comuníquese con el equipo de soporte.</p>

   <br />
   <br />

   <p>Atentamente,</p>
   <p><strong>Equipo de Gestión Documental</strong></p>

   <br />

   {{-- Unsubscribe Section --}}
   <p style="font-size: 14px;">Si desea dejar de recibir estas notificaciones, puede hacerlo <a href="{{ config('app.url') . '/cancel-subscription?c='.encrypt($data['mail']) }}" title="Cancelar suscripción">aquí</a>.</p>
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
       Nota: Si tiene problemas para acceder al enlace, copie y péguelo en la barra de direcciones de su navegador. Este correo es informativo; por favor, no responda a este mensaje.
      @endcomponent
   @endslot
@endcomponent
