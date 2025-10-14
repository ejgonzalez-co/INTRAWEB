<!-- Pc Needs Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_needs_id', trans('Pc Needs Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_needs_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_needs_id }", 'v-model' => 'dataForm.pc_needs_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Needs Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_needs_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_needs_id">@{{ error }}</p>
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

<!-- Estimated Start Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('estimated_start_date', trans('Estimated Start Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('estimated_start_date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.estimated_start_date }", 'id' => 'estimated_start_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.estimated_start_date', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Estimated Start Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estimated_start_date">
            <p class="m-b-0" v-for="error in dataErrors.estimated_start_date">@{{ error }}</p>
        </div>
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#estimated_start_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Selection Mode Field -->
<div class="form-group row m-b-15">
    {!! Form::label('selection_mode', trans('Selection Mode').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('selection_mode', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.selection_mode }", 'v-model' => 'dataForm.selection_mode', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Selection Mode')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.selection_mode">
            <p class="m-b-0" v-for="error in dataErrors.selection_mode">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Estimated Total Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('estimated_total_value', trans('Estimated Total Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('estimated_total_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.estimated_total_value }", 'v-model' => 'dataForm.estimated_total_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Estimated Total Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estimated_total_value">
            <p class="m-b-0" v-for="error in dataErrors.estimated_total_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Estimated Value Current Validity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('estimated_value_current_validity', trans('Estimated Value Current Validity').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('estimated_value_current_validity', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.estimated_value_current_validity }", 'v-model' => 'dataForm.estimated_value_current_validity', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Estimated Value Current Validity')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.estimated_value_current_validity">
            <p class="m-b-0" v-for="error in dataErrors.estimated_value_current_validity">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Additions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('additions', trans('Additions').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('additions', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.additions }", 'v-model' => 'dataForm.additions', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Additions')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.additions">
            <p class="m-b-0" v-for="error in dataErrors.additions">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Total Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_value', trans('Total Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('total_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.total_value }", 'v-model' => 'dataForm.total_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Total Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.total_value">
            <p class="m-b-0" v-for="error in dataErrors.total_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Future Validity Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('future_validity_status', trans('Future Validity Status').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- future_validity_status switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="future_validity_status" id="future_validity_status"  v-model="dataForm.future_validity_status">
        <label for="future_validity_status"></label>
        <small>@lang('Select the') @{{ `@lang('Future Validity Status')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.future_validity_status">
            <p class="m-b-0" v-for="error in dataErrors.future_validity_status">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>
