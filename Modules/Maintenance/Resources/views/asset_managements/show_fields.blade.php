<div class="row">
    <!-- Nombre Activo Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Nombre del activo'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.nombre_activo }}.</dd>
</div>


<div class="row">
    <!-- Tipo Mantenimiento Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Tipo de mantenimiento'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.tipo_mantenimiento }}.</dd>
</div>


<div class="row">
    <!-- Kilometraje Actual Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Kilometraje de solicitud'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.request_need ? dataShow.request_need.kilometraje_horometro : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- Nombre Proveedor Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Nombre Proveedor'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.nombre_proveedor }}.</dd>
</div>


<div class="row">
    <!-- No Salida Almacen Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Número salida del almacen'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.no_salida_almacen }}.</dd>
</div>


<div class="row">
    <!-- No Factura Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Número de la factura'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.no_factura }}.</dd>
</div>

<div class="row">
    <!-- Fecha de solicitud Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Fecha de la solicitud'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.request_need ? (dataShow.request_need.date_supervisor_submission == null ? "No Aplica" : dataShow.request_need.date_supervisor_submission) : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- Fecha de envio al proveedor Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Fecha de envío al proveedor'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.request_need ? dataShow.request_need.approval_date : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- Kilometraje recibido por el proveedor Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Kilometraje recibido por el proveedor'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.order ? dataShow.order.current_mileage_or_hourmeter : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- Kilometraje de finalización del proveedor Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Kilometraje de finalización del proveedor'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.order ? dataShow.order.mileage_or_hourmeter_received : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- Fecha de finalización del proveedor Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Fecha de finalización del proveedor'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.order ? dataShow.order.supplier_end_date : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- Kilometraje de salida del almacén Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Kilometraje de salida del almacén'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.order ? (dataShow.order.mileage_out_stock == "N/A" ? "No Aplica" : (dataShow.order.mileage_out_stock == null ? "No Aplica" : dataShow.order.mileage_out_stock)) : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- Fecha de salida Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Fecha de salida'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.order ? (dataShow.order.fecha_salida_almacen == null ? "No Aplica" : dataShow.order.fecha_salida_almacen) : "No Aplica" }}.</dd>
</div>

<div class="row">
    <!-- No Solicitud Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Número de la solicitud'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.no_solicitud }}.</dd>
</div>

<div class="row" v-if="dataShow.actividades">
    <label for="" class="text-inverse text-left col-3 text-break"><strong>Actividades</strong></label>
    <!-- Actividad Field -->
    <table class="table table-hover m-b-0">
        <thead>
            <tr>
                <td class="text-center border"><strong>Descripción</strong></td>
                <td class="text-center border"><strong>Valor total</strong></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center border">@{{ dataShow.actividades.descripcion_nombre ? dataShow.actividades.descripcion_nombre : "No Aplica" }}</td>
                <td class="text-center border">@{{ dataShow.actividades.valor_total ? dataShow.actividades.valor_total : "No Aplica" }}</td>
            </tr>
        </tbody>
    </table>    
</div>

<div class="row mt-3" v-if="dataShow.repuestos">
    <label for="" class="text-inverse text-left col-3 text-break"><strong>Repuestos</strong></label>
    <!-- Actividad Field -->
    <table class="table table-hover m-b-0">
        <thead>
            <tr>
                <td class="text-center border"><strong>Descripción</strong></td>
                <td class="text-center border"><strong>Cantidad solicitada</strong></td>
                <td class="text-center border"><strong>Valor total</strong></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center border">@{{ dataShow.repuestos ? dataShow.repuestos.descripcion_nombre : ""}}</td>
                <td class="text-center border">@{{ dataShow.repuestos.cantidad_solicitada ? dataShow.repuestos.cantidad_solicitada : "No Aplica" }}</td>
                <td class="text-center border">$ @{{ dataShow.repuestos.valor_total ? dataShow.repuestos.valor_total : "No Aplica" }}</td>
            </tr>
        </tbody>
    </table>    
</div>