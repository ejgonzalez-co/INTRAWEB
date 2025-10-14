<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Información general</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">

       <div class="row">

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Oficina productora:</strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.dependencia ? dataShow.dependencia.nombre + ' - ' + dataShow.dependencia.codigo : 'N/A' }}</label>
                </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label
                       class="col-form-label col-md-4 text-black-transparent-7"><strong>Serie o subserie documental:</strong></label>

                       <label class="col-form-label col-md-8">
                        @{{ dataShow.series_osubseries 
                          ? (dataShow.series_osubseries.type === 'Subserie' 
                              ? dataShow.series_osubseries.name_serie + ' - ' + dataShow.series_osubseries.no_serie + ' - ' +dataShow.series_osubseries.no_subserie + ' - '+dataShow.series_osubseries.name_subserie  
                              : dataShow.series_osubseries.no_serie + ' - ' + dataShow.series_osubseries.name_serie) 
                          : 'N/A' 
                        }}
                      </label>
                      
                      
                </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label
                       class="col-form-label col-md-4 text-black-transparent-7"><strong>Descripción del expediente:</strong></label>
                   <label class="col-form-label col-md-8"> @{{ dataShow.description_expedient ? dataShow.description_expedient : 'N/A' }}</label>
               </div>
           </div>


           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label
                       class="col-form-label col-md-4 text-black-transparent-7"><strong>Folios</strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.folios ? dataShow.folios : 'N/A' }}</label>
               </div>
           </div>

           

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Frecuencia de consulta:</strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.consultation_frequency ? dataShow.consultation_frequency : 'N/A' }}</label>
               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Soporte:</strong></label>
                   <label class="col-form-label col-md-8"> @{{ dataShow.soport ? dataShow.soport : 'N/A' }}</label>
               </div>
           </div>


           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Clasificación:</strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.clasification ? dataShow.clasification : 'N/A' }}</label>
               </div>
           </div>
           
           <div class="col-md-6">
               <div class="form-group row m-b-15">
                  <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Observación:</strong></label>
                  <label class="col-form-label col-md-8">@{{ dataShow.observation ? dataShow.observation : 'N/A' }}</label>
               </div>
         </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Documentos del inventario:</strong></label>
                   <div class="form-group row m-b-15 col-md-8">
                       <div v-if="dataShow.attachment">
                               <div v-for="attachment in dataShow.attachment.split(',')" style="margin-left: -15px;">
                                   <viewer-attachement :link-file-name="true" :list="attachment"></viewer-attachement>
                               </div>
                       </div>
                       <ul v-else>
                           <li>No tiene adjuntos</li>
                       </ul>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Signatura topográfica</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
                   
           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Estantería:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.shelving ? dataShow.shelving : 'N/A' }}</label>
               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Bandeja:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.tray ? dataShow.tray : 'N/A'}}</label>
               </div>
           </div>

           <div class="col-md-6">
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Caja:
                        </strong></label>
                     <label class="col-form-label col-md-8">@{{ dataShow.box ? dataShow.box : 'N/A' }}</label>
                  </div>
            </div>

            <div class="col-md-6">
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Carpeta:
                        </strong></label>
                     <label class="col-form-label col-md-8">@{{ dataShow.file ? dataShow.file : 'N/A'}}</label>
                  </div>
            </div>

            <div class="col-md-6">
               <div class="form-group row m-b-15">
                  <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Libro:
                     </strong></label>
                  <label class="col-form-label col-md-8">@{{ dataShow.book ? dataShow.book : 'N/A' }}</label>
               </div>
         </div>

       </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="dataShow.criterios_busqueda_value != ''">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Criterios de búsqueda</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
                   
           <div class="col-md-6" v-if="dataShow.texto_criterio">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Texto:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.texto_criterio }}</label>
               </div>
           </div>

           <div class="col-md-6" v-if="dataShow.lista_criterio">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Lista:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.lista_criterio }}</label>
               </div>
           </div>

           <div class="col-md-6" v-if="dataShow.numero_criterio">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Numero:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.numero_criterio}}</label>
               </div>
           </div>

           <div class="col-md-6" v-if="dataShow.contenido_criterio">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>contenido:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.contenido_criterio}}</label>
               </div>
           </div>

           <div class="col-md-6" v-if="dataShow.fecha_criterio">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Fecha:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.fecha_criterio}}</label>
               </div>
           </div>

       </div>
   </div>
   <!-- end panel-body -->
</div>


<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Fechas extremas</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
                   
           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Fecha inicial:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.date_initial ? dataShow.date_initial : 'N/A' }}</label>
               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Fecha Final:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.date_finish ? dataShow.date_finish : 'N/A' }}</label>
               </div>
           </div>

       </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Rango de numeración</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
                   
           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Rango inicial:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.range_initial ? dataShow.range_initial : 'N/A' }}</label>
               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Rango Final:
                       </strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.range_finish ? dataShow.range_finish : 'N/A' }}</label>
               </div>
           </div>

       </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="card mt-4">
   <div class="card-header bg-secondary text-white">
       <b class="card-title">Metadatos</b>
   </div>
   <div class="card-body">
       <div v-if="dataShow.metadatos" class="metadata-table">
           <b>Listado de metadatos:</b>
           <table class="table">
               <thead>
                   <tr>
                       <th>Archivo</th>
                       <th>Item y Páginas</th>
                   </tr>
               </thead>
               <tbody>
                   <tr v-for="(metadata, attachment) in dataShow.metadatos" :key="attachment">
                       <td> <a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+attachment" target="_blank">@{{ getFileName(attachment) }}</a></td>
                       <td>

                           <table class="w-100">
                              <colgroup>
                                 <col style="width: 50%;">
                                 <col style="width: 50%;">
                              </colgroup>
                              <thead>
                                 <tr>
                                    <th>Item</th>
                                    <th>Página</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr v-for="(meta, metaIndex) in metadata" :key="metaIndex">
                                    <td>@{{ meta.item }}</td>
                                    <td>@{{ meta.pagina }}</td>
                                 </tr>
                              </tbody>
                           </table>
                           

                       </td>
                   </tr>
               </tbody>
           </table>
       </div>
       <div v-else>
           <p class="alert alert-warning">Aún no hay metadatos</p>
       </div>
   </div>
</div>