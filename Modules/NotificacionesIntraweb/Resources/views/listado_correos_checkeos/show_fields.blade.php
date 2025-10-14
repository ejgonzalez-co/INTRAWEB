
 <div class="row">
    <!-- Cities Id Field -->
    <dt class="text-inverse text-left col-4 ">Email:</dt>
    <dd class="col-7">@{{ dataShow.email }}.</dd>
 </div>
 

 <div class="row">
    <!-- Cities Id Field -->
    <dt class="text-inverse text-left col-4 ">Estado:</dt>
    <dd class="col-7" v-if="dataShow.estado == 'Valida'">Válido.</dd>
    <dd class="col-7" v-else>No válido.</dd>

 </div>
 
 <div class="row">
    <!-- Cities Id Field -->
    <dt class="text-inverse text-left col-4 ">Fecha de verificación:</dt>
    <dd class="col-7">@{{ dataShow.fecha_verificacion }}.</dd>
 </div>
 
 <div class="row">
    <!-- Cities Id Field -->
    <dt class="text-inverse text-left col-4 ">Usuario:</dt>
    <dd class="col-7">@{{ dataShow.user_name }}.</dd>
 </div>
 


