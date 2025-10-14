<!-- Dependencias Id Field -->
<dt class="text-inverse text-right col-3 text-truncate">@lang('Dependencia'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.dependencias? dataShow.dependencias.nombre : '' }}.</dd>

<!-- Responsable Field -->
<dt class="text-inverse text-right col-3 text-truncate">@lang('Responsable'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.usuarios ? dataShow.usuarios.name : '' }}.</dd>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle" style="padding-bottom: 0px;">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Lista de categorías autorizadas</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha de autorización</th>
                                <th>Tipo de activo</th>
                                <th>Categoría</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(category, key) in dataShow.authorized_categories_model" v-if="!category.mant_category.deleted_at">
                                <td>@{{ category.created_at }}</td>
                                <td>@{{ category.mant_asset_type.name }}</td>
                                <td>@{{ category.mant_category.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>