<div class="row">
   <!-- Name Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>
</div>


<div class="row">
   <!-- Pin Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Pin'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.pin }}.</dd>
</div>


<div class="row">
   <!-- Password Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Password'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.password }}.</dd>
</div>


<div class="row">
   <!-- Identification Number Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Identification Number'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.identification_number }}.</dd>
</div>


<div class="row">
   <!-- Email Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Email'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.email }}.</dd>
</div>


<div class="row">
   <!-- Telephone Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Telephone'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.telephone }}.</dd>
</div>


<div class="row">
   <!-- Direction Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Direction'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.direction }}.</dd>
</div>


<div class="row">
   <!-- Charge Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Charge'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.charge }}.</dd>
</div>


<div class="row">
   <!-- Receptionist Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Receptionist'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.receptionist }}.</dd>
</div>


<div class="row">
   <!-- Functions Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Functions'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.functions }}.</dd>
</div>


<div class="row">
   <!-- Firm Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Firm'):</dt>
   <td><a :href="'{{ asset('storage') }}/'+dataShow.firm" class="col-3" target="_blank">Ver Adjunto</a></td>
</div>


<div class="row">
   <!-- State Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('State'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.publication_status }}.</dd>
</div>