<!-- Created at Field -->
<dt class="text-inverse text-left col-4">@lang('Created_at'):</dt>
<dd class="col-8">@{{ dataShow.created_at }}.</dd>


<!-- Name Field -->
<dt class="text-inverse text-left col-4">@lang('Name'):</dt>
<dd class="col-8">@{{ dataShow.name }}.</dd>


<!-- Unit Time Field -->
<dt class="text-inverse text-left col-4">@lang('Unit Time'):</dt>
<dd class="col-8">@{{ dataShow.unit_time_name }}.</dd>


<!-- Type Term Field -->
<dt class="text-inverse text-left col-4">@lang('Type Term'):</dt>
<dd class="col-8">@{{ dataShow.type_term_name? dataShow.type_term_name : '' }}.</dd>


<!-- Term Field -->
<dt class="text-inverse text-left col-4">@lang('Application deadline'):</dt>
<dd class="col-8">@{{ dataShow.term + ' ' + dataShow.unit_time_name }}.</dd>


<!-- Early Field -->
<dt class="text-inverse text-left col-4">@lang('Early warning'):</dt>
<dd class="col-8">@{{ dataShow.early + ' ' + dataShow.unit_time_name }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-left col-4">@lang('Description'):</dt>
<dd class="col-8">@{{ dataShow.description }}</dd>
