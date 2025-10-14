<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Detalles de la ruta</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Nombre Ruta Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Nombre Ruta'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.nombre_ruta }}.</dd>
        </div>
        

        <div class="row">
            <!-- Descripcion Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Descripcion'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.descripcion }}.</dd>
        </div>
        

        <div class="row">
            <!-- Nombre Usuario Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Creador'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.nombre_usuario }}.</dd>
        </div>
    </div>
</div>
 

 <div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Dependencias asociadas a la ruta</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
       <div class="row">
 
          <dd class="col-12">
             <table class="table table-bordered m-b-0" v-if="dataShow.planilla_ruta_dependencias ? dataShow.planilla_ruta_dependencias.length > 0 : ''">
                   <thead class="text-center">
                      <tr>
                         <th>#</th>
                         <th>Dependencia</th>
                      </tr>
                   </thead>
                   <tbody>
                      <tr v-for="(dependencia, key) in dataShow.planilla_ruta_dependencias" :key="key" class="text-center">
                         <td>@{{ key + 1 }}</td>
                         <td>@{{ dependencia.dependencias.nombre }}</td>
                      </tr>
                   </tbody>
             </table>
          </dd>
       </div>
    </div>
    <!-- end panel-body -->
 </div>