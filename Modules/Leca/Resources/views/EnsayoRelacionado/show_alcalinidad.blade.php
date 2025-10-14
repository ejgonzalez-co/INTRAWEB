{{-- Aqui empieza lo de el formulario --}}
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <table style="margin: 0 auto;" cellspacing="2" cellpadding="2" border="1">
            <tr>
                <th align="center" colspan="1" rowspan="5"><img src="{{ asset('assets/img/default/icon_epa.png') }}"
                        width="100" /></th>
                <th colspan="3" rowspan="5">Registro de datos primarios Ensayo Alcalinidad

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
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Fórmula</strong></h4>
        </div>
        <table class="tableTH" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
            <tr>
                <th>
                    Alcalinidad <strong>&le;</strong> 20 mg/L
                </th>
            </tr>
            <tr>
                <td>
                    <p>Alcalinidad mg CaCO<sub>3</sub>/L=((2b-C)*50000*N)/B</p>
                    <p>b = Volumen (mL) de H<sub>2</sub>SO<sub>4</sub> gastado hasta llegar a escala de pH 4,3 - 4,7</p>
                    <p>c = volumen total de h<sub>2</sub>SO<sub>4</sub> gastado (mL) = (b+mL de ácido para reducir el pH
                        en 0,3 unidades</p>
                </td>
            </tr>
        </table>
        <br><br>
        <table class="tableTH" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
            <tr>
                <th>
                    Alcalinidad <strong>&ge;</strong> 20 mg/L
                </th>
            </tr>
            <tr>
                <td>
                    <p>Alcalinidad mg CaCO<sub>3</sub>/L = (A*50000*N)/B</p>
                    <p>A = Volumen (mL) de H<sub>2</sub>SO<sub>4</sub></p>
                    <p>N = Normalidad de la Solución H<sub>2</sub>SO<sub>4</sub></p>
                    <p>B = Volumen de la muestra en mL</p>
                </td>
            </tr>
        </table>
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
                    <label class="col-form-label col-md-3">@lang('limit_cuantifi_method'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.limite_cuantificacion }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('v_muestra'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.v_muestra }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">Concentración H<sub>2</sub>SO<sub>4</sub> alcalinidad
                        alta:</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.alcalinidad_alta }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('Factor_calculate'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.factor_alcal_alta }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">Concentración H<sub>2</sub>SO<sub>4</sub> alcalinidad
                        baja:</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.alcalinidad_baja }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('Factor_calculate'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.factor_alcal_baja }}.</dd>
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
            <h4 class="panel-title"><strong>Control</strong></h4>
        </div>
        <table class="tableTH" width="500" height="100" cellspacing="2" cellpadding="2" border="1"
            style="margin: 0 auto;">
            <tr>
                <th>
                    Patrón de Na<sub>2</sub>Co<sub>3</sub> 10mg/L para alcalinidades &le; 20 mg/L y un patrón de 50 mg/L
                    para alcalinidades &ge; 20 mg/L
                </th>
                <th>
                    <p>DPR = Diferencia porcentual relativa</p>
                    <p>DPR = [|MFL - MFLD/((| MFL + MFLD/2))]*100</p>
                </th>
                <th>
                    <p>Control de pH</p>
                    <p>Verificar con solución buffer de pH a escala de 7 antes de iniciar el lote de muestras</p>
                </th>
            </tr>
        </table>
        <div class="row mt-3">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('curve'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.curva }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('date_curve'):</label>
                    <div class="col-md-9">
                        <dd class="form-control">@{{ dataShow.fehca_curva }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
   <div class="panel-body">
       <table class="tableTH" width="350" height="50" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
           <tr>
               <th>Convenciones
                   <p>DPR = Diferencia porcentual relativa</p>
                   <p>V = Volumen</p>
                   <p>F = Con fenolftaleína</p>
                   <p>M = con indicador Mixto</p>
                   <p>SM = Método estándar</p>
                   <p>X = Promedio duplicado muestras</p>
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
