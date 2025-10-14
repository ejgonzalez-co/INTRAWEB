<!-- Lc Rm Report Management Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('lc_rm_report_management_id', trans('Lc Rm Report Management Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('lc_rm_report_management_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.lc_rm_report_management_id }", 'v-model' => 'dataForm.lc_rm_report_management_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Lc Rm Report Management Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.lc_rm_report_management_id">
            <p class="m-b-0" v-for="error in dataErrors.lc_rm_report_management_id">@{{ error }}</p>
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

<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
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

<!-- Attachment Field -->
<div class="form-group row m-b-15">
    {!! Form::label('attachment', trans('Attachment').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('attachment', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.attachment }", 'v-model' => 'dataForm.attachment', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Attachment')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.attachment">
            <p class="m-b-0" v-for="error in dataErrors.attachment">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status', trans('Status').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('status', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.status }", 'v-model' => 'dataForm.status', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Comments Field -->
<div class="form-group row m-b-15">
    {!! Form::label('comments', trans('Comments').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('comments', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.comments }", 'v-model' => 'dataForm.comments', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Comments')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.comments">
            <p class="m-b-0" v-for="error in dataErrors.comments">@{{ error }}</p>
        </div>
    </div>
</div>
