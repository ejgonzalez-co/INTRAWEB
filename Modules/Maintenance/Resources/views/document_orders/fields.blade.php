
<!-- Nombre Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nombre')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nombre">
            <p class="m-b-0" v-for="error in dataErrors.nombre">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- adjunto  Field -->
<div class="form-group row m-b-15">
    {!! Form::label('adjunto', 'Adjunto', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::file('adjunto', ['@change' => 'inputFile($event, "adjunto")']) !!}
        <br>
        
        <label v-if="dataForm.adjunto" style="margin-top: 5px">Adjunto actual actual: <a class="col-3 text-truncate":href="'{{ asset('storage') }}/'+dataForm.adjunto"target="_blank">Ver adjunto</a></label>
        <small v-else>Seleccione un @{{ 'archivo' | lowercase }}</small> 
        <div class="invalid-feedback" v-if="dataErrors.adjunto">
            <p class="m-b-0" v-for="error in dataErrors.adjunto">@{{ error }}</p>
        </div>
    </div>
</div>
