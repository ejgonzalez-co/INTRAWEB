<!-- Validity Field -->
<dt class="text-inverse text-left col-3">@lang('Validity'):</dt>
<dd class="col-9">@{{ dataShow.validity }}.</dd>

<!-- Name Field -->
<dt class="text-inverse text-left col-3">@lang('Name'):</dt>
<dd class="col-9">@{{ dataShow.name }}.</dd>


<!-- Start Date Field -->
<dt class="text-inverse text-left col-3">@lang('Start Date'):</dt>
<dd class="col-9">@{{ dataShow.start_date }}.</dd>


<!-- Closing Alert Date Field -->
<dt class="text-inverse text-left col-3">@lang('Closing Alert Date'):</dt>
<dd class="col-9">@{{ dataShow.closing_alert_date }}.</dd>


<!-- Closing Date Field -->
<dt class="text-inverse text-left col-3">@lang('Closing Date'):</dt>
<dd class="col-9">@{{ dataShow.closing_date }}.</dd>

<!-- Closing Date Field -->
<dt class="text-inverse text-left col-3">@lang('Attached'):</dt>
<dd class="col-9">
   <a v-if="dataShow.attached" :href="'{{ asset('storage') }}/'+dataShow.attached" target="_blank">Ver adjunto</a>
</dd>


<!-- Observation Message Field -->
<dt class="text-inverse text-left col-3">@lang('Observation Message'):</dt>
<dd class="col-9">@{{ dataShow.observation_message }}.</dd>
