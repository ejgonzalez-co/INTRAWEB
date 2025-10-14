<table border="1">
   <thead>
      <!-- <tr>
         <td></td>
         <td colspan="8">REPORTE DE SOLICITUDES</td>
      </tr> -->
      <tr>
         <td>@lang('Created_at')</td>
         <td>@lang('Consecutive')</td>
         <td>@lang('Name')</td>
         <td>@lang('Serial')</td>
         <td>@lang('Inventory Plate')</td>
         <td>Categoría</td>
         <td>@lang('Dependency')</td>
         <td>@lang('Provider')</td>
         <td>@lang('General Description')</td>
         <td>@lang('Dirección del lugar')</td>
         <td>@lang('Brand')</td>
         <td>@lang('Model')</td>
         <td>@lang('Processor')</td>
         <td>@lang('Ram')</td>
         <td>@lang('Hdd')</td>
         <td>@lang('Operating System')</td>
         <td>@lang('Serial Microsoft License')</td>
         <td>@lang('License Microsoft Office')</td>
         <td>@lang('Serial Licencia Microsoft Office')</td>
         <td>@lang('Monitor')</td>
         <td>@lang('Keyboard')</td>
         <td>@lang('Mouse')</td>
         <td>@lang('Provider')</td>
         <td>@lang('Purchase Date')</td>
         <td>@lang('User')</td>
         <td>@lang('State')</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['created_at'] ?? 'N/A' !!}</td>
            <td>{!! $item['consecutive'] ?? 'N/A' !!}</td>
            <td>{!! $item['name'] ?? 'N/A' !!}</td>
            <td>{!! $item['serial'] ?? 'N/A' !!}</td>
            <td>{!! $item['inventory_plate'] !!}</td> 
            <td>{!! $item['tic_type_assets']? $item['tic_type_assets']->name: '' !!}</td>
            <td>{!! $item['dependencias']? $item['dependencias']->nombre: '' !!}</td>
            {{-- <td>{!! $item['provider_name'] !!}</td> --}}
            @if(array_key_exists('provider_name',$item))
               <td>{!! $item['provider_name'] !!}</td>
            @else
               <td>{!! "" !!}</td>
            @endif
            @if(array_key_exists('general_description',$item))
               <td>{!! $item['general_description'] !!}</td>
            @else
               <td>{!! "" !!}</td>
            @endif
            <td>{!! $item['location_address'] ?? 'N/A' !!}</td> 
            <td>{!! $item['brand'] ?? 'N/A' !!}</td>
            <td>{!! $item['model'] ?? 'N/A' !!}</td>
            <td>{!! $item['processor'] ?? 'N/A' !!}</td>
            <td>{!! $item['ram'] ?? 'N/A' !!}</td>
            <td>{!! $item['hdd'] ?? 'N/A' !!}</td>
            <td>{!! $item['operating_systems_info']? $item['operating_systems_info']->name: 'N/A' !!}</td>
            <td>{!! $item['operating_system_serial'] !!}</td>
            <td>{!! $item['office_automation_versions_info']? $item['office_automation_versions_info']->name: 'N/A' !!}</td>
            <td>{!! $item['serial_licencia_microsoft_office'] !!}</td>
            <td>{!! $item['monitor_id'] ?? 'N/A' !!}</td>
            <td>{!! $item['keyboard_id'] ?? 'N/A' !!}</td>
            <td>{!! $item['mouse_id'] ?? 'N/A' !!}</td>
            <td>{!! $item['provider_name'] ?? 'N/A' !!}</td>
            <td>{!! $item['purchase_date'] ?? 'N/A' !!}</td>
            <td>{!! $item['users']? $item['users']->name: 'N/A' !!}</td>
            <td>{!! $item['state_name'] ?? 'N/A' !!}</td>
         </tr> 
      @endforeach
   </tbody>
</table>