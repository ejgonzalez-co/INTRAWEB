<table border="1">
   <thead>
      <tr>
         <td>Fecha de creación</td>
         <td>Signatura topográfica</td>
         <td>Serie/subserie documental</td>
         <td>Descripción</td>
         <td>Fecha inicial</td>
         <td>Fecha final</td>
         <td>Total folios</td>
         <td>Soportes</td>
         <td>Cantidad tomos digitalizados</td>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $item)
         @foreach ($item as $item2)
         <tr>
               <td style="text-align: center">{!! $item2->created_at ? $item2->created_at : null !!}</td>
               <td style="text-align: center">
                  <Strong>Estantería: &nbsp;&nbsp;</Strong>{!! $item2->shelving ? $item2->shelving : 'N/A'!!} <br>
                  <strong>Bandeja: &nbsp;&nbsp;</strong>{!! $item2->tray ? $item2->tray : 'N/A' !!}  <br>
                  <strong>Caja: &nbsp;&nbsp;</strong>{!! $item2->box ? $item2->box : 'N/A' !!}  <br>
                  <strong>Carpeta: &nbsp;&nbsp;</strong>{!! $item2->file ? $item2->file : 'N/A' !!}  <br>
                  <strong>Libro: &nbsp;&nbsp;</strong>{!! $item2->book ? $item2->book : 'N/A' !!}  <br>
               </td>
               <td style="text-align: center">{!! $item2->name_serie_subserie ? $item2->name_serie_subserie : null !!}</td>
               <td>{!! $item2->description_expedient ? $item2->description_expedient : null !!}</td>
               <td style="text-align: center">{!! $item2->date_initial ? $item2->date_initial : null !!}</td>
               <td style="text-align: center">{!! $item2->date_finish ? $item2->date_finish: null !!}</td>
               <td style="text-align: center">{!! $item2->folios ? $item2->folios: null !!}</td>
               <td style="text-align: center">{!! $item2->soport ? $item2->soport : null !!}</td>
               <td style="text-align: center">{!! $item2->count_documents ? $item2->count_documents : 0 !!}</td>
            </tr>
         @endforeach
      @endforeach
   </tbody>
</table>