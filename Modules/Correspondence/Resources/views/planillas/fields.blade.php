<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Información inicial de la planilla</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Tipo Correspondencia Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('tipo_correspondencia', trans('Tipo de correspondencia').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select class="form-control" name="tipo_correspondencia" id="tipo_correspondencia" v-model="dataForm.tipo_correspondencia" required>
                    <option value="Externa Recibida">Externa Recibida</option>
                    <option value="Externa Enviada">Externa Enviada</option>
                    <option value="Interna">Interna</option>
                </select>
                <small>@lang('Select the') el @{{ `@lang('Tipo de Correspondencia')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.tipo_correspondencia">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_correspondencia">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Rango Planilla Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('rango_planilla', trans('Rango de la planilla').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-4">
                {!! Form::label('rango_planilla_desde', trans('Desde').':', ['class' => 'col-form-label required']) !!}
                <input class="form-control" type="datetime-local" v-model="dataForm.rango_planilla_desde">
                <small>@lang('Select the') la fecha de inicio</small>
                <div class="invalid-feedback" v-if="dataErrors.rango_planilla">
                    <p class="m-b-0" v-for="error in dataErrors.rango_planilla">@{{ error }}</p>
                </div>
            </div>

            <div class="col-md-4">
                {!! Form::label('rango_planilla_hasta', trans('Hasta').':', ['class' => 'col-form-label required']) !!}
                <input class="form-control" type="datetime-local" v-model="dataForm.rango_planilla_hasta">
                <small>@lang('Select the') la fecha fin</small>
                <div class="invalid-feedback" v-if="dataErrors.rango_planilla">
                    <p class="m-b-0" v-for="error in dataErrors.rango_planilla">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Rango Planilla Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('ruta', trans('Ruta') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="correspondence_planilla_ruta_id" reduce-label="nombre_ruta" name-resource="get-planilla-rutas" :value="dataForm" :is-required="true" ></select-check>
                <small>@lang('Select the') el @{{ `@lang('Rango de la Planilla')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.rango_planilla">
                    <p class="m-b-0" v-for="error in dataErrors.rango_planilla">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
