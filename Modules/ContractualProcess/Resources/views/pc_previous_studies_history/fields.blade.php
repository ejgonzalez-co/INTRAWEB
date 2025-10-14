<!-- Organizational Unit Field -->
<div class="form-group row m-b-15">
    {!! Form::label('organizational_unit', trans('Organizational Unit').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('organizational_unit', null, ['class' => 'form-control', 'v-model' => 'dataForm.organizational_unit', 'required' => true]) !!}
    </div>
</div>

<!-- Program Field -->
<div class="form-group row m-b-15">
    {!! Form::label('program', trans('Program').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('program', null, ['class' => 'form-control', 'v-model' => 'dataForm.program', 'required' => true]) !!}
    </div>
</div>

<!-- Subprogram Field -->
<div class="form-group row m-b-15">
    {!! Form::label('subprogram', trans('Subprogram').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('subprogram', null, ['class' => 'form-control', 'v-model' => 'dataForm.subprogram', 'required' => true]) !!}
    </div>
</div>

<!-- Project Field -->
<div class="form-group row m-b-15">
    {!! Form::label('project', trans('Project').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('project', null, ['class' => 'form-control', 'v-model' => 'dataForm.project', 'required' => true]) !!}
    </div>
</div>

<!-- Lineproject Field -->
<div class="form-group row m-b-15">
    {!! Form::label('lineproject', trans('Lineproject').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('lineproject', null, ['class' => 'form-control', 'v-model' => 'dataForm.lineproject', 'required' => true]) !!}
    </div>
</div>

<!-- Justification Tecnic Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('justification_tecnic_description', trans('Justification Tecnic Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('justification_tecnic_description', null, ['class' => 'form-control', 'v-model' => 'dataForm.justification_tecnic_description', 'required' => true]) !!}
    </div>
</div>

<!-- Justification Tecnic Approach Field -->
<div class="form-group row m-b-15">
    {!! Form::label('justification_tecnic_approach', trans('Justification Tecnic Approach').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('justification_tecnic_approach', null, ['class' => 'form-control', 'v-model' => 'dataForm.justification_tecnic_approach', 'required' => true]) !!}
    </div>
</div>

<!-- Justification Tecnic Modality Field -->
<div class="form-group row m-b-15">
    {!! Form::label('justification_tecnic_modality', trans('Justification Tecnic Modality').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('justification_tecnic_modality', null, ['class' => 'form-control', 'v-model' => 'dataForm.justification_tecnic_modality', 'required' => true]) !!}
    </div>
</div>

<!-- Fundaments Juridics Field -->
<div class="form-group row m-b-15">
    {!! Form::label('fundaments_juridics', trans('Fundaments Juridics').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('fundaments_juridics', null, ['class' => 'form-control', 'v-model' => 'dataForm.fundaments_juridics', 'required' => true]) !!}
    </div>
</div>

<!-- Imputation Budget Rubro Field -->
<div class="form-group row m-b-15">
    {!! Form::label('imputation_budget_rubro', trans('Imputation Budget Rubro').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('imputation_budget_rubro', null, ['class' => 'form-control', 'v-model' => 'dataForm.imputation_budget_rubro', 'required' => true]) !!}
    </div>
</div>

<!-- Imputation Budget Interventor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('imputation_budget_interventor', trans('Imputation Budget Interventor').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('imputation_budget_interventor', null, ['class' => 'form-control', 'v-model' => 'dataForm.imputation_budget_interventor', 'required' => true]) !!}
    </div>
</div>

<!-- Determination Object Field -->
<div class="form-group row m-b-15">
    {!! Form::label('determination_object', trans('Determination Object').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('determination_object', null, ['class' => 'form-control', 'v-model' => 'dataForm.determination_object', 'required' => true]) !!}
    </div>
</div>

<!-- Determination Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('determination_value', trans('Determination Value').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('determination_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.determination_value', 'required' => true]) !!}
    </div>
</div>

<!-- Determination Time Limit Field -->
<div class="form-group row m-b-15">
    {!! Form::label('determination_time_limit', trans('Determination Time Limit').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('determination_time_limit', null, ['class' => 'form-control', 'v-model' => 'dataForm.determination_time_limit', 'required' => true]) !!}
    </div>
</div>

<!-- Determination Form Pay Field -->
<div class="form-group row m-b-15">
    {!! Form::label('determination_form_pay', trans('Determination Form Pay').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('determination_form_pay', null, ['class' => 'form-control', 'v-model' => 'dataForm.determination_form_pay', 'required' => true]) !!}
    </div>
</div>

<!-- Obligation Principal Field -->
<div class="form-group row m-b-15">
    {!! Form::label('obligation_principal', trans('Obligation Principal').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('obligation_principal', null, ['class' => 'form-control', 'v-model' => 'dataForm.obligation_principal', 'required' => true]) !!}
    </div>
</div>

<!--  Obligation Principal Documentation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('obligation_principal_documentation', trans('Obligation Principal Documentation').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- obligation_principal_documentation switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="obligation_principal_documentation" id="obligation_principal_documentation"  v-model="dataForm.obligation_principal_documentation">
        <label for="obligation_principal_documentation"></label>
    </div>
</div>


<!--  Situation Estates Public Field -->
<div class="form-group row m-b-15">
    {!! Form::label('situation_estates_public', trans('Situation Estates Public').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- situation_estates_public switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="situation_estates_public" id="situation_estates_public"  v-model="dataForm.situation_estates_public">
        <label for="situation_estates_public"></label>
    </div>
</div>


<!-- Situation Estates Public Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('situation_estates_public_observation', trans('Situation Estates Public Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('situation_estates_public_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.situation_estates_public_observation', 'required' => true]) !!}
    </div>
</div>

<!--  Situation Estates Private Field -->
<div class="form-group row m-b-15">
    {!! Form::label('situation_estates_private', trans('Situation Estates Private').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- situation_estates_private switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="situation_estates_private" id="situation_estates_private"  v-model="dataForm.situation_estates_private">
        <label for="situation_estates_private"></label>
    </div>
</div>


<!-- Situation Estates Private Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('situation_estates_private_observation', trans('Situation Estates Private Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('situation_estates_private_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.situation_estates_private_observation', 'required' => true]) !!}
    </div>
</div>

<!--  Solution Servitude Field -->
<div class="form-group row m-b-15">
    {!! Form::label('solution_servitude', trans('Solution Servitude').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- solution_servitude switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="solution_servitude" id="solution_servitude"  v-model="dataForm.solution_servitude">
        <label for="solution_servitude"></label>
    </div>
</div>


<!-- Solution Servitude Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('solution_servitude_observation', trans('Solution Servitude Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('solution_servitude_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.solution_servitude_observation', 'required' => true]) !!}
    </div>
</div>

<!--  Solution Owner Field -->
<div class="form-group row m-b-15">
    {!! Form::label('solution_owner', trans('Solution Owner').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- solution_owner switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="solution_owner" id="solution_owner"  v-model="dataForm.solution_owner">
        <label for="solution_owner"></label>
    </div>
</div>


<!-- Solution Owner Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('solution_owner_observation', trans('Solution Owner Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('solution_owner_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.solution_owner_observation', 'required' => true]) !!}
    </div>
</div>

<!-- Process Concilation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_concilation', trans('Process Concilation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('process_concilation', null, ['class' => 'form-control', 'v-model' => 'dataForm.process_concilation', 'required' => true]) !!}
    </div>
</div>

<!--  Process Licenses Environment Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_environment', trans('Process Licenses Environment').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_environment switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_environment" id="process_licenses_environment"  v-model="dataForm.process_licenses_environment">
        <label for="process_licenses_environment"></label>
    </div>
</div>


<!--  Process Licenses Beach Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_beach', trans('Process Licenses Beach').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_beach switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_beach" id="process_licenses_beach"  v-model="dataForm.process_licenses_beach">
        <label for="process_licenses_beach"></label>
    </div>
</div>


<!--  Process Licenses Forestal Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_forestal', trans('Process Licenses Forestal').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_forestal switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_forestal" id="process_licenses_forestal"  v-model="dataForm.process_licenses_forestal">
        <label for="process_licenses_forestal"></label>
    </div>
</div>


<!--  Process Licenses Guadua Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_guadua', trans('Process Licenses Guadua').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_guadua switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_guadua" id="process_licenses_guadua"  v-model="dataForm.process_licenses_guadua">
        <label for="process_licenses_guadua"></label>
    </div>
</div>


<!--  Process Licenses Tree Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_tree', trans('Process Licenses Tree').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_tree switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_tree" id="process_licenses_tree"  v-model="dataForm.process_licenses_tree">
        <label for="process_licenses_tree"></label>
    </div>
</div>


<!--  Process Licenses Road Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_road', trans('Process Licenses Road').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_road switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_road" id="process_licenses_road"  v-model="dataForm.process_licenses_road">
        <label for="process_licenses_road"></label>
    </div>
</div>


<!--  Process Licenses Demolition Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_demolition', trans('Process Licenses Demolition').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_demolition switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_demolition" id="process_licenses_demolition"  v-model="dataForm.process_licenses_demolition">
        <label for="process_licenses_demolition"></label>
    </div>
</div>


<!--  Process Licenses Tree Urban Field -->
<div class="form-group row m-b-15">
    {!! Form::label('process_licenses_tree_urban', trans('Process Licenses Tree Urban').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- process_licenses_tree_urban switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="process_licenses_tree_urban" id="process_licenses_tree_urban"  v-model="dataForm.process_licenses_tree_urban">
        <label for="process_licenses_tree_urban"></label>
    </div>
</div>


<!-- Tipification Danger Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tipification_danger', trans('Tipification Danger').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('tipification_danger', null, ['class' => 'form-control', 'v-model' => 'dataForm.tipification_danger', 'required' => true]) !!}
    </div>
</div>

<!-- Indication Danger Precontractual Field -->
<div class="form-group row m-b-15">
    {!! Form::label('indication_danger_precontractual', trans('Indication Danger Precontractual').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('indication_danger_precontractual', null, ['class' => 'form-control', 'v-model' => 'dataForm.indication_danger_precontractual', 'required' => true]) !!}
    </div>
</div>

<!-- Indication Danger Ejecution Field -->
<div class="form-group row m-b-15">
    {!! Form::label('indication_danger_ejecution', trans('Indication Danger Ejecution').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('indication_danger_ejecution', null, ['class' => 'form-control', 'v-model' => 'dataForm.indication_danger_ejecution', 'required' => true]) !!}
    </div>
</div>

<!--  State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- state switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="state" id="state"  v-model="dataForm.state">
        <label for="state"></label>
    </div>
</div>


<!-- Date Project Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_project', trans('Date Project').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('date_project', null, ['class' => 'form-control', 'id' => 'date_project',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_project', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#date_project').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('type', null, ['class' => 'form-control', 'v-model' => 'dataForm.type', 'required' => true]) !!}
    </div>
</div>

<!-- Users Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_name', trans('Users Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('users_name', null, ['class' => 'form-control', 'v-model' => 'dataForm.users_name', 'required' => true]) !!}
    </div>
</div>

<!-- Pc Previous Studies Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('pc_previous_studies_id', trans('Pc Previous Studies Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('pc_previous_studies_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.pc_previous_studies_id', 'required' => true]) !!}
    </div>
</div>
