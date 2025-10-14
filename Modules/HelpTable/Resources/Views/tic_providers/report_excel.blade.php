<table border="1">
   <thead>
      <!-- <tr>
         <td></td>
         <td colspan="8">REPORTE DE PROVEEDORES TIC</td>
      </tr> -->
      <tr>
         <td>@lang('Type Person')</td>
         <td>@lang('Type Document')</td>
         <td>@lang('Identification')</td>
         <td>@lang('Name')</td>
         <td>@lang('Email')</td>
         <td>@lang('State')</td>
         <td>@lang('Address')</td>
         <td>@lang('Regime')</td>  
         <td>@lang('Phone')</td>  
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['type_person_name'] !!}</td>
            <td>{!! $item['document_type_name'] !!}</td>
            <td>{!! $item['identification'] !!}</td>
            <td>{!! $item['name'] !!}</td>
            <td>{!! $item['users']['email'] !!}</td>           
            <td>{!! $item['state_name'] !!}</td>
            <td>{!! $item['address'] !!}</td>  
            <td>{!! $item['regime_name'] !!}</td>  
            <td>{!! $item['phone'] !!}</td>           

         </tr> 
      @endforeach
   </tbody>
</table>