
<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('observation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('url', trans('url').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <input-file
         :value="dataForm"
         name-field="url"
         :max-files="4"
         file-path="public/documents/documents_attachments_equipment"
         message="Arrastre o seleccione los archivos"
         help-text="Lista de archivos adjuntos."
         :is-update="isUpdate"
      >
      </input-file>
        <div class="invalid-feedback" v-if="dataErrors.url">
            <p class="m-b-0" v-for="error in dataErrors.url">@{{ error }}</p>
        </div>
    </div>
</div>