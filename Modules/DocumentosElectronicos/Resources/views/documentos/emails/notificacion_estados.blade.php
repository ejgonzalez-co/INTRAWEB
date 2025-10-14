@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado(a) {{ $data["users_name_actual"] ?? "Usuario" }},

   Hemos recibido un nuevo documento electrónico en la plataforma Intraweb de {{ config('app.name') }}.

   **Documento Electrónico:** {{ $data["titulo_asunto"] ?? "No aplica" }}  
   **Consecutivo:** {{ $data["consecutivo"] ?? "No aplica" }}  
   **Enviado por:** {{ $data["nombre_usuario_version"] ?? "No aplica" }}  
   **Observación:** {{ $data["observacion"] ?? "No aplica" }}  
   **Estado:** {{ $data["estado"] ?? "No aplica" }}  
   
   Para ver el documento, por favor utiliza el siguiente código de acceso: **{{ $data["codigo_acceso_documento"] ?? "No aplica" }}**

   <a href="{{ config('app.url') . '/documentos-electronicos/validar-codigo/' . $data['id_encriptado'] }}" title="Verifica el código de acceso y visualiza el documento">
      Verifica el código y accede al documento
   </a>

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Si el enlace no funciona, copia y pega la URL en la barra de direcciones de tu navegador.  
         Este es un correo informativo. Por favor, no respondas a este mensaje.
      @endcomponent
   @endslot
@endcomponent
