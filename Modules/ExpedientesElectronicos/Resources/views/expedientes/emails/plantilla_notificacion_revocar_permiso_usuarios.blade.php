@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado/a {{ $data["nombre_funcionario"] ?? 'Funcionario' }},

   <p>Le informamos que sus permisos para gestionar documentos en el expediente con consecutivo <strong>{{ $data["consecutivo"] }}</strong> han sido revocados. A partir de ahora, ya no podrá agregar ni administrar documentos en dicho expediente.</p>

   <p>Detalles del expediente:</p>
   <ul>
       <li><strong>Consecutivo:</strong> {{ $data["consecutivo"] }}</li>
       <li><strong>Nombre:</strong> {{ $data["nombre_expediente"] }}</li>
       <li><strong>Fecha de creación:</strong> {{ $data["created_at"] }}</li>
   </ul>

   <br />

   <p style="font-size: 15x;">Si considera que esto es un error o necesita más información, le recomendamos contactar con el equipo de soporte.</p>

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
