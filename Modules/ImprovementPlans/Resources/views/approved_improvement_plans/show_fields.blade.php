<div class="row mb-2">
   <!-- Type Evaluation Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Type Evaluation'):</strong>
   <p class="col-3 text-break">@{{ dataShow.type_evaluation }}.</p>

   <!-- Evaluation Name Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Evaluation Name'):</strong>
   <p class="col-3 text-break">@{{ dataShow.evaluation_name }}.</p>
</div>


<div class="row mb-2">
   <!-- Objective Evaluation Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Objective Evaluation'):</strong>
   <p class="col-3 text-break">@{{ dataShow.objective_evaluation }}.</p>


   <!-- Evaluation Scope Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Evaluation Scope'):</strong>
   <p class="col-3 text-break">@{{ dataShow.evaluation_scope }}.</p>
</div>

<div class="row mb-2">
   <!-- Evaluation Start Date Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Evaluation Start Date'):</strong>
   <p class="col-3 text-break">@{{ formatDate(dataShow.evaluation_start_date) }}.</p>


   <!-- Evaluation Start Time Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Evaluation Start Time'):</strong>
   <p class="col-3 text-break">@{{ dataShow.evaluation_start_time }}.</p>
</div>

<div class="row mb-2">
   <!-- Evaluation End Date Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Evaluation End Date'):</strong>
   <p class="col-3 text-break">@{{ formatDate(dataShow.evaluation_end_date) }}.</p>


   <!-- Evaluation End Time Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Evaluation End Time'):</strong>
   <p class="col-3 text-break">@{{ dataShow.evaluation_end_time }}.</p>

</div>


<div class="row mb-2">
   <!-- Unit Responsible For Evaluation Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Internal unit or external entity responsible for evaluation'):</strong>
   <p class="col-3 text-break">@{{ dataShow.unit_responsible_for_evaluation }}.</p>

   <!-- Evaluation Officer Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Designated official of the evaluating unit or entity'):</strong>
   <p class="col-3 text-break">@{{ dataShow.evaluation_officer }}.</p>

</div>

<div class="row mb-2">
   <!-- Process Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('dependencies'):</strong>
   <table border="1" class="text-center col-3">
       <thead>
           <tr>
               <td><strong>Dependencias</strong></td>
           </tr>
       </thead>
       <tbody>
           <tr v-for="(dependence,key) in dataShow.evaluation_dependences" :key="key">
               <td>@{{ dependence.dependence_name }}</td>
           </tr>
       </tbody>
   </table>
</div>

<div class="row mb-2">
   <!-- Evaluation Site Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Criterios de evaluación'):</strong>
   <table border="1" class="text-center col-3">
       <thead>
           <tr>
               <td><strong>Criterios de evaluación</strong></td>
           </tr>
       </thead>
       <tbody>
           <tr v-for="(evaluation_criterion,key) in dataShow.evaluation_criteria" :key="key">
               <td>@{{ evaluation_criterion.criteria_name }}</td>
           </tr>
       </tbody>
   </table>
</div>

<div class="row mb-2">
   <!-- Process Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Process'):</strong>
   <p class="col-8 text-break">@{{ dataShow.process }}.</p>
</div>

<div class="row mb-2">
       <!-- Attached Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Attached file'):</strong>
   <p class="col-3 text-break" v-if="dataShow.attached != null"> <a class="ml-2" :href="'{{ asset('storage') }}/' + dataShow.attached"
           target="_blank"><strong>Ver adjunto</strong></a>
   </p>
    <p v-else class="col-3 text-break">No Aplica</p>
</div>

<div class="row mb-2">
   <!-- Evaluation Site Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Evaluation Site'):</strong>
   <p class="col-3 text-break">@{{ dataShow.evaluation_site }}.</p>

   <!-- Attached Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Adjunto de la ejecución del proceso de evaluación'):</strong>
   <p class="col-3 text-break" v-if="dataShow.evaluation_process_attachment != null"> <a class="ml-2" :href="'{{ asset('storage') }}/' + dataShow.evaluation_process_attachment"
           target="_blank"><strong>Ver adjunto</strong></a>
   </p>
    <p v-else class="col-3 text-break">No Aplica</p>
</div>

<div class="row mb-2">
    <!-- Type improvement Field -->
    <strong class="text-inverse text-left col-3 text-break">@lang('Tipo de plan de mejoramiento'):</strong>
    <p class="col-3 text-break">@{{ dataShow.name_improvement_plan }}.</p>
 
    <!-- Attached Field -->
    <strong class="text-inverse text-left col-3 text-break">@lang('Nombre del plan de mejoramiento'):</strong>
    <p class="col-3 text-break" >@{{dataShow.type_name_improvement_plan}}</p>
 </div>
 

<div class="row">
<strong class="panel-title text-inverse text-left col-3 text-break"> Porcentaje de ejecución del plan de mejoramiento </strong>

<div class="progress ml-2" style="width: 100px;" v-cloak>
    <div class="progress-bar" role="progressbar" :style="'width: ' + dataShow.percentage_execution + '%;'" :aria-valuenow="dataShow.percentage_execution" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<p style="padding-left: 10px" v-cloak>@{{ dataShow.percentage_execution ? currencyFormat(dataShow.percentage_execution) + "%" : "0%" }}</p>
</div>
<br><br>
<div class="row mb-2">
   <!-- Evaluation Site Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Oportunidades de Mejora y no Conformidades'):</strong>
   <table border="1" class="text-center col-8">
       <thead>
           <tr>
                <td><strong>Criterio de evaluacion</strong></td>
                <td><strong>Descripción de la oportunidad de mejora o no conformidad</strong></td>
                <td><strong>Tipo de oportunidad de mejora o no conformidad</strong></td>
                <td><strong>Nombre de la oportunidad de mejora o no conformidad</strong></td>
           </tr>
       </thead>
       <tbody>
           <tr v-for="(evaluation_opportunities,key) in dataShow.evaluation_improvement_opportunities" :key="key">
                <td>@{{ evaluation_opportunities.evaluation_criteria }}</td>
                <td>@{{ evaluation_opportunities.description_opportunity_improvement }}</td>
                <td>@{{ evaluation_opportunities.type_oportunity_improvements.name }}</td>
                <td>@{{ evaluation_opportunities.name_opportunity_improvement }}</td>
           </tr>
       </tbody>
   </table>
</div>
<!-- Panel Información ciudadano -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-for="(evaluation_criterion,key) in dataShow.evaluation_criteria" :key="key">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Criterio de evaluación @{{ key+1 }}</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Evaluation Site Field -->
        <label for="" class="text-inverse text-left text-break"><strong>Nombre del criterio: </strong></label><span> @{{ evaluation_criterion.criteria_name }}</span><br />
        {{-- <label for="" class="text-inverse text-left text-break"><strong>Peso del criterio en el plan: </strong></label><span> @{{ evaluation_criterion.weight }}%</span><br /> --}}
        {{-- <label for="" class="text-inverse text-left text-break"><strong>Porcentaje de ejecución: </strong></label><span> @{{ evaluation_criterion.percentage_execution }}% de 100%</span><br /> --}}
        {{-- <label for="" class="text-inverse text-left text-break"><strong>Fecha del criterio: </strong></label><span> @{{ evaluation_criterion.created_at }}</span> --}}
        <br />
        <br />
        {{-- oportunidades de mejora --}}
        <div class="panel col-md-12" data-sortable-id="ui-general-1" v-for="(opportunity,key) in evaluation_criterion.opportunities" :key="key">

            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title"><strong>Oportunidad de mejora @{{ key+1 }}</strong></h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <label for="" class="text-inverse text-left text-break"><strong>Nombre de la oportunidad de mejora: </strong></label><span> @{{ opportunity.name_opportunity_improvement }}</span><br />
                <label for="" class="text-inverse text-left text-break"><strong>Peso de la oportunidad de mejora en el plan: </strong></label><span> @{{ opportunity.weight }}%</span><br />
                <label for="" class="text-inverse text-left text-break"><strong>Porcentaje de ejecución: </strong></label><span> @{{ opportunity.percentage_execution ? currencyFormat(opportunity.percentage_execution) + "%" : "0%" }} de 100%</span><br />
                <label for="" class="text-inverse text-left text-break"><strong>Fecha de la oportunidad de mejora: </strong></label><span> @{{ formatDate(opportunity.deadline_submission) }}</span>
                <br />

                {{-- Metas --}}
                <div class="panel col-md-12" data-sortable-id="ui-general-1" v-for="(goal,key) in opportunity.goals" :key="key">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <h3 class="panel-title"><strong>Meta @{{ key+1 }}</strong></h3>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body">
                        <label for="" class="text-inverse text-left text-break"><strong>Nombre de la meta: </strong></label><span> @{{ goal.goal_name }}</span><br />
                        <label for="" class="text-inverse text-left text-break"><strong>Tipo de meta: </strong></label><span> @{{ goal.goal_type }}</span><br />
                        <label for="" class="text-inverse text-left text-break"><strong>Peso de la meta en el plan: </strong></label><span> @{{ goal.goal_weight }}%</span><br />
                        <label for="" class="text-inverse text-left text-break"><strong>Porcentaje de ejecución: </strong></label><span> @{{ goal.percentage_execution ? currencyFormat(goal.percentage_execution) + "%" : "0%" }} de 100%</span><br />
                        <label for="" class="text-inverse text-left text-break"><strong>Fecha de la meta: </strong></label><span> @{{ goal.created_at }}</span>
                        <br />
                        <br />
                        <div v-if="goal.goal_type == 'Cuantitativa'">
                            <!-- Panel Información ciudadano -->
                            <div class="panel col-md-12" data-sortable-id="ui-general-1" v-for="(activitie,key) in goal.goal_activities" :key="key">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h3 class="panel-title"><strong>Actividad @{{ key+1 }}</strong></h3>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    {{-- Actividades --}}
                                    <label for="" class="text-inverse text-left text-break"><strong>Nombre de la actividad: </strong></label><span> @{{ activitie.activity_name }}</span><br />
                                    <label for="" class="text-inverse text-left text-break"><strong>Peso de la actividad: </strong></label><span> @{{ activitie.activity_weigth }}%</span><br />
                                    <div v-if="activitie.goal_type == 'Cuantitativa'">
                                        <label for="" class="text-inverse text-left text-break"><strong>Cantidad: </strong></label><span> @{{ activitie.activity_quantity }}</span><br />
                                        <label for="" class="text-inverse text-left text-break"><strong>Linea base para la meta: </strong></label><span> @{{ activitie.baseline_for_goal }}</span><br />
                                        <label for="" class="text-inverse text-left text-break"><strong>Brecha para cumplimiento de la meta: </strong></label><span> @{{ activitie.gap_meet_goal }}</span><br />
                                        <label for="" class="text-inverse text-left text-break"><strong>Fecha de la actividad: </strong></label><span> @{{ activitie.created_at }}</span><br />
                                    </div>      
                                    <br />
                                    <br />
                                    {{-- Avances --}}
                                    <div v-if="activitie.goal_progresses.length > 0">
                                        <label for="" class="text-inverse text-left text-break"><strong>Avances: </strong></label>
                                        <table border="1" class="text-center table">
                                            <thead>
                                                <tr>
                                                    <td><strong>Fecha del avance</strong></td>
                                                    <td><strong>Cantidad del avance</strong></td>
                                                    <td><strong>Peso del avance (%)</strong></td>
                                                    <td><strong>Evidencia del avance</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(avance,key) in activitie.goal_progresses" :key="key">
                                                    <td>@{{ avance.created_at }}</td>
                                                    <td>@{{ avance.progress_weigth }}</td>
                                                    <td>@{{ avance.activity_weigth }}%</td>
                                                    <td class="text-center col-4 text-break">
                                                        <p v-if="avance.url_progress_evidence != null"> <a class="ml-2" :href="'{{ asset('storage') }}/' + avance.url_progress_evidence"
                                                                target="_blank"><strong>Ver adjunto</strong></a>
                                                        </p>
                                                        <p v-else class="col-3 text-break">No Aplica</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else class="bg-light p-4 text-center rounded shadow-sm">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-info-circle text-primary mb-3" style="font-size: 3rem;"></i>
                                            <h4 class="text-muted mb-2">Sin avances</h4>
                                            <p class="text-secondary">No se han registrado avances para esta actividad por el momento.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="panel col-md-12" data-sortable-id="ui-general-1" v-for="(activitie,key) in goal.goal_activities" :key="key">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h3 class="panel-title"><strong>Actividad @{{ key+1 }}</strong></h3>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    {{-- Actividades --}}
                                    <label for="" class="text-inverse text-left text-break"><strong>Nombre de la actividad: </strong></label><span> @{{ activitie.activity_name }}</span><br />
                                    <label for="" class="text-inverse text-left text-break"><strong>Peso de la actividad: </strong></label><span> @{{ activitie.activity_weigth }}%</span><br />
                                    <div v-if="activitie.goal_type == 'Cuantitativa'">
                                        <label for="" class="text-inverse text-left text-break"><strong>Cantidad: </strong></label><span> @{{ activitie.activity_quantity }}</span><br />
                                        <label for="" class="text-inverse text-left text-break"><strong>Linea base para la meta: </strong></label><span> @{{ activitie.baseline_for_goal }}</span><br />
                                        <label for="" class="text-inverse text-left text-break"><strong>Brecha para cumplimiento de la meta: </strong></label><span> @{{ activitie.gap_meet_goal }}</span><br />
                                        <label for="" class="text-inverse text-left text-break"><strong>Fecha de la actividad: </strong></label><span> @{{ activitie.created_at }}</span><br />
                                    </div>      
                                    <br />
                                    <br />
                                    {{-- Avances --}}
                                    <div v-if="activitie.goal_progresses.length > 0">
                                        <label for="" class="text-inverse text-left text-break"><strong>Avances: </strong></label>
                                        <table border="1" class="text-center table">
                                            <thead>
                                                <tr>
                                                    <td><strong>Nombre de la actividad</strong></td>
                                                    <td><strong>Peso de la actividad (%)</strong></td>
                                                    <td><strong>Fecha del avance</strong></td>
                                                    <td><strong>Evidencia del avance</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(avance,key) in activitie.goal_progresses" :key="key">
                                                    <td>@{{ avance.progress_weigth }}</td>
                                                    <td>@{{ avance.activity_weigth }}%</td>
                                                    <td>@{{ avance.created_at }}</td>
                                                    <td class="text-center col-4 text-break">
                                                        <p v-if="avance.url_progress_evidence != null"> <a class="ml-2" :href="'{{ asset('storage') }}/' + avance.url_progress_evidence"
                                                                target="_blank"><strong>Ver adjunto</strong></a>
                                                        </p>
                                                        <p v-else class="col-3 text-break">No Aplica</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else class="bg-light p-4 text-center rounded shadow-sm">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-info-circle text-primary mb-3" style="font-size: 3rem;"></i>
                                            <h4 class="text-muted mb-2">Sin avances</h4>
                                            <p class="text-secondary">No se han registrado avances para esta actividad por el momento.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>