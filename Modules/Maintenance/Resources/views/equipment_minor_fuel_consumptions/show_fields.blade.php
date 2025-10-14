<div class="row">
   <!-- Supply Date Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Supply Date'):</dt>
   <dd class="col-9 ">@{{ dataShow.supply_date }}.</dd>
</div>


<div class="row">
   <!-- Process Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Process'):</dt>
   <dd class="col-9 ">@{{ dataShow.dependencias ? dataShow.dependencias.nombre:''}}.</dd>
</div>


<div class="row">
   <!-- Equipment Description Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Equipment Description'):</dt>
   <dd class="col-9 ">@{{ dataShow.mant_resume_equipment_machinery ? dataShow.mant_resume_equipment_machinery.name_equipment + ' - ' + dataShow.mant_resume_equipment_machinery.no_inventory  :''}}.</dd>
</div>

<div class="row">
   <!-- Gallons Supplied Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Litros suministrados'):</dt>
   <dd class="col-9 ">@{{ dataShow.liter_supplied ?  dataShow.liter_supplied : "NA"}}.L</dd>
</div>


<div class="row">
   <!-- Gallons Supplied Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Gallons Supplied'):</dt>
   <dd class="col-9 ">@{{ formatNumber(dataShow.gallons_supplied,4 )}} gal.</dd>
</div>


<div class="row">
   <!-- Name Receives Equipment Field -->
   <dt class="text-inverse text-left col-3">@lang('Name Receives Equipment'):</dt>
   <dd class="col-9 ">@{{ dataShow.name_receives_equipment }}.</dd>
</div>


<div  class="mt-3"  v-if="dataShow.mant_documents_minor_equipments?.length ">
   <h5 class="text-center">Documentos relacionados con los registros de combustible de equipos menores.</h5>
   <div class="container mt-3">
         <div class="row justify-content-center">
            <table class="text-center default" border="1">
               <tr>
                     <th>Nombre</th>
                     <th>Descripción</th>
                     <th>Adjunto</th>
               </tr>
               <tr v-for="attachment in dataShow.mant_documents_minor_equipments">
                     <td style="padding: 15px">@{{ attachment . name }}</td>
                     <td style="padding: 15px">@{{ attachment . observation }}</td>
                     <td style="padding: 15px">
                        <div>
                           <span v-for="url in attachment.url.split(',')"><a
                                    class="col-9 " :href="'{{ asset('storage') }}/'+url"
                                    target="_blank">Ver
                                    adjunto</a><br /></span>
                        </div>
                     </td>

               </tr>
            </table>
         </div>
   </div>
</div>

