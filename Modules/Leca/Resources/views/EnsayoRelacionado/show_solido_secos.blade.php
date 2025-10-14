{{-- Aqui empieza lo de el formulario --}}
<div class="panel" data-sortable-id="ui-general-1">
   <div class="panel-body">
       <table style="margin: 0 auto;" cellspacing="2" cellpadding="2" border="1">
           <tr>
               <th align="center" colspan="1" rowspan="5"><img src="{{ asset('assets/img/default/icon_epa.png') }}"
                       width="100" /></th>
               <th colspan="3" rowspan="5">Registro de datos primarios Ensayo de sólidos

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
           {{-- <div class="col-md-6">
               <!-- Pendiente -->
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-3">@lang('listTrial'):</label>
                   <div class="col-md-9">
                       <dd class="form-control">@{{ dataShow.ensayo_uno }}.</dd>
                   </div>
               </div>
           </div> --}}
           <div class="col-md-6">
               <!-- Pendiente -->
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-3">@lang('listTrial_two'):</label>
                   <div class="col-md-9">
                       <dd class="form-control">@{{ dataShow.ensayo_dos }}.</dd>
                   </div>
               </div>
           </div>
           <div class="col-md-6">
               <!-- Pendiente -->
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-3">@lang('Cantidad de decimales'):</label>
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
            <table class="tableTH" width="350" height="50" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
                {{-- <tr>
                    <th>Ensayo de sólidos totales disueltos a 180°C</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>
                        mg sólidos totales disueltos/L :
                        <div class="fraction">
                        <span class="fup">(A-B)*1000</span>
                        <span class="bar">/</span>
                        <span class="fdn">Volumen de muestra en mL</span>
                        </div>
                    </td>
                    <td>
                        <p>A = Peso del filtro + residuo seco en mg y</p>
                        <p>B = Peso del filtro, mg</p>
                    </td>
                </tr> --}}
                <tr>
                    <th>Ensayo de sólidos totales secos a (103-105)°C</th>
                    <th>Descripción</th>
                </tr>
                <tr>
                    <td>
                        mg sólidos totales secos/L :
                        <div class="fraction">
                        <span class="fup">(A-B)*1000</span>
                        <span class="bar">/</span>
                        <span class="fdn">Volumen de muestra en mL</span>
                        </div>
                    </td>
                    <td>
                        <p>A = Peso de residuo seco + plato mg</p>
                        <p>B = Peso de plato mg</p>
                    </td>
                </tr>
            </table>
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
            <h4 class="panel-title"><strong>Documento de referencia</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('duocument_reference_one'):</label>
                    <div class="col-md-9">
                        <dd >@{{ dataShow.documento_referencia_uno }}.</dd>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Pendiente -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3">@lang('duocument_reference_two'):</label>
                    <div class="col-md-9">
                        <dd >@{{ dataShow.documento_referencia_dos }}.</dd>
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
       <div class="row text-center">
           <div class="col-md-9 ">
               <p>@{{ dataShow.obervacion }}.</p>
           </div>
       </div>
   </div>
</div>
