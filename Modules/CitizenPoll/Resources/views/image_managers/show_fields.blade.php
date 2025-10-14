<div class="row">
   <!-- Name Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>
</div>


<div class="row">
   <!-- Url Image Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('image'):</dt>
   <dd class="col-9 text-truncate">
      <img width="250" :src="'{{ asset('storage') }}/'+dataShow.url_image" alt=""></dd>
</div>


