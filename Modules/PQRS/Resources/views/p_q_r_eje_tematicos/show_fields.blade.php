<div class="container-fluid bg-light min-vh-100 py-4">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-2"><strong>Información general</strong>
                    </h5>
                    <div class="row">
                        <dt>@lang('Código'):</dt>
                        <dd>@{{ dataShow.codigo }}.</dd>
                    </div>
                    <div class="row">
                        <!-- Nombre Field -->
                        <dt>@lang('Nombre'):</dt>
                        <dd>@{{ dataShow.nombre }}.</dd>
                    </div>
                    <div class="row">
                        <!-- Descripcion Field -->
                        <dt>@lang('Descripción'):</dt>
                        <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.descripcion }}.</dd>
                    </div>

                    <div class="row">
                        <!-- Tipo Plazo Field -->
                        <dt>@lang('Tipo de plazo'):</dt>
                        <dd>@{{ dataShow.tipo_plazo }}.</dd>

                        <!-- Estado Field -->
                        <dt>@lang('Estado'):</dt>
                        <dd>@{{ dataShow.estado }}.</dd>
                    </div>


                    <div class="row">
                        <!-- Plazo Field -->
                        <dt>@lang('Plazo'):</dt>
                        <dd>@{{ dataShow.plazo }}.</dd>

                        <!-- Plazo Unidad Field -->
                        <dt>@lang('U0nidad del plazo'):</dt>
                        <dd>@{{ dataShow.plazo_unidad }}.</dd>
                    </div>


                    <div class="row">
                        <!-- Temprana Field -->
                        <dt>@lang('Alerta temprana'):</dt>
                        <dd>@{{ dataShow.temprana }}.</dd>

                        <!-- Temprana Unidad Field -->
                        <dt>@lang('Unidad de la alerta temprana'):</dt>
                        <dd>@{{ dataShow.temprana_unidad }}.</dd>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<!-- Panel Información general -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información general</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Codigo Field -->

            <!-- Nombre Field -->

        </div>



    </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Dependencias asociadas al eje temático</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <dd class="col-12">
                <table class="table table-bordered m-b-0"
                    v-if="dataShow.ejetematico_has_dependencias? dataShow.ejetematico_has_dependencias.length > 0 : ''">
                    <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Dependencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(dependencia, key) in dataShow.ejetematico_has_dependencias" :key="key"
                            class="text-center">
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

<!-- Panel Historial -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Historial de cambios</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <table id="historial" class="table text-center mt-2" border="1">
            <thead>
                <tr class="font-weight-bold">
                    <td>Nombre</td>
                    <td>Descripción</td>
                    <td>Tipo de plazo</td>
                    <td>Plazo</td>
                    <td>Unidad del plazo</td>
                    <td>Alerta temprana</td>
                    <td>Usuario</td>
                    <td>Fecha de creación</td>
                    <td>Estado</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="historial in dataShow.pqr_eje_tematico_historial">
                    <td>@{{ historial.nombre }}</td>
                    <td>@{{ historial.descripcion }}</td>
                    <td>@{{ historial.tipo_plazo }}</td>
                    <td>@{{ historial.plazo }}</td>
                    <td>@{{ historial.plazo_unidad }}</td>
                    <td>@{{ historial.temprana }}</td>
                    <td>@{{ historial.users?.name }}</td>
                    <td>@{{ historial.created_at }}</td>
                    <td>@{{ historial.estado }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
