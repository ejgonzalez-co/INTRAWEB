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
    $defaultIcon = '📜'; @endphp
   # Estimado/a <label>{{$data['funcionary_name'] ?? "Funcionario/a"}},</label>
   Le informamos que se le ha asignado una nueva correspondencia recibida con el número de radicado: <strong>{{ $data["consecutive"] ?? "No aplica" }}</strong>
   <br>
   <p><b>Asunto: </b>{{ $data["issue"] ?? "No especificado" }}</p>
   <p><b>PQRS asociado: </b>{{ $data["pqr"] ?? "No disponible" }}</p>
   <br>
   <p>Para revisar la correspondencia, haga clic en el siguiente enlace:<br>👉<a style="text-align:center;" href="{{ config('app.url') }}/correspondence/external-receiveds?qder={{ !empty($data['id_encrypted']) ? $data['id_encrypted'] : '' }}"> Consulta la información de la correspondencia recibida</a></p> 
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
                  <div style="margin-bottom: 8px;">
                      {{ $icon }} 
                      <a href="{{ config('app.url') }}/correspondence/watch-archives-received?document={{ urlencode(Crypt::encryptString(trim($document))) }}&id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}" 
                         style="color: #0066cc; display: inline-block; padding: 3px 0; vertical-align: middle;">
                          {{ basename($document) }}
                      </a>
                  </div>
              @endforeach
          </div>
      @else<p>No hay documentos disponibles.</p>@endif
   </div>
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
                          {{ basename($anex) }}
                      </a>
                  </div>
              @endforeach
          </div>
      @else<p>No hay anexos disponibles.</p>@endif
   </div>
   <br>
   <br>
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
       Nota: Si tiene problemas para acceder al enlace, copie y péguelo en la barra de direcciones de su navegador. Este correo es informativo; por favor, no responda a este mensaje.
      @endcomponent
   @endslot
@endcomponent