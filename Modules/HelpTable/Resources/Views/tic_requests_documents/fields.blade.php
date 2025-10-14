
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
    {!! Form::label('description', trans('Observación').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') una @{{ `@lang('Observación')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Url Field -->
<div class="form-group row m-b-15">
    {!! Form::label('url', trans('Attached').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::file('url', ['@change' => 'inputFile($event, "url")']) !!}
        <br>
        <label v-if="dataForm.url" style="margin-top: 5px">Documento Actual: <a class="col-3 text-truncate":href="'{{ asset('storage') }}/'+dataForm.url"target="_blank">Ver adjunto</a></label> 
        <div class="invalid-feedback" v-if="dataErrors.url">
            <p class="m-b-0" v-for="error in dataErrors.url">@{{ error }}</p>
        </div>
    </div>

</div>
