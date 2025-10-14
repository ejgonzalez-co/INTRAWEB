@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado(a) {{ $data["nombre_usuario"] ?? "Usuario" }},

   Hemos recibido un nuevo documento electrónico en la plataforma Intraweb de {{ config('app.name') }}.

   **Documento Electrónico:** {{ $data["titulo_asunto"] ?? "No disponible" }}  
   **Consecutivo:** {{ $data["consecutivo"] ?? "No disponible" }}  
   **Enviado por:** {{ $data["nombre_usuario_version"] ?? "No disponible" }}  
   **Observación:** {{ $data["observacion"] ?? "No disponible" }}  
   **Estado:** {{ $data["estado"] ?? "No disponible" }}  

   Para visualizar el documento, utiliza el siguiente código de acceso: **{{ $data["codigo_acceso_documento"] ?? "No disponible" }}**

   <a href="{{ config('app.url') . '/documentos-electronicos/validar-codigo/' . $data['id_encriptado'] }}" title="Verifica el código de acceso y visualiza el documento">
      Verifica el código y accede al documento
   </a>

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Si el enlace no funciona, copia y pega la URL en la barra de direcciones de tu navegador.  
         Este es un mensaje informativo. Por favor, no respondas a este correo.
      @endcomponent
   @endslot
@endcomponent
