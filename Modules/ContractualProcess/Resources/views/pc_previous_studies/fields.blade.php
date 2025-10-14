<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- Type Document Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        <select v-model="dataForm.type" name="type" required="required" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Funcionamiento">Funcionamiento</option>
                            <option value="Proyecto de inversión">Proyecto de inversión</option>
                            <option value="Funcionamiento e inversión">Funcionamiento e inversión</option>

                            
                        </select>
                        <small>Seleccione el tipo estudio previo</small>
                    </div>
                </div>
            </div>

            <div v-if="dataForm.type=='Proyecto de inversión' || dataForm.type=='Funcionamiento e inversión'" class="col-md-12">

                    <div class="form-group row m-b-15">
                        {!! Form::label('process', trans('Proceso').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">

                            @if (!Auth::user()->hasRole('PC Líder de proceso'))
                                <select-check 
                                    css-class="form-control"
                                    name-field="process"
                                    reduce-label="name_process"
                                    reduce-key="id"
                                    :name-resource="'get-pc-previous-studies-needs?id_leaders='+dataForm.leaders_id"
                                    :value="dataForm"
                                    :is-required="true">
                                </select-check>
                            @else
                                <select-check
                                    css-class="form-control"
                                    name-field="process"
                                    reduce-label="name_process"
                                    reduce-key="id"
                                    name-resource="get-pc-previous-studies-needs"
                                    :value="dataForm"
                                    :is-required="true">
                                </select-check>
                            @endif
                            

                           

                        </div>
                    </div>

                    <div class="form-group row m-b-15" v-if="dataForm.process">
                        {!! Form::label('projects', 'Proyectos:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">

                        {!! Form::text('project_or_needs', null, ['class' => 'form-control', 'v-model' => 'dataForm.project_or_needs', 'required' => true]) !!}
                        <small>Ingrese el nombre del proyecto en la caja</small>
                            {{-- <add-list-autocomplete  
                                :value="dataForm"
                                name-prop="description_problem_need"
                                name-field-autocomplete="search_field"
                                name-field="projects"
                                :name-resource="'get-pc-previous-studies-needs-only?id_proccess='+dataForm.process"
                                name-options-list="projects_list"
                                :name-labels-display="['name_projects.name','description_problem_need']"
                                name-key="id"
                                help="Ingrese el nombre del proyecto en la caja y seleccione para agregar a la lista"
                                >
                            </add-list-autocomplete>  --}}

                        </div>
                    </div>   
            </div>


            <div v-if="dataForm.type=='Funcionamiento'" class="col-md-12">
                
                <div class="form-group row m-b-15">
                    {!! Form::label('process', trans('Proceso').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">

                        @if (!Auth::user()->hasRole('PC Líder de proceso'))
                                <select-check 
                                    css-class="form-control"
                                    name-field="process"
                                    reduce-label="name_process"
                                    reduce-key="id"
                                    :name-resource="'get-pc-previous-studies-needs?id_leaders='+dataForm.leaders_id"
                                    :value="dataForm"
                                    :is-required="true">
                                </select-check>
                            @else
                                <select-check
                                    css-class="form-control"
                                    name-field="process"
                                    reduce-label="name_process"
                                    reduce-key="id"
                                    name-resource="get-pc-previous-studies-needs"
                                    :value="dataForm"
                                    :is-required="true">
                                </select-check>
                            @endif
                            


                    </div>
                </div>

                <div class="form-group row m-b-15" v-if="dataForm.process">
                    {!! Form::label('need', 'Necesidad:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">

                        {!! Form::text('project_or_needs', null, ['class' => 'form-control', 'v-model' => 'dataForm.project_or_needs', 'required' => true]) !!}
                        <small>Ingrese el nombre de la  necesidad</small>

                        {{-- <select-check
                            css-class="form-control"
                            name-field="pc_functioning_needs_id"
                            reduce-label="description"
                            reduce-key="id"
                            :name-resource="'get-pc-previous-studies-needs-functioning?id_proccess='+dataForm.process"
                            :value="dataForm"
                            :is-required="true">
                        </select-check> --}}

                    </div>
                </div>   

                <div class="form-group row m-b-15">
                    {!! Form::label('Programa', 'Programa:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">

                        {!! Form::text('program', null, ['class' => 'form-control', 'v-model' => 'dataForm.program', 'required' => true]) !!}
                        <small>Ingrese el programa</small>

                      

                    </div>
                </div>   

                <div class="form-group row m-b-15">
                    {!! Form::label('Subprograma', 'Subprograma:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">

                        {!! Form::text('subprogram', null, ['class' => 'form-control', 'v-model' => 'dataForm.subprogram', 'required' => true]) !!}
                        <small>Ingrese el subprograma</small>

                      

                    </div>
                </div>   

                <div class="form-group row m-b-15">
                    {!! Form::label('Proyecto', 'Proyecto:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">

                        {!! Form::text('project', null, ['class' => 'form-control', 'v-model' => 'dataForm.project', 'required' => true]) !!}
                        <small>Ingrese el proyecto</small>


                    </div>
                </div>   
        </div>
            
        </div>
    </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>1. Marco Legal (Dirección Jurídica)</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <div class="col-md-12">
                        <p class="text-justify">
                        Empresas Públicas de Armenia ESP., es una Empresa Industrial y Comercial del Estado del Orden Municipal, que regula sus actividades contractuales con fundamento en las siguientes disposiciones:
                        <br><b>Artículo 31 de La ley 142 de 1994:</b> por la cual se establece el Régimen de los Servicios Públicos Domiciliarios y se dictan otras disposiciones.  De otra parte la Ley 689 de 2001: por medio de la cual se modifica parcialmente la Ley 142 de  1994, en cuanto al Artículo 31, preceptúa: ¨ Régimen de la Contratación. Los Contratos que celebren las Entidades Estatales que presten los Servicios Públicos a los que se refiere esta Ley no estarán sujetos a las disposiciones del Estatuto General de la Contratación de la Administración Pública, salvo en lo que la presente Ley disponga otra cosa.
                        
                        </p>
                        <hr>
                        <p class="text-justify"><b>Acuerdo No. 013 de octubre 08 de 2007:</b> en su Artículo 9. Actos y Contratos, establece que los actos y contratos de Empresas Públicas de Armenia ESP., se regirán por las Reglas del Derecho Privado, salvo las excepciones consagradas expresamente en la Constitución Política de Colombia, la Ley 142 de 1994 y las demás disposiciones reglamentarias de los Servicios Públicos Domiciliarios y el actual Manual  de Contratación adoptado mediante el Acuerdo 17 de Julio del 2015 señala en su Artículo 21 el Régimen aplicable a los Contratos suscritos por Empresas Públicas de Armenia ESP. </p>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>2. Justificación técnica (Unidad Organizativa)</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('justification_tecnic_description', trans('2.1 Descripción de la necesidad').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('justification_tecnic_description', null, [ 'rows' => 6, 'class' => 'form-control', 'v-model' => 'dataForm.justification_tecnic_description', 'required' => true ]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('justification_tecnic_approach', trans('2.2 Planteamiento técnico de solución').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('justification_tecnic_approach', null, [ 'rows' => 4, 'class' => 'form-control', 'v-model' => 'dataForm.justification_tecnic_approach', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('justification_tecnic_modality', trans('2.3 Modalidad del Contrato a Celebrar').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('justification_tecnic_modality', null, [ 'rows' => 3, 'class' => 'form-control', 'v-model' => 'dataForm.justification_tecnic_modality', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>
         
         
        </div>
    </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>3. Fundamentos jurídicos de la modalidad de selección <span class="text-red">*</span></strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <div class="col-md-12">
                    {!! Form::textarea('fundaments_juridics', null, [ 'rows' => 5, 'class' => 'form-control', 'v-model' => 'dataForm.fundaments_juridics', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

         
        </div>
    </div>
</div>



<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>4. Imputación presupuestal e interventoría</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('imputation_budget_rubro', trans('4.1 Rubro Presupuestal').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('imputation_budget_rubro', null, ['rows' => 6, 'class' => 'form-control', 'v-model' => 'dataForm.imputation_budget_rubro', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('imputation_budget_interventor', trans('4.2 Interventor y/o Supervisor Sugerido').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('imputation_budget_interventor', null, ['rows' => 2, 'class' => 'form-control', 'v-model' => 'dataForm.imputation_budget_interventor', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>
       
        </div>
    </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>5. Determinación del objeto contractual (Descripción, Unidad Organizativa) </strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('determination_object', trans('Objeto').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::text('determination_object', null, ['class' => 'form-control', 'v-model' => 'dataForm.determination_object', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('determination_time_limit', trans('Plazo de Ejecución').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::text('determination_time_limit', null, ['class' => 'form-control', 'v-model' => 'dataForm.determination_time_limit', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('determination_value', trans('Valor').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    <currency-input
                        v-model="dataForm.determination_value"
                        required="true"
                        :currency="{'prefix': '$ '}"
                        locale="es"
                        class="form-control"
                        :key="keyRefresh"
                        >
                    </currency-input>
                        <small></small>
                    </div>
                </div>
            </div>

    
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('determination_form_pay', trans('Forma de Pago').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::text('determination_form_pay', null, ['class' => 'form-control', 'v-model' => 'dataForm.determination_form_pay', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('obligation_principal', trans('Obligaciones Principales').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('obligation_principal', null, ['rows' => 5, 'class' => 'form-control', 'v-model' => 'dataForm.obligation_principal', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('obligation_principal_documentation', trans('Chequee en caso de que aplique la siguiente obligación al objeto del contrato').':', ['class' => 'col-form-label col-md-4']) !!}
                    <!-- switcher -->
                    <div class="switcher col-md-8 m-t-5">
                        <input type="checkbox" name="obligation_principal_documentation" id="obligation_principal_documentation" v-model="dataForm.obligation_principal_documentation">
                        <label for="obligation_principal_documentation"></label>
                        <small>Verificar la existencia de la documentación en el Sistema de Gestión Integrado de las actividades a ejecutar aplicables al objeto del contrato y en el marco de la Política de Gestión del conocimiento y la innovación de Empresas Públicas de Armenia ESP., documentarla, ajustarla y/o actualizarla teniendo en cuenta las acciones realizadas y las lecciones aprendidas en el desarrollo de las obligaciones contractuales ejecutadas.</small>
                    </div>
                </div>
            </div>

       
        </div>
    </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>6. Situación de predios</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            
            <div class="col-md-4">
                <div class="form-group row m-b-15">
                    {!! Form::label('', trans('La obra a realizar afecta exclusivamente predios públicos').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <!-- switcher -->
                    <div class="switcher col-md-8 m-t-5">
                        <input type="checkbox" name="situation_estates_public" id="situation_estates_public" v-model="dataForm.situation_estates_public">
                        <label for="situation_estates_public"></label>
                        <small></small>
                    </div>
                </div>
            </div>


            <div class="col-md-8">
                <div class="form-group row m-b-15">
                {!! Form::label('situation_estates_public_observation', trans('Observaciones').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8">
                    {!! Form::text('situation_estates_public_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.situation_estates_public_observation', 'required' => false]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group row m-b-15">
                    {!! Form::label('', trans('La obra a realizar afecta un predio privado').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <!-- switcher -->
                    <div class="switcher col-md-8 m-t-5">
                        <input type="checkbox" name="situation_estates_private" id="situation_estates_private" v-model="dataForm.situation_estates_private">
                        <label for="situation_estates_private"></label>
                        <small></small>
                    </div>
                </div>
            </div>


            <div class="col-md-8">
                <div class="form-group row m-b-15">
                {!! Form::label('situation_estates_private_observation', trans('Observaciones').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8">
                    {!! Form::text('situation_estates_private_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.situation_estates_private_observation', 'required' => false]) !!}
                        <small></small>
                    </div>
                </div>
            </div>



            <div class="panel" data-sortable-id="ui-general-1">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Solución planteada y avance de la misma</strong></h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <div class="row">

                        <div class="panel col-md-12" data-sortable-id="ui-general-1">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title"><strong>Afectación a servidumbre</strong></h4>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('', trans('El Predio a intervenir se encuentra afectado por servidumbre').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                            <!-- switcher -->
                                            <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="solution_servitude" id="solution_servitude" v-model="dataForm.solution_servitude">
                                                <label for="solution_servitude"></label>
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                        {!! Form::label('solution_servitude_observation', trans('Observaciones').':', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-8">
                                            {!! Form::text('solution_servitude_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.solution_servitude_observation', 'required' => false]) !!}
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                    </div>


                    <div class="panel col-md-12" data-sortable-id="ui-general-1">
                            <!-- begin panel-heading -->
                            <div class="panel-heading ui-sortable-handle">
                                <h4 class="panel-title"><strong>Solución planteada y avance de la misma</strong></h4>
                            </div>
                            <!-- end panel-heading -->
                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('', trans('Se requiere trámite de conciliación con el propietario').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                            <!-- switcher -->
                                            <div class="switcher col-md-8 m-t-5">
                                                <input type="checkbox" name="solution_owner" id="solution_owner" v-model="dataForm.solution_owner">
                                                <label for="solution_owner"></label>
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                        {!! Form::label('solution_owner_observation', trans('Observaciones').':', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-8">
                                            {!! Form::text('solution_owner_observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.solution_owner_observation', 'required' => false]) !!}
                                                <small></small>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>


                    </div>
                </div>
        </div>


        <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('process_concilation', trans('Trámite de conciliación con el propietario').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('process_concilation', null, ['rows' => 5, 'class' => 'form-control', 'v-model' => 'dataForm.process_concilation', 'required' => false]) !!}
                        <small></small>
                    </div>
                </div>
            </div>


                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th colspan="5">Trámite de licencias y permisos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Trámite</td>
                        <td colspan="2">Si / No</td>
                        <td>Entidad</td>
                        <td>Observaciones</td>
                    </tr>
                    <tr>
                        <td>La obra requiere Licencia Ambiental</td>
                        <td colspan="2"> 
                            <div class="switcher col-md-8 m-t-5">
                                    <input type="checkbox" name="process_licenses_environment" id="process_licenses_environment" v-model="dataForm.process_licenses_environment">
                                    <label for="process_licenses_environment"></label>
                                    <small></small>
                            </div>
                        </td>
                        <td rowspan="5">Corporación Autónoma Regional del Quindío CRQ</td>
                        <td rowspan="5">Ver: www.crq.gov.co<br>Link Tramites</td>
                    </tr>
                    <tr>
                        <td>La obra requiere permiso ocupación de cauces, playas y lechos</td>
                        <td colspan="2">
                            <div class="switcher col-md-8 m-t-5">
                                        <input type="checkbox" name="process_licenses_beach" id="process_licenses_beach" v-model="dataForm.process_licenses_beach">
                                        <label for="process_licenses_beach"></label>
                                        <small></small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>La obra requiere permisos de aprovechamiento forestal</td>
                        <td colspan="2">
                        
                            <div class="switcher col-md-8 m-t-5">
                                        <input type="checkbox" name="process_licenses_forestal" id="process_licenses_forestal" v-model="dataForm.process_licenses_forestal">
                                        <label for="process_licenses_forestal"></label>
                                        <small></small>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>La obra requiere permiso de corte y aprovechamiento de guadua</td>
                        <td colspan="2">
                            
                            <div class="switcher col-md-8 m-t-5">
                                        <input type="checkbox" name="process_licenses_guadua" id="process_licenses_guadua" v-model="dataForm.process_licenses_guadua">
                                        <label for="process_licenses_guadua"></label>
                                        <small></small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>La obra requiere permiso de "Aprovechamiento forestal árboles aislados"</td>
                        <td colspan="2">
                        
                            <div class="switcher col-md-8 m-t-5">
                                        <input type="checkbox" name="process_licenses_tree" id="process_licenses_tree" v-model="dataForm.process_licenses_tree">
                                        <label for="process_licenses_tree"></label>
                                        <small></small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Requiere permiso para la ocupación temporal de carreteras concesionadas</td>
                        <td colspan="2">
                            <div class="switcher col-md-8 m-t-5">
                                        <input type="checkbox" name="process_licenses_road" id="process_licenses_road" v-model="dataForm.process_licenses_road">
                                        <label for="process_licenses_road"></label>
                                        <small></small>
                            </div>
                        
                        </td>
                        <td>Instituto Nacional de Concesiones INCO</td>
                        <td>Ver: www.inco.gov.co<br>Link Trámites</td>
                    </tr>
                    <tr>
                        <td>La obra requiere permiso de corte y demolición de pavimento</td>
                        <td colspan="2">
                            <div class="switcher col-md-8 m-t-5">
                                        <input type="checkbox" name="process_licenses_demolition" id="process_licenses_demolition" v-model="dataForm.process_licenses_demolition">
                                        <label for="process_licenses_demolition"></label>
                                        <small></small>
                            </div>
                        </td>
                        <td>Secretaria de Infraestructura Municipal</td>
                        <td>De requerirlo el contratista debe enviar un oficio que contemple la Ubicación de la Obra</td>
                    </tr>
                    <tr>
                        <td>La obra requiere permiso para intervención del Árbol Urbano</td>
                        <td colspan="2">
                        
                            <div class="switcher col-md-8 m-t-5">
                                        <input type="checkbox" name="process_licenses_tree_urban" id="process_licenses_tree_urban" v-model="dataForm.process_licenses_tree_urban">
                                        <label for="process_licenses_tree_urban"></label>
                                        <small></small>
                            </div>
                        </td>
                        <td>Departamento Administrativo de Planeación</td>
                        <td>Ver: www.armenia.gov.co<br>Link TrámitesVer: www.armenia.gov.co<br>Link Trámites</td>
                    </tr>
                    </tbody>
                    </table>

      

    </div>
</div>
</div>



<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>7. Tipificación, cuantificación y asignación de riesgos previsibles, no asegurables</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">


            <dynamic-list
                label-button-add="Agregar ítem a la lista"
                :data-list.sync="dataForm.pc_previous_studies_tipifications"
                class-table="table-responsive table-bordered"
                :data-list-options="[
                    {label:'Tipo', name:'type_danger', isShow: true},
                    {label:'Riesgos', name:'danger', isShow: true},
                    {label:'Efecto', name:'effect', isShow: true},
                    {label:'Probabilidad de ocurrencia (de 1 a 5)', name:'probability', isShow: true},
                    {label:'Impacto (1 a 5)', name:'impact', isShow: true},
                    {label:'Asignación del Riesgo', name:'allocation_danger', isShow: true}
                ]">
                <template #fields="scope">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="type_danger" class="col-form-label col-md-4 required">Tipo:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.type_danger" name="type_danger" id="type_danger" required>
                                            <option value="">Seleccione</option>
                                            <option value="ADMINISTRATIVOS">ADMINISTRATIVOS</option>
                                            <option value="JURIDICOS">JURIDICOS</option>
                                            <option value="FINANCIEROS Y/O DE MEDIDORES">FINANCIEROS Y/O DE MEDIDORES</option>
                                            <option value="CAUSAS Y/O EVENTOS DE LA NATURALEZA, FUERZA MAYOR O CASO FORTUITO">CAUSAS Y/O EVENTOS DE LA NATURALEZA, FUERZA MAYOR O CASO FORTUITO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">    

                            <div class="col-md-6">
                                <div class="form-group row m-b-15">
                                    <label for="danger" class="col-form-label col-md-4 required">Riesgos:</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" required type="text" v-model="scope.dataForm.danger" placeholder="Riesgos"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row m-b-15">
                                    <label for="effect" class="col-form-label col-md-4 required">Efecto:</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" required type="text" v-model="scope.dataForm.effect" placeholder="Efecto"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row m-b-15">
                                    <label for="probability" class="col-form-label col-md-4 required">Probabilidad de ocurrencia (de 1 a 5):</label>
                                    <div class="col-md-8">
                                        <input class="form-control" required type="number" min="1" max="5"  class="col-md-3" v-model="scope.dataForm.probability" placeholder="Probabilidad de ocurrencia (de 1 a 5)">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="form-group row m-b-15">
                                    <label for="impact" class="col-form-label col-md-4 required">Impacto (1 a 5):</label>
                                    <div class="col-md-8">
                                        <input class="form-control" required type="number" min="1" max="5"  class="col-md-2" v-model="scope.dataForm.impact" placeholder="Impacto (1 a 5)">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row m-b-15">
                                    <label for="allocation_danger" class="col-form-label col-md-4 required">Asignación del Riesgo:</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" required type="text" v-model="scope.dataForm.allocation_danger" placeholder="Asignación del Riesgo"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
              
               </template>
            </dynamic-list>

            </div>
         
        </div>
    </div>
</div>



<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>8. Indicación de las coberturas de los riesgos asegurables</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('indication_danger_precontractual', trans('En la etapa Pre-contractual').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('indication_danger_precontractual', null, ['rows' => 5, 'class' => 'form-control', 'v-model' => 'dataForm.indication_danger_precontractual', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('indication_danger_ejecution', trans('En la etapa de ejecución (Garantía Única)').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                    {!! Form::textarea('indication_danger_ejecution', null, ['rows' => 5, 'class' => 'form-control', 'v-model' => 'dataForm.indication_danger_ejecution', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
</div>
