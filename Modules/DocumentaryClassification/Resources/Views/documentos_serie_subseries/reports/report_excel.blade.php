

@if (array_key_exists( 'canal' , $data[0]))
    
<table>
  <thead>
    <tr>
      <th style="text-align: center" colspan="16">Reporte de administración de Inventario documental digital de PQRS</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td > <strong> CONSECUTIVO </strong></td>
      <td > <strong> CANAL </strong></td>
      <td > <strong> FOLIOS </strong></td>
      <td > <strong> ORIGEN </strong></td>
      <td > <strong> NOMBRE DEPENDENCIA</strong></td>
      <td > <strong> NOMBRE SERIE</strong></td>
      <td > <strong> CÓDIGO SERIE </strong></td>
      <td > <strong> NOMBRE SUBSERIE </strong></td>
      <td > <strong> CÓDIGO DE SUBSERIE </strong></td>
    
  
    </tr>
    @foreach ($data as $key => $item)
    <tr>
      <td>{!! htmlentities($item['consecutivo']? $item['consecutivo']: 'NA') !!}</td>
      <td>{!! htmlentities($item['canal'] ? $item['canal'] : 'NA') !!}</td>
      <td>{!! htmlentities($item['folios'] ? $item['folios'] : 'NA') !!}</td>
      <td>{!! htmlentities($item['origen'] ? $item['origen'] : 'NA') !!}</td>
      <td>{!! htmlentities($item['nombre'] ? $item['nombre'] : 'NA') !!}</td>
      <td>{!! htmlentities($item['name_serie'] ? $item['name_serie'] : 'NA') !!}</td>
      <td>{!! htmlentities($item['no_serie'] ? $item['no_serie'] : 'NA') !!}</td>
      <td>{!! htmlentities($item['name_subserie'] ? $item['name_subserie'] : 'NA') !!}</td>
      <td>{!! htmlentities($item['no_subserie']? $item['no_subserie']: 'NA') !!}</td>
    
      
    </tr>
    @endforeach
  </tbody>
  </table>

@else

 <table>
    <thead>
      <tr>
        <th style="text-align: center" colspan="16">Reporte de administración de Inventario documental digital {{ $data[0]['tipo_correspondencia'] }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td > <strong> CONSECUTICO </strong></td>
        <td > <strong> TIPO DE DOCUMENTO </strong></td>
        <td > <strong> FOLIOS </strong></td>
        <td > <strong> ORIGEN </strong></td>
        <td > <strong> NOMBRE DEPENDENCIA</strong></td>
        <td > <strong> NOMBRE SERIE</strong></td>
        <td > <strong> CÓDIGO SERIE </strong></td>
        <td > <strong> NOMBRE SUBSERIE </strong></td>
        <td > <strong> CÓDIGO DE SUBSERIE </strong></td>
      
    
      </tr>
      @foreach ($data as $key => $item)
      <tr>
        <td>{!! htmlentities($item['consecutivo']? $item['consecutivo']: 'NA') !!}</td>
        <td>{!! htmlentities($item['tipo_documento'] ? $item['tipo_documento'] : 'NA') !!}</td>
        <td>{!! htmlentities($item['folios'] ? $item['folios'] : 'NA') !!}</td>
        <td>{!! htmlentities($item['origen'] ? $item['origen'] : 'NA') !!}</td>
        <td>{!! htmlentities($item['nombre'] ? $item['nombre'] : 'NA') !!}</td>
        <td>{!! htmlentities($item['name_serie'] ? $item['name_serie'] : 'NA') !!}</td>
        <td>{!! htmlentities($item['no_serie'] ? $item['no_serie'] : 'NA') !!}</td>
        <td>{!! htmlentities($item['name_subserie'] ? $item['name_subserie'] : 'NA') !!}</td>
        <td>{!! htmlentities($item['no_subserie']? $item['no_subserie']: 'NA') !!}</td>
      
        
      </tr>
      @endforeach
    </tbody>
    </table>

@endif
    
   