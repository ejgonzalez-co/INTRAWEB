
<!-- panel:informacion del ciudadano -->
<div class="panel w-100" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información del ciudadano</strong></h4>
    </div>
    <div class="panel-heading ui-sortable-handle">
        <h6>Señor ciudadano, la siguiente información le servirá para que resuelva la encuesta correctamente.</h6><br>
    </div>

    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- number_account_event Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Número de cuenta para consultas y pagos'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.number_account }}</label>
                </div>
            </div>

            <!-- name_event Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Name'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.name }}</label>
                </div>
            </div>

            <!-- gender_event Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Gender'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.gender }}</label>
                </div>
            </div>

            <!-- email_event Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Email'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.email }}</label>
                </div>
            </div>

            <!-- direction_state_event Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Direction State'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.direction_state }}</label>
                </div>
            </div>

            <!-- phone_event Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Phone'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.phone }}</label>
                </div>
            </div>

            <!-- suscriber_quality_event Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Suscriber Quality'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.suscriber_quality }}</label>
                </div>
            </div>
  
        </div>
    <!-- end panel-body -->
    </div>
</div>

<!-- panel:servicio prestado -->
<div class="panel w-100" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>En cuanto al servicio prestado</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <h6 style="margin-bottom: 30px">¿Empresas públicas de Armenia ESP, le presta el servicio de?:</h6>
        <div class="row">

            <!-- Aqueduct Field -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Aqueduct'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.aqueduct }}</label>
                </div>
            </div>

            <!-- Sewerage Field -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Sewerage'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.sewerage }}</label>
                </div>    
            </div>

            <!-- Cleanliness Field -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Cleanliness'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.cleanliness }}</label>
                </div>    
            </div>

        </div>
    </div>
</div>


<!-- panel:calificacion en cuanto al servicio -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Servicio acueducto y alcantarillado</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <!-- Aqueduct Benefit Service Field -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-5">@lang('¿Cómo califica la prestación del servicio de acueducto y alcantarillado?'):</label>
                    <label class="col-form-label col-md-7">@{{ dataShow.aqueduct_benefit_service_name }}</label>
                </div>
            </div>

            <!-- Aqueduct Continuity Service Field -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-5">@lang('¿Cómo considera usted la continuidad en la prestación del servicio de acueducto, medio este en la cantidad de horas?'):</label>
                    <label class="col-form-label col-md-7">@{{ dataShow.aqueduct_continuity_service_name }}</label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- panel:calificacion en cuanto al servicio -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Servicio de aseo</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">

            <!-- Aqueduct Benefit Service Field -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-5">@lang('¿Cómo califica usted la calidad del servicio público de aseo domiciliario (vías, separadores, parques, calles, otros) en su lugar de residencia?'):</label>
                    <label class="col-form-label col-md-7">@{{ dataShow.chance_name }}</label>
                </div>
            </div>

            <!-- Aqueduct Continuity Service Field -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-5">@lang('¿¿Cómo califica usted el servicio de recoleccion de residuos sólidos de acuerdo con los horarios y los dias establecidos?'):</label>
                    <label class="col-form-label col-md-7">@{{ dataShow.reports_effectiveness_name }}</label>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- panel:calificacion en cuanto a reportes -->
<div class="panel w-100" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Reporte de daños</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <h6 style="margin-bottom:30px">En caso de haber reportado daños evalúe:</h6>

        <div class="row">
            
            <!-- Reporte de daños -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-5">¡Ha reportado daños?:</label>
                    <label class="col-form-label col-md-7">@{{ dataShow.damage_report }}</label>
                </div>
            </div>

            <!-- Chance Field -->
            <div class="col-md-12" v-if="dataShow.damage_report=='Si'">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-5">@lang('Chance'):</label>
                    <label class="col-form-label col-md-7">@{{ dataShow.arrived_on_time_name }}</label>
                </div>
            </div>

            <!-- Reports Effectiveness Field -->
            <div class="col-md-12" v-if="dataShow.damage_report=='Si'">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-5">@lang('¿La efectividad fue?(Arreglaron el daño)'):</label>
                    <label class="col-form-label col-md-7">@{{ dataShow.damage_well_fixed_name }}</label>
                </div>
            </div>

        </div>
    </div>
</div>