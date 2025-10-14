<!-- Panel Detalles de la serie -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Detalles de la serie</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 row">
                <!-- Type Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Tipo'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.type }}.</dd>
            </div>

            <div class="col-md-12 row">
                <!-- No Serie Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('No. Serie'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.no_serie }}.</dd>
            </div>

            <div class="col-md-12 row">
                <!-- Name Serie Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Nombre de la serie'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.name_serie }}.</dd>
            </div>

            <div class="col-md-12 row" v-if="dataShow.type === 'Subserie'">
                <!-- No Subserie Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('No. Subserie'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.no_subserie }}.</dd>
            </div>

            <div class="col-md-12 row" v-if="dataShow.type === 'Subserie'">
                <!-- Name Subserie Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Nombre de la Subserie'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.name_subserie }}.</dd>
            </div>

            <div class="col-md-12 row">
                <!-- Time Gestion Archives Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Tiempo en archivo de gestión'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.time_gestion_archives }}.</dd>
            </div>

            <div class="col-md-12 row">
                <!-- Time Central File Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Tiempo en archivo central'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.time_central_file }}.</dd>
            </div>

            <div class="col-md-12 row">
                <!-- Soport Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Soporte'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.soport }}.</dd>
            </div>

            <div class="col-md-12 row">
                <!-- Confidentiality Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Confidencialilidad'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.confidentiality }}.</dd>
            </div>

            <!-- enable_expediente Field -->
            <div class="col-md-12 row">
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('¿Habilitada para expedientes?'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.enable_expediente ? '@lang('yes')' : '@lang('no')' }}.</dd>
            </div>
        </div>
    </div>
</div>

<!-- Panel Detalles de la serie -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Disposición final</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 row">
                <!-- Full Conversation Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Disposición final'):</dt>
                <dd v-if="dataShow.full_conversation == 1" class="col-sm-8 col-md-8 col-lg-8 ">Conservación total.</dd>
                <dd v-else-if="dataShow.Selección == 1" class="col-sm-8 col-md-8 col-lg-8 ">Selección.</dd>
                <dd v-else-if="dataShow.delete == 1" class="col-sm-8 col-md-8 col-lg-8 ">Eliminación.</dd>
                <dd v-else-if="dataShow.medium_tecnology == 1" class="col-sm-8 col-md-8 col-lg-8 ">Medios Tecnológicos.</dd>
                <dd v-else-if="dataShow.not_transferable_central == 1" class="col-sm-8 col-md-8 col-lg-8 ">No Transferible al Archivo Central.</dd>
            </div>

            <div class="col-md-12 row">
                <!-- Description Final Field -->
                <dt class="text-inverse col-sm-4 col-md-4 col-lg-4 ">@lang('Procedimiento'):</dt>
                <dd class="col-sm-8 col-md-8 col-lg-8 ">@{{ dataShow.description_final }}.</dd>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>
