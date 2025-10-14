
 <!-- Panel Detalles de la anotación -->
 <div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Detalles</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body"> 
        <div class="row">
            <!-- Nombre Usuario Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Usuario'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.nombre_usuario }}.</dd>
        </div>

        <div class="row">
            <!-- Anotacion Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Anotación'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.anotacion }}.</dd>
        </div>
    </div>
 </div>

 <!-- Panel leidos de la anotación -->
{{-- <div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Quiénes han leido la anotación</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <table id="anotaciones" class="table text-center" border="1">
            <thead>
               <tr class="font-weight-bold" style="background-color: #ff98008a">
                  <td>Usuario</td>
                  <td>Rol</td>
                  <td>Accesos</td>
               </tr>
            </thead>
            <tbody>
               <tr v-for="leido_anotacion in dataShow.pqr_anotacion_leidos">
                  <td>@{{ leido_anotacion.nombre_usuario }}</td>
                  <td>@{{ leido_anotacion.tipo_usuario }}</td>
                  <td v-html="leido_anotacion.accesos"></td>
               </tr>
            </tbody>
        </table>
    </div>
</div> --}}