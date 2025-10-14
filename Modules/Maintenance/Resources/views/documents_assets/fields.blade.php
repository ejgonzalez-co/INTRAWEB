<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>Ingrese el nombre del documento.</small>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description']) !!}
        <small>Ingrese la descripción del documento.</small>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('description','Imagenes del equipo:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="switcher col-md-6">
        <input type="checkbox" name="images_equipo" id="images_equipo"
            v-model="dataForm.images_equipo">
        <label for="images_equipo"></label>
        <small>Indique si estos adjuntos corresponden a las imagenes del equipo</small>
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
   {!! Form::label('affair', trans('Adjunto').':', ['class' => 'col-form-label col-md-3 required' ] ) !!}
   <div class="col-md-9">
      <input-file
         :value="dataForm"
         name-field="url_document"
         :max-files="10"
         :max-filesize="5"
         file-path="public/maintenance/documents_assets"
         :is-update="isUpdate"
         message="Arrastre o seleccione los archivos"
         help-text="Lista de archivos adjuntos."
      >
      </input-file>
   </div>
</div>