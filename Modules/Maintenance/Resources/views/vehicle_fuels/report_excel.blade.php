<table border="1">
  <thead>
     <!-- <tr>
        <td></td>
        <td colspan="8">REPORTE DE GESTION DE COCMBUSTIBLES</td>
     </tr> -->
     <tr><td>REPORTE DE COMBUSTIBLE DE VEHÍCULOS</td></tr>
     <tr>
        <td>Fecha de creación</td>
        <td>Fecha de modificación</td>
        <td>Fecha de recibo</td>
        <td>Número de recibo</td>
        <td>Hora de tanqueo</td>
        <td>Tipo de activo</td>
        <td>Placa</td>
        <td>Nombre del conductor</td>
        <td>Kilometraje actual</td>
        <td>Kilometraje anterior</td>
        <td>Horómetro actual</td>
        <td>Horómetro anterior</td>
        <td>Tipo de combustible</td>
        <td>Cantidad de combustible</td>
        <td>Precio galón</td>
        <td>Precio total</td>
        <td>Nombre del activo</td>
        <td>Proceso</td>
        <td>Variaciones de horas en los tanqueos</td>
        <td>Variación en Km recorridos por tanqueo</td>
        <td>Rendimiento por galón</td>
     </tr>
  </thead>
  <tbody>

     @foreach ($data as $key => $item)
        <tr>       
          @if(array_key_exists('created_migration',$item) && $item['created_migration']!=null)
              <td>{!! $item['created_migration'] !!}</td>
           @else
              <td>{!! $item['created_at'] !!}</td>
           @endif     
           <td>{!! $item['updated_at'] !!}</td>
           <td>{!! $item['invoice_date'] !!}</td>
           <td>{!! $item['invoice_number'] !!}</td>
           <td>{!! $item['tanking_hour'] !!}</td>
           <td>{!! $item['asset_type'] ? $item['asset_type']['name']: '' !!}</td>
           <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']['plaque']: '' !!}</td>
           <td>{!! htmlentities($item['driver_name']) !!}</td>
           @if(array_key_exists('current_mileage',$item) && $item['current_mileage'] != null)
             <td>{!! $item['current_mileage'] !!}</td>
           @else
             <td>{!! "" !!}</td>
           @endif
           @if( array_key_exists('previous_mileage',$item) && $item['previous_mileage'] && $item['previous_mileage']!=0)
            <td>{!! $item['previous_mileage'] !!}</td>
           @else
            <td>{!! "" !!}</td>
           @endif
           @if(array_key_exists('current_hourmeter',$item) && $item['current_hourmeter'] != null)
             <td>{!! $item['current_hourmeter'] !!}</td>
           @else
             <td>{!! "" !!}</td>
           @endif
           @if( array_key_exists('previous_hourmeter',$item) && $item['previous_hourmeter']!=0 && $item['previous_hourmeter'])
            <td>{!! $item['previous_hourmeter'] !!}</td>
           @else
            <td> {!! "" !!} </td>
           @endif
           <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']['fuel_type']: '' !!}</td>
           <td>{!! $item['fuel_quantity'] !!}</td>
           <td>{!! $item['gallon_price'] !!}</td>
           <td>{!! $item['total_price'] !!}</td>
           <td>{!! htmlentities($item['asset_name']) !!}</td>
           <td>{!! $item['dependencias'] ? htmlentities($item['dependencias']['nombre']): '' !!}</td>
           @if(array_key_exists('variation_tanking_hour',$item) && $item['variation_tanking_hour'] != null)
             <td>{!! $item['variation_tanking_hour'] !!}</td>
           @else
             <td>{!! "" !!}</td>
           @endif
           @if(array_key_exists('variation_route_hour',$item) && $item['variation_route_hour'] != null)
             <td>{!! $item['variation_route_hour'] !!}</td>     
           @else
             <td>{!! "" !!}</td>
           @endif
           <td>{!! $item['performance_by_gallon'] !!}</td>
        </tr> 
     @endforeach
  </tbody>
</table>
