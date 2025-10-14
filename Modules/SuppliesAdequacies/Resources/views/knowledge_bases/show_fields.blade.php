<div class="row">
   <!-- User Creator Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Created_at'):</strong>
   <p class="col-9 text-break">@{{ dataShow.created_at }}.</p>
</div>

<div class="row">
   <!-- User Creator Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Registered by'):</strong>
   <p class="col-9 text-break">@{{ dataShow.user_creator ? dataShow.user_creator.name : "N/E" }}.</p>
</div>

<div class="row">
   <!-- Knowledge Type Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Knowledge Type'):</strong>
   <p class="col-9 text-break">@{{ dataShow.knowledge_type }}.</p>
</div>


<div class="row">
   <!-- Subject Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Subject'):</strong>
   <p class="col-9 text-break">@{{ dataShow.subject_knowledge }}.</p>
</div>


<div class="row">
   <!-- Description Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Description'):</strong>
   <p class="col-9 text-break">@{{ dataShow.description }}.</p>
</div>


<div class="row">
   <!-- Status Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Status'):</strong>
   <p class="col-9 text-break">@{{ dataShow.status }}.</p>
</div>


<div class="row">
   <!-- Url Attacheds Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Attacheds'):</strong>
   <viewer-attachement :list="dataShow.url_attacheds" :key="dataShow.url_attacheds" name="Adjunto"></viewer-attachement>
</div>


