<!-- Lc Sample Taking Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('lc_sample_taking_id', trans('Lc Sample Taking Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('lc_sample_taking_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.lc_sample_taking_id }", 'v-model' => 'dataForm.lc_sample_taking_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Lc Sample Taking Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.lc_sample_taking_id">
            <p class="m-b-0" v-for="error in dataErrors.lc_sample_taking_id">@{{ error }}</p>
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

<!-- Lc Customers Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('lc_customers_id', trans('Lc Customers Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('lc_customers_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.lc_customers_id }", 'v-model' => 'dataForm.lc_customers_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Lc Customers Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.lc_customers_id">
            <p class="m-b-0" v-for="error in dataErrors.lc_customers_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Consecutive Field -->
<div class="form-group row m-b-15">
    {!! Form::label('consecutive', trans('Consecutive').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('consecutive', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consecutive }", 'v-model' => 'dataForm.consecutive', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Consecutive')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.consecutive">
            <p class="m-b-0" v-for="error in dataErrors.consecutive">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Nex Consecutiveic Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nex_consecutiveIC', trans('Nex Consecutiveic').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('nex_consecutiveIC', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nex_consecutiveIC }", 'v-model' => 'dataForm.nex_consecutiveIC', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nex Consecutiveic')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nex_consecutiveIC">
            <p class="m-b-0" v-for="error in dataErrors.nex_consecutiveIC">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Nex Consecutiveie Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nex_consecutiveIE', trans('Nex Consecutiveie').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('nex_consecutiveIE', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nex_consecutiveIE }", 'v-model' => 'dataForm.nex_consecutiveIE', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Nex Consecutiveie')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.nex_consecutiveIE">
            <p class="m-b-0" v-for="error in dataErrors.nex_consecutiveIE">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Name Customer Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_customer', trans('Name Customer').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name_customer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_customer }", 'v-model' => 'dataForm.name_customer', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name Customer')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name_customer">
            <p class="m-b-0" v-for="error in dataErrors.name_customer">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mail Customer Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mail_customer', trans('Mail Customer').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('mail_customer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mail_customer }", 'v-model' => 'dataForm.mail_customer', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mail Customer')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mail_customer">
            <p class="m-b-0" v-for="error in dataErrors.mail_customer">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Coments Consecutive Field -->
<div class="form-group row m-b-15">
    {!! Form::label('coments_consecutive', trans('Coments Consecutive').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('coments_consecutive', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.coments_consecutive }", 'v-model' => 'dataForm.coments_consecutive', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Coments Consecutive')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.coments_consecutive">
            <p class="m-b-0" v-for="error in dataErrors.coments_consecutive">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Date Report Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_report', trans('Date Report').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('date_report', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date_report }", 'v-model' => 'dataForm.date_report', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Date Report')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.date_report">
            <p class="m-b-0" v-for="error in dataErrors.date_report">@{{ error }}</p>
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

<!-- Query Report Field -->
<div class="form-group row m-b-15">
    {!! Form::label('query_report', trans('Query Report').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('query_report', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.query_report }", 'v-model' => 'dataForm.query_report', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Query Report')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.query_report">
            <p class="m-b-0" v-for="error in dataErrors.query_report">@{{ error }}</p>
        </div>
    </div>
</div>
