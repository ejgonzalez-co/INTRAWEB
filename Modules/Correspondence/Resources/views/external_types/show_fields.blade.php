<!-- Panel Variables de la plantilla -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Detalles de la plantilla</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Name Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Name'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.name }}.</dd>
        </div>

        <div class="row">
            <!-- Template Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Template') Google Docs:</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">
                <div v-if="dataShow.template">
                    <span v-for="template in dataShow.template.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+template" target="_blank">Ver plantilla</a><br/></span>
                </div>
                <div v-else>
                    <span>No tiene plantilla</span>
                </div>
            </dd>
        </div>

        <div class="row">
            <!-- Prefix Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Prefix'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.prefix }}.</dd>
        </div>
    </div>
</div>
 
<!-- Panel Variables de la plantilla -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Variables de la plantilla</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <table id="anotaciones" class="table text-center mt-2" border="1">
            <thead>
               <tr class="font-weight-bold" style="background-color: rgb(198, 198, 198)">
                  <td>Variable</td>
                  <td>Descripción</td>
               </tr>
            </thead>
            <tbody>
                {{-- Listado de variables de la plantilla --}}
                <tr v-for="variable in dataShow.variables?.split(',')">
                    <td>@{{ variable }}</td>
                    <td v-if="variable == '#consecutivo'">Consecutivo del documento</td>
                    <td v-else-if="variable == '#titulo'">Título del documento</td>
                    <td v-else-if="variable == '#remitente'">Remitente del documento</td>
                    <td v-else-if="variable == '#dependencia_remitente'">Dependencia remitente del documento</td>
                    <td v-else-if="variable == '#destinatarios'">Destinatarios del documento</td>
                    <td v-else-if="variable == '#copias'">Copias del documento</td>
                    <td v-else-if="variable == '#contenido'">Contenido del documento</td>
                    <td v-else-if="variable == '#anexos'">Anexos del documento</td>
                    <td v-else-if="variable == '#tipo_documento'">Tipo de documento</td>
                    <td v-else-if="variable == '#elaborado'">Persona que elaboró el documento</td>
                    <td v-else-if="variable == '#revisado'">Persona que revisó el documento</td>
                    <td v-else-if="variable == '#aprobado'">Persona que aprobó el documento</td>
                    <td v-else-if="variable == '#proyecto'">Persona que proyectó el documento</td>
                    <td v-else-if="variable == '#respuesta_correspondencia'">Correspondencia a la que hace respuesta</td>
                    <td v-else-if="variable == '#codigo_dependencia'">Código de la dependencia</td>
                    <td v-else-if="variable == '#fecha'">Fecha de publicación del documento</td>
                    <td v-else-if="variable == '#codigo_validacion'">Código de validación del documento</td>
                    <td v-else></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>