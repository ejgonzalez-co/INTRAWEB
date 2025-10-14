<div class="panel">
   <div class="panel-heading">
      <div class="panel-title"><strong>Generar orden</strong></div>
   </div>
   <div class="panel-body">

      <table class="table table-hover m-b-0">
         <thead>
             <tr>
                 <td class="text-center border"><strong>Descripción</strong></td>
                 <td class="text-center border" v-if="dataShow.tramite_almacen == 'Salida Pendiente' || dataShow.tramite_almacen == 'Salida Confirmada'"><strong>Código</strong></td>
                 <td class="text-center border"><strong>Unidad de medida</strong></td>
                 <td class="text-center border"><strong>Cantidad solicitada</strong></td>

                 <td class="text-center border"><strong>Unidad convertida</strong></td>
                 <td class="text-center border"><strong>Cantidad convertida</strong></td>


                 <td class="text-center border"><strong>Tipo Mantenimiento</strong></td>
                 <td class="text-center border"><strong>Observación</strong></td>
             </tr>
         </thead>
         <tbody>
             <tr v-for="(orden_item,key) in dataShow.ordenes_item" :key="key">
                 <td class="text-center border">@{{ orden_item.descripcion_nombre ? orden_item.descripcion_nombre : "" }}</td>
                 <td class="text-center border" v-if="dataShow.tramite_almacen == 'Salida Pendiente' || dataShow.tramite_almacen == 'Salida Confirmada'">@{{ orden_item.codigo_salida ? orden_item.codigo_salida : orden_item.codigo_entrada }}</td>
                 <td class="text-center border">@{{ orden_item.unidad ? orden_item.unidad : "" }}</td>
                 <td class="text-center border">@{{ orden_item.cantidad ? orden_item.cantidad : "" }}</td>

                 <!-- 🔹 Nuevos valores -->
                 <td class="text-center border">
                     @{{ orden_item.unidad_medida_conversion ? orden_item.unidad_medida_conversion : "No aplica" }}
                 </td>
                 <td class="text-center border">
                     @{{ orden_item.cantidad_solicitada_conversion ? orden_item.cantidad_solicitada_conversion : "No aplica" }}
                 </td>

                 <td class="text-center border">@{{ orden_item.tipo_mantenimiento ? orden_item.tipo_mantenimiento : "" }}</td>
                 <td class="text-center border">@{{ orden_item.observacion ? orden_item.observacion : "" }}</td>
             </tr>
         </tbody>
     </table>

   </div>
</div>

<div class="panel">
   <div class="panel-heading">
      <div class="panel-title" v-if="dataShow.numero_entrada_almacen"><strong>Información de entrada del almacén</strong></div>
      <div class="panel-title" v-else><strong>Información de salida del almacén</strong></div>
   </div>
   <div class="panel-body">

    <div class="row" v-if="dataShow.numero_salida_almacen">
        <!-- Anotacion Field -->
        <strong class="text-inverse text-left col-4 text-break" >Número de salida del almacén:</strong>
        <p class="col-4 text">@{{ dataShow.numero_salida_almacen }}.</p>
    </div>
    <div class="row" v-if="dataShow.fecha_salida_almacen">
        <!-- Anotacion Field -->
        <strong class="text-inverse text-left col-4 text-break">Fecha de salida del almacén:</strong>
        <p class="col-4 text">@{{ dataShow.fecha_salida_almacen ? formatDate(dataShow.fecha_salida_almacen) : "" }}.</p>
    </div>

    <div class="row" v-if="dataShow.numero_entrada_almacen">
        <!-- Anotacion Field -->
        <strong class="text-inverse text-left col-4 text-break">Número de entrada del almacén:</strong>
        <p class="col-4 text">@{{ dataShow.numero_entrada_almacen }}.</p>
    </div>
    <div class="row" v-if="dataShow.numero_factura">
        <!-- Anotacion Field -->
        <strong class="text-inverse text-left col-4 text-break">Número de factura:</strong>
        <p class="col-4 text">@{{ dataShow.numero_factura }}.</p>
    </div>
    <div class="row" v-if="dataShow.fecha_entrada_almacen">
        <!-- Anotacion Field -->
        <strong class="text-inverse text-left col-4 text-break">Fecha de entrada del almacén:</strong>
        <p class="col-4 text">@{{ dataShow.fecha_entrada_almacen ? formatDate(dataShow.fecha_entrada_almacen) : "" }}.</p>
    </div>
    <div class="row" v-if="dataShow.mileage_out_stock">
        <!-- Anotacion Field -->
        <strong class="text-inverse text-left col-4 text-break">Kilometraje de salida del almacén:</strong>
        <p class="col-4 text">@{{ dataShow.mileage_out_stock ? dataShow.mileage_out_stock : "" }}.</p>
    </div>

   </div>
</div>