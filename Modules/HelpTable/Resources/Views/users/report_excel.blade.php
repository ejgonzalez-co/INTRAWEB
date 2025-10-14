<table border="1">
   <thead>
      <!-- <tr>
         <td></td>
         <td colspan="8">REPORTE DE SOLICITUDES</td>
      </tr> -->
      <tr>
         <td>@lang('Name')</td>
         <td>@lang('Username')</td>
         <td>@lang('Email')</td>
         <td>Cargo</td>
         <td>@lang('Dependency')</td>
         <td>@lang('Block Account')</td>
         <td>@lang('Account Verified')</td>
         <td>¿Desea recibir notificaciones al correo?</td>
         <td>@lang('Created_at')</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['name'] !!}</td>
            <td>{!! $item['username'] !!}</td>
            <td>{!! $item['email'] !!}</td>
            <td>{!! $item['positions']? $item['positions']: '' !!}</td>
            <td>{!! $item['dependencies']? $item['dependencies']: '' !!}</td>
            <td>{!! $item['block']? 'Si': 'No' !!}</td>
            <td>{!! $item['email_verified_at']? 'Si': 'No' !!}</td>
            <td>{!! $item['sendEmail']? 'Si': 'No' !!}</td>
            <td>{!! $item['created_at']  !!}</td>
         </tr> 
      @endforeach
   </tbody>
</table>