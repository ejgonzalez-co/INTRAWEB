<div class="row">
   <!-- Source Information Id Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Source Information'):</strong>
   <p class="col-4 text-break">@{{ dataShow.source_information ? dataShow.source_information.name : '' }}.</p>
</div>

<div class="row">
   <!-- Type Oportunity Improvements Id Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Tipo de oportunidad de mejora'):</strong>
   <p class="col-4 text-break">@{{ dataShow.type_oportunity_improvements ? dataShow.type_oportunity_improvements.name : '' }}.</p>
</div>
<div class="row">

   <!-- Type Oportunity Improvements Id Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Criterio de evaluación'):</strong>
   <p class="col-4 text-break">@{{ dataShow.evaluation_criteria }}.</p>
</div>
<div class="row">
   <!-- Name Opportunity Improvement Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Nombre de la oportunidad de mejora'):</strong>
   <p class="col-4 text-break">@{{ dataShow.name_opportunity_improvement }}.</p>

</div>
<div class="row">
   <!-- Description Opportunity Improvement Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Description of improvement opportunity or nonconformity'):</strong>
   <p class="col-4 text-break">@{{ dataShow.description_opportunity_improvement }}.</p>

</div>
<div class="row">
   <!-- Unit Responsible Improvement Opportunity Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Unit or process responsible for the opportunity for improvement'):</strong>
   <p class="col-4 text-break">@{{ dataShow.unit_responsible_improvement_opportunity }}.</p>

</div>
<div class="row">

   <!-- Offical responsible Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Official responsible for the unit'):</strong>
   <p class="col-4 text-break">@{{ dataShow.official_responsible }}.</p>
</div>
<div class="row">
   <!-- Deadline Submission Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Deadline for submission of improvement plan'):</strong>
   <p class="col-4 text-break">@{{ formatDate(dataShow.deadline_submission) }}.</p>

</div>
<div class="row">
   <!-- Evidence Field -->
   <strong class="text-inverse text-left col-4 text-break">@lang('Evidence of opportunity for improvement or nonconformity'):</strong>
   <p class="col-4 text-break"> <a class="ml-2" :href="'{{ asset('storage') }}/' + dataShow.evidence" target="_blank">Ver adjunto</a></p>
</div>
