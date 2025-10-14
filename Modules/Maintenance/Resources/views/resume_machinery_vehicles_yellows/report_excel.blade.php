<table border="1">
   <thead>
      <tr>     
         <td>@lang('Created_at')</td>
         <td>@lang('Mant_asset_type')</td>
         <td>@lang('Asset Name')</td>
         <td>@lang('Dependency')</td>
         <td>N° de inventario</td>
         <td>@lang('Brand')</td>
         <td>@lang('Model')</td>
         <td>Serie</td>
         <td>Placa de vehículo</td>
         <td>@lang('State')</td>
         <td>Tipo de categoría</td>
         <td>Consecutivo</td>
         <td>Precio de compra</td>
         <td>Tipo de combustible</td>
         <td>Fecha de vencimiento del SOAT</td>
         <td>Fecha de vencimiento de la tecnicomecánica</td>
      </tr>
   </thead>
   <tbody>

     @foreach ($data as $item)
         <tr>   
            <td>{!! $item['created_at'] !!}</td>   
            <td>{!! isset($item['asset_type']) ? $item['asset_type']->name: 'No aplica' !!}</td>
            @if($item['mant_category']->mant_asset_type->form_type == 1)
             <td>{!! htmlentities(isset($item['name_vehicle_machinery']) ? $item['name_vehicle_machinery'] : 'No aplica') !!}</td>
            @elseif($item['mant_category']->mant_asset_type->form_type == 2 || $item['mant_category']->mant_asset_type->form_type == 4)
             <td>{!! htmlentities(isset($item['name_equipment']) ? $item['name_equipment'] : 'No aplica') !!}</td>
            @elseif($item['mant_category']->mant_asset_type->form_type == 3)
             <td>{!! htmlentities(isset($item['name_equipment_machinery']) ? $item['name_equipment_machinery'] : 'No aplica') !!}</td>
            @else
             <td>{!! htmlentities(isset($item['description_equipment_name']) ? $item['description_equipment_name'] : 'No aplica') !!}</td>
            @endif
            <td>{!! isset($item['dependencies']) ? $item['dependencies']->nombre : 'No aplica' !!}</td>
            <td>{!! isset($item['no_inventory']) ? $item['no_inventory']: ( isset($item['inventory_no']) ? $item['inventory_no']: (isset($item['no_inventory_epa_esp']) ? $item['no_inventory_epa_esp']: " ")) !!}</td> 
            <td>{!! htmlentities(isset($item['mark']) ? $item['mark']: 'No aplica') !!}</td> 
            <td>{!! htmlentities(isset($item['model']) ? $item['model']: 'No aplica') !!}</td> 
            <td>{!! htmlentities(isset($item['serie']) ? $item['serie'] : 'No aplica') !!}</td>
            <td>{!! isset($item['plaque']) ? $item['plaque']: 'No aplica' !!}</td> 
            <td>{!! isset($item['status']) ? $item['status']: 'No aplica' !!}</td> 
            <td>{!! $item['mant_category'] ? $item['mant_category']->name : ' ' !!}</td>
            <td>{!! isset($item['consecutive']) ? $item['consecutive']: 'No aplica' !!}</td>
            <td>{!! isset($item['purchase_price']) ? $item['purchase_price']: '0' !!}</td>
            <td>{!! isset($item['fuel_type']) ? $item['fuel_type']: 'No aplica' !!}</td>
            <td>{!! isset($item['expiration_date_soat']) ? $item['expiration_date_soat'] : 'No aplica' !!}</td>
             <td>{!! isset($item['expiration_date_tecnomecanica']) ? $item['expiration_date_tecnomecanica'] : 'No aplica' !!}</td>
            {{-- @if(count($item['mant_documents_asset']) == 0)
              <td>{!! "No aplica" !!}</td>
            @else
              <td>{!! count($item['mant_documents_asset']) !!}</td> 
            @endif --}}
         </tr> 
      @endforeach
   </tbody>
</table>