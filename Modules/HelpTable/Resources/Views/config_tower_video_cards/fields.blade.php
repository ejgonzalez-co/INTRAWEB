<!-- Video Card Field -->
<div class="form-group row m-b-15">
    {!! Form::label('video_card', trans('Video Card').':', ['class' => 'col-form-label col-12 col-lg-2 required']) !!}
    <div class="col-12 col-lg-4">
        {!! Form::text('video_card', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.video_card }", 'v-model' => 'dataForm.video_card', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Video Card')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.video_card">
            <p class="m-b-0" v-for="error in dataErrors.video_card">@{{ error }}</p>
        </div>
    </div>

    {!! Form::label('status', trans('Status').':', ['class' => 'col-form-label col-12 col-lg-2 required']) !!}
    <div class="col-12 col-lg-4">
        <select class="form-control" v-model="dataForm.status" name="status">
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
        <small>@lang('Enter the') @{{ `@lang('Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div>
</div>
<!-- Marca Field -->
<div class="form-group row m-b-15">
    {!! Form::label('marca', trans('Marca').':', ['class' => 'col-form-label col-12 col-lg-2 required']) !!}
    <div class="col-12 col-lg-4">
        {!! Form::text('marca', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.marca }", 'v-model' => 'dataForm.marca', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Marca')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.marca">
            <p class="m-b-0" v-for="error in dataErrors.marca">@{{ error }}</p>
        </div>
    </div>
</div>