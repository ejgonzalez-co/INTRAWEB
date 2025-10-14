<div class="row">
   <!-- Title Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Title'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.title }}.</dd>
</div>


<div class="row">
   <!-- Content Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">Contenido:</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 " v-html="dataShow.content"></dd>
</div>


<div class="row">
   <!-- Users Name Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">Creador:</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.users_name }}.</dd>
</div>


<div class="row">
   <!-- State Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('State'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.state }}.</dd>
</div>


<div class="row">
   <!-- Date Start Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Start Date'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.date_start }}.</dd>
</div>


<div class="row">
   <!-- Date End Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('End Date'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.date_end }}.</dd>
</div>


<div class="row">
   <!-- Views Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Views'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.views }}.</dd>
</div>


<div class="row">
   <!-- Image Presentation Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">Imagen presentación:</dt>
   <img width="200" v-if="dataShow.image_presentation == 'imagen_default_noticias.png'" :src="'{{ asset('assets/img') }}/'+dataShow.image_presentation" alt=""style="opacity: 0.7;">
   <img width="200" v-else :src="'{{ asset('storage') }}/'+dataShow.image_presentation" alt="">

</div>

<div class="row">
   <!-- Image Banner Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">Imagen Banner:</dt>
   <img width="200" v-if="dataShow.image_banner == 'imagen_default_noticias.png'" :src="'{{ asset('assets/img') }}/'+dataShow.image_banner" alt=""style="opacity: 0.7;">
   <img width="200" v-else :src="'{{ asset('storage') }}/'+dataShow.image_banner" alt="">

</div>


<div class="row">
   <!-- Keywords Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">Palabras claves:</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.keywords }}.</dd>
</div>


<div class="row">
   <!-- Featured Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">Destacado:</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.featured }}.</dd>
</div>

<div class="row">
   <!-- Intranet News Category Id Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Category'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.category?.name }}.</dd>
</div>
<br><br>

<!-- Panel Seguimiento al trámite -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Historial de seguimiento</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="table-responsive">
           <table id="anotaciones" class="table table-bordered">
               <thead>
                   <tr class="font-weight-bold" style="background: #00b0bd;
                   color: white;">
                   <td>Usuario</td>
                   <td>Fecha visualización</td>
               </tr>
               </thead>
               <tbody>
                  <tr v-for="control in dataShow.notice_monitoring_control">
                     <td>@{{ control.fullname }}</td>
                     <td><ul v-for="data in control.fechas_visualizaciones.split(',')">@{{ data }}</ul></td>




               </tr>
               </tbody>
           </table>
       </div>
   </div>
</div>


<div class="row">
   <!-- Intranet News Category Id Field -->
   <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Annexes'):</dt>
   <viewer-attachement v-if="dataShow.annexes" :list="dataShow.annexes"></viewer-attachement>
   <p class="col-sm-9 col-md-9 col-lg-9" v-else>No tiene anexos adjuntados</p>
</div>


