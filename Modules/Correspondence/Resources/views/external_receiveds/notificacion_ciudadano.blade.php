@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   @php
   $citizen_name = str_replace('<br>', ' ', $data["citizen_name"] ?? 'Estimado usuario');
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

   # Estimado/a <label>{{ $citizen_name }},</label>
   Le informamos que se ha registrado una nueva correspondencia a su nombre con el número de radicado: <strong>{{ $data["consecutive"] ?? "No disponible" }}</strong>.
   <p><b>Asunto: </b>{{ $data["issue"] ?? "No especificado" }}</p>
   <p><b>PQRS asociado: </b>{{ $data["pqr"] ?? "No disponible" }}</p>
   <br>
   <p>Para consultar el estado de la correspondencia y/o PQRS, haga clic en el siguiente enlace:<br>👉<a style="text-align:center;" href="{{ config('app.url') }}/correspondence/search-pqrs-ciudadano"> Consulta la información de la correspondencia recibida y/o PQRS</a></p> 
   <p>Para acceder al documento, utilice el siguiente código de acceso:<br>🔑<b> Código de acceso: </b>{{ $data["validation_code"] ?? "No disponible." }}</p> 
   @if (!empty($data['documents']))
   <p>Al hacer clic en cualquier documento adjunto, este se marcará automáticamente como leído, y podrá visualizarlo o descargarlo según sea necesario.</p>
   @else 
   <p>Si desea marcar este correo como leído, puede hacerlo aquí: <br>👉<a style="text-align:center;font-size:15px;" href="{{ config('app.url') }}/correspondence/watch-archives-received?id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}"> Haga clic aquí para marcar este correo electrónico como leído.</a></p>
   @endif
   <div style="margin-bottom: 25px;">
   <h4 style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">Documento principal de la correspondencia recibida</h4>
   @if (!empty($data['documents']))
       <div style="padding-left: 10px;">
           @foreach (explode(',', $data['documents']) as $document)
            @php
                $fileName = basename($document);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $icon = $icons[$extension] ?? $defaultIcon;
            @endphp
                    {{ $icon }} 
                    <div style="margin-bottom: 8px;">
                   <a href="{{ config('app.url') }}/correspondence/watch-archives-received?document={{ urlencode(Crypt::encryptString(trim($document))) }}&id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}" 
                      style="color: #0066cc; display: inline-block; padding: 3px 0; vertical-align: middle;">
                       {{ $fileName }}
                   </a>
               </div>
           @endforeach
       </div>
   @else<p>No hay documentos disponibles.</p>@endif</div>
   <div style="margin-bottom: 25px;">
      <h4 style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">Anexos de la correspondencia recibida</h4>
      @if (!empty($data['anexes']))
          <div style="padding-left: 10px;">
              @foreach (explode(',', $data['anexes']) as $anex)
              @php
                $fileName = basename($anex);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $icon = $icons[$extension] ?? $defaultIcon;
              @endphp
                  <div style="margin-bottom: 8px;">
                    {{ $icon }}
                      <a href="{{ config('app.url') }}/correspondence/watch-archives-received?document={{ urlencode(Crypt::encryptString(trim($anex))) }}&id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}" 
                         style="color: #0066cc; display: inline-block; padding: 3px 0; vertical-align: middle;">
                          {{ $fileName }}
                      </a>
                  </div>
              @endforeach
          </div>
      @else<p>No hay anexos disponibles.</p>@endif</div>
   <br>
   <br>
   <small>Si desea dejar de recibir estas notificaciones, puede hacerlo <a href="{{ config('app.url') . '/cancel-subscription?c='.encrypt($data['mail']) }}" title="Cancelar suscripción">aquí</a>.</small>
   @slot('footer')
      @component('mail::footer')
       Importante: Si no puede acceder al enlace, copie y péguelo en la barra de direcciones de su navegador. Este correo es informativo, por favor, no responda a este mensaje.
      @endcomponent
   @endslot

   @slot('avisoLegal')
      @component('mail::avisoLegal')
      Aviso Legal y Políticas de Privacidad: Este correo y sus archivos adjuntos son confidenciales y para uso exclusivo del destinatario. Si ha recibido este mensaje por error, notifíquelo al remitente y elimínelo de su sistema. esta entidad protege su información personal conforme a las leyes de privacidad vigentes. No compartimos sus datos con terceros sin su consentimiento. Para más información sobre nuestras políticas de privacidad, visite nuestro sitio web. Cualquier uso no autorizado de este correo está prohibido y puede ser ilegal.
      @endcomponent
   @endslot
@endcomponent