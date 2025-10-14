<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE GESTION DE COCMBUSTIBLES</td>
       </tr> -->
       <tr><td>REPORTE HISTORICO DE COMBUSTIBLE DE VEHÍCULOS</td></tr>
       <tr>
          <td>Fecha de creación</td>
          <td>Fecha de recibo</td>
          <td>Hora de tanqueo</td>
          <td>Tipo de activo</td>
          <td>Placa</td>
          <td>Nombre del conductor</td>
          <td>Kilometraje actual</td>
          <td>Kilometraje anterior</td>
          <td>Horómetro actual</td>
          <td>Horómetro anterior</td>

          <td>Precio galón</td>

          <td>Rendimiento por galón</td>

          <td>Tipo de combustible</td>

          <td>Cantidad de combustible</td>

          <td>Tipo de activo</td>
          <td>Nombre del activo</td>
          <td>Variación en Km recorridos por tanqueo</td>

          <td>Precio total</td>


       </tr>
    </thead>
    <tbody>
 
       @foreach ($data as $key => $item)
          <tr>            
             <td>{!! $item['created_migration'] !!}</td>
             <td>{!! $item['invoice_date'] !!}</td>
             <td>{!! $item['tanking_hour'] !!}</td>
             <td>{!! $item['asset_type'] ? $item['asset_type']['name']: '' !!}</td>
             <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']['plaque']: '' !!}</td>
             <td>{!! $item['driver_name'] !!}</td>
             <td>{!! $item['current_mileage'] !!}</td>
             <td>{!! $item['previous_mileage'] !!}</td>
             <td>{!! $item['current_hourmeter'] !!}</td>
             <td>{!! $item['previous_hourmeter'] !!}</td>

             <td>{!! $item['gallon_price'] !!}</td>


             <td>{!! $item['performance_by_gallon'] !!}</td>

             <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']['fuel_type']: '' !!}</td>



             <td>{!! $item['fuel_quantity'] !!}</td>


             <td>{!! $item['asset_type'] ? $item['asset_type']['name']: '' !!}</td>
             <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']['name_vehicle_machinery']: '' !!}</td>
             <td>{!! $item['variation_route_hour'] !!}</td>

             <td>{!! $item['total_price'] !!}</td>


          </tr> 
       @endforeach
    </tbody>
 </table>