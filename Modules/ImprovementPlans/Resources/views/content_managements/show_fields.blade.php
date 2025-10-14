<div class="row">
    <!-- Name Field -->
    <strong class="text-inverse text-left col-3 text-break">@lang('Name'):</strong>
    <p class="col-9 text-truncate">@{{ dataShow.name }}.</p>
</div>


<div class="row">
    <!-- Archive Field -->
    <strong class="text-inverse text-left col-3 text-break">@lang('Adjunto'):</strong>
    <p class="col-3 text-break"> <a class="ml-2" :href="'{{ asset('storage') }}/' + dataShow.archive" target="_blank">Ver
            adjunto</a>
</div>

<div class="row">
    <!-- Color Field -->
    <strong class="text-inverse text-left col-3 text-break">@lang('Color'):</strong>
    <div class="text-center col-3" :style="'color:#FFFFFF;background-color:' + dataShow.color" v-if="dataShow.color" :title="dataShow.color">@{{ dataShow.color }}</div>
</div>
