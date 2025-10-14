
<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('content', trans('Content').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('content', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.content }", 'v-model' => 'dataForm.content', 'required' => true]) !!}
        <small>@lang('Enter the') una @{{ `@lang('Content')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.content">
            <p class="m-b-0" v-for="error in dataErrors.content">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Url Field -->
<div class="form-group row m-b-15">
    {!! Form::label('url', trans('Attached').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <input-file :file-name-real="true":value="dataForm" name-field="url_attachments" :max-files="5" :max-filesize="11" file-path="public/container/help_table/tic_asset_document_{{ date('Y') }}"
        message="Arrastre o seleccione los archivos" help-text="Lista de documentos\. El tamaño máximo permitido es de 10 MB\."
        :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id" :mostrar-eliminar-adjunto="dataForm.users_id == {{ Auth::user()->id }}">
        </input-file>
        <div class="invalid-feedback" v-if="dataErrors.url">
            <p class="m-b-0" v-for="error in dataErrors.url">@{{ error }}</p>
        </div>
    </div>

</div>
