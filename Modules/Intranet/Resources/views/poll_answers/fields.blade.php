<!-- Answer Field -->
<div class="form-group row m-b-15">
    {!! Form::label('answer', trans('Answer').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('answer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.answer }", 'v-model' => 'dataForm.answer', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Answer')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.answer">
            <p class="m-b-0" v-for="error in dataErrors.answer">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Users Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_name', trans('Users Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('users_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_name }", 'v-model' => 'dataForm.users_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Users Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.users_name">
            <p class="m-b-0" v-for="error in dataErrors.users_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Intranet Polls Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('intranet_polls_id', trans('Intranet Polls Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('intranet_polls_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.intranet_polls_id }", 'v-model' => 'dataForm.intranet_polls_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Intranet Polls Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.intranet_polls_id">
            <p class="m-b-0" v-for="error in dataErrors.intranet_polls_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Intranet Polls Questions Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('intranet_polls_questions_id', trans('Intranet Polls Questions Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('intranet_polls_questions_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.intranet_polls_questions_id }", 'v-model' => 'dataForm.intranet_polls_questions_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Intranet Polls Questions Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.intranet_polls_questions_id">
            <p class="m-b-0" v-for="error in dataErrors.intranet_polls_questions_id">@{{ error }}</p>
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
