{{-- <html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 14">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>INVENTARIO DOCUMENTAL</title>
</head> --}}


<table border="1">
   <thead>
       <tr class="clase_gris_claro">
         <td rowspan="3">Orden</td>
         <td colspan="5" style="text-align: center">Número de</td>
         <td rowspan="3" style="text-align: center">Nombre de las Series y Subseries Documentales</td>
         <td colspan="6" style="text-align: center">Fechas Extremas</td>
         <td rowspan="3" style="text-align: center">Número de Folios</td>
         <td colspan="3" rowspan="2" style="text-align: center">Soporte<br>(Marque X)</td>
         <td rowspan="3">Observaciones</td>
       </tr>
       <tr class="clase_gris_claro">
         <td rowspan="2" width="50"> Estantería</td>
         <td rowspan="2" width="50">Bandeja</td>
         <td rowspan="2" width="50"> Caja</td>
         <td rowspan="2" width="50">Carpeta</td>
         <td rowspan="2" width="50">Libro</td>
         <td colspan="3" style="text-align: center">Inicial</td>
         <td colspan="3" style="text-align: center">Final</td>
       </tr>
       <tr class="clase_gris_claro">
         <td>AAAA</td>
         <td>MM</td>
         <td>DD</td>
         <td>AAAA</td>
         <td>MM</td>
         <td>DD</td>
         <td style="text-align: center">Físico</td>
         <td style="text-align: center">Electrónico</td>
         <td style="text-align: center">Físico y Electrónico</td>
       </tr>
   </thead>
   <tbody>
      @foreach ($data as $item)
            @foreach ($item as $item2)
               <tr>
                  <td style="text-align: center">{!! $item2['orden'] ? $item2['orden'] : null !!}</td>
                  <td style="text-align: center">{!! $item2['shelving'] ? $item2['shelving'] : 'NA' !!}</td>
                  <td style="text-align: center">{!! $item2['tray'] ? $item2['tray'] : 'NA' !!} </td>
                  <td style="text-align: center">{!! $item2['box'] ? $item2['box'] : 'NA' !!}  </td>
                  <td style="text-align: center">{!! $item2['file'] ? $item2['file'] : 'NA' !!} </td>
                  <td style="text-align: center">{!! $item2['book'] ? $item2['book'] : 'NA' !!} </td>
                  <td style="text-align: center">{!! $item2['name_serie_subserie'] ? $item2['name_serie_subserie'] : null !!}</td>
                  <td style="text-align: center">{!! $item2['date_initial'] ? $item2['date_initial'] : 0 !!}</td>
                  <td style="text-align: center">{!! $item2['mounth_initial'] ? $item2['mounth_initial'] : 0 !!}</td>
                  <td style="text-align: center">{!! $item2['day_initial'] ? $item2['day_initial'] : 0 !!}</td>
                  <td style="text-align: center">{!! $item2['date_finish'] ? $item2['date_finish'] : 0 !!}</td>
                  <td style="text-align: center">{!! $item2['mounth_finish'] ? $item2['mounth_finish'] : 0 !!}</td>
                  <td style="text-align: center">{!! $item2['mounth_finish'] ? $item2['mounth_finish'] : 0 !!}</td>
                  <td style="text-align: center">{!! $item2['folios'] ? $item2['folios'] : null !!}</td>
                  <td style="text-align: center">
                     @if ($item2['soport'] == 'Físico')
                        X
                        @else

                     @endif
                  </td>
                  <td style="text-align: center">
                     @if ($item2['soport'] == 'Electrónico')
                        X
                        @else

                     @endif
                  </td>
                  <td style="text-align: center">
                     @if ($item2['soport'] == 'Físico y Electrónico')
                        X
                        @else

                     @endif
                  </td>
                  <td >{!! $item2['description_expedient'] ? $item2['description_expedient'] : null !!}</td>
               </tr>
            @endforeach
      @endforeach
   </tbody>
</table>
