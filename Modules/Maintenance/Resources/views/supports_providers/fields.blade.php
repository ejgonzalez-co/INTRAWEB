<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>Ingrese el nombre del documento</small>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>Ingrese la descripción del documento</small>
    </div>
</div>

<!-- Url Document Field -->
<!-- <div class="form-group row m-b-15">
    {!! Form::label('url_document', trans('Documento').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::file('url_document', ['accept' => '', '@change' => 'inputFile($event, "url_document")', 'required' => false]) !!}
    </div>
</div> -->

<div class="form-group row m-b-15">
   {!! Form::label('affair', trans('Adjunto').':', ['class' => 'col-form-label col-md-3']) !!}
   <div class="col-md-9">
      <input-file
         :value="dataForm"
         name-field="url_document"
         :max-files="10"
         :max-filesize="5"
         file-path="public/maintenance/supports_providers"
         :is-update="isUpdate"
         message="Arrastre o seleccione los archivos"
         help-text="Lista de archivos adjuntos."
      >
      </input-file>
   </div>
</div>