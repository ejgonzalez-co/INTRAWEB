<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Seguimiento</strong></h3>
    </div>
    <div v-if="dataShow.tic_type_category_history?.length > 0" class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover fix-vertical-table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th class="col-2">@lang('Created_at')</th>
                        <th class="col-3">@lang('Nombre')</th>
                        <th class="col-2">@lang('Estado')</th>
                        <th class="col-2">@lang('Id Usuario')</th>
                        <th class="col-3">@lang('Usuario')</th>
                        <th class="col-3">@lang('Tipos')</th>
                        <th class="col-2">@lang('Fecha Actualización')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(history, key) in dataShow.tic_type_category_history" class="align-middle">
                        <td class="col-2">@{{ history.created_at }}</td>
                        <td class="col-3">@{{ history.name }}</td>
                        <td class="col-2">@{{ history.estado }}</td>
                        <td class="col-2">@{{ history.id_usuario }}</td>
                        <td class="col-3">@{{ history.name_user }}</td>
                        <td class="col-3">@{{ history.tipos }}</td>
                        <td class="col-2">@{{ history.updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div v-else>
        <div class="panel-body">
            <div class="alert alert-info" role="alert">
                <strong>No hay historial disponible</strong>
            </div>
        </div>
    </div>
</div>