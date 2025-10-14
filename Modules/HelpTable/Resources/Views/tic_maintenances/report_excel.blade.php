<table border="1">
   <thead>
      <!-- <tr>
         <td></td>
         <td colspan="8">REPORTE DE MANTENIMIENTOS</td>
      </tr> -->
      <tr>
         <td>@lang('Created_at')</td>
         <td>@lang('Contract Number')</td>
         <td>@lang('Cost')</td>
         <td>@lang('Active Tic')</td>
         <td>@lang('Type Maintenance')</td>
         <td>Descripción Falla o Daño</td>
         <td>@lang('Service Start Date')</td>
         <td>@lang('End Date Service')</td>
         <td>@lang('Maintenance Status')</td>
         <td>@lang('Maintenance Description')</td>
         <td>@lang('Warranty Start Date')</td>
         <td>@lang('Warranty End Date')</td>
         <td>Dependencia</td>
         <td>@lang('Provider')</td>  
         <td>@lang('Brand')</td> 
         <td>Dirección del lugar</td>
         <td>Funcionario</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['created_at'] !!}</td>
            <td>{!! $item['contract_number'] !!}</td>
            <td>{!! $item['cost'] !!}</td>
            <td>{!! $item['tic_assets']? $item['tic_assets']['name']: '' !!}</td>
            <td>{!! $item['type_maintenance_name'] !!}</td>
            <td>{!! $item['fault_description'] !!}</td>
            <td>{!! $item['service_start_date'] !!}</td>
            <td>{!! $item['end_date_service'] !!}</td> 
            <td>{!! $item['maintenance_status_name'] !!}</td>
            <td>{!! $item['maintenance_description'] !!}</td>
            <td>{!! $item['warranty_start_date'] !!}</td>
            <td>{!! $item['warranty_end_date'] !!}</td>
            <td>{!! $item['dependencias']? $item['dependencias']['nombre']: '' !!}</td>
            {{-- <td>{!! $item['provider_name'] !!}</td> --}}
            @if(array_key_exists('provider_name',$item))
               <td>{!! $item['provider_name'] !!}</td>
            @else
               <td>{!! "" !!}</td>
            @endif
            <td>{!! $item['tic_assets']? $item['tic_assets']['brand']: '' !!}</td>
            <td>{!! $item['tic_assets']? $item['tic_assets']['location_address']: '' !!}</td>
            <td>{!! $item['user_name'] !!}</td>         
         </tr> 
      @endforeach
   </tbody>
</table>