<div class="row">
   <!-- Direction Field -->
   <dt class="text-inverse text-left col-3 text-truncate">Dia no laboral</dt>
   <dd class="col-9">@{{ dataShow.non_business_days }}.</dd>
</div>

<div class="row">
   <!-- Users Id Field -->
   <dt class="text-inverse text-left col-3 text-truncate">Funcionario:</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.users ? dataShow.users.name: ''}}.</dd>
</div>


<div class="row">
   <!-- Lc Monthly Routines Id Field -->
   <dt class="text-inverse text-left col-3 text-truncate">Personal de apoyo:</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.users_contract ? dataShow.users_contract.name: ''}}.</dd>
</div>