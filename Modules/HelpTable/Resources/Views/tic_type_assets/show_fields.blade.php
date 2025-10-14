<!-- Name Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Name'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.name }}.</dd>

<!-- Ht Tic Type Tic Categories Id Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Category'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.tic_type_tic_categories? dataShow.tic_type_tic_categories.name : '' }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Description'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.description }}</dd>