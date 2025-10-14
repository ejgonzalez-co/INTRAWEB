<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Identificación de necesidades</strong></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                <thead class="text-center bg-primary text-white">
                    <tr>
                        <td><strong>Necesidad</strong></td>
                        <td><strong>Descripción</strong></td>
                        <td><strong>Unidad de medida</strong></td>
                        <td><strong>Valor Unitario</strong></td>
                        <td><strong>IVA</strong></td>
                        <td><strong>Cantidad solicitada</strong></td>
                        <td><strong>Valor total</strong></td>
                        <td><strong>Tipo de mantenimiento</strong></td>
                        <td><strong>Aprobación</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(need, key) in dataShow.needs">
                        <td>@{{ need.need }}</td>
                        <td>@{{ need.description }}</td>
                        <td>@{{ need.unit_measurement }}</td>
                        <td>@{{ "$" + currencyFormat(need.unit_value) }}</td>
                        <td>@{{ "$" + currencyFormat(need.iva) }}</td>
                        <td>@{{ need.amount_requested }}</td>
                        <td>@{{ "$" + currencyFormat(need.valor_total) }}</td>
                        <td>@{{ need.maintenance_type }}</td>
                        <td class="text-center">
                            <span v-if="need.is_approved == 1" class="badge badge-success">Aprobado</span>
                            <span v-else class="badge badge-secondary">Sin aprobar</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Observaciones</strong></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <dt class="text-inverse text-left col-3 text-break">@lang('Observations'):</dt>
            <dd class="col-9 text-break">@{{ dataShow.provider_observation }}.</dd>
        </div>
    </div>
</div>