{{-- <!-- Ee Documentos Expediente Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ee_documentos_expediente_id', trans('Ee Documentos Expediente Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('ee_documentos_expediente_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ee_documentos_expediente_id }", 'v-model' => 'dataForm.ee_documentos_expediente_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ee Documentos Expediente Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ee_documentos_expediente_id">
            <p class="m-b-0" v-for="error in dataErrors.ee_documentos_expediente_id">{{ error }}</p>
        </div>
    </div>
</div>

<!-- Users Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('Users Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('users_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_id }", 'v-model' => 'dataForm.users_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Users Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.users_id">
            <p class="m-b-0" v-for="error in dataErrors.users_id">{{ error }}</p>
        </div>
    </div>
</div>

<!-- User Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('user_name', trans('User Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('user_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.user_name }", 'v-model' => 'dataForm.user_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('User Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.user_name">
            <p class="m-b-0" v-for="error in dataErrors.user_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Nombre Expediente Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre_expediente', trans('Nombre Expediente').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre_expediente', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_expediente }", 'v-model' => 'dataForm.nombre_expediente', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre Expediente')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre_expediente">
            <p class="m-b-0" v-for="error in dataErrors.nombre_expediente">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Nombre Tipo Documental Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre_tipo_documental', trans('Nombre Tipo Documental').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre_tipo_documental', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_tipo_documental }", 'v-model' => 'dataForm.nombre_tipo_documental', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre Tipo Documental')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre_tipo_documental">
            <p class="m-b-0" v-for="error in dataErrors.nombre_tipo_documental">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Origen Creacion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('origen_creacion', trans('Origen Creacion').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('origen_creacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.origen_creacion }", 'v-model' => 'dataForm.origen_creacion', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Origen Creacion')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.origen_creacion">
            <p class="m-b-0" v-for="error in dataErrors.origen_creacion">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Nombre Documento Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre_documento', trans('Nombre Documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre_documento', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_documento }", 'v-model' => 'dataForm.nombre_documento', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre Documento')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre_documento">
            <p class="m-b-0" v-for="error in dataErrors.nombre_documento">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Fecha Documento Field -->
<div class="form-group row m-b-15">
   {!! Form::label('fecha_documento', trans('Fecha Documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="fecha_documento"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Fecha Documento')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.fecha_documento">
            <p class="m-b-0" v-for="error in dataErrors.fecha_documento">{{ error }}</p>
        </div>
    </div>
</div>


<!-- Descripcion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('descripcion', trans('Descripcion').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Descripcion')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.descripcion">
            <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Pagina Inicio Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pagina_inicio', trans('Pagina Inicio').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pagina_inicio', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pagina_inicio }", 'v-model' => 'dataForm.pagina_inicio', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pagina Inicio')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pagina_inicio">
            <p class="m-b-0" v-for="error in dataErrors.pagina_inicio">{{ error }}</p>
        </div>
    </div>
</div>

<!-- Pagina Fin Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pagina_fin', trans('Pagina Fin').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pagina_fin', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pagina_fin }", 'v-model' => 'dataForm.pagina_fin', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pagina Fin')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pagina_fin">
            <p class="m-b-0" v-for="error in dataErrors.pagina_fin">{{ error }}</p>
        </div>
    </div>
</div>

<!-- Adjunto Field -->
<div class="form-group row m-b-15">
    {!! Form::label('adjunto', trans('Adjunto').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('adjunto', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.adjunto }", 'v-model' => 'dataForm.adjunto', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Adjunto')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.adjunto">
            <p class="m-b-0" v-for="error in dataErrors.adjunto">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Modulo Intraweb Field -->
<div class="form-group row m-b-15">
    {!! Form::label('modulo_intraweb', trans('Modulo Intraweb').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('modulo_intraweb', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.modulo_intraweb }", 'v-model' => 'dataForm.modulo_intraweb', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Modulo Intraweb')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.modulo_intraweb">
            <p class="m-b-0" v-for="error in dataErrors.modulo_intraweb">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Consecutivo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('consecutivo', trans('Consecutivo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('consecutivo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consecutivo }", 'v-model' => 'dataForm.consecutivo', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Consecutivo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.consecutivo">
            <p class="m-b-0" v-for="error in dataErrors.consecutivo">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Accion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('accion', trans('Accion').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('accion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.accion }", 'v-model' => 'dataForm.accion', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Accion')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.accion">
            <p class="m-b-0" v-for="error in dataErrors.accion">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Justificacion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('justificacion', trans('Justificacion').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('justificacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.justificacion }", 'v-model' => 'dataForm.justificacion', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Justificacion')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.justificacion">
            <p class="m-b-0" v-for="error in dataErrors.justificacion">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Vigencia Field -->
<div class="form-group row m-b-15">
    {!! Form::label('vigencia', trans('Vigencia').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('vigencia', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.vigencia }", 'v-model' => 'dataForm.vigencia', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Vigencia')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.vigencia">
            <p class="m-b-0" v-for="error in dataErrors.vigencia">{{ error }}</p>
        </div>
    </div>
</div>

<!-- Modulo Consecutivo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('modulo_consecutivo', trans('Modulo Consecutivo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('modulo_consecutivo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.modulo_consecutivo }", 'v-model' => 'dataForm.modulo_consecutivo', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Modulo Consecutivo')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.modulo_consecutivo">
            <p class="m-b-0" v-for="error in dataErrors.modulo_consecutivo">@{{ error }}</p>
        </div>
    </div>
</div> --}}