<div class="row">
    <!-- Pm Goal Activities Id Field -->
    <strong class="text-inverse text-left col-4 text-break">@lang('Nombre de la actividad'):</strong>
    <p class="col-7 text-break">@{{ dataShow.goal_activities ? dataShow.goal_activities.activity_name : "" }}.</p>
</div>

<div class="row">
    <!-- Activity Weigth Field -->
    <strong class="text-inverse text-left col-4 text-break">@lang('Peso de la actividad'):</strong>
    <p class="col-7 text-break">@{{ dataShow.activity_weigth + "%" }}.</p>
</div>

<div class="row">
    <!-- Gap to Meet the goal Field -->
    <strong class="text-inverse text-left col-4 text-break">@lang('Brecha para cumplimiento de la meta'):</strong>
    <p class="col-7 text-break">@{{ dataShow.goal_activities ? dataShow.goal_activities.gap_meet_goal : "N/A" }}.</p>
</div>

<div class="row">
    <!-- Progress Weigth Field -->
    <strong class="text-inverse text-left col-4 text-break">@lang('Cantidad del avance'):</strong>
    <p class="col-7 text-break">@{{ dataShow.progress_weigth ? currencyFormat(dataShow.progress_weigth) : "" }}.</p>
</div>

<div class="row">
    <!-- Progress Weigth Field -->
    <strong class="text-inverse text-left col-4 text-break">@lang('Peso del avance'):</strong>
    <p class="col-7 text-break">@{{ dataShow.percentage_execution ? currencyFormat(dataShow.percentage_execution) + "%" : "" }}.</p>
</div>


<div class="row">
    <!-- Evidence Description Field -->
    <strong class="text-inverse text-left col-4 text-break">@lang('Descripción de la evidencia'):</strong>
    <p class="col-7 text-break">@{{ dataShow.evidence_description ?? "N/E" }}</p>
</div>

<div class="row">
    <!-- Evaluator Name Field -->
    <strong class="text-inverse text-left col-4 text-break">Nombre del evaluador:</strong>
    <p class="col-7 text-break">@{{ dataShow.evaluator_name ?? "N/E" }}</p>
</div>

<div class="row" v-if="dataShow.status == 'Aprobado'">
    <!-- Evidence Date Field -->
    <strong class="text-inverse text-left col-4 text-break">Fecha de aprobación:</strong>
    <p class="col-7 text-break">@{{ dataShow.updated_at ?? "N/E" }}</p>
</div>

<div class="row" v-if="dataShow.observation != null">
    <!-- Evidence Description Field -->
    <strong class="text-inverse text-left col-4 text-break">Observación del evaluador:</strong>
    <p class="col-7 text-break">@{{ dataShow.observation ?? "N/E" }}</p>
</div>


<div class="row">
    <!-- Url Progress Evidence Field -->
    <strong class="text-inverse text-left col-4 text-break">@lang('Evidencia del avance'):</strong>
    <p v-if="dataShow.url_progress_evidence != null" class="col-3 text-break"> <a class="ml-2" :href="'{{ asset('storage') }}/' + dataShow.url_progress_evidence"
            target="_blank"><strong>Ver adjunto</strong></a>
    </p>
    <p v-else class="col-3 text-break">No Aplica</p>
</div>
