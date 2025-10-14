<!-- Adjunto Field -->
<div class="form-group row m-b-15">
    {!! Form::label('adjunto', trans('Mapa de procesos').':', ['class' => 'col-form-label col-md-4 required']) !!}
    <div class="col-md-8">
        {!! Form::file('adjunto', ['accept' => '*', '@change' => 'inputFile($event, "adjunto")', 'required' => true]) !!}
        <small>Seleccione un adjunto de máximo 5Mb.</small>
        <div class="invalid-feedback" v-if="dataErrors.adjunto">
            <p class="m-b-0" v-for="error in dataErrors.adjunto">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Descripcion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('descripcion', trans('Descripción').':', ['class' => 'col-form-label col-md-4']) !!}
    <div class="col-md-8">
        {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion', 'required' => false]) !!}
        <small>@lang('Enter the') una @{{ `@lang('Descripción')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.descripcion">
            <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
        </div>
    </div>
</div>
