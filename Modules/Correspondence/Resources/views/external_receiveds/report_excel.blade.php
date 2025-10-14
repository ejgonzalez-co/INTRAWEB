<style>
         .encabezado{
            border-collapse: collapse;
         }

         .encabezado th{
            padding: 5px;
            text-align: center;
            vertical-align: middle;
         }

         .encabezado  td{
            padding: 5px;
            text-align: center;
            width: 243px;
         }

         .listaRegistros{
            border-collapse: collapse;
         }

         .listaRegistros  th{
            padding: 5px;
            text-align: center;
            vertical-align: middle;
            width: 110px;
         }

         .listaRegistros  td{
            padding: 5px;
            text-align: center;
            width: 100px;
         }
</style>


@foreach ($data as $request)

<table class="encabezado" border="1">
   <thead>
      <tr>
         <th colspan="11" style="text-align: center;"><b>REPORTE DE CORRESPONDENCIA EXTERNA RECIBIDA</b></th>
      </tr>
   </thead>
   <tbody>
      <tr >
        <td colspan="5" style="text-align: center;"><b>DESTINATARIO:</b> {!! $request['radicador'] !!}</td>
        <td colspan="5" style="text-align: center;"><b>TOTAL:</b> {!! $request['total'] !!}</td>
     </tr>
     <tr>
      <th colspan="11" rowspan="2"></th>
     </tr>
   </tbody>
   </table>

   <table class="listaRegistros" border="1">
      <thead><!--Inicio de los titulos de la tabla de los registros-->
         <tr>
            <th style="text-align: center; width: 100px;"><b>CONSECUTIVO</b></th>
            <th style="text-align: center; width: 100px;"><b>PROCEDENCIA</b></th>
            <th style="text-align: center; width: 100px;"><b>DESTINATARIO</b></th>
            <th style="text-align: center; width: 100px;"><b>ASUNTO</b></th>
            <th style="text-align: center; width: 100px;"><b>RADICÓ</b></th>
            <th style="text-align: center; width: 100px;"><b>ANEXOS</b></th>
            <th style="text-align: center; width: 100px;"><b>OFICINA</b></th>
            <th style="text-align: center; width: 100px;"><b>FECHA DE CREACIÓN</b></th>
            <th style="text-align: center; width: 100px;"><b>FECHA DE MODIFICACIÓN</b></th>
            <th style="text-align: center; width: 100px;"><b>CANAL</b></th>
            <th style="text-align: center; width: 100px;"><b>RECIBIDO FÍSICO</b></th>
         </tr>
      </thead>
      @foreach ($request['internas'] as $data)<!--Fin de los titulos de la tabla de los registros-->
      <tbody>
         <tr>
            <th>{!! $data['consecutive'] ?? 'NA' !!}</th>
            <th>{!! isset($data['citizen_name']) && !empty($data['citizen_name']) ? htmlentities($data['citizen_name'], ENT_QUOTES, 'UTF-8') : "N/A" !!}</th>
            <th>{!! isset($data['functionary_name']) ? htmlentities($data['functionary_name'], ENT_QUOTES, 'UTF-8') : 'NA' !!}</th>
            <th>{!! isset($data['issue']) && !empty($data['issue']) ? htmlentities($data['issue'], ENT_QUOTES, 'UTF-8') : "N/A" !!}</th>
            <th>{!! isset($data['user_name']) ? htmlentities($data['user_name'], ENT_QUOTES, 'UTF-8') : "N/A" !!}</th>
            <th>{!! isset($data['annexed']) ? htmlentities($data['annexed'], ENT_QUOTES, 'UTF-8') : "NA" !!}</th>
            <th>{!! isset($data['dependency_name']) ? htmlentities($data['dependency_name'], ENT_QUOTES, 'UTF-8') : "NA" !!}</th>
            <th>{!! $data['created_at'] ?? "NA" !!}</th>
            <th>{!! $data['updated_at'] ?? "NA" !!}</th>
            <th>{!! $data['channel_name'] ?? "NA" !!}</th>
            <th>{!! isset($data['recibido_fisico']) ? htmlentities($data['recibido_fisico'], ENT_QUOTES, 'UTF-8') : "Pendiente de recibido físico" !!}</th>
        </tr>
      </tbody>
      @endforeach
      <tr>
         <td colspan="11" rowspan="4"></td>
      </tr>
      <tr></tr>
      <tr></tr>
   </table>

@endforeach

