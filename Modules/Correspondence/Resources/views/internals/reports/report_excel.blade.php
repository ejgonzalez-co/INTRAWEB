
    <table>
    <thead>
      <tr>
        <th style="text-align: center" colspan="10">REPORTE DE REPOSITORIO CORRESPONDENCIA EINTERNA</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="text-align: center" colspan="5"><strong>DATOS DE LA CORRESPONDENCIA</strong></td>
        <td style="text-align: center" colspan="2"><strong>DATOS DE ORIGEN </strong></td>
        <td style="text-align: center" colspan="1"><strong>DATOS DE DESTINO</strong></td>
        <td style="text-align: center" colspan="2"><strong>CLASIFICACION DOCUMENTAL</strong></td>
        
      </tr>
      <tr>
        <td > <strong> CONSECUTIVO </strong> </td>
        <td > <strong> ASUNTO </strong></td>
        <td > <strong> PLANTILLA </strong></td>
        <td > <strong> TIPO DOCUMENTO </strong></td>
        <td > <strong> ESTADO </strong></td>

        <td ><strong>DEPENDENCIA </strong></td>
        <td ><strong>FUNCIONARIO </strong></td>

        <td ><strong>ENVIADO A </strong></td>

        <td ><strong>OFICINA PRODUCTORA </strong></td>
        <td ><strong>SERIE SUB-SERIE DOCUMENTAL </strong></td>
       
       
    
      </tr>
      @foreach ($data as $key => $item)
      <tr>
        <td>{!! $item['consecutivo'] ? htmlentities($item['consecutivo'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
        <td>{!! $item['asunto'] ? htmlentities($item['asunto'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
        <td>{!! $item['tipodoc'] ? htmlentities($item['tipodoc'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
        <td>{!! $item['tipodoc'] ? htmlentities($item['tipodoc'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
        <td>{!! $item['estado'] ? htmlentities($item['estado'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
    
        <td>{!! $item['dependencia_remitente'] ? htmlentities($item['dependencia_remitente'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
        <td>{!! $item['funcionario_remitente'] ? htmlentities($item['funcionario_remitente'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
    
        <td>{!! $item['funcionario_destinatario'] ? htmlentities($item['funcionario_destinatario'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
    
        <td>{!! array_key_exists('oficina_productora', $item) ? htmlentities($item['oficina_productora'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
        <td>{!! array_key_exists('serie_subserie', $item) ? htmlentities($item['serie_subserie'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</td>
    </tr>
      @endforeach
    </tbody>
    </table>