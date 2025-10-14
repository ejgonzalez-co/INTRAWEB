<!-- Panel Información de la dependencia -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Información de la dependencia</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">

       <div class="row">
         <!-- Id Sede Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('headquarter')</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.headquarters? dataShow.headquarters.nombre : '' }}.</dd>
       </div>

       <div class="row">
         <!-- Codigo Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Code'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.codigo }}.</dd>
       </div>

       <div class="row">
         <!-- Nombre Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Name'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.nombre }}.</dd>
       </div>

       <div class="row">
         <!-- Codigo Oficina Productora Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Siglas del proceso'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.codigo_oficina_productora }}.</dd>
       </div>

       <div class="row">
         <!-- Codigo direccion Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Dirección:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.direccion }}.</dd>
       </div>

       <div class="row">
         <!-- Codigo piso Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Piso:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.piso }}.</dd>
       </div>

       <div class="row">
         <!-- Codigo codigo_postal Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Codigo postal:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.codigo_postal }}.</dd>
       </div>
       
       <div class="row">
         <!-- Codigo telefono Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Telefono:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.telefono }}.</dd>
       </div>

       <div class="row">
         <!-- Codigo extension Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Extensión:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.extension }}.</dd>
       </div>

       <div class="row">
         <!-- Codigo correo Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Correo:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.correo }}.</dd>
       </div>

       <div class="row">
         <!-- Document pdf Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Logo:</dt>
         <dd class="col-12" v-if="dataShow.logo && dataShow.logo.length > 0">
            <viewer-attachement :link-file-name="true" v-if="dataShow.logo" :list="dataShow.logo "></viewer-attachement>
         </dd>
         <dd v-else class="col-sm-9 col-md-9 col-lg-9">
             <span>No tiene logo adjunto</span>
         </dd>
      </div>

       <div class="row">
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">Dependencias relacionadas:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 " v-for="item in dataShow.dependencias_list"><p>@{{ item.nombre }}</p></dd>
      </div>
   </div>
</div>