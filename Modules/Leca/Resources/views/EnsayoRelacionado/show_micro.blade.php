{{-- Aqui empieza lo de el formulario --}}
<div class="panel" data-sortable-id="ui-general-1">
   <div class="panel-body">
       <table style="margin: 0 auto;" cellspacing="2" cellpadding="2" border="1">
           <tr>
               <th align="center" colspan="1" rowspan="5"><img src="{{ asset('assets/img/default/icon_epa.png') }}"
                       width="100" /></th>
               <th colspan="3" rowspan="5">Registro de datos primarios Ensayos Microbiología

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
                       <dd class="form-control">@{{ dataShow.processo }}.</dd>
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
                   <label class="col-form-label col-md-3">@lang('Cantidad de decimales'):</label>
                   <div class="col-md-9">
                       <dd class="form-control">@{{ dataShow.decimales }}.</dd>
                   </div>
               </div>
           </div>
           <div class="col-md-6">
               <!-- Pendiente -->
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-3">@lang('technique'):</label>
                   <div class="col-md-9">
                       <dd class="form-control">@{{ dataShow.tecnica }}.</dd>
                   </div>
               </div>
           </div>
           <div class="col-md-6">
            <!-- Pendiente -->
            <div class="form-group row m-b-15">
                <label class="col-form-label col-md-3">@lang('listTrial'):</label>
                <div class="col-md-9">
                    <dd class="form-control">@{{ dataShow.ensayo }}.</dd>
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
