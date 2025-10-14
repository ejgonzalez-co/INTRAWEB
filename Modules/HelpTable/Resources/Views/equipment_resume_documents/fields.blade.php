<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Nombre del documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>Ingrese el nombre del documento</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>Ingrese la descripción del documento</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Url Field -->
<div class="form-group row m-b-15">
    {!! Form::label('url', trans('Adjuntar documento').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::file('url', ['@change' => 'inputFile($event, "url_new")', 'required' => true, ':class' => "'form-control'"]) !!}
        <div class="" v-if="dataForm.url">
            {!! Form::label('url', trans('Documento Actual').':', ['class' => 'col-form-label col-md-4 required']) !!}
            <a class="ml-2" :href="'{{ asset('storage') }}/' + dataForm.url" target="_blank">Ver adjunto</a>
        </div>
        <div class="invalid-feedback" v-if="dataErrors.url">
            <p class="m-b-0" v-for="error in dataErrors.url">@{{ error }}</p>
        </div>
    </div>
</div>
