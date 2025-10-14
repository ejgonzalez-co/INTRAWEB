
<div class="panel">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Clasificación</strong></h4>
    </div>
    <!-- end panel-heading -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-9">
                <div class="form-group row m-b-15">
                    {!! Form::label('classification', trans('Tipo').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::select('type',['Serie'=>'Serie','Subserie' => 'Subserie'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.soport }", 'v-model' => 'dataForm.type', 'required' => true]) !!}
                        <small>@lang('Select the') @{{ 'el tipo de clasificación.' | lowercase }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" v-if="dataForm.type ? dataForm.type : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información</strong></h4>
    </div>
    <!-- end panel-heading -->
    <div class="col-md-9" v-if="dataForm.type === 'Serie'">
        <div class="form-group row m-b-15">
            {!! Form::label('no_serie', trans('Número de Serie').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::text('no_serie', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_serie }", 'v-model' => 'dataForm.no_serie', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ 'el número de la Serie documental.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.no_serie">
                    <p class="m-b-0" v-for="error in dataErrors.no_serie">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9" v-if="dataForm.type === 'Serie'">
        <div class="form-group row m-b-15">
            {!! Form::label('name_serie', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::text('name_serie', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_serie }", 'v-model' => 'dataForm.name_serie', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ 'el nombre de la Serie.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.name_serie">
                    <p class="m-b-0" v-for="error in dataErrors.name_serie">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9"  v-if="dataForm.type == 'Subserie'">
        <div class="form-group row m-b-15">
            {!! Form::label('name_serie', trans('Serie documental').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                <select-check
                css-class="form-control"
                name-field="id_serie"
                :reduce-label="['no_serie','name_serie']"
                reduce-key="id"
                name-resource="get-series"
                :value="dataForm"
                :is-required="true"
                :enable-search="true">
                </select-check>
                <small>@lang('Enter the') @{{ 'el nombre de la Serie.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.name_serie">
                    <p class="m-b-0" v-for="error in dataErrors.name_serie">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9" v-if="dataForm.type === 'Subserie'">
        <div class="form-group row m-b-15">
            {!! Form::label('no_subserie', trans('Número de Subserie').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::text('no_subserie', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_subserie }", 'v-model' => 'dataForm.no_subserie', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ 'el número de la Subserie documental' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.no_subserie">
                    <p class="m-b-0" v-for="error in dataErrors.no_subserie">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9" v-if="dataForm.type === 'Subserie'">
        <div class="form-group row m-b-15">
            {!! Form::label('name_subserie', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::text('name_subserie', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_subserie }", 'v-model' => 'dataForm.name_subserie', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ 'nombre de Subserie.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.name_subserie">
                    <p class="m-b-0" v-for="error in dataErrors.name_subserie">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('time_gestion_archives', trans('Cantidad de años de en archivos de gestión').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::text('time_gestion_archives', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.time_gestion_archives }", 'v-model' => 'dataForm.time_gestion_archives', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ 'la cantidad de años que puede durar el documento en el archivo de gestión.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.time_gestion_archives">
                    <p class="m-b-0" v-for="error in dataErrors.time_gestion_archives">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('time_central_file', trans('Cantidad de años en archivo central').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::text('time_central_file', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.time_central_file }", 'v-model' => 'dataForm.time_central_file', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ 'la cantidad de años que puede durar el documento en el archivo central.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.time_central_file">
                    <p class="m-b-0" v-for="error in dataErrors.time_central_file">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('soport', trans('Soporte').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::select('soport',['Físico'=>'Físico', 'Electrónico' => 'Electrónico', 'Físico y Electrónico' => 'Físico y Electrónico'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.soport }", 'v-model' => 'dataForm.soport', 'required' => true]) !!}
                <small>@lang('Select the') @{{ 'el tipo de soporte que debe tener el documento.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.soport">
                    <p class="m-b-0" v-for="error in dataErrors.soport">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('confidentiality', trans('Confidencialidad').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div  class="col-md-9">
                {!! Form::select('confidentiality',['Pública' => 'Pública', 'Clasificada' => 'Clasificada', 'Reservada' => 'Reservada'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.confidentiality }", 'v-model' => 'dataForm.confidentiality', 'required' => true]) !!}
                <small>@lang('Select the') @{{ 'según la importancia el tipo de confidencialidad que debe tener el documento.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.confidentiality">
                    <p class="m-b-0" v-for="error in dataErrors.confidentiality">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- enable_expediente Field -->
    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('enable_expediente', trans('¿Habilitada para expedientes?').':', ['class' => 'col-form-label col-md-3']) !!}
            <!-- switcher -->
            <div class="switcher col-md-9 m-t-5">
                <input type="checkbox" name="enable_expediente" id="enable_expediente" v-model="dataForm.enable_expediente" :disabled="dataForm.type == 'Subserie' && !dataForm.serie?.enable_expediente">
                <label for="enable_expediente" :title="dataForm.type == 'Subserie' && !dataForm.serie?.enable_expediente && 'Debe habilitar primero la serie para expedientes'":style="dataForm.type == 'Subserie' && !dataForm.serie?.enable_expediente && 'cursor: not-allowed;'"></label>
                <small>Indique si la serie o subserie puede generar expedientes.</small>
            </div>
        </div>
    </div>
    <br>
</div>

<div class="panel" v-if="dataForm.type ? dataForm.type : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Tipos documentales (Opcional)</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

                {{-- <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                    name-field-autocomplete="types_autocomplete" name-field="types"
                    name-resource="get-type-documentaries-all-request"
                    name-options-list="types_list" :name-labels-display="['name']"  name-key="id"
                    help="Ingrese el nombre del tipo documental que desea relacionar al GSSD, seleccione una opcion del listado."
                    :key="keyRefresh">
                </add-list-autocomplete> --}}
            <dynamic-list label-button-add="Agregar tipo documental" icon-button-add="fas fa-user-plus" :data-list.sync="dataForm.types_list"
                :data-list-options="[{ label: 'Tipo documental', name: 'id_type_documentaries', isShow: true, nameObjectKey: ['tipo_documental', 'name'], refList: 'tipo_documental' }]"
                class-container="col-md-12" class-table="table table-bordered" campo-validar-existencia="id_type_documentaries" url-eliminar-registro="">
                <template #fields="scope">

                    <div class="form-group row m-b-15">
                        {!! Form::label('name', 'Tipo documental: ', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <select-check
                                css-class="form-control"
                                name-field="id_type_documentaries"
                                reduce-label="name"
                                reduce-key="id"
                                name-resource="get-type-documentaries-all-request"
                                :value="scope.dataForm"
                                :is-required="true"
                                :enable-search="true"
                                name-field-object="tipo_documental"
                                ref-select-check="tipo_documental"
                                >
                            </select-check>

                            {{-- {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => false]) !!} --}}
                            <small>Seleccione el nombre del tipo documental que desea relacionar al GSSD, agréguelo como una opcion del listado.</small>

                            <div class="invalid-feedback" v-if="dataErrors.name">
                                <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                            </div>
                        </div>

                    </div>
                </template>
            </dynamic-list>

            <div class="invalid-feedback" v-if="dataErrors.full_conversation">
                <p class="m-b-0" v-for="error in dataErrors.full_conversation">@{{ error }}</p>
            </div>
        </div>
    </div>
</div>

<div class="panel"  v-if="dataForm.type ? dataForm.type : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Disposición final</strong></h4>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('full_conversation', trans('Conservación total').':', ['class' => 'col-form-label col-md-3']) !!}
            <div  class="col-md-9">
                {!! Form::select('full_conversation',[true => 'Si', false => 'No'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.confidentiality }", 'v-model' => 'dataForm.full_conversation']) !!}
                <small>@lang('Select the') @{{ 'si el documento debe ser conservado como disposición final.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.full_conversation">
                    <p class="m-b-0" v-for="error in dataErrors.full_conversation">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('select', trans('Selección').':', ['class' => 'col-form-label col-md-3']) !!}
            <div  class="col-md-9">
                {!! Form::select('select',[true => 'Si', false => 'No'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.confidentiality }", 'v-model' => 'dataForm.select']) !!}
                <small>@lang('Select the') @{{ 'si el docuemnto debe ser seleccionado como disposición final.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.select">
                    <p class="m-b-0" v-for="error in dataErrors.select">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('delete', trans('Eliminación').':', ['class' => 'col-form-label col-md-3']) !!}
            <div  class="col-md-9">
                {!! Form::select('delete',[true => 'Si', false => 'No'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.confidentiality }", 'v-model' => 'dataForm.delete']) !!}
                <small>@lang('Select the') @{{ 'si el docuemnto debe ser eliminado como disposición final.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.delete">
                    <p class="m-b-0" v-for="error in dataErrors.delete">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('medium_tecnology', trans('Medios Tecnológicos').':', ['class' => 'col-form-label col-md-3']) !!}
            <div  class="col-md-9">
                {!! Form::select('medium_tecnology',[true => 'Si', false => 'No'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.confidentiality }", 'v-model' => 'dataForm.medium_tecnology']) !!}
                <small>@lang('Select the') @{{ 'si el documento como disposicion final debe quedar en medio tecnológicos.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.medium_tecnology">
                    <p class="m-b-0" v-for="error in dataErrors.medium_tecnology">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('not_transferable_central', trans('No Transferible al Archivo Central').':', ['class' => 'col-form-label col-md-3']) !!}
            <div  class="col-md-9">
                {!! Form::select('not_transferable_central',[true => 'Si', false => 'No'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.confidentiality }", 'v-model' => 'dataForm.not_transferable_central']) !!}
                <small>@lang('Select the') @{{ 'si el documento no es transferible al archivo central como disposición final.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.not_transferable_central">
                    <p class="m-b-0" v-for="error in dataErrors.not_transferable_central">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group row m-b-15">
            {!! Form::label('description_final', trans('Procedimiento').':', ['class' => 'col-form-label col-md-3']) !!}
            <div  class="col-md-9">
                {!! Form::textarea('description_final', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description_final }", 'v-model' => 'dataForm.description_final' ]) !!}
                <small>@lang('Enter the') @{{ 'una pequeña descripción al procedimiento del documento para su disposición final.' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.description_final">
                    <p class="m-b-0" v-for="error in dataErrors.description_final">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <br>

</div>

