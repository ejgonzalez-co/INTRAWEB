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

<!-- Pc Validities Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_validities_id', trans('Pc Validities Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_validities_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_validities_id }", 'v-model' => 'dataForm.pc_validities_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Validities Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_validities_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_validities_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Pc Name Projects Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_name_projects_id', trans('Pc Name Projects Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_name_projects_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_name_projects_id }", 'v-model' => 'dataForm.pc_name_projects_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Name Projects Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_name_projects_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_name_projects_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Dependencias Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependencias_id', trans('Dependencias Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('dependencias_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.dependencias_id }", 'v-model' => 'dataForm.dependencias_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Dependencias Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
            <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Pc Management Unit Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_management_unit_id', trans('Pc Management Unit Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_management_unit_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_management_unit_id }", 'v-model' => 'dataForm.pc_management_unit_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Management Unit Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_management_unit_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_management_unit_id">@{{ error }}</p>
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

<!-- Pc Project Lines Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_project_lines_id', trans('Pc Project Lines Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_project_lines_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_project_lines_id }", 'v-model' => 'dataForm.pc_project_lines_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Project Lines Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_project_lines_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_project_lines_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Pc Poir Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_poir_id', trans('Pc Poir Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_poir_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pc_poir_id }", 'v-model' => 'dataForm.pc_poir_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Pc Poir Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pc_poir_id">
            <p class="m-b-0" v-for="error in dataErrors.pc_poir_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Code Bppiepa Field -->
<div class="form-group row m-b-15">
    {!! Form::label('code_bppiepa', trans('Code Bppiepa').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('code_bppiepa', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.code_bppiepa }", 'v-model' => 'dataForm.code_bppiepa', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Code Bppiepa')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.code_bppiepa">
            <p class="m-b-0" v-for="error in dataErrors.code_bppiepa">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Date Presentation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_presentation', trans('Date Presentation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('date_presentation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date_presentation }", 'id' => 'date_presentation',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_presentation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Date Presentation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.date_presentation">
            <p class="m-b-0" v-for="error in dataErrors.date_presentation">@{{ error }}</p>
        </div>
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#date_presentation').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Update Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('update_date', trans('Update Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('update_date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.update_date }", 'id' => 'update_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.update_date', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Update Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.update_date">
            <p class="m-b-0" v-for="error in dataErrors.update_date">@{{ error }}</p>
        </div>
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#update_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Responsible User Field -->
<div class="form-group row m-b-15">
    {!! Form::label('responsible_user', trans('Responsible User').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('responsible_user', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.responsible_user }", 'v-model' => 'dataForm.responsible_user', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Responsible User')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.responsible_user">
            <p class="m-b-0" v-for="error in dataErrors.responsible_user">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Municipal Development Plan Field -->
<div class="form-group row m-b-15">
    {!! Form::label('municipal_development_plan', trans('Municipal Development Plan').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- municipal_development_plan switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="municipal_development_plan" id="municipal_development_plan"  v-model="dataForm.municipal_development_plan">
        <label for="municipal_development_plan"></label>
        <small>@lang('Select the') @{{ `@lang('Municipal Development Plan')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.municipal_development_plan">
            <p class="m-b-0" v-for="error in dataErrors.municipal_development_plan">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Period Field -->
<div class="form-group row m-b-15">
    {!! Form::label('period', trans('Period').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- period switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="period" id="period"  v-model="dataForm.period">
        <label for="period"></label>
        <small>@lang('Select the') @{{ `@lang('Period')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.period">
            <p class="m-b-0" v-for="error in dataErrors.period">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Strategic Line Field -->
<div class="form-group row m-b-15">
    {!! Form::label('strategic_line', trans('Strategic Line').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- strategic_line switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="strategic_line" id="strategic_line"  v-model="dataForm.strategic_line">
        <label for="strategic_line"></label>
        <small>@lang('Select the') @{{ `@lang('Strategic Line')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.strategic_line">
            <p class="m-b-0" v-for="error in dataErrors.strategic_line">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Sector Field -->
<div class="form-group row m-b-15">
    {!! Form::label('sector', trans('Sector').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- sector switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="sector" id="sector"  v-model="dataForm.sector">
        <label for="sector"></label>
        <small>@lang('Select the') @{{ `@lang('Sector')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.sector">
            <p class="m-b-0" v-for="error in dataErrors.sector">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Program Field -->
<div class="form-group row m-b-15">
    {!! Form::label('program', trans('Program').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- program switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="program" id="program"  v-model="dataForm.program">
        <label for="program"></label>
        <small>@lang('Select the') @{{ `@lang('Program')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.program">
            <p class="m-b-0" v-for="error in dataErrors.program">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Subprogram Field -->
<div class="form-group row m-b-15">
    {!! Form::label('subprogram', trans('Subprogram').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- subprogram switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="subprogram" id="subprogram"  v-model="dataForm.subprogram">
        <label for="subprogram"></label>
        <small>@lang('Select the') @{{ `@lang('Subprogram')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.subprogram">
            <p class="m-b-0" v-for="error in dataErrors.subprogram">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Other Planning Documents Field -->
<div class="form-group row m-b-15">
    {!! Form::label('other_planning_documents', trans('Other Planning Documents').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('other_planning_documents', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.other_planning_documents }", 'v-model' => 'dataForm.other_planning_documents', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Other Planning Documents')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.other_planning_documents">
            <p class="m-b-0" v-for="error in dataErrors.other_planning_documents">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Which Other Document Field -->
<div class="form-group row m-b-15">
    {!! Form::label('which_other_document', trans('Which Other Document').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('which_other_document', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.which_other_document }", 'v-model' => 'dataForm.which_other_document', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Which Other Document')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.which_other_document">
            <p class="m-b-0" v-for="error in dataErrors.which_other_document">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Problem Need Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description_problem_need', trans('Description Problem Need').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description_problem_need', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description_problem_need }", 'v-model' => 'dataForm.description_problem_need', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description Problem Need')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description_problem_need">
            <p class="m-b-0" v-for="error in dataErrors.description_problem_need">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Project Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('project_description', trans('Project Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('project_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.project_description }", 'v-model' => 'dataForm.project_description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Project Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.project_description">
            <p class="m-b-0" v-for="error in dataErrors.project_description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Justification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('justification', trans('Justification').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('justification', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.justification }", 'v-model' => 'dataForm.justification', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Justification')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.justification">
            <p class="m-b-0" v-for="error in dataErrors.justification">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Background Field -->
<div class="form-group row m-b-15">
    {!! Form::label('background', trans('Background').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('background', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.background }", 'v-model' => 'dataForm.background', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Background')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.background">
            <p class="m-b-0" v-for="error in dataErrors.background">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- General Objective Field -->
<div class="form-group row m-b-15">
    {!! Form::label('general_objective', trans('General Objective').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('general_objective', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.general_objective }", 'v-model' => 'dataForm.general_objective', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('General Objective')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.general_objective">
            <p class="m-b-0" v-for="error in dataErrors.general_objective">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Overall Goal Field -->
<div class="form-group row m-b-15">
    {!! Form::label('overall_goal', trans('Overall Goal').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('overall_goal', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.overall_goal }", 'v-model' => 'dataForm.overall_goal', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Overall Goal')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.overall_goal">
            <p class="m-b-0" v-for="error in dataErrors.overall_goal">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- General Baseline Field -->
<div class="form-group row m-b-15">
    {!! Form::label('general_baseline', trans('General Baseline').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('general_baseline', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.general_baseline }", 'v-model' => 'dataForm.general_baseline', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('General Baseline')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.general_baseline">
            <p class="m-b-0" v-for="error in dataErrors.general_baseline">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Cost Units Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cost_units', trans('Cost Units').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('cost_units', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cost_units }", 'v-model' => 'dataForm.cost_units', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cost Units')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cost_units">
            <p class="m-b-0" v-for="error in dataErrors.cost_units">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Replacement Field -->
<div class="form-group row m-b-15">
    {!! Form::label('replacement', trans('Replacement').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- replacement switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="replacement" id="replacement"  v-model="dataForm.replacement">
        <label for="replacement"></label>
        <small>@lang('Select the') @{{ `@lang('Replacement')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.replacement">
            <p class="m-b-0" v-for="error in dataErrors.replacement">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Expansion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('expansion', trans('Expansion').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- expansion switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="expansion" id="expansion"  v-model="dataForm.expansion">
        <label for="expansion"></label>
        <small>@lang('Select the') @{{ `@lang('Expansion')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.expansion">
            <p class="m-b-0" v-for="error in dataErrors.expansion">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Rehabilitation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('rehabilitation', trans('Rehabilitation').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- rehabilitation switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="rehabilitation" id="rehabilitation"  v-model="dataForm.rehabilitation">
        <label for="rehabilitation"></label>
        <small>@lang('Select the') @{{ `@lang('Rehabilitation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.rehabilitation">
            <p class="m-b-0" v-for="error in dataErrors.rehabilitation">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Coverage Field -->
<div class="form-group row m-b-15">
    {!! Form::label('coverage', trans('Coverage').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- coverage switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="coverage" id="coverage"  v-model="dataForm.coverage">
        <label for="coverage"></label>
        <small>@lang('Select the') @{{ `@lang('Coverage')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.coverage">
            <p class="m-b-0" v-for="error in dataErrors.coverage">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Continuity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('continuity', trans('Continuity').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- continuity switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="continuity" id="continuity"  v-model="dataForm.continuity">
        <label for="continuity"></label>
        <small>@lang('Select the') @{{ `@lang('Continuity')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.continuity">
            <p class="m-b-0" v-for="error in dataErrors.continuity">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Irca Water Quality Risk Index Field -->
<div class="form-group row m-b-15">
    {!! Form::label('irca_water_quality_risk_index', trans('Irca Water Quality Risk Index').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- irca_water_quality_risk_index switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="irca_water_quality_risk_index" id="irca_water_quality_risk_index"  v-model="dataForm.irca_water_quality_risk_index">
        <label for="irca_water_quality_risk_index"></label>
        <small>@lang('Select the') @{{ `@lang('Irca Water Quality Risk Index')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.irca_water_quality_risk_index">
            <p class="m-b-0" v-for="error in dataErrors.irca_water_quality_risk_index">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Micrometer Field -->
<div class="form-group row m-b-15">
    {!! Form::label('micrometer', trans('Micrometer').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- micrometer switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="micrometer" id="micrometer"  v-model="dataForm.micrometer">
        <label for="micrometer"></label>
        <small>@lang('Select the') @{{ `@lang('Micrometer')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.micrometer">
            <p class="m-b-0" v-for="error in dataErrors.micrometer">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Ianc Unaccounted Water Index Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ianc_unaccounted_water_index', trans('Ianc Unaccounted Water Index').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- ianc_unaccounted_water_index switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="ianc_unaccounted_water_index" id="ianc_unaccounted_water_index"  v-model="dataForm.ianc_unaccounted_water_index">
        <label for="ianc_unaccounted_water_index"></label>
        <small>@lang('Select the') @{{ `@lang('Ianc Unaccounted Water Index')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ianc_unaccounted_water_index">
            <p class="m-b-0" v-for="error in dataErrors.ianc_unaccounted_water_index">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Ipufi Loss Index Billed User Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ipufi_loss_index_billed_user', trans('Ipufi Loss Index Billed User').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- ipufi_loss_index_billed_user switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="ipufi_loss_index_billed_user" id="ipufi_loss_index_billed_user"  v-model="dataForm.ipufi_loss_index_billed_user">
        <label for="ipufi_loss_index_billed_user"></label>
        <small>@lang('Select the') @{{ `@lang('Ipufi Loss Index Billed User')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ipufi_loss_index_billed_user">
            <p class="m-b-0" v-for="error in dataErrors.ipufi_loss_index_billed_user">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Icufi Index Water Consumed User Field -->
<div class="form-group row m-b-15">
    {!! Form::label('icufi_index_water_consumed_user', trans('Icufi Index Water Consumed User').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- icufi_index_water_consumed_user switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="icufi_index_water_consumed_user" id="icufi_index_water_consumed_user"  v-model="dataForm.icufi_index_water_consumed_user">
        <label for="icufi_index_water_consumed_user"></label>
        <small>@lang('Select the') @{{ `@lang('Icufi Index Water Consumed User')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.icufi_index_water_consumed_user">
            <p class="m-b-0" v-for="error in dataErrors.icufi_index_water_consumed_user">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Isufi Supply Index Billed User Field -->
<div class="form-group row m-b-15">
    {!! Form::label('isufi_supply_index_billed_user', trans('Isufi Supply Index Billed User').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- isufi_supply_index_billed_user switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="isufi_supply_index_billed_user" id="isufi_supply_index_billed_user"  v-model="dataForm.isufi_supply_index_billed_user">
        <label for="isufi_supply_index_billed_user"></label>
        <small>@lang('Select the') @{{ `@lang('Isufi Supply Index Billed User')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.isufi_supply_index_billed_user">
            <p class="m-b-0" v-for="error in dataErrors.isufi_supply_index_billed_user">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Ccpi Consumption Corrected Losses Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ccpi_consumption_corrected_losses', trans('Ccpi Consumption Corrected Losses').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- ccpi_consumption_corrected_losses switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="ccpi_consumption_corrected_losses" id="ccpi_consumption_corrected_losses"  v-model="dataForm.ccpi_consumption_corrected_losses">
        <label for="ccpi_consumption_corrected_losses"></label>
        <small>@lang('Select the') @{{ `@lang('Ccpi Consumption Corrected Losses')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ccpi_consumption_corrected_losses">
            <p class="m-b-0" v-for="error in dataErrors.ccpi_consumption_corrected_losses">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Pressure Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pressure', trans('Pressure').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- pressure switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="pressure" id="pressure"  v-model="dataForm.pressure">
        <label for="pressure"></label>
        <small>@lang('Select the') @{{ `@lang('Pressure')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.pressure">
            <p class="m-b-0" v-for="error in dataErrors.pressure">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Discharge Treatment Index Field -->
<div class="form-group row m-b-15">
    {!! Form::label('discharge_treatment_index', trans('Discharge Treatment Index').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- discharge_treatment_index switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="discharge_treatment_index" id="discharge_treatment_index"  v-model="dataForm.discharge_treatment_index">
        <label for="discharge_treatment_index"></label>
        <small>@lang('Select the') @{{ `@lang('Discharge Treatment Index')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.discharge_treatment_index">
            <p class="m-b-0" v-for="error in dataErrors.discharge_treatment_index">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Tons Bbo Removed Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tons_bbo_removed', trans('Tons Bbo Removed').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- tons_bbo_removed switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="tons_bbo_removed" id="tons_bbo_removed"  v-model="dataForm.tons_bbo_removed">
        <label for="tons_bbo_removed"></label>
        <small>@lang('Select the') @{{ `@lang('Tons Bbo Removed')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tons_bbo_removed">
            <p class="m-b-0" v-for="error in dataErrors.tons_bbo_removed">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Tons Sst Removed Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tons_sst_removed', trans('Tons Sst Removed').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- tons_sst_removed switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="tons_sst_removed" id="tons_sst_removed"  v-model="dataForm.tons_sst_removed">
        <label for="tons_sst_removed"></label>
        <small>@lang('Select the') @{{ `@lang('Tons Sst Removed')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tons_sst_removed">
            <p class="m-b-0" v-for="error in dataErrors.tons_sst_removed">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Operational Claim Index Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operational_claim_index', trans('Operational Claim Index').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- operational_claim_index switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="operational_claim_index" id="operational_claim_index"  v-model="dataForm.operational_claim_index">
        <label for="operational_claim_index"></label>
        <small>@lang('Select the') @{{ `@lang('Operational Claim Index')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.operational_claim_index">
            <p class="m-b-0" v-for="error in dataErrors.operational_claim_index">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Commercial Claim Index Field -->
<div class="form-group row m-b-15">
    {!! Form::label('commercial_claim_index', trans('Commercial Claim Index').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- commercial_claim_index switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="commercial_claim_index" id="commercial_claim_index"  v-model="dataForm.commercial_claim_index">
        <label for="commercial_claim_index"></label>
        <small>@lang('Select the') @{{ `@lang('Commercial Claim Index')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.commercial_claim_index">
            <p class="m-b-0" v-for="error in dataErrors.commercial_claim_index">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Efficiency Collection Field -->
<div class="form-group row m-b-15">
    {!! Form::label('efficiency_collection', trans('Efficiency Collection').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- efficiency_collection switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="efficiency_collection" id="efficiency_collection"  v-model="dataForm.efficiency_collection">
        <label for="efficiency_collection"></label>
        <small>@lang('Select the') @{{ `@lang('Efficiency Collection')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.efficiency_collection">
            <p class="m-b-0" v-for="error in dataErrors.efficiency_collection">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Via Aqueduct Sewerage Rates Field -->
<div class="form-group row m-b-15">
    {!! Form::label('via_aqueduct_sewerage_rates', trans('Via Aqueduct Sewerage Rates').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- via_aqueduct_sewerage_rates switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="via_aqueduct_sewerage_rates" id="via_aqueduct_sewerage_rates"  v-model="dataForm.via_aqueduct_sewerage_rates">
        <label for="via_aqueduct_sewerage_rates"></label>
        <small>@lang('Select the') @{{ `@lang('Via Aqueduct Sewerage Rates')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.via_aqueduct_sewerage_rates">
            <p class="m-b-0" v-for="error in dataErrors.via_aqueduct_sewerage_rates">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Cleaning Fee Resources Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cleaning_fee_resources', trans('Cleaning Fee Resources').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- cleaning_fee_resources switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="cleaning_fee_resources" id="cleaning_fee_resources"  v-model="dataForm.cleaning_fee_resources">
        <label for="cleaning_fee_resources"></label>
        <small>@lang('Select the') @{{ `@lang('Cleaning Fee Resources')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cleaning_fee_resources">
            <p class="m-b-0" v-for="error in dataErrors.cleaning_fee_resources">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Regalias Field -->
<div class="form-group row m-b-15">
    {!! Form::label('regalias', trans('Regalias').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- regalias switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="regalias" id="regalias"  v-model="dataForm.regalias">
        <label for="regalias"></label>
        <small>@lang('Select the') @{{ `@lang('Regalias')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.regalias">
            <p class="m-b-0" v-for="error in dataErrors.regalias">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  General Participation System Field -->
<div class="form-group row m-b-15">
    {!! Form::label('general_participation_system', trans('General Participation System').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- general_participation_system switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="general_participation_system" id="general_participation_system"  v-model="dataForm.general_participation_system">
        <label for="general_participation_system"></label>
        <small>@lang('Select the') @{{ `@lang('General Participation System')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.general_participation_system">
            <p class="m-b-0" v-for="error in dataErrors.general_participation_system">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Decentralized Entity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('decentralized_entity', trans('Decentralized Entity').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- decentralized_entity switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="decentralized_entity" id="decentralized_entity"  v-model="dataForm.decentralized_entity">
        <label for="decentralized_entity"></label>
        <small>@lang('Select the') @{{ `@lang('Decentralized Entity')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.decentralized_entity">
            <p class="m-b-0" v-for="error in dataErrors.decentralized_entity">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Capital Contributed Field -->
<div class="form-group row m-b-15">
    {!! Form::label('capital_contributed', trans('Capital Contributed').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- capital_contributed switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="capital_contributed" id="capital_contributed"  v-model="dataForm.capital_contributed">
        <label for="capital_contributed"></label>
        <small>@lang('Select the') @{{ `@lang('Capital Contributed')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.capital_contributed">
            <p class="m-b-0" v-for="error in dataErrors.capital_contributed">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Contributed Capital Official Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contributed_capital_official', trans('Contributed Capital Official').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- contributed_capital_official switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="contributed_capital_official" id="contributed_capital_official"  v-model="dataForm.contributed_capital_official">
        <label for="contributed_capital_official"></label>
        <small>@lang('Select the') @{{ `@lang('Contributed Capital Official')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.contributed_capital_official">
            <p class="m-b-0" v-for="error in dataErrors.contributed_capital_official">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Capital Contributions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('capital_contributions', trans('Capital Contributions').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- capital_contributions switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="capital_contributions" id="capital_contributions"  v-model="dataForm.capital_contributions">
        <label for="capital_contributions"></label>
        <small>@lang('Select the') @{{ `@lang('Capital Contributions')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.capital_contributions">
            <p class="m-b-0" v-for="error in dataErrors.capital_contributions">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Third Party Contributions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('third_party_contributions', trans('Third Party Contributions').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- third_party_contributions switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="third_party_contributions" id="third_party_contributions"  v-model="dataForm.third_party_contributions">
        <label for="third_party_contributions"></label>
        <small>@lang('Select the') @{{ `@lang('Third Party Contributions')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.third_party_contributions">
            <p class="m-b-0" v-for="error in dataErrors.third_party_contributions">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  National Debt Field -->
<div class="form-group row m-b-15">
    {!! Form::label('national_debt', trans('National Debt').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- national_debt switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="national_debt" id="national_debt"  v-model="dataForm.national_debt">
        <label for="national_debt"></label>
        <small>@lang('Select the') @{{ `@lang('National Debt')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.national_debt">
            <p class="m-b-0" v-for="error in dataErrors.national_debt">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Foreign Debt Field -->
<div class="form-group row m-b-15">
    {!! Form::label('foreign_debt', trans('Foreign Debt').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- foreign_debt switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="foreign_debt" id="foreign_debt"  v-model="dataForm.foreign_debt">
        <label for="foreign_debt"></label>
        <small>@lang('Select the') @{{ `@lang('Foreign Debt')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.foreign_debt">
            <p class="m-b-0" v-for="error in dataErrors.foreign_debt">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Social Field -->
<div class="form-group row m-b-15">
    {!! Form::label('social', trans('Social').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('social', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.social }", 'v-model' => 'dataForm.social', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Social')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.social">
            <p class="m-b-0" v-for="error in dataErrors.social">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Environmental Field -->
<div class="form-group row m-b-15">
    {!! Form::label('environmental', trans('Environmental').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('environmental', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.environmental }", 'v-model' => 'dataForm.environmental', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Environmental')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.environmental">
            <p class="m-b-0" v-for="error in dataErrors.environmental">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Economical Field -->
<div class="form-group row m-b-15">
    {!! Form::label('economical', trans('Economical').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('economical', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.economical }", 'v-model' => 'dataForm.economical', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Economical')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.economical">
            <p class="m-b-0" v-for="error in dataErrors.economical">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Jobs To Generate Field -->
<div class="form-group row m-b-15">
    {!! Form::label('jobs_to_generate', trans('Jobs To Generate').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('jobs_to_generate', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.jobs_to_generate }", 'v-model' => 'dataForm.jobs_to_generate', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Jobs To Generate')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.jobs_to_generate">
            <p class="m-b-0" v-for="error in dataErrors.jobs_to_generate">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Requires Environmental License Field -->
<div class="form-group row m-b-15">
    {!! Form::label('requires_environmental_license', trans('Requires Environmental License').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('requires_environmental_license', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.requires_environmental_license }", 'v-model' => 'dataForm.requires_environmental_license', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Requires Environmental License')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.requires_environmental_license">
            <p class="m-b-0" v-for="error in dataErrors.requires_environmental_license">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- License Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('license_number', trans('License Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('license_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.license_number }", 'v-model' => 'dataForm.license_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('License Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.license_number">
            <p class="m-b-0" v-for="error in dataErrors.license_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Expedition Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('expedition_date', trans('Expedition Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('expedition_date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.expedition_date }", 'id' => 'expedition_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.expedition_date', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Expedition Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.expedition_date">
            <p class="m-b-0" v-for="error in dataErrors.expedition_date">@{{ error }}</p>
        </div>
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#expedition_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!--  State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- state switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="state" id="state"  v-model="dataForm.state">
        <label for="state"></label>
        <small>@lang('Select the') @{{ `@lang('State')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.state">
            <p class="m-b-0" v-for="error in dataErrors.state">@{{ error }}</p>
        </div>
    </div>
</div>

