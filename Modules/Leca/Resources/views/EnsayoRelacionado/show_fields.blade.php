{{-- Aqui empieza lo de el formulario --}}
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <table style="margin: 0 auto;" cellspacing="2" cellpadding="2" border="1">
            <tr>
                <th align="center" colspan="1" rowspan="5"><img src="{{ asset('assets/img/default/icon_epa.png')}}" width="100"/></th>
                <th colspan="3" rowspan="5">Registro de datos primarios Ensayos espectrofotométricos</th>
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
                        <dd class="form-control">@{{ dataShow.processo }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('Ensayo'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.ensayo }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">Documento de referencia::</label>
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
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Fórmula</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('K_pending'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.k_pendiente }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('b_intercep'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.b_intercepto }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('fd_factor'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.fd_factor_dilucion }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel" data-sortable-id="ui-general-1" v-if="dataShow.lc_critical_equipments? dataShow.lc_critical_equipments.length > 0 : ''">
        <div class="panel-body">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Equipo crítico</strong></h4>
            </div>
            <div>
    
            <table class="table table-bordered m-b-0" >
            <thead>
                <tr>
                    <th>Equipo crítico</th>
                    <th>Identificación</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for ="(item, key) in dataShow.lc_critical_equipments" :key="key">
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
            <h4 class="panel-title"><strong>Patrón de control</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('name'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.nombre_patron }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('expected_value'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.valor_esperado }}.</dd>
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
            <h4 class="panel-title"><strong>Recuperación adicionado</strong></h4>
        </div>
        <div style="text-align: center">
            <div style="padding: 30px">
                <strong>
                    <div class="eq-c">
                        <i>%</i>Recuperación del adicionado =
                        <div class="fraction">
                        <span class="fup">concentración <sub>adicionado</sub> * volumen <sub>adicionado</sub> + volumen <sub>muestra</sub> - <br>
                        concentración <sub>muestra</sub> * volumen <sub>muestra</sub></span>
                        <span class="bar">/</span>
                        <span class="fdn">concentración <sub>solución madre</sub> * volumen <sub>solución madre adicionado</sub></span>
                        </div>
                        *100
                    </div>
                </strong>
            </div>
        </div>
        <div class="row">

        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Límite</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('quantification_limit'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.limite_cuantificacion }}.</dd>
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
            <h4 class="panel-title"><strong>Diferencia porcentual relativa</strong></h4>
        </div>
        <div style="padding: 30px">
            <table class="tableTH" width="350" height="50" cellspacing="2" cellpadding="2" style="margin: 0 auto;">
                <tr>
                    <th>
                        DPR = [IMFL - MFLD/((IMFL+MFLD/2) )]*100
                    </th>
                </tr>
            </table>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('curve_number'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.curva_numero }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('date_curve'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.fecha_curva }}.</dd>
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
            <h4 class="panel-title"><strong>Observaciones</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-9 ">
                <p>@{{ dataShow.obervacion }}.</p>
            </div>
        </div>
    </div>
</div>

