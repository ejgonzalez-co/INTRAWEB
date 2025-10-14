<div class="form-group row m-b-15">
    <label for="" class="col-form-label col-md-2 required">Tipo de conocimiento:</label>
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.knowledge_type" required>
            <option value="Infraestuctura">Infraestuctura</option>
            <option value="Suministros de consumo">Suministros de consumo</option>
            <option value="Suministros devolutivo - Asignación">Suministros devolutivo / Asignación</option>
        </select>
        <small>Seleccione el tipo de conocimiento.</small>
    </div>
</div>

<!-- Subject Field -->
<div class="form-group row m-b-15">
    {!! Form::label('subject_knowledge', trans('Subject').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-9">
        {!! Form::text('subject_knowledge', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.subject }", 'v-model' => 'dataForm.subject_knowledge', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Subject')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.subject">
            <p class="m-b-0" v-for="error in dataErrors.subject">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

{{-- <div class="form-group row m-b-15">
    <label for="" class="col-form-label col-md-2 required">Tipo de requerimiento:</label>
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.status" required>
            <option value="Infraestuctura">Infraestuctura</option>
            <option value="Suministros de consumo">Suministros de consumo</option>
            <option value="Suministros devolutivo - Asignación">Suministros devolutivo / Asignación</option>
        </select>
        <small>Seleccione el estado.</small>
    </div>
</div> --}}

<div class="form-group row m-b-15">
    {!! Form::label('documents', 'Archivos:', ['class' => 'col-form-label col-md-2']) !!}
    <div class="col-md-9">
        <input-file :value="dataForm" name-field="url_attacheds" :max-files="10"
            :max-filesize="5"
            file-path="public/supplies-adequacies/knowledge_bases/documents_{{ date('Y') }}"
            message="Arrastre o seleccione los archivos"
            help-text="Agregue aquí máximo 10 archivos a esta solicitud. Peso máximo es de 5 MB."
            :is-update="isUpdate" ruta-delete-update="correspondence/internals-delete-file"
            :id-file-delete="dataForm.id">
        </input-file>
    </div>
</div>