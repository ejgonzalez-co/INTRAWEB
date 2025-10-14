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
         <th colspan="11" style="text-align: center;"><b>REPORTE DE CORRESPONDENCIA INTERNA</b></th>
      </tr>
   </thead>
   <tbody>
      <tr >
        <td colspan="6" style="text-align: center;"><b>RADICADOR:</b> {!! $request['radicador'] !!}</td>
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
            <th style="text-align: center; width: 100px;"><b>ESTADO</b></th>
            <th style="text-align: center; width: 100px;"><b>ENVIADO A</b></th>
            <th style="text-align: center; width: 100px;"><b>RADICADO POR</b></th>
            <th style="text-align: center; width: 100px;"><b>PLANTILLA</b></th>
            <th style="text-align: center; width: 100px;"><b>FECHA DE CREACIÓN</b></th>
            <th style="text-align: center; width: 100px;"><b>ORIGEN</b></th>
            <th style="text-align: center; width: 100px;"><b>ULTIMA MODIFICACIÓN</b></th>
            <th style="text-align: center; width: 100px;"><b>COPIAS</b></th>
            <th style="text-align: center; width: 100px;"><b>COMPARTIDOS</b></th>
            <th style="text-align: center; width: 100px;"><b>LEIDO</b></th>

         </tr>
      </thead>
      @foreach ($request['internas'] as $data)<!--Fin de los titulos de la tabla de los registros-->
      <tbody>
         <tr>
            <th>{!! $data['consecutive'] !!}</th>
            <th>{!! $data['state'] !!}</th>
            <th>{!! $data['recipients'] !!}</th>
            <th>{!! $data['from'] !!}</th>
            <th>{!! $data['type_document'] !!}</th>
            <th>{!! $data['created_at'] !!}</th>
            <th>{!! $data['origen'] !!}</th>
            <th>{!! $data['updated_at'] !!}</th>
            <th>
               {!! implode(', ', array_column($data['internal_copy'], 'name')) !!}
            </th>
            <th>
               {!! implode(', ', array_column($data['internal_shares'], 'name')) !!}
            </th>
            <th>
               {!! implode(', ', array_column($data['internal_chequeos'], 'fullname')) !!}
            </th>
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

