
<div class="row mt-3">
   <!-- Condition Field -->
   <dt class="text-inverse text-left col-3 ">Estado de la solicitud:</dt>
   <dd class="col-9 ">@{{ dataShow.condition }}.</dd>
</div>

<div class="row mt-3">
   <!-- User Name Field -->
   <dt class="text-inverse text-left col-3 ">Nombre del usuario que va ser consultado:</dt>
   <dd class="col-9 ">@{{ dataShow.user_name }}.</dd>
</div>


<div class="row mt-3">
   <!-- Consultation Time Field -->
   <dt class="text-inverse text-left col-3 ">Tiempo solicitado para la consulta:</dt>
   <dd class="col-9 ">@{{ dataShow.consultation_time }}.</dd>
</div>




<div class="row mt-3">
   <!-- Reason Consultation Field -->
   <dt class="text-inverse text-left col-3 ">Razón de la consulta:</dt>
   <dd class="col-9 ">@{{ dataShow.reason_consultation }}.</dd>
</div>





<div class="row mt-3" v-if="dataShow.answer">
   <!-- Answer Field -->
   <dt class="text-inverse text-left col-3 ">Respuesta:</dt>
   <dd class="col-9 ">@{{ dataShow.answer }}.</dd>
</div>

<div class="row mt-3" v-if="dataShow.date_start">
   <!-- Date Start Field -->
   <dt class="text-inverse text-left col-3 ">Fecha inicial:</dt>
   <dd class="col-9 ">@{{ dataShow.date_start }}.</dd>
</div>


<div class="row mt-3" v-if="dataShow.date_final">
   <!-- Date Final Field -->
   <dt class="text-inverse text-left col-3 ">Fecha final:</dt>
   <dd class="col-9 ">@{{ dataShow.date_final }}.</dd>
</div>


