{{-- Aqui empieza lo de el formulario --}}
<div class="panel" data-sortable-id="ui-general-1">
   <div class="panel-body">
       <table style="margin: 0 auto;" cellspacing="2" cellpadding="2" border="1">
           <tr>
               <th align="center" colspan="1" rowspan="5"><img src="{{ asset('assets/img/default/icon_epa.png') }}"
                       width="100" /></th>
               <th colspan="3" rowspan="5">Registro de datos primarios  Ensayo Sulfatos

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
                   <p>Curva de Sulfatos:</p>
                   <p>Y = m*x+b</p>
                   <i>Para turb &ge; 10</i>
                   <div class="fraction">
                   <span class="fup">mg</span>
                   <span class="bar">/</span>
                   <span class="fdn">L</span>
                   </div>
                   donde x= Turb(<sub>2</sub>)-Turb (<sub>1</sub>) <br>
                   <i>Para turb &le; 10</i>
                   <div class="fraction">
                   <span class="fup">mg</span>
                   <span class="bar">/</span>
                   <span class="fdn">L</span>
                   </div>
                   donde x= Turb (<sub>2</sub>) - (Turb (<sub>1</sub>) - turb BK)
                   <br>
                   <p>F1 = Y= 3,8757X - 17,092</p>
                   <p>F2 = Y= 3,9483X + 42,794</p>
                   <p>F3 = N/A</p>
               </th>
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
       </div>
   </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
   <div class="panel-body">
       <!-- begin panel-heading -->
       <div class="panel-heading ui-sortable-handle">
           <h4 class="panel-title"><strong>Convenciones</strong></h4>
       </div>
       <table class="tableTH" width="350" height="50" cellspacing="2" cellpadding="2" border="1" style="margin: 0 auto;">
           <tr>
               <th>Convenciones
                   <p>BUFFER A = concentraciones mayores de 10mg/L</p>
                   <p>BUFFER B = concentraciones menores de 10mg/L de sulfatos (con sulfato de sodio)</p>
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
