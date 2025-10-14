@push('styles')
   <style>
      .table-arial-12 {
         font-family: Arial, sans-serif;
         font-size: 12px;
         border-collapse: collapse;
      }
      .table-arial-12 th,
      .table-arial-12 td {
         border: 1px solid black;
         padding: 8px;
         text-align: center;
      }
   </style>
@endpush

<table border="1" style="border-collapse: collapse;">
   <thead>
       <tr>
           <th colspan="7" style="text-align: center; border: 1px solid black;">REPORTE DE CORRESPONDENCIA RECIBIDA</th>
       </tr>
       <tr>
           <th style="text-align: center; border: 1px solid black;"><b>FECHA</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>RADICACION</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>PROCEDENCIA</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>DESTINATARIO</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>ANEXOS</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>OFICINA</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>RECIBIDO FÍSICO</b></th>
       </tr>
   </thead>
   <tbody>
       @foreach ($data as $item)

       <tr>
           <td style="border: 1px solid black; ">{!! $item->date?$item->date:null !!}</td>
           <td style="border: 1px solid black; ">{!! $item->consecutive?$item->consecutive:null !!}</td>
           <td style="border: 1px solid black; ">{!! $item->citizen_name?$item->citizen_name:null !!}</td>
           <td style="border: 1px solid black; ">{!! $item->functionary_name?$item->functionary_name:null !!}</td>
           <td style="border: 1px solid black; text-align:center;">{!! $item->annexed?$item->annexed:null !!}</td>
           <td style="border: 1px solid black;">{!! $item->dependency_name?$item->dependency_name:null !!}</td>
           <td style="border: 1px solid black; ">{!! $item->recibido_fisico ? $item->recibido_fisico : "Pendiente de recibido físico" !!}</td>
       </tr>
       @endforeach
   </tbody>
</table>

