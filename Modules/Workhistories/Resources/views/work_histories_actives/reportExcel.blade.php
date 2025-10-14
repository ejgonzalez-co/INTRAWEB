<table border="1">
<colgroup>
<col>
<col>
<col>
<col>
<col>
<col>
<col>
<col>
<col>
<col>
</colgroup>
<thead >
  <tr>
    <th colspan="3" rowspan="5" height="5"><img src="assets/img/default/icon_epa.png" width="100"></th>
    <th colspan="5" rowspan="5" width="30" height="5" style="text-align:center">Hoja de Control Historia Laboral</th>
    <th colspan="2"  width="20" height="25">Documento Controlado</th>
  </tr>
  <tr>
    <td colspan="2" height="25">Código: GTH-R-031</td>
  </tr>
  <tr>
    <td colspan="2">Versión: 02</td>
  </tr>
  <tr>
    <td colspan="2">Fecha de Emisión: 17-05-10</td>
  </tr>
  <tr>
    <td colspan="2">Pagina:</td>
  </tr>
</thead>
<tbody>
  @foreach($data as $data_ind)

  <tr>
    <td colspan="3">Nombre Funcionario</td>
    <td colspan="5">{{ $data_ind["name"] }}</td>
    <td>Cedula de Ciudadanía</td>
    <td>{{ $data_ind["number_document"] }}</td>
  </tr>
  <tr>
    <td colspan="3">Fecha de Ingreso del tipo documental</td>
    <td colspan="6" rowspan="2">Descripción del tipo documental</td>
    <td rowspan="2">Numero de Folio (s)</td>
  </tr>
  <tr>
    <td width="12">AA</td>
    <td width="12">MM</td>
    <td width="12">DD</td>
  </tr>



    @if(!empty($data_ind["workHistorieDocuments"]))
      @foreach($data_ind["workHistorieDocuments"] as $document)
      <tr>

        @php

        if(!$document["document_date"]){
          $document["document_date"] = date("Y-m-d");
          }

          $values = explode("-",$document["document_date"]);
            $values2 = explode(" ",$values[2]);

        @endphp

            <td>{{ $values[0] }}</td>
            <td>{{  $values[1] }}</td>
            <td>{{  $values2[0] }}</td>
            <td colspan="6">{{ $document["description"] }}</td>
            <td>{{ $document["sheet"] }}</td>
        </tr>
        @endforeach
    @endif


      @endforeach

  <tr>
    <td colspan="3" rowspan="3">Validación fecha de ingreso del tipo documental relacionado en el Ultimo Reglón de la Página</td>
    <td>AA</td>
    <td>MM</td>
    <td>DD</td>
    <td>Firma</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td rowspan="2">Nombre</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td style="border-bottom: 1px solid #000000;" colspan="3">Funcionario responsable de Historias Laboral</td>
  </tr>
</tbody>
</table>
