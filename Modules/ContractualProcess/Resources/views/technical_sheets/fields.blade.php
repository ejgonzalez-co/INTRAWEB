<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general del proyecto:</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <!-- Code Bppiepa Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('code_bppiepa', trans('Code Bppiepa').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('code_bppiepa', null, ['class' => 'form-control', 'v-model' => 'dataForm.code_bppiepa', 'required' => true]) !!}
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Validity Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('validity', trans('Validity').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="validity"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants-active/technical_sheets_validity"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Project Name Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('project_name', trans('Project Name').':', ['class' => 'col-form-label col-md-2']) !!}
                    <div class="col-md-10">
                        <select-check
                            css-class="form-control"
                            name-field="project_name"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants-active/technical_sheets_project_name"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Dependencias Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('dependencias_id', trans('Submanagement or Direction').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::number('dependencias_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.dependencias_id', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Pc Management Unit Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('pc_management_unit_id', trans('Management Unit').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::number('pc_management_unit_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.pc_management_unit_id', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Municipal Development Plan Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('municipal_development_plan', trans('Municipal Development Plan').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="municipal_development_plan"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Period Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('period', trans('Period').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="period"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Strategic Line Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('strategic_line', trans('Strategic Line').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="strategic_line"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Sector Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('sector', trans('Sector').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="sector"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Program Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('program', trans('Program').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="program"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Subprogram Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('subprogram', trans('Subprogram').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="subprogram"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Project Line Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('project_line', trans('Project Line').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="project_line"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Identification Project Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('identification_project', trans('Identification Project').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="identification_project"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>Identificación del proyecto con el Plan de Obras e Inversiones Regulado (Aplica para proyectos de Acueducto y Alcantarillado) según el SUI.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Identificación del problema o necesidad:</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            
            <div class="col-md-12">
                <!-- Description Problem Need Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('description_problem_need', trans('Description Problem Need').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('description_problem_need', null, ['class' => 'form-control', 'v-model' => 'dataForm.description_problem_need', 'required' => true]) !!}
                        <small>Describa en forma resumida el "problema" o "necesidad" que se pretende resolver con la ejecución del proyecto.</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Project Description Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('project_description', trans('Project Description').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('project_description', null, ['class' => 'form-control', 'v-model' => 'dataForm.project_description', 'required' => true]) !!}
                        <small>Describa detalladamente el proyecto.</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Justification Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('justification', trans('Justification').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('justification', null, ['class' => 'form-control', 'v-model' => 'dataForm.justification', 'required' => true]) !!}
                        <small>Justificación (Técnica, Normativa, Ambiental, …)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Background Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('background', trans('Background').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('background', null, ['class' => 'form-control', 'v-model' => 'dataForm.background', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Cities Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('cities_id', trans('City').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="cities_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Service Coverage Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('service_coverage', trans('Service Coverage').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="service_coverage"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/state_knowledge_tic"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>
               
            <div class="col-md-6">
                <!-- Neighborhood Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('neighborhood', trans('Neighborhood').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('neighborhood', null, ['class' => 'form-control', 'v-model' => 'dataForm.neighborhood', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Commune Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('commune', trans('Commune').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('commune', null, ['class' => 'form-control', 'v-model' => 'dataForm.commune', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                 <!-- Number Inhabitants Field -->
                 <div class="form-group row m-b-15">
                    {!! Form::label('number_inhabitants', trans('Number Inhabitants').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::number('number_inhabitants', null, ['class' => 'form-control', 'v-model' => 'dataForm.number_inhabitants', 'required' => true]) !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>