<table>
    <thead>
      <tr>
        <th  style="text-align: center" colspan="14">REPORTE DE REPOSITORIO CORRESPONDENCIA EXTERNA RECIBIDA</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="text-align: center" colspan="4" ><strong> DATOS DE LA CORRESPONDENCIA </strong></td>
        <td style="text-align: center" colspan="6" ><strong> DATOS DE ORIGEN  </strong></td>
        <td style="text-align: center" colspan="2" ><strong> DATOS DE DESTINO </strong></td>
        <td style="text-align: center" colspan="2" ><strong> CLASIFICACIÓN DOCUMENTAL </strong></td>
        
      </tr>
      <tr>
        <td style="text-align: center" ><strong> CONSECUTIVO </strong></td>
        <td style="text-align: center" ><strong> ASUNTO </strong></td>
        <td style="text-align: center" ><strong> TIPO DOCUMENTO </strong></td>
        <td style="text-align: center" ><strong> CANAL </strong></td>
        <td style="text-align: center" ><strong> CIUDADANO </strong></td>
        <td style="text-align: center" ><strong> DOCUMENTO </strong></td>
        <td style="text-align: center" ><strong> TÉLEFONO </strong></td>
        <td style="text-align: center" ><strong> CIUDAD  </strong></td>
        <td style="text-align: center" ><strong> DEPARTAMENTO </strong></td>
        <td style="text-align: center" ><strong> DEPENDENCIA </strong></td>
        <td style="text-align: center" ><strong> FUNCIONARIO </strong></td>
    
      </tr>
      @foreach ($data as $key => $item)
      <tr>
        <td>{!! htmlentities($item['consecutivo'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['asunto'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['tipodoc'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['canal'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['nombre_ciudadano'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['documento_ciudadano'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['email_ciudadano'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['telefono'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['ciudad'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['depto'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['dependencia_destinataria'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['funcionario_destinatario'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['oficina_productora'] ?? 'N/A') !!}</td>
        <td>{!! htmlentities($item['serie_subserie'] ?? 'N/A') !!}</td>
      </tr>
      @endforeach
    </tbody>
    </table>