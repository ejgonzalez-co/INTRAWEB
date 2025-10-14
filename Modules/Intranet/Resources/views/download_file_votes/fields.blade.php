<!--  Rating Field -->
<div class="form-group row m-b-15">
    {!! Form::label('rating', trans('Rating').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- rating switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="rating" id="rating"  v-model="dataForm.rating">
        <label for="rating"></label>
        <small>@lang('Select the') @{{ `@lang('Rating')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.rating">
            <p class="m-b-0" v-for="error in dataErrors.rating">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Ordering Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ordering', trans('Ordering').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('ordering', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ordering }", 'v-model' => 'dataForm.ordering', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ordering')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ordering">
            <p class="m-b-0" v-for="error in dataErrors.ordering">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Intranet Downloads Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('intranet_downloads_id', trans('Intranet Downloads Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('intranet_downloads_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.intranet_downloads_id }", 'v-model' => 'dataForm.intranet_downloads_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Intranet Downloads Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.intranet_downloads_id">
            <p class="m-b-0" v-for="error in dataErrors.intranet_downloads_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Users Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('Users Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('users_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_id }", 'v-model' => 'dataForm.users_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Users Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.users_id">
            <p class="m-b-0" v-for="error in dataErrors.users_id">@{{ error }}</p>
        </div>
    </div>
</div>
