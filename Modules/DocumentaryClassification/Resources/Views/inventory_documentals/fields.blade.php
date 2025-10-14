<!-- Id Dependencias Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('id_dependencias', trans('Id Dependencias').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_dependencias', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_dependencias }", 'v-model' => 'dataForm.id_dependencias', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Dependencias')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_dependencias">
            <p class="m-b-0" v-for="error in dataErrors.id_dependencias">@{{ error }}</p>
        </div>
    </div>
</div> --}}

<!-- Id Series Subseries Field -->
{{-- <div class="form-group row m-b-15">
    {!! Form::label('id_series_subseries', trans('Id Series Subseries').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('id_series_subseries', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.id_series_subseries }", 'v-model' => 'dataForm.id_series_subseries', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Id Series Subseries')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.id_series_subseries">
            <p class="m-b-0" v-for="error in dataErrors.id_series_subseries">@{{ error }}</p>
        </div>
    </div>
</div> --}}


{{-- select --}}

<div class="panel">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Clasificación</strong></h4>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('serie_subserie', trans('Oficina productora').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check
                    css-class="form-control"
                    name-field="id_dependencias"
                    :reduce-label="['codigo_oficina_productora','nombre']"
                    reduce-key="id"
                    name-resource="get-dependencias-inventory"
                    :value="dataForm"
                    :is-required="true"
                >
                </select-check>
            <small>@lang('Select the') @{{ 'la oficina productora a la que pertenece el inventario' | lowercase }}</small>

            </div>
        </div>
        <br>
    </div>

    <div class="col-md-12" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
        <div class="form-group row m-b-15">
            {!! Form::label('serie_subserie', trans('Serie o subserie documental').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check
                css-class="form-control"
                name-field="id_series_subseries"
                :reduce-label="['type','no_serieosubserie','name']"
                :name-resource="'get-inventory-documentals-serie-subserie/'+ dataForm.id_dependencias"
                reduce-key="id_series_subseries"
                :value="dataForm"
                :is-required="true"
                :key="dataForm.id_dependencias"
                {{-- :enable-search='true' --}}
                >
                </select-check>



                <small>@lang('Select the') @{{ 'serie o subserie que desea agregar al inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.description_expedient">
                    <p class="m-b-0" v-for="error in dataErrors.description_expedient">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
        <div class="form-group row m-b-15">
            {!! Form::label('description_expedient', trans('Descripción del expediente').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('description_expedient', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description_expedient }", 'v-model' => 'dataForm.description_expedient', 'required' => true]) !!}
                <small>@lang('indicate the') @{{ 'la descripción del expediente' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.description_expedient">
                    <p class="m-b-0" v-for="error in dataErrors.description_expedient">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
        <div class="form-group row m-b-15">
            {!! Form::label('folios', trans('Folios').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('folios', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.folios }", 'v-model' => 'dataForm.folios', 'required' => true]) !!}
                <small>@lang('indicate the') @{{ 'la cantidad de folios de cada carpeta' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.folios">
                    <p class="m-b-0" v-for="error in dataErrors.folios">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
        <div class="form-group row m-b-15">
            {!! Form::label('clasification', trans('Clasificación').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::select('Clasification',['Público'=>'Público','Clasificado'=>'Clasificado','Reservado'=>'Reservado'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.clasification }", 'v-model' => 'dataForm.clasification', 'required' => true]) !!}
                <small>@lang('indicate the') @{{ 'la clasificación del inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.clasification">
                    <p class="m-b-0" v-for="error in dataErrors.clasification">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
        <div class="form-group row m-b-15">
            {!! Form::label('consultation_frequency', trans('Frecuencia de consulta').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::select('consultation_frequency',['Alta'=>'Alta', 'Media'=>'Media', 'Baja' => 'Baja'],null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consultation_frequency }", 'v-model' => 'dataForm.consultation_frequency', 'required' => true]) !!}
                <small>@lang('Select the') @{{ 'el tipo de frecuencia de consulta del inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.consultation_frequency">
                    <p class="m-b-0" v-for="error in dataErrors.consultation_frequency">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
        <div class="form-group row m-b-15">
            {!! Form::label('soport', trans('Soporte').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::select('soport',['Físico' => 'Físico', 'Electrónico' => 'Electrónico', 'Físico y Electrónico' => 'Físico y Electrónico'] ,null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.soport }", 'v-model' => 'dataForm.soport', 'required' => true]) !!}
                <small>@lang('Select the') @{{ 'el tipo de soporte del inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.soport">
                    <p class="m-b-0" v-for="error in dataErrors.soport">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <br>

</div>

<div class="panel" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Signatura topográfica</strong></h4>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('shelving', trans('Estantería').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::text('shelving', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.shelving }", 'v-model' => 'dataForm.shelving']) !!}
                <small>@lang('indicate the') @{{ 'estante en el cual se ubica del inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.shelving">
                    <p class="m-b-0" v-for="error in dataErrors.shelving">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('tray', trans('Bandeja').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::text('tray', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tray }", 'v-model' => 'dataForm.tray']) !!}
                <small>@lang('indicate the') @{{ 'ubicación en el estante del inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.tray">
                    <p class="m-b-0" v-for="error in dataErrors.tray">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('box', trans('Caja').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::text('box', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.box }", 'v-model' => 'dataForm.box']) !!}
                <small>@lang('indicate the') @{{ 'unidad de conservación documental donde se encuentra su inventario'|lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.box">
                    <p class="m-b-0" v-for="error in dataErrors.box">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('file', trans('Carpeta').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::text('file', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.file }", 'v-model' => 'dataForm.file']) !!}
                <small>@lang('indicate the') @{{ 'carpeta que hace parte de su inventario documental' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.file">
                    <p class="m-b-0" v-for="error in dataErrors.file">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('book', trans('Libro').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::text('book', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.book }", 'v-model' => 'dataForm.book']) !!}
                <small>@lang('indicate the') @{{ 'libro que hace parte de su inventario documental' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.book">
                    <p class="m-b-0" v-for="error in dataErrors.book">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <br>

</div>


<div class="panel" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">

    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Fechas extremas</strong></h4>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('date_initial', trans('Fecha inicial').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::date('date_initial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date_initial }", 'v-model' => 'dataForm.date_initial']) !!}
                <small>@lang('indicate the') @{{ 'fecha inicial de su inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.date_initial">
                    <p class="m-b-0" v-for="error in dataErrors.date_initial">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('date_finish', trans('Fecha final').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::date('date_finish', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date_finish }", 'v-model' => 'dataForm.date_finish']) !!}
                <small>@lang('indicate the') @{{ 'fecha inicial de su inventario' | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.date_finish">
                    <p class="m-b-0" v-for="error in dataErrors.date_finish">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <br>

</div>


<div class="panel" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Rango de numeración</strong></h4>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('range_initial', trans('Rango inicial').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::text('range_initial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.range_initial }", 'v-model' => 'dataForm.range_initial']) !!}
                <div class="invalid-feedback" v-if="dataErrors.range_initial">
                    <p class="m-b-0" v-for="error in dataErrors.range_initial">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('range_finish', trans('Rango final').':', ['class' => 'col-form-label col-md-3 ']) !!}
            <div class="col-md-9">
                {!! Form::text('range_finish', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.range_finish }", 'v-model' => 'dataForm.range_finish']) !!}
                <div class="invalid-feedback" v-if="dataErrors.range_finish">
                    <p class="m-b-0" v-for="error in dataErrors.range_finish">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <br>

</div>




<div class="panel" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Documentos digitales</strong></h4>
    </div>
    <!-- end panel-heading -->

            <div class="col-md-12">
                <!--  Other officials Field destination-->
                <div class="form-group row m-b-15">
                    {!! Form::label('attachment', 'Lista de archivos digitales:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <input-file :file-name-real="true":value="dataForm" name-field="attachment" :max-files="30"
                        :max-filesize="11" file-path="public/container/gestiondocumental_{{ date('Y') }}"
                            message="Arrastre o seleccióne los archivos" help-text="Lista de archivos digitales."
                            :is-update="isUpdate" :key="keyRefresh" :file-name-real="true" ruta-delete-update="correspondence/gestiondocumental-delete-file" :id-file-delete="dataForm.id">
                        </input-file>

                    </div>
                </div>
            </div>


</div>

<div class="panel" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Criterios de búsqueda</strong></h4>
    </div>

    <criterios-busqueda-save v-if="dataForm.criterios_busqueda_value" :id-serie="dataForm.id_series_subseries" :data-edit="dataForm.criterios_busqueda_value" edit="Si" filter="No"></criterios-busqueda-save>

    <criterios-busqueda-save v-else :id-serie="dataForm.id_series_subseries" filter="No"></criterios-busqueda-save>

    <!-- end panel-heading -->




</div>


{{-- <div class="panel" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Criterios de búsqueda</strong></h4>
    </div>

    <div class="col-md-12">
        <div class="col-md-15">
            <p><strong>¡Importante!: </strong>Los criterios de búsqueda son información puntual para buscar documentos, estos criterios son configurados y relaciónados a cada serie documental.</p>
            <p>Debe selecciónar una serie o subserie documental para obtener los criterios de búsqueda</p>
        </div>
    </div>

    <br>

</div> --}}

<div class="panel" v-if="dataForm.id_dependencias ? dataForm.id_dependencias : null">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Observaciónes</strong></h4>
    </div>

    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('observation', trans('Observaciónes').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation']) !!}
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>

    <br>
</div>

