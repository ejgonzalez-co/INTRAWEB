
<table>
  <thead>
    <tr>
      <th style="text-align: center" colspan="16">REPORTE DE REPOSITORIO DE PQRS</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="text-align: center" colspan="16"><strong>DATOS DE LA PQRS</strong></td>
      {{-- <td style="text-align: center" colspan="6"><strong>DATOS DE CIUDADANO </strong></td>
      <td style="text-align: center" colspan="2"><strong>DATOS DE DESTINO</strong></td>
      <td style="text-align: center" colspan="2"><strong>CLASIFICACION DOCUMENTAL</strong></td> --}}
    </tr>
    <tr>
      <td > <strong> Radicado </strong> </td>
      <td > <strong> Estado </strong></td>
      <td > <strong> Tipo de finalización </strong></td>
      <td > <strong> Nombre eje tematico </strong></td>
      <td > <strong> Plazo eje tematico </strong></td>
      <td > <strong> Fecha de ingreso </strong></td>
      <td > <strong> Asunto </strong></td>
      <td > <strong> Peticionario </strong></td>
      <td > <strong> Funcionario asignado </strong></td>
      <td > <strong> Dependencia competente </strong></td>
      <td > <strong> Fecha de vencimiento </strong></td>
      {{-- <td > <strong> Correspondencia asociada </strong></td> --}}
      <td > <strong> Oficio (respuesta) </strong></td>
      <td > <strong> Fecha de respuesta </strong></td>
      {{-- <td > <strong> Recibido </strong></td> --}}
      <td > <strong> Canal </strong></td>
      <td > <strong> Respuesta </strong></td>
      <td > <strong> Fecha que se respondio </strong></td> 
      {{-- @if ($data[0]['encuesta_sactisfaccion'] == 'Si')
        <td style="background: #D9D9D9;"><strong>¿ La accesibilidad al canal de atención para <br> instaurar una PQRSDF mediante el aplicativo suministrado en el Portal<br> de la entidad, le pareció sencilla y eficaz?</strong></td>
        <td align="center" style="background: #D9D9D9;"><strong>¿ La respuesta al PQRSDF <br> fue oportuna ?</strong></td>
        <td align="center" style="background: #D9D9D9;"><strong>¿La respuesta a su pregunta <br> fue acertada y resolvió <br> la inquietud planteada?</strong></td>
      @endif --}}
    </tr>
    @foreach ($data as $key => $item)
    <tr>
      <td>{!! htmlentities($item['id'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['estado'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['tipo_finalizacion'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['nombre_ejetematico'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['plazo'] ?? 'N/A')." ".htmlentities($item['temprana_unidad'] ?? 'Días') !!}</td>
      <td>{!! htmlentities($item['cf_created'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['contenido'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['nombre_ciudadano'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['funcionario_asignado'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['dependencia_asignada'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['fechavence'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['no_oficio_solicitud'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['fechafin'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['canal'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['respuesta'] ?? 'N/A') !!}</td>
      <td>{!! htmlentities($item['cf_modified'] ?? 'N/A') !!}</td>
      {{-- @if ($data[0]['encuesta_sactisfaccion'] == 'Si')
      <td>{!! htmlentities($item['encuesta'][0]->respuesta1 ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
      <td>{!! htmlentities($item['encuesta'][0]->respuesta2 ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
      <td>{!! htmlentities($item['encuesta'][0]->respuesta3 ?? 'N/A', ENT_QUOTES, 'UTF-8') !!}</td>
      @endif --}}

    </tr>
    @endforeach
  </tbody>
</table>
