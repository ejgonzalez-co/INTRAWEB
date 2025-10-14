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
           <th colspan="3" style="text-align: center; border: 1px solid black;">REPORTE DE CORREOS REENVIADOS</th>
       </tr>
       <tr>
           <th style="text-align: center; border: 1px solid black;"><b>ESTADO</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>CORREO</b></th>
           <th style="text-align: center; border: 1px solid black;"><b>MENSAJE</b></th>

       </tr>
   </thead>
   <tbody>
       @foreach ($data as $item)
        
       <tr>
           <td style="border: 1px solid black; ">{!! $item['estado'] !!}</td>
           <td style="border: 1px solid black; ">{!! $item['correo'] !!}</td>
            <td style="border: 1px solid black; ">{!! nl2br(e(wordwrap($item['mensaje'] ?? 'N/A', 60, "\n", true))) !!}</td>


       </tr>
       @endforeach
   </tbody>
</table>

