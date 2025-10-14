<!-- Ht Tic Type Request Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_type_request_id', trans('Ht Tic Type Request Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_type_request_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_type_request_id }", 'v-model' => 'dataForm.ht_tic_type_request_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Type Request Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_type_request_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_type_request_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Ht Tic Request Status Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_request_status_id', trans('Ht Tic Request Status Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_request_status_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_request_status_id }", 'v-model' => 'dataForm.ht_tic_request_status_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Request Status Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_request_status_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_request_status_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Assigned By Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('assigned_by_id', trans('Assigned By Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('assigned_by_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.assigned_by_id }", 'v-model' => 'dataForm.assigned_by_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Assigned By Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.assigned_by_id">
            <p class="m-b-0" v-for="error in dataErrors.assigned_by_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Assigned By Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('assigned_by_name', trans('Assigned By Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('assigned_by_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.assigned_by_name }", 'v-model' => 'dataForm.assigned_by_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Assigned By Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.assigned_by_name">
            <p class="m-b-0" v-for="error in dataErrors.assigned_by_name">@{{ error }}</p>
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

<!-- Users Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_name', trans('Users Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('users_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_name }", 'v-model' => 'dataForm.users_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Users Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.users_name">
            <p class="m-b-0" v-for="error in dataErrors.users_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Assigned User Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('assigned_user_id', trans('Assigned User Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('assigned_user_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.assigned_user_id }", 'v-model' => 'dataForm.assigned_user_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Assigned User Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.assigned_user_id">
            <p class="m-b-0" v-for="error in dataErrors.assigned_user_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Assigned User Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('assigned_user_name', trans('Assigned User Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('assigned_user_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.assigned_user_name }", 'v-model' => 'dataForm.assigned_user_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Assigned User Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.assigned_user_name">
            <p class="m-b-0" v-for="error in dataErrors.assigned_user_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Ht Tic Type Tic Categories Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_type_tic_categories_id', trans('Ht Tic Type Tic Categories Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_type_tic_categories_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_type_tic_categories_id }", 'v-model' => 'dataForm.ht_tic_type_tic_categories_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Type Tic Categories Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_type_tic_categories_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_type_tic_categories_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Priority Request Field -->
<div class="form-group row m-b-15">
    {!! Form::label('priority_request', trans('Priority Request').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- priority_request switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="priority_request" id="priority_request"  v-model="dataForm.priority_request">
        <label for="priority_request"></label>
        <small>@lang('Select the') @{{ `@lang('Priority Request')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.priority_request">
            <p class="m-b-0" v-for="error in dataErrors.priority_request">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Affair Field -->
<div class="form-group row m-b-15">
    {!! Form::label('affair', trans('Affair').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('affair', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.affair }", 'v-model' => 'dataForm.affair', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Affair')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.affair">
            <p class="m-b-0" v-for="error in dataErrors.affair">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Floor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('floor', trans('Floor').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('floor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.floor }", 'v-model' => 'dataForm.floor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Floor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.floor">
            <p class="m-b-0" v-for="error in dataErrors.floor">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Assignment Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('assignment_date', trans('Assignment Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="assignment_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Assignment Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.assignment_date">
            <p class="m-b-0" v-for="error in dataErrors.assignment_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Prox Date To Expire Field -->
<div class="form-group row m-b-15">
    {!! Form::label('prox_date_to_expire', trans('Prox Date To Expire').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="prox_date_to_expire"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Prox Date To Expire')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.prox_date_to_expire">
            <p class="m-b-0" v-for="error in dataErrors.prox_date_to_expire">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Expiration Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('expiration_date', trans('Expiration Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="expiration_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Expiration Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.expiration_date">
            <p class="m-b-0" v-for="error in dataErrors.expiration_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Date Attention Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_attention', trans('Date Attention').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="date_attention"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Date Attention')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.date_attention">
            <p class="m-b-0" v-for="error in dataErrors.date_attention">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Closing Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('closing_date', trans('Closing Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="closing_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Closing Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.closing_date">
            <p class="m-b-0" v-for="error in dataErrors.closing_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Reshipment Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('reshipment_date', trans('Reshipment Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="reshipment_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Reshipment Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.reshipment_date">
            <p class="m-b-0" v-for="error in dataErrors.reshipment_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Next Hour To Expire Field -->
<div class="form-group row m-b-15">
    {!! Form::label('next_hour_to_expire', trans('Next Hour To Expire').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('next_hour_to_expire', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.next_hour_to_expire }", 'v-model' => 'dataForm.next_hour_to_expire', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Next Hour To Expire')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.next_hour_to_expire">
            <p class="m-b-0" v-for="error in dataErrors.next_hour_to_expire">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Hours Field -->
<div class="form-group row m-b-15">
    {!! Form::label('hours', trans('Hours').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('hours', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.hours }", 'v-model' => 'dataForm.hours', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Hours')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.hours">
            <p class="m-b-0" v-for="error in dataErrors.hours">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tracing Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tracing', trans('Tracing').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('tracing', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tracing }", 'v-model' => 'dataForm.tracing', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tracing')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tracing">
            <p class="m-b-0" v-for="error in dataErrors.tracing">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Request Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('request_status', trans('Request Status').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('request_status', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.request_status }", 'v-model' => 'dataForm.request_status', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Request Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.request_status">
            <p class="m-b-0" v-for="error in dataErrors.request_status">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Survey Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('survey_status', trans('Survey Status').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- survey_status switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="survey_status" id="survey_status"  v-model="dataForm.survey_status">
        <label for="survey_status"></label>
        <small>@lang('Select the') @{{ `@lang('Survey Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.survey_status">
            <p class="m-b-0" v-for="error in dataErrors.survey_status">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Time Line Field -->
<div class="form-group row m-b-15">
    {!! Form::label('time_line', trans('Time Line').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('time_line', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.time_line }", 'v-model' => 'dataForm.time_line', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Time Line')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.time_line">
            <p class="m-b-0" v-for="error in dataErrors.time_line">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Support Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('support_type', trans('Support Type').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- support_type switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="support_type" id="support_type"  v-model="dataForm.support_type">
        <label for="support_type"></label>
        <small>@lang('Select the') @{{ `@lang('Support Type')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.support_type">
            <p class="m-b-0" v-for="error in dataErrors.support_type">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Username Requesting Requirement Field -->
<div class="form-group row m-b-15">
    {!! Form::label('username_requesting_requirement', trans('Username Requesting Requirement').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('username_requesting_requirement', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.username_requesting_requirement }", 'v-model' => 'dataForm.username_requesting_requirement', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Username Requesting Requirement')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.username_requesting_requirement">
            <p class="m-b-0" v-for="error in dataErrors.username_requesting_requirement">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Location Field -->
<div class="form-group row m-b-15">
    {!! Form::label('location', trans('Location').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('location', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.location }", 'v-model' => 'dataForm.location', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Location')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.location">
            <p class="m-b-0" v-for="error in dataErrors.location">@{{ error }}</p>
        </div>
    </div>
</div>
