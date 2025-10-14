<table border="1">
   <thead>
     <tr>
         <td colspan="9">ACTUALIZACIÓN DE DATOS</td>
      </tr>

      <tr>
        <th>@lang('Payment Account Number')</th>
        <th>@lang('Subscriber Quality')</th>
        <th>@lang('Citizen')</th>
        <th>@lang('Document Type')</th>
        <th>@lang('Identification')</th>
        <th>@lang('Gender')</th>
        <th>@lang('Telephone')</th>
        <th>@lang('Email')</th>
        <th>@lang('Birth Date')</th>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['payment_account_number'] !!}</td>
            <td>{!! $item['subscriber_quality'] !!}</td>
            <td>{!! $item['citizen_name'] !!}</td>
            <td>{!! $item['document_type'] !!}</td>
            <td>{!! $item['identification']!!}</td>
            <td>{!! $item['gender']!!}</td>
            <td>{!! $item['telephone']!!}</td>
            <td>{!! $item['email']!!}</td>
            <td>{!! $item['date_birth']!!}</td>
         </tr> 
      @endforeach
   </tbody>
</table>