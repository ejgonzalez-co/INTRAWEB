{{-- Aqui empieza lo de el formulario --}}
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <table style="margin: 0 auto;" cellspacing="2" cellpadding="2" border="1">
            <tr>
                <th align="center" colspan="1" rowspan="5"><img src="{{ asset('assets/img/default/icon_epa.png') }}"
                        width="100" /></th>
                <th colspan="3" rowspan="5">Registro de datos primarios Ensayos de lectura directa
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
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Para ensayo de color</strong></h4>
        </div>
        <table class="tableTH" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
            <tr>
                <th>
                    Unidades de color:
                    <div class="fraction">
                        <span class="fup">(A * 50)</span>
                        <span class="bar">/</span>
                        <span class="fdn">B</span>
                    </div>
                    <p>A = Color estimado de la muestra diluida</p>
                    <p>N = mL de muestra tomados para la dilución</p>
                </th>
            </tr>
        </table>
        <br>
        <table class="tableTH" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
            <tr>
                <th>
                    Rango de color
                </th>
                <th>
                    Reportar al cercano
                </th>
            </tr>
            <tr>
                <td>
                    <p>De 1 a 50 UPC</p>
                    <p>De 51 a 100 UPC</p>
                    <p>De 101 a 250 UPC</p>
                    <p>De 251 a 500 UPC</p>
                </td>
                <td>
                    <p>1</p>
                    <p>5</p>
                    <p>10</p>
                    <p>20</p>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Olor</strong></h4>
        </div>
        <table class="tableTH" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
            <tr>
                <td>0</td>
                <td>Libre de olor</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Muy leve</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Leve</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Moderado</td>
            </tr>
            <tr>
                <td>12</td>
                <td>Fuerte</td>
            </tr>
        </table>
        <br>
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
            <h4 class="panel-title"><strong>Ajuste curva</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('date_curve_one'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.fecha_ajuste_curva_uno }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('date_curve_two'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.fecha_ajuste_curva_dos }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('date_curve_three'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.fecha_ajuste_curva_tres }}.</dd>
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
            <h4 class="panel-title"><strong>Documento de referencia</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('duocument_reference_one'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.documento_referencia_uno }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('duocument_reference_two'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.documento_referencia_dos }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('duocument_reference_three'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.documento_referencia_tres }}.</dd>
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
            <h4 class="panel-title"><strong>Límite de cuantificación</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('limit_cuantifi_one'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.limite_cuantificacion_uno }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('limit_cuantifi_two'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.limite_cuantificacion_dos }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('limit_cuantifi_three'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.limite_cuantificacion_tres }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('floating_substances'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.sustancias_flotantes }}.</dd>
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
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Patrón de control (3)</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('name'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.nombre_patron_tres }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('expected_value'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.valor_esperador_tres }}.</dd>
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
                <th>Convenciones
                    <p>Cond: Conductividad</p>
                    <p>UPC: Unidades de platino cobalto</p>
                    <p>Ẋ: Promedio</p>
                    <p>DPR: (Diferencia porcentual relativa)</p>
                    <p>T: Temperatura en grados Celsius</p>
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
        <div class="row">
            <div class="col-md-9 ">
                <p>@{{ dataShow.obervacion }}.</p>
            </div>
        </div>
    </div>
</div>
