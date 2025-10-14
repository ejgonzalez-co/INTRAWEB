{{-- Aqui empieza lo de el formulario --}}
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <table style="margin: 0 auto;" cellspacing="2" cellpadding="2" border="1">
            <tr>
                <th align="center" colspan="1" rowspan="5"><img src="{{ asset('assets/img/default/icon_epa.png') }}"
                        width="100" /></th>
                <th colspan="3" rowspan="5">Registro de datos primarios Ensayo turbiedad y pH
                </th>
                <th rowspan="1">Documento Controlado</th>
            </tr>
        </table>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Informacíón General</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('Process'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.proceso }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('duocument_reference'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.documento_referencia }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('year'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.año }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('month'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.mes }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">Cantidad de decimales:</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.decimales }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel" data-sortable-id="ui-general-1"
        v-if="dataShow.lc_critical_equipments? dataShow.lc_critical_equipments.length > 0 : ''">
        <div class="panel-body">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Equipo crítico</strong></h4>
            </div>
            <div>

                <table class="table table-bordered m-b-0">
                    <thead>
                        <tr>
                            <th>Equipo crítico</th>
                            <th>Identificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, key) in dataShow.lc_critical_equipments" :key="key">
                            <td>@{{ item.equipo_critico }}</td>
                            <td>@{{ item.identificacion }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Límite de cuantificación</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('limit_cuantifi_method_one'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.limite_cuantificacion_uno }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('limit_cuantifi_method_two'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.limite_cuantificacion_dos }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Patrón de control (1)</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('name'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.nombre_patron_uno }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('expected_value'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.valor_esperador_uno }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Patrón de control (2)</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('name'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.nombre_patron_dos }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('expected_value'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.valor_esperado_dos }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <table class="tableTH" width="350" height="50" cellspacing="2" cellpadding="2" border="1"
            style="margin: 0 auto;">
            <tr>
                <th>
                    Nota: Para el procedimiento de pH la medición de cada ítem de ensayo examinado por duplicado y la
                    diferencia entre las dos mediciones no puede superar 0,1 unidades pH y se reportará el valor de la
                    medida (X)
                </th>
            </tr>
        </table>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Ajuste curva</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('date_curve'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.fecha_curva }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('pending'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.pendiente }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <table class="tableTH" width="350" height="50" cellspacing="2" cellpadding="2" border="1"
            style="margin: 0 auto;">
            <tr>
                <th>
                    Expresión de resultados para turbiedad
                </th>
            </tr>
            <tr>
                <th>
                    Rango de turbiedad (NTU)
                </th>
                <th>
                    Reportar al valor más cercano (NTU)
                </th>
            </tr>
            <tr>
                <td>
                    <p>0-1,0</p>
                    <p>> 1-10</p>
                    <p>> 10-40</p>
                    <p>> 40-100</p>
                    <p>> 100-400</p>
                    <p>> 400-1000</p>
                    <p>> 1000</p>
                </td>
                <td>
                    <p>0,05</p>
                    <p>0,1</p>
                    <p>1</p>
                    <p>5</p>
                    <p>10</p>
                    <p>50</p>
                    <p>10</p>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <table class="tableTH" width="350" height="50" cellspacing="2" cellpadding="2" border="1"
            style="margin: 0 auto;">
            <tr>
                <th>Convenciones
                    <p>X = Promedio</p>
                    <p>NTU = Unidad nefelométrica de turbiedad</p>
                    <p>T = Temperatura en grados celsius</p>
                    <p>PH = Potencial de hidrógeno </p>
                    <p>DPR = (Diferencia porcentual relativa)</p>
                </th>
            </tr>
        </table>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Observaciones</strong></h4>
        </div>
        <div class="row text-center">
            <div class="col-md-9 ">
                <p>@{{ dataShow.obervacion }}.</p>
            </div>
        </div>
    </div>
</div>
