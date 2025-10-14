<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información general</strong></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break">Kilometraje/Horómetro actual:</strong>
            <p class="col-4 text">@{{ dataShow.current_mileage_or_hourmeter }}.</p>
        </div>
        
        <div class="row">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break">Kilometraje/Horómetro Recibido:</strong>
            <p class="col-4 text">@{{ dataShow.mileage_or_hourmeter_received }}.</p>
        </div>

        <div class="row">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break">Fecha finalización de trabajo:</strong>
            <p class="col-4 text">@{{ dataShow.date_work_completion }}.</p>
        </div>
        
        <div class="row">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break">Justificación:</strong>
            <p class="col-4 text">@{{ dataShow.provider_observation }}.</p>
        </div>

        <div class="row">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break">Evidencias:</strong>
            <p class="col-8">
                <viewer-attachement :list="dataShow.url_evidences" :open-default="false" :key="dataShow.url_evidences"></viewer-attachement>
            </p>
        </div>

        <div class="row">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break">Estado de la solicitud:</strong>
            <p v-if="dataShow.estado_proveedor == 'Pendiente' || dataShow.estado_proveedor == 'Entrada Pendiente' || dataShow.estado_proveedor == 'Salida Pendiente' || dataShow.estado_proveedor == 'Pendiente por finalizar'" class="text-white text-center p-4 bg-orange states_style">Pendiente.</p>
            <p v-if="dataShow.estado_proveedor == 'Entrada Confirmada' || dataShow.estado_proveedor == 'Salida Confirmada' || dataShow.estado_proveedor == 'Finalizado'" class="text-white text-center p-4 bg-green states_style">Finalizado.</p>
        </div>

    </div>
</div>

{{-- TODO: Obtener la ley de cierre de la solicitud principal pero teniendo en cuenta los items asignados --}}
<!-- Panel Necesidades-->

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Necesidades</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
           <div class="col-md-12">
               <div class="table-responsive">

                 <table class="table table-hover fix-vertical-table">
                    <thead>
                        <tr>
                        <th>Necesidad</th>
                        <th>Descripción</th>
                        <th>Cantidad solicitada</th>
                        <th>Unidad de medida</th>
                        <th>Valor Unitario</th>
                        <th>IVA</th>
                        <th>Valor total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(necesidad, key) in dataShow.ordenes_item" :key="key">
                        <td>@{{ necesidad.mant_request_need_item ? necesidad.mant_request_need_item.necesidad : "N/A" }}</td>
                        <td>@{{ necesidad.descripcion_nombre }}</td>
                        <td>@{{ necesidad.mant_request_need_item ? necesidad.mant_request_need_item.cantidad_solicitada : "N/A" }}</td>
                        <td>@{{ necesidad.mant_request_need_item ? necesidad.mant_request_need_item.unidad_medida : "N/A" }}</td>
                        <td>$ @{{ necesidad.mant_request_need_item ? necesidad.mant_request_need_item.valor_unitario : "N/A" }}</td>
                        <td>$ @{{ necesidad.mant_request_need_item ? necesidad.mant_request_need_item.IVA : "N/A" }}</td>
                        <td>$ @{{ necesidad.mant_request_need_item ? necesidad.mant_request_need_item.valor_total : "N/A" }}</td>
                        </tr>
                    </tbody>
                    <tfoot v-if="dataShow.ordenes_item && dataShow.ordenes_item.length">
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total general:</strong></td>
                        <td>
                        <strong>
                            $ @{{ 
                            dataShow.ordenes_item
                                ? dataShow.ordenes_item.reduce((sum, n) => 
                                    sum + (n.mant_request_need_item ? parseFloat(n.mant_request_need_item.valor_total) || 0 : 0), 
                                0).toLocaleString()
                                : 0
                            }}
                        </strong>
                        </td>
                    </tr>
                    </tfoot>

                    </table>

                 
               </div>
           </div>
       </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" v-show="dataShow.additions_spare_part_activities_approved">
    <div class="panel-heading">
        <div class="panel-title"><strong>Adición de repuestos y actividades</strong></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                <thead class="text-center bg-primary text-white">
                    <tr>
                        <td><strong>Necesidad </strong></td>
                        <td><strong>Descripción </strong></td>
                        <td><strong>Unidad de medida </strong></td>
                        <td><strong>Valor Unitario </strong></td>
                        <td><strong>IVA </strong></td>
                        <td><strong>Cantidad solicitada </strong></td>
                        <td><strong>Valor total </strong></td>
                        <td><strong>Tipo de mantenimiento </strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(need, key) in dataShow.additions_spare_part_activities_approved">
                        <td>@{{ need.need }}</td>
                        <td>@{{ need.description }}</td>
                        <td>@{{ need.unit_measurement }}</td>
                        <td>@{{ "$" + currencyFormat(need.unit_value) }}</td>
                        <td>@{{ "$" + currencyFormat(need.iva) }}</td>
                        <td>@{{ need.amount_requested }}</td>
                        <td>@{{ "$" + currencyFormat(need.valor_total) }}</td>
                        <td>@{{ need.maintenance_type }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
