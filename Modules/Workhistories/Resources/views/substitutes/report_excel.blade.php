<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE SUSTITOS</td>
       </tr> -->
       <tr>
         <td>@lang('Nombre(pensionado fallecido)')</td>
         <td>@lang('Número de documento(pensionado fallecido)')</td>
         <td>@lang('Type Document')</td>
         <td>@lang('Número de documento')</td>
         <td>@lang('Document_date')</td>
         <td>@lang('Name')</td>
         <td>@lang('Birth Date')</td>
         <td>@lang('Departament')</td>
         <td>@lang('City')</td>
         <td>@lang('Address')</td>
         <td>@lang('Teléfono')</td>
         <td>@lang('Email')</td>
         <td>@lang('Tipo sustituto')</td>
         <td>@lang('Estado de la pensión')</td>

       </tr>
    </thead>
    <tbody>
 
       @foreach ($data as $key => $item)
          <tr>
            <td>{!! $item ['work_histories_p']?$item['work_histories_p']['name']: null!!} {!! $item['work_histories_p']?$item['work_histories_p']['surname']: null !!}</td>
            <td>{!! $item ['work_histories_p']?$item ['work_histories_p']['number_document']: null !!}</td>           
            <td>{!! $item ['type_document'] !!}</td>
            <td>{!! $item['number_document'] !!}</td>
            <td>{!! $item['date_document']  !!}</td>
            <td>{!! $item['name'] !!} {!! $item['surname'] !!}</td>
            <td>{!! $item['birth_date']  !!}</td>
            <td>{!! $item['depto']  !!}</td>
            <td>{!! $item['city']  !!}</td>
            <td>{!! $item['address']  !!}</td>
            <td>{!! $item['phone']  !!}</td>
            <td>{!! $item['email']  !!}</td>
            <td>{!! $item['type_substitute']  !!}</td>
            <td>{!! $item['state_name']  !!}</td>

          </tr> 
       @endforeach
    </tbody>
 </table>