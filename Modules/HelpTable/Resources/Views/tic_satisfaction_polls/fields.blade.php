<!-- Ht Tic Requests Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_requests_id', trans('Ht Tic Requests Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_requests_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_requests_id }", 'v-model' => 'dataForm.ht_tic_requests_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Requests Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_requests_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_requests_id">@{{ error }}</p>
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
            <p class="m-b-0" v-for="error in dataErrors.users_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Functionary Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('functionary_id', trans('Functionary Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('functionary_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.functionary_id }", 'v-model' => 'dataForm.functionary_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Functionary Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.functionary_id">
            <p class="m-b-0" v-for="error in dataErrors.functionary_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Question Field -->
<div class="form-group row m-b-15">
    {!! Form::label('question', trans('Question').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('question', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.question }", 'v-model' => 'dataForm.question', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Question')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.question">
            <p class="m-b-0" v-for="error in dataErrors.question">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Reply Field -->
<div class="form-group row m-b-15">
    {!! Form::label('reply', trans('Reply').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('reply', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reply }", 'v-model' => 'dataForm.reply', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Reply')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.reply">
            <p class="m-b-0" v-for="error in dataErrors.reply">@{{ error }}</p>
        </div>
    </div>
</div>
