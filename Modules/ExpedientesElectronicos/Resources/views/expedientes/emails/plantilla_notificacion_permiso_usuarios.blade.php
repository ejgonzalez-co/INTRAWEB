@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado/a {{ $data["nombre_funcionario"] ?? 'Funcionario' }},

   <p>Le informamos que ha sido habilitado/a para gestionar documentos en el expediente con consecutivo <strong>{{ $data["consecutivo"] }}</strong>, con el siguiente permiso: <strong>{{ $data["permiso"] }}</strong>.</p>

   <p>Detalles del expediente:</p>
   <ul>
       <li><strong>Consecutivo:</strong> {{ $data["consecutivo"] }}</li>
       <li><strong>Nombre:</strong> {{ $data["nombre_expediente"] }}</li>
       <li><strong>Fecha de creación:</strong> {{ $data["created_at"] }}</li>
   </ul>

   @if($data["tipo_usuario"] == "Externo")

   <p>Detalles de acceso:</p>
   <ul>
       <li><strong>Correo electrónico:</strong> {{ $data["correo"] }}</li>
       <li><strong>Pin de acceso:</strong> {{ $data["pin_acceso"] }}</li>
   </ul>
   
   @endif

   <p>Para comenzar a gestionar los documentos, por favor acceda al sistema de <strong>Intraweb</strong> mediante el siguiente enlace: <a href="{{ $data["tipo_usuario"] == "Interno" ? config('app.url') : config('app.url') . '/login-usuarios-externos-expedientes' }}" title="Ingresar a Intraweb">Ir a Intraweb</a></p>

   <br />

   <p style="font-size: 15x;">Si tiene alguna duda o requiere asistencia, no dude en contactar con el equipo de soporte.</p>

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
