<!-- Evaluator Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluator_id', trans('Evaluator Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('evaluator_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluator_id }", 'v-model' => 'dataForm.evaluator_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluator Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluator_id">
            <p class="m-b-0" v-for="error in dataErrors.evaluator_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Evaluated Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluated_id', trans('Evaluated Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('evaluated_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluated_id }", 'v-model' => 'dataForm.evaluated_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluated Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluated_id">
            <p class="m-b-0" v-for="error in dataErrors.evaluated_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Type Evaluation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type_evaluation', trans('Type Evaluation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('type_evaluation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type_evaluation }", 'v-model' => 'dataForm.type_evaluation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Type Evaluation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.type_evaluation">
            <p class="m-b-0" v-for="error in dataErrors.type_evaluation">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Evaluation Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_name', trans('Evaluation Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('evaluation_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluation_name }", 'v-model' => 'dataForm.evaluation_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluation Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_name">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Objective Evaluation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('objective_evaluation', trans('Objective Evaluation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('objective_evaluation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.objective_evaluation }", 'v-model' => 'dataForm.objective_evaluation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Objective Evaluation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.objective_evaluation">
            <p class="m-b-0" v-for="error in dataErrors.objective_evaluation">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Evaluation Scope Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_scope', trans('Evaluation Scope').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('evaluation_scope', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluation_scope }", 'v-model' => 'dataForm.evaluation_scope', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluation Scope')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_scope">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_scope">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Evaluation Site Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_site', trans('Evaluation Site').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('evaluation_site', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluation_site }", 'v-model' => 'dataForm.evaluation_site', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluation Site')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_site">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_site">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Evaluation Start Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_start_date', trans('Evaluation Start Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="evaluation_start_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Evaluation Start Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_start_date">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_start_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Evaluation Start Time Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_start_time', trans('Evaluation Start Time').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('evaluation_start_time', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluation_start_time }", 'v-model' => 'dataForm.evaluation_start_time', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluation Start Time')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_start_time">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_start_time">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Evaluation End Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_end_date', trans('Evaluation End Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="evaluation_end_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Evaluation End Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_end_date">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_end_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Evaluation End Time Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_end_time', trans('Evaluation End Time').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('evaluation_end_time', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluation_end_time }", 'v-model' => 'dataForm.evaluation_end_time', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluation End Time')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_end_time">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_end_time">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Unit Responsible For Evaluation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_responsible_for_evaluation', trans('Unit Responsible For Evaluation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('unit_responsible_for_evaluation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.unit_responsible_for_evaluation }", 'v-model' => 'dataForm.unit_responsible_for_evaluation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Unit Responsible For Evaluation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.unit_responsible_for_evaluation">
            <p class="m-b-0" v-for="error in dataErrors.unit_responsible_for_evaluation">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Evaluation Officer Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_officer', trans('Evaluation Officer').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('evaluation_officer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluation_officer }", 'v-model' => 'dataForm.evaluation_officer', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluation Officer')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_officer">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_officer">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Process Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process', trans('Process').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('process', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.process }", 'v-model' => 'dataForm.process', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Process')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.process">
            <p class="m-b-0" v-for="error in dataErrors.process">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Attached Field -->
<div class="form-group row m-b-15">
    {!! Form::label('attached', trans('Attached').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('attached', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.attached }", 'v-model' => 'dataForm.attached', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Attached')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.attached">
            <p class="m-b-0" v-for="error in dataErrors.attached">@{{ error }}</p>
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

<!-- Evaluation Process Attachment Field -->
<div class="form-group row m-b-15">
    {!! Form::label('evaluation_process_attachment', trans('Evaluation Process Attachment').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('evaluation_process_attachment', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.evaluation_process_attachment }", 'v-model' => 'dataForm.evaluation_process_attachment', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Evaluation Process Attachment')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.evaluation_process_attachment">
            <p class="m-b-0" v-for="error in dataErrors.evaluation_process_attachment">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- General Description Evaluation Results Field -->
<div class="form-group row m-b-15">
    {!! Form::label('general_description_evaluation_results', trans('General Description Evaluation Results').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('general_description_evaluation_results', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.general_description_evaluation_results }", 'v-model' => 'dataForm.general_description_evaluation_results', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('General Description Evaluation Results')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.general_description_evaluation_results">
            <p class="m-b-0" v-for="error in dataErrors.general_description_evaluation_results">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Name Improvement Plan Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_improvement_plan', trans('Name Improvement Plan').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name_improvement_plan', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_improvement_plan }", 'v-model' => 'dataForm.name_improvement_plan', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name Improvement Plan')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name_improvement_plan">
            <p class="m-b-0" v-for="error in dataErrors.name_improvement_plan">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Is Accordance Field -->
<div class="form-group row m-b-15">
    {!! Form::label('is_accordance', trans('Is Accordance').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('is_accordance', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.is_accordance }", 'v-model' => 'dataForm.is_accordance', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Is Accordance')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.is_accordance">
            <p class="m-b-0" v-for="error in dataErrors.is_accordance">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Execution Percentage Field -->
<div class="form-group row m-b-15">
    {!! Form::label('execution_percentage', trans('Execution Percentage').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('execution_percentage', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.execution_percentage }", 'v-model' => 'dataForm.execution_percentage', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Execution Percentage')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.execution_percentage">
            <p class="m-b-0" v-for="error in dataErrors.execution_percentage">@{{ error }}</p>
        </div>
    </div>
</div>
