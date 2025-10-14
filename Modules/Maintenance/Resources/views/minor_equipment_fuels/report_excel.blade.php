<table border="1">
   <tr style="margin: 5px"><td> Combustible de equipos menores</td></tr>
   @foreach ($data as $key => $item)
   <thead>
      <tr><td> Información general</td></tr>
      <tr>
            <td>Responsable del proceso</td>
            <td>Fecha del suministro</td>
            <td>Hora del suministro</td>
            <td>Fecha inicial del periodo</td>
            <td>Fecha final del periodo</td>
            <td>Saldo inicial de combustible (gal)</td>
            <td>Más compras en el periodo (gal)</td>
            <td>Menos entregas de combustible (gal)</td>
            <td>Saldo final de combustible (gal)</td>
            <td>Número de factura</td>
            <td>Valor galón</td>
            <td>Combustible facturado (gal)</td>
            <td>Costo en pesos</td>
            <td>Nombre del responsable</td>
            <td>Cargo</td>
            <td>Proceso que aprobó</td>
            <td>Nombre del líder del proceso</td>


      </tr>
   </thead>
   <tbody>
            <tr>
               <td>{!! $item['dependencias_responsable'] ? $item['dependencias_responsable']['nombre'] : '' !!}</td>
               <td>{!! $item['supply_date'] !!}</td>
               <td>{!! $item['supply_hour'] !!}</td>
               <td>{!! $item['start_date_fortnight'] !!}</td>
               <td>{!! $item['end_date_fortnight'] !!}</td>
               <td>{!! $item['initial_fuel_balance'] !!} </td>
               <td>{!! $item['more_buy_fortnight'] !!} </td>
               <td>{!! $item['less_fuel_deliveries'] !!} </td>
               <td>{!! $item['final_fuel_balance'] !!} </td>
               <td>{!! $item['bill_number'] !!}</td>
               <td>${!! $item['gallon_value'] !!}</td>
               <td>{!! $item['checked_fuel'] !!}</td>
               <td>${!! $item['cost_in_pesos'] !!}</td>
               <td>{!! $item['name'] !!}</td>
               <td>{!! $item['position'] !!}</td>
               <td>{!! $item['dependencias_aprobo'] ? $item['dependencias_aprobo']['nombre'] : '' !!}</td>
               <td>{!! $item['name_leader'] !!}</td>
            </tr>
            <tr><td> </td></tr>
            <tr><td>Información de consumo</td> </tr>
            <tr>
               <td>Fecha de registro</td>
               <td>Fecha del suministro</td>
               <td>Descripción del equipo</td>
               <td>Proceso</td>
               <td>Galones suministrados (gal)</td>
            </tr>
            @foreach ($item['mant_equipment_fuel_consumptions'] as $value)
            <tr>
               <td>{!! $value['created_at'] !!}</td>
               <td>{!! $value['supply_date'] !!}</td>
               <td>{!! $value['mant_resume_equipment_machinery'] ? $value['mant_resume_equipment_machinery']['name_equipment'].' - '.$value['mant_resume_equipment_machinery']['no_inventory'] : ''!!}</td>
               <td>{!! $value['dependencias'] ? $value['dependencias']['nombre'] : '' !!}</td>
               <td>{!! $value['gallons_supplied'] !!}</td>
            </tr>           
            @endforeach
            <tr><td> </td></tr>
   </tbody>
   @endforeach
</table>
