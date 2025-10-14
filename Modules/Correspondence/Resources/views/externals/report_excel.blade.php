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
@php
    function limpiarHtmlParaExcel($texto) {
        return preg_replace('/<br\s*\/?>/i', "\n", strip_tags($texto, '<br>'));
    }
@endphp


@foreach ($data as $request)

<table class="encabezado" border="1">
   <thead>
      <tr>
         <th colspan="9" style="text-align: center;"><b>REPORTE DE CORRESPONDENCIA EXTERNA</b></th>
      </tr>
   </thead>
   <tbody>
      <tr >
        <td colspan="5" style="text-align: center;"><b>RADICADOR:</b> {!! $request['radicador'] !!}</td>
        <td colspan="4" style="text-align: center;"><b>TOTAL:</b> {!! $request['total'] !!}</td>
     </tr>
     <tr>
      <th colspan="9" rowspan="2"></th>
     </tr>
   </tbody>
   </table>

   <table class="listaRegistros" border="1">
      <thead><!--Inicio de los titulos de la tabla de los registros-->
         <tr>
            <th style="text-align: center; width: 100px;"><b>CONSECUTIVO</b></th>
            <th style="text-align: center; width: 100px;"><b>ESTADO</b></th>
            <th style="text-align: center; width: 100px;"><b>RADICADO POR</b></th>
            <th style="text-align: center; width: 100px;"><b>TIPO DE DOCUMENTO</b></th>
            <th style="text-align: center; width: 100px;"><b>DEPENDENCIA</b></th>
            <th style="text-align: center; width: 100px;"><b>FUNCIONARIO</b></th>
            <th style="text-align: center; width: 100px;"><b>CIUDADANO</b></th>
            <th style="text-align: center; width: 100px;"><b>CANAL</b></th>
            <th style="text-align: center; width: 100px;"><b>FECHA DE CREACIÓN</b></th>
         </tr>
      </thead>
      @foreach ($request['internas'] as $data)<!--Fin de los titulos de la tabla de los registros-->
      <tbody>
         <tr>
            <td>{!! htmlentities($data['consecutive'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($data['state'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($data['from'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($data['type_document'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($data['dependency_from'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($data['radicador'] ?? 'N/A') !!}</td>
            <td>{{ limpiarHtmlParaExcel($data['citizen_name'] ?? 'N/A') }}</td>
            <td>{!! htmlentities($data['channel_name'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($data['created_at'] ?? 'N/A') !!}</td>
         </tr>
      </tbody>
      @endforeach
      <tr>
         <td colspan="9" rowspan="4"></td>
      </tr>
      <tr></tr>
      <tr></tr>
   </table>

@endforeach

