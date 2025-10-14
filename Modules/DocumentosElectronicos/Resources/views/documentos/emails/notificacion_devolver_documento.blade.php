@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado(a) {{ $data["users"]["fullname"] ?? "Usuario" }},

   Le informamos que ha recibido un documento electrónico desde la plataforma Intraweb de {{ config('app.name') }}.

   **Documento Electrónico:** {{ $data["titulo_asunto"] ?? "No aplica" }}  
   **Consecutivo:** {{ $data["consecutivo"] ?? "No aplica" }}  
   **Devuelto por:** {{ $data["tipo_usuario"] }} {{ $data["nombre_usuario_accion_devolver"] ?? "No aplica" }}  
   **Observación:** {{ $data["observacion_devuelto"] ?? "No aplica" }}

   <a href="{{ config('app.url') }}{{ $data['link'] }}" title="Accede al sistema Intraweb">
      Haga clic aquí para ingresar al sistema
   </a>

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Importante: Si no puede acceder al enlace directamente, copie y pegue en la barra de direcciones de su navegador web.  
         Este correo es informativo, por favor no responda a este mensaje.
      @endcomponent
   @endslot
@endcomponent
