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
   <p class="col-3 text-break">@{{ dataShow.process }}.</p>
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
 
