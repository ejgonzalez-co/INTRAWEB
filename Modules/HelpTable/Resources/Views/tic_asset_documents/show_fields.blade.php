<div class="row">
    <!-- Name Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Name'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.name }}.</dd>
</div>


<div class="row">
    <!-- Content Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Content'):</dt>
    <dd class="col-9 text-break">@{{ dataShow.content }}.</dd>
</div>


<div class="row">
    <!-- Url Attachments Field -->
    <dt class="text-inverse text-left col-3 text-break">@lang('Attachments'):</dt>
    <dd class="col-9 text-break">
        <viewer-attachement :list="dataShow.url_attachments" :key="dataShow.url_attachments"></viewer-attachement>
    </dd>
</div>
