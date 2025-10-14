<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general del proyecto:</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- Code Bppiepa Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('code_bppiepa', trans('Code Bppiepa').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('code_bppiepa', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.code_bppiepa }", 'v-model' => 'dataForm.code_bppiepa', 'required' => true]) !!}
                        <small>@lang('Enter the') el @{{ `@lang('Code Bppiepa')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.code_bppiepa">
                            <p class="m-b-0" v-for="error in dataErrors.code_bppiepa">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Pc Validities Id Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('pc_validities_id', trans('Validity').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="pc_validities_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-validities"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') la @{{ `@lang('Validity')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.pc_validities_id">
                            <p class="m-b-0" v-for="error in dataErrors.pc_validities_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-6">
                <!-- Date Presentation Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('date_presentation', trans('Date Presentation').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <date-picker
                            :value="dataForm"
                            name-field="date_presentation"
                            :input-props="{required: true}"
                        >
                        </date-picker>
                        <small>@lang('Select the') la @{{ `@lang('Date Presentation')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.date_presentation">
                            <p class="m-b-0" v-for="error in dataErrors.date_presentation">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="col-md-6">
                <!-- Pc Name Projects Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('pc_name_projects_id', trans('Project Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="pc_name_projects_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-name-projects"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('Project Name')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.pc_name_projects_id">
                            <p class="m-b-0" v-for="error in dataErrors.pc_name_projects_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Dependencias Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('dependencias_id', trans('Submanagement or Direction').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="dependencias_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') la @{{ `@lang('Submanagement or Direction')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
                            <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Pc Management Unit Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('pc_management_unit_id', trans('Management Unit').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="pc_management_unit_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-management-unit"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') @{{ `@lang('Management Unit')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.pc_management_unit_id">
                            <p class="m-b-0" v-for="error in dataErrors.pc_management_unit_id">@{{ error }}</p>
                        </div>
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
        <h4 class="panel-title"><strong>Coherencia del proyecto con el plan de desarrollo municipal y otros documentos de planificación:</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

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
                            name-resource="get-constants-active/investment_technical_municipal_development_plan"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('Municipal Development Plan')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.municipal_development_plan">
                            <p class="m-b-0" v-for="error in dataErrors.municipal_development_plan">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Period Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('period', trans('Period').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="switcher col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="period"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants-active/investment_technical_period"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('Period')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.period">
                            <p class="m-b-0" v-for="error in dataErrors.period">@{{ error }}</p>
                        </div>
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
                            name-resource="get-constants-active/investment_technical_strategic_line"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') la @{{ `@lang('Strategic Line')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.strategic_line">
                            <p class="m-b-0" v-for="error in dataErrors.strategic_line">@{{ error }}</p>
                        </div>
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
                            name-resource="get-constants-active/investment_technical_sector"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') @{{ `@lang('Sector')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.sector">
                            <p class="m-b-0" v-for="error in dataErrors.sector">@{{ error }}</p>
                        </div>
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
                            name-resource="get-constants-active/investment_technical_program"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('Program')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.program">
                            <p class="m-b-0" v-for="error in dataErrors.program">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Subprogram Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('subprogram', trans('Subprogram').':', ['class' => 'col-form-label col-md-3']) !!}
                    <!-- subprogram switcher -->
                    <div class="switcher col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="subprogram"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants-active/investment_technical_subprogram"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('Subprogram')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.subprogram">
                            <p class="m-b-0" v-for="error in dataErrors.subprogram">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Pc Project Lines Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('pc_project_lines_id', trans('Project Line').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="pc_project_lines_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-project-lines"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Enter the') la @{{ `@lang('Project Line')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.pc_project_lines_id">
                            <p class="m-b-0" v-for="error in dataErrors.pc_project_lines_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Pc Poir Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('pc_poir_id', trans('Identification Project').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="pc_poir_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-poir"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>Identificación del proyecto con el Plan de Obras e Inversiones Regulado (Aplica para proyectos de Acueducto y Alcantarillado) según el SUI.</small>
                        <div class="invalid-feedback" v-if="dataErrors.pc_poir_id">
                            <p class="m-b-0" v-for="error in dataErrors.pc_poir_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Other Planning Documents Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('other_planning_documents', trans('Other Planning Documents').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="other_planning_documents"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants-active/iv_other_planning_documents"
                            :value="dataForm"
                            :is-multiple="true">
                        </select-check>
                        <small>Seleccione los documentos presionando control (ctrl)</small>
                        <div class="invalid-feedback" v-if="dataErrors.other_planning_documents">
                            <p class="m-b-0" v-for="error in dataErrors.other_planning_documents">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="(dataForm.other_planning_documents? dataForm.other_planning_documents.includes(6): '') || (dataForm.other_planning_documents? dataForm.other_planning_documents.includes('6'): '')" class="col-md-6">
                <!-- Which Other Document Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('which_other_document', trans('¿Cuál?').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('which_other_document', null, ['class' => 'form-control', 'v-model' => 'dataForm.which_other_document', ':key' => 'keyRefresh', 'required' => true]) !!}
                        <small>@lang('Enter the') el @{{ `@lang('Which Other Document')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.which_other_document">
                            <p class="m-b-0" v-for="error in dataErrors.which_other_document">@{{ error }}</p>
                        </div>
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
                    {!! Form::label('description_problem_need', trans('Description Problem Need').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('description_problem_need', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description_problem_need }", 'v-model' => 'dataForm.description_problem_need', 'required' => true]) !!}
                        <small>Describa en forma resumida el "problema" o "necesidad" que se pretende resolver con la ejecución del proyecto.</small>
                        <div class="invalid-feedback" v-if="dataErrors.description_problem_need">
                            <p class="m-b-0" v-for="error in dataErrors.description_problem_need">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Direct Causes Need Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('direct_causes_problems', trans('Enumere causas directas del problema').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <dynamic-list
                            label-button-add="Agregar ítem a la lista"
                            :data-list.sync="dataForm.direct_causes_problems"
                            class-table="table-hover text-inverse table-bordered"
                            :data-list-options="[
                                {label:'Causa directa', name:'name', isShow: true},
                            ]">
                            <template #fields="scope">
                                <input class="form-control" required type="text" v-model="scope.dataForm.name" placeholder="Causa directa">
                            </template>
                        </dynamic-list>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Indirect Causes Need Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('indirect_causes_problems', trans('Enumere causas indirectas del problema').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <dynamic-list
                            label-button-add="Agregar ítem a la lista"
                            :data-list.sync="dataForm.indirect_causes_problems"
                            class-table="table-hover text-inverse table-bordered"
                            :data-list-options="[
                                {label:'Causa indirecta', name:'name', isShow: true},
                            ]">
                            <template #fields="scope">
                                <input class="form-control" required type="text" v-model="scope.dataForm.name" placeholder="Causa indirecta">
                            </template>
                        </dynamic-list>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Direct Effects Need Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('direct_effects_problems', trans('Enumere efectos directos del problema').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <dynamic-list
                            label-button-add="Agregar ítem a la lista"
                            :data-list.sync="dataForm.direct_effects_problems"
                            class-table="table-hover text-inverse table-bordered"
                            :data-list-options="[
                                {label:'Efecto directo', name:'name', isShow: true},
                            ]">
                            <template #fields="scope">
                                <input class="form-control" required type="text" v-model="scope.dataForm.name" placeholder="Efecto directo">
                            </template>
                        </dynamic-list>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Indirect Effects Need Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('indirect_effects_problems', trans('Enumere efectos indirectos del problema').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <dynamic-list
                            label-button-add="Agregar ítem a la lista"
                            :data-list.sync="dataForm.indirect_effects_problems"
                            class-table="table-hover text-inverse table-bordered"
                            :data-list-options="[
                                {label:'Efecto indirecto', name:'name', isShow: true},
                            ]">
                            <template #fields="scope">
                                <input class="form-control" required type="text" v-model="scope.dataForm.name" placeholder="Efecto indirecto">
                            </template>
                        </dynamic-list>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
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
            </div>

            <div class="col-md-12">
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
            </div>

            <div class="col-md-12">
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
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Área de influencia del proyecto:</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <dynamic-list
                label-button-add="Agregar ítem a la lista"
                :data-list.sync="dataForm.project_areas_influences"
                class-table="table-hover text-inverse table-bordered"
                :data-list-options="[
                    {label:'Ciudad', name:'cities_id', isShow: true, refList: 'cities_idRef'},
                    {label:'Área de cobertura del servicio', name:'service_coverage', isShow: true, refList: 'service_coverageRef'},
                    {label:'Barrio', name:'neighborhood', isShow: true},
                    {label:'Comuna', name:'commune', isShow: true},
                    {label:'Número de habitantes', name:'number_inhabitants', isShow: true},
                ]"
                class-container="col-md-12"
                >
                <template #fields="scope">
                    <div class="row">

                        <div class="col-md-6">
                            <!-- Cities Id Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('cities_id', 'Municipios:', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    <select-check
                                        css-class="form-control"
                                        name-field="cities_id"
                                        reduce-label="name"
                                        reduce-key="id"
                                        name-resource="get-cities-by-department/2874"
                                        :value="scope.dataForm"
                                        ref-select-check="cities_idRef"
                                        :is-required="true">
                                    </select-check>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!--  Service Coverage Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('service_coverage', trans('Service Coverage').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    <select-check
                                        css-class="form-control"
                                        name-field="service_coverage"
                                        reduce-label="name"
                                        reduce-key="id"
                                        name-resource="get-constants-active/investment_technical_service_coverage"
                                        :value="scope.dataForm"
                                        ref-select-check="service_coverageRef"
                                        :is-required="true">
                                    </select-check>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Neighborhood Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('neighborhood', 'Barrio/Vereda:', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('neighborhood', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.neighborhood', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Commune Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('commune', trans('Commune').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('commune', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.commune', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Number Inhabitants Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('number_inhabitants', trans('Number Inhabitants').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    {!! Form::number('number_inhabitants', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.number_inhabitants', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>

                    </div>

                </template>
            </dynamic-list>
        </div>
    </div>
    <!-- end panel-body -->
</div>
