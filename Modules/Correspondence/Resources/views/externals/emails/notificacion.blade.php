@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   
   @php
      $citizen_name = str_replace('<br>', ',', $data["citizen_name"] ?? 'Estimado usuario');
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
   Le informamos que ha recibido una correspondencia enviada a través de la plataforma {{ config('app.name') }} con el siguiente consecutivo: <strong>{{ $data["consecutive"] ?? "No aplica" }}.</strong>
   @if(!empty($data['pqr_consecutive']))
   <p><strong style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">
   PQRS asociado: 
   </strong>{{ $data["pqr_consecutive"] ?? "No disponible" }}</p>
   @endif
   @if(!empty($data['tipo_finalizacion']))
   <p><strong style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">
   Tipo de finalización: 
   </strong>{{ $data["tipo_finalizacion"] ?? "No disponible" }}</p>
   @endif
   @if (!empty($data['external_received_consecutive']))
   <p><strong style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">
   Correspondencia recibida asociada: 
   </strong>{{ $data["external_received_consecutive"] ?? "No disponible" }}</p>
   @endif
   <br>
   @if (!empty($data['documents']))
   <p>Al hacer clic en cualquier documento adjunto, este se marcará automáticamente como leído, y podrá visualizarlo o descargarlo según sea necesario.</p>
   @else<p>Si desea marcar este correo como leído, puede hacerlo aquí: <br>👉<a style="text-align:center;font-size:15px;"href="{{ config('app.url') }}/correspondence/watch-archives-received?id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}"> Haga clic aquí para marcar este correo electrónico como leído.</a></p>
   @endif
   <div style="margin: 20px 0; font-family: Arial, sans-serif;">
        <div style="margin-bottom: 25px;">
            <h4 style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">Documento de la correspondencia enviada</h4>
            @if (!empty($data['document_pdf']))
                <div style="padding-left: 10px;">
                    @foreach (explode(',', $data['document_pdf']) as $document)
                    @php
                        $fileName = basename($document);
                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $icon = $icons[$extension] ?? $defaultIcon;
                    @endphp
                        <div style="margin-bottom: 8px;">
                            {{$icon}}
                            <a href="{{ config('app.url') }}/correspondence/watch-archives?document={{ urlencode(Crypt::encryptString(trim($document))) }}&id={{ $data['id'] }}" 
                            style="color: #0066cc; display: inline-block; padding: 3px 0; vertical-align: middle;">
                                {{ basename($document)}}
                            </a>
                        </div>
                    @endforeach
                </div>
            @else<p>No hay documentos disponibles.</p>@endif
        </div>
        <div style="margin-bottom: 25px;">
            <h4 style="color: #333; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">Anexos de la correspondencia enviada</h4>
            <div style="padding-left: 10px;">
                @if (!empty($data['annexes_digital']))
                    @foreach (explode(',', $data['annexes_digital']) as $annex)
                        @php
                            $fileName = basename($annex);
                            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                            $icon = $icons[$extension] ?? $defaultIcon;
                        @endphp
                        <div style="margin-bottom: 8px;">
                            {{$icon}}
                            <a href="{{ config('app.url') }}/correspondence/watch-archives?document={{ urlencode(Crypt::encryptString(trim($annex))) }}&id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}" 
                               style="color: #0066cc; display: inline-block; padding: 3px 0; vertical-align: middle;">
                                {{ basename($annex) }}
                            </a>
                        </div>
                    @endforeach
                @else<p>No hay anexos disponibles.</p>@endif
            </div>
        </div>
        
   </div>
   {!! $data["encuesta"] ?? "" !!}   

   @slot('footer')
      @component('mail::footer')
         Importante: Si no puede acceder al enlace directamente, copie y pegue la URL en la barra de direcciones de su navegador web.
         Este correo es de tipo informativo, por lo tanto, le pedimos no responder a este mensaje.
      @endcomponent
   @endslot
@endcomponent