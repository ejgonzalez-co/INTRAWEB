@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimados firmantes,

   Hemos recibido un nuevo documento electrónico a través de la plataforma Intraweb de {{ config('app.name') }}.

   **Documento Electrónico:** {{ $data["titulo_asunto"] ?? "No disponible" }}  
   **Consecutivo:** {{ $data["consecutivo"] ?? "No disponible" }}  
   **Estado Actual:** {{ $data["estado"] ?? "No disponible" }}

   Para acceder al documento, por favor, utiliza el siguiente código de acceso: **{{ $data["codigo_acceso_documento"] ?? "No disponible" }}**

   <a href="{{ config('app.url') . '/documentos-electronicos/validar-codigo/' . $data['id_encriptado'] }}" title="Verifica el código de acceso y visualiza el documento">
      Verifica el código y accede al documento
   </a>

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Si el botón no funciona, copia y pega la URL en la barra de direcciones de tu navegador.  
         Este correo es informativo, por lo que no es necesario responder a este mensaje.
      @endcomponent
   @endslot
@endcomponent
