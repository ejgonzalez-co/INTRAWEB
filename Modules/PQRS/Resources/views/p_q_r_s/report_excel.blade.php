<table border="1">
   <thead>

      <tr class="text-center">
         <td align="center" colspan="{{ $data[0]['encuesta_sactisfaccion'] == 'Si' ? 21 : 18 }}">Reporte de PQRS </td>
      </tr>

      <tr class="text-center">
         <td align="center" style="background: #D9D9D9;" colspan="18">Datos</td>
         @if ($data[0]['encuesta_sactisfaccion'] == 'Si')
            <td align="center" style="background: #D9D9D9;" colspan="3">Encuesta de satisfacción</td>
         @endif
      </tr>

      <tr class="text-center">
         <td align="center" style="background: #D9D9D9;"><strong>Radicado</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Estado</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Tipo de finalización</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Nombre eje tematico</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Tipo solicitud/PQR</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Plazo eje tematico</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Fecha de ingreso</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Asunto</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Peticionario</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Funcionario asignado</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Dependencia competente</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Fecha de vencimiento</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Correspondencia asociada</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Oficio (respuesta)</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Fecha de respuesta</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Recibido</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Canal</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Respuesta</strong></td>
         <td align="center" style="background: #D9D9D9;"><strong>Fecha que se respondio</strong></td>
         @if ($data[0]['encuesta_sactisfaccion'] == 'Si')
            <td style="background: #D9D9D9;"><strong>¿ La accesibilidad al canal de atención para <br> instaurar una PQRSDF mediante el aplicativo suministrado en el Portal<br> de la entidad, le pareció sencilla y eficaz?</strong></td>
            <td align="center" style="background: #D9D9D9;"><strong>¿ La respuesta al PQRSDF <br> fue oportuna ?</strong></td>
            <td align="center" style="background: #D9D9D9;"><strong>¿La respuesta a su pregunta <br> fue acertada y resolvió <br> la inquietud planteada?</strong></td>
         @endif
      </tr>

   </thead>
   <tbody>
      @foreach ($data as $key => $item)
  
      <tr>
         <td>{!! htmlentities($item['pqr_id'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>
             {!! htmlentities($item['estado'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}
             @if($item['estado'] != 'Abierto' && $item['estado'] != 'Finalizado vencido justificado' && $item['estado'] != 'Cancelado')
                 <br />({!! htmlentities($item['linea_tiempo'] ?? '', ENT_QUOTES, 'UTF-8') !!})
             @endif
             @php
               $contenido = $item['contenido'] ?? 'N/A';

               if(preg_match('/=\?UTF-8\?B\?(.*?)\?=/', $contenido, $matches)){
                  $contenido = base64_decode($matches[1]);
               }
             @endphp
         </td>
         <td>{!! htmlentities($item['tipo_finalizacion'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['nombre_ejetematico'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['pqr_tipo_solicitud']->nombre ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['plazo'] ?? 'N/A', ENT_QUOTES, 'UTF-8') .' '. htmlentities($item['pqr_eje_tematico']->plazo_unidad ?? 'Días', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['created_at'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! nl2br(e(wordwrap($contenido ?? 'N/A', 30, "\n", true))) !!}</td>
         <td>{!! htmlentities($item['nombre_ciudadano'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['funcionario_users']->name ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['funcionario_users']->dependencies->nombre ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['fecha_vence'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['pqr_correspondence']->consecutive ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['no_oficio_respuesta'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! htmlentities($item['fecha_fin'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>N/A</td>
         <td>{!! htmlentities($item['canal'] ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         <td>{!! nl2br(e(wordwrap($item['respuesta'] ?? 'N/A', 30, "\n", true))) !!}</td>
         <td>{!! htmlentities($item['estado'] == 'Finalizado' ? $item['updated_at'] : 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         @if ($data[0]['encuesta_sactisfaccion'] == 'Si')
             <td>{!! htmlentities($item['encuesta'][0]->respuesta1 ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
             <td>{!! htmlentities($item['encuesta'][0]->respuesta2 ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
             <td>{!! htmlentities($item['encuesta'][0]->respuesta3 ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
         @endif
         <td></td>
     </tr>
      @endforeach
   </tbody>
</table>
