<style>
   table {
     width: 100%;
     border-collapse: collapse;
   }

   th, td {
     padding: 8px;
     text-align: left;
     border-bottom: 1px solid #ddd;
   }

   th {
     background-color: #f2f2f2;
   }

   td.truncate {
     max-width: 200px;
     overflow: hidden;
     text-overflow: ellipsis;
     white-space: nowrap;
   }
   </style>

   <table>
     <thead>
       <tr>
         <th>CONSECUTIVO</th>
         <th>ESTADO</th>
         <th class="truncate">ENVIADO A</th>
         <th width="30">RADICADO POR</th>
         <th>PLANTILLA</th>
         <th width="25">FECHA DE CREACIÓN</th>
         <th>CANAL</th>
         <th>ORIGEN</th>
       </tr>
     </thead>
     <tbody>
       @foreach ($data as $request)
       <tr>
         <td>{!! htmlentities($request['consecutive'] ?? 'N/A') !!}</td>
         <td>{!! htmlentities($request['state'] ?? 'N/A') !!}</td>
         <td class="truncate">{!! $request['recipients'] ?? 'N/A' !!}</td>
         <td>{!! htmlentities($request['from'] ?? 'N/A') !!}</td>
         <td>{!! htmlentities($request['plantilla'] ?? 'N/A') !!}</td>
         <td>{!! htmlentities($request['created_at'] ?? 'N/A') !!}</td>
         <td>{!! htmlentities($request['channel	'] ?? 'N/A') !!}</td>
         <td>{!! htmlentities($request['origen'] ?? 'N/A') !!}</td>
       </tr>
       @endforeach
     </tbody>
   </table>
