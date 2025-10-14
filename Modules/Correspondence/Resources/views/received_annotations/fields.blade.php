<!-- Annotation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('annotation', trans('Annotation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('annotation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annotation }", 'v-model' => 'dataForm.annotation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Annotation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.annotation">
            <p class="m-b-0" v-for="error in dataErrors.annotation">@{{ error }}</p>
        </div>
    </div>
</div>

<div class="col-md-12">
    <!--  Other officials Field destination-->
    <div class="form-group row m-b-15">
        {!! Form::label('url', 'Lista de archivos adjuntos:', ['class' => 'col-form-label col-md-3']) !!}
        <div class="col-md-9">
            <input-file :file-name-real="true":value="dataForm" name-field="attached" :max-files="30"
            :max-filesize="11" file-path="public/container/anotations/external_received{{ date('Y') }}"
                message="Arrastre o seleccione los archivos" help-text="Lista de archivos adjuntos."
                :is-update="isUpdate" :key="keyRefresh">
            </input-file>

        </div>
    </div>
</div>
