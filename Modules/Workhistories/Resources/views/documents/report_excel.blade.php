<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE PENSIONADOS CUOTAS PARTES</td>
       </tr> -->
       <tr>
         <td>@lang('Created_at')</td>
         <td>@lang('Description')</td>
         <td>@lang('Sheet')</td>
         <td>@lang('Document_date')</td>
         <td>@lang('Type Document')</td>
       </tr>
    </thead>
    <tbody>
 
       @foreach ($data as $key => $item)
          <tr>
            <td>{!! $item ['created_at'] !!}</td>
             <td>{!! $item['description'] !!}</td>
             <td>{!! $item ['sheet'] !!}</td>
             <td>{!! $item['document_date']  !!}</td>
             <td>{!! $item['work_histories_config_documents']['name'] !!}</td>

          </tr> 
       @endforeach
    </tbody>
 </table>