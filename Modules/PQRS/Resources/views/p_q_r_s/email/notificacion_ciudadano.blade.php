@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   @php
      $icons = [
        'doc' => '📄', 'docx' => '📄',
        'xls' => '📊', 'xlsx' => '📊',
        'ppt' => '📽️', 'pptx' => '📽️',
        'pdf' => '📜',
        'zip' => '🗜️', 'rar' => '🗜️',
        'jpg' => '🖼️', 'jpeg' => '🖼️',
        'png' => '🖼️', 'gif' => '🖼️',
    ];
    $defaultIcon = '📜';
   @endphp


   # Estimado/a {{ $data["nombre_ciudadano"] ?? 'Ciudadano/a' }},

   {!! $data['mensaje'] !!}

   @if (($data['estado'] == 'Finalizado' || $data['estado'] == 'Respuesta parcial') && !empty($data['adjunto']))
<p>Al hacer clic en cualquier documento adjunto, este se marcará automáticamente como leído, y podrá visualizarlo o descargarlo según sea necesario.</p>
   <div style="margin: 20px 0; font-family: Arial, sans-serif;">
        <div style="margin-bottom: 25px;">
            <h4 style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">Documento de respuesta: </h4>
            @if (!empty($data['adjunto']))
                <div style="padding-left: 10px;">
                    @foreach (explode(',', $data['adjunto']) as $document)
                    @php
                        $fileName = basename($document);
                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $icon = $icons[$extension] ?? $defaultIcon;
                    @endphp
                        <div style="margin-bottom: 8px;">
                            {{$icon}}
                            <a href="{{ config('app.url') }}/pqrs/watch-archives?document={{ urlencode(Crypt::encryptString(trim($document))) }}&id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}" 
                            style="color: #0066cc; display: inline-block; padding: 3px 0; vertical-align: middle;">
                                {{ basename($document)}}
                            </a>
                        </div>
                    @endforeach
                </div>
            @else<p>No hay documentos disponibles.</p>@endif
        </div>
   </div>
   @else
<p>Si desea marcar este correo como leído, puede hacerlo aquí: <br>👉<a style="text-align:center;font-size:15px;"href="{{ config('app.url') }}/pqrs/watch-archives?id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}"> Haga clic aquí para marcar este correo electrónico como leído.</a></p>
   @endif

   Si dispone de una cuenta de usuario, le recomendamos ingresar al sistema para obtener más detalles y realizar segumiento a su solicitud.

   👉<a href="{{ config('app.url') }}" title="Haga clic aquí para gestionar su solicitud"> Para realizar seguimiento a su solicitud, haga clic aquí.</a>
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es informativo. Por favor, no responda a este mensaje. Si necesita asistencia adicional, contacte a nuestro soporte.
      @endcomponent
   @endslot
@endcomponent
