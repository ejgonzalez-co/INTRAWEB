
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
        <td>{!! $item['consecutivo'] ? $item['consecutivo'] : 'NA' !!}</td>
        <td>{!! $item['asunto'] ? $item['asunto'] : 'NA' !!}</td>
        <td>{!! $item['tipodoc'] ? $item['tipodoc'] : 'NA' !!}</td>
        <td>{!! $item['tipodoc'] ? $item['tipodoc'] : 'NA' !!}</td>
        <td>{!! $item['estado'] ? $item['estado'] : 'NA' !!}</td>


        <td>{!! $item['dependencia_remitente'] ? $item['dependencia_remitente'] : 'NA' !!}</td>
        <td>{!! $item['funcionario_remitente'] ? $item['funcionario_remitente'] : 'NA' !!}</td>

        <td>{!! $item['funcionario_destinatario'] ? $item['funcionario_destinatario'] : 'NA' !!}</td>

        <td>{!! $item['oficina_productora'] ? $item['oficina_productora'] : 'NA' !!}</td>
        <td>{!! $item['serie_subserie'] ? $item['serie_subserie'] : 'NA' !!}</td>
        
      </tr>
      @endforeach
    </tbody>
    </table>