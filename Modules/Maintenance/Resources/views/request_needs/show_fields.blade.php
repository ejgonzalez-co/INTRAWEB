<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Datos generales de la solicitud: @{{ dataShow.consecutivo }}</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">

       <div class="row">

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Dependencia:</strong></label>
                   <label class="col-form-label col-md-8">   @{{ dataShow.dependencia?.nombre ?? 'Nombre no disponible' }} - @{{ dataShow.dependencia?.codigo_oficina_productora ?? 'Código no disponible' }}
                  </label>
               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label
                       class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('consecutivo'):</strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.consecutivo }}</label>
               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label
                       class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('State'):</strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.estado }}</label>
               </div>
           </div>


           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label
                       class="col-form-label col-md-4 text-black-transparent-7"><strong>Activo:</strong></label>
                   <label class="col-form-label col-md-8">@{{ dataShow.activo_nombre ?? "N/A" }}</label>
               </div>
           </div>

           

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Rubro:</strong></label>
                   <label class="col-form-label col-md-8">   @{{ dataShow.heading_information?.name ?? 'Nombre no disponible' }} - @{{ dataShow.heading_information?.code_cost ?? 'Código no disponible' }} - @{{ dataShow.heading_information?.cost_center_name ?? 'Centro no disponible' }}
               </div>
           </div>

           
           <div class="col-md-6" v-if="dataShow.second_rubro_id">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Segundo Rubro:</strong></label>
                   <label class="col-form-label col-md-8">   @{{ dataShow.heading_information_second?.name ?? 'Nombre no disponible' }} - @{{ dataShow.heading_information_second?.code_cost ?? 'Código no disponible' }} - @{{ dataShow.heading_information_second?.cost_center_name ?? 'Centro no disponible' }}
               </div>
           </div>


           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Objeto del Contrato:</strong></label>
                   <label class="col-form-label col-md-8">   @{{ dataShow.contrato_datos?.object ?? 'Nombre no disponible' }} - @{{ dataShow.contrato_datos?.contract_number ?? 'Código no disponible' }}

               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Proveedor nombre:</strong></label>
                   <label class="col-form-label col-md-8">   @{{ dataShow.proveedor_nombre ?? 'N/A' }}

               </div>
           </div>

           <div class="col-md-6">
               <div class="form-group row m-b-15">
                   <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Trámite almacén:</strong></label>
                   <label class="col-form-label col-md-8">   @{{ dataShow.estado_stock_almacen ?? 'N/A' }}

               </div>
           </div>


       </div>
   </div>
</div>


<!-- Panel Necesidades-->
<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Necesidades</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
           <div class="col-md-12">
               <div class="table-responsive">
                   
                   <table class="table table-hover fix-vertical-table">
                     <thead>
                         <tr>
                             <th>Necesidad</th>
                             <th>Descripción</th>
                             <th>Cantidad solicitada</th>
                             <th>Unidad de medida</th>
                             <th>Valor Unitario</th>
                             <th>IVA</th>
                             <th>Valor total</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr v-for="(necesidad, key) in dataShow.necesidades">
                             <td>@{{ necesidad.necesidad }}</td>
                             <td>@{{ necesidad.descripcion_nombre }}</td>
                             <td>@{{ necesidad.cantidad_solicitada }}</td>
                             <td>@{{ necesidad.unidad_medida }}</td>
                             <td>$ @{{ necesidad.valor_unitario }}</td>
                             <td>$ @{{ necesidad.IVA }}</td>
                             <td>$ @{{ necesidad.valor_total }}</td>
                         </tr>
                         <!-- Suma de la columna valor_total -->
                         <tr>
                             <td colspan="6" style="text-align: right;"><strong>Total:</strong></td>
                             <td>
                                 <strong>
                                     @{{dataShow.total_solicitud}}
                                 </strong>
                             </td>
                         </tr>
                     </tbody>
                 </table>
                 
               </div>
           </div>
       </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" v-if="dataShow.numero_salida_almacen">
    <div class="panel-heading">
       <div class="panel-title"><strong>Información de salida del almacén</strong></div>
    </div>
    <div class="panel-body">
        <div class="row" v-if="dataShow.numero_salida_almacen">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break" >Número de salida del almacén:</strong>
            <p class="col-4 text">@{{ dataShow.numero_salida_almacen }}.</p>
        </div>
        <div class="row" v-if="dataShow.fecha_salida_almacen">
            <!-- Anotacion Field -->
            <strong class="text-inverse text-left col-4 text-break">Fecha de salida del almacén:</strong>
            <p class="col-4 text">@{{ dataShow.fecha_salida_almacen ? formatDate(dataShow.fecha_salida_almacen) : "" }}.</p>
        </div> 
    </div>
 </div>

 <div class="panel" v-if="dataShow.url_documents">
    <div class="panel-heading">
       <div class="panel-title"><strong>Documentos de la solicitud</strong></div>
    </div>
    <div class="panel-body">
        <viewer-attachement :list="dataShow.url_documents" :key="dataShow.url_documents"></viewer-attachement>
    </div>
 </div>

<!-- Panel ordenes-->
<div  v-if="dataShow.ordenes?.length > 0" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Ordenes</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
           <div class="col-md-12">
               <div class="table-responsive">
                   
                   <table class="table table-hover fix-vertical-table">
                     <thead>
                         <tr>
                           <th>Fecha</th>

                             <th>Consecutivo</th>
                             <th>Estado</th>
                             <th>Estado proveedor</th>
                             <th>Proveedor nombre</th>
                             <th>Bodega</th>
                             <th>Trámite almacén</th>
                             <th>Tipo solicitud</th>
                             <th>Número entrada almacen</th>
                             <th>Número factura</th>
                             <th>Fecha entrada almacén</th>
                             <th>Número salida almacén</th>
                             <th>Fecha salida almacén</th>

                         </tr>
                     </thead>
                     <tbody>
                         <tr v-for="(necesidad, key) in dataShow.ordenes">
                           
                           <td>@{{ necesidad.created_at ?? "No Aplica"}}</td>

                             <td>@{{ necesidad.consecutivo ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.estado ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.estado_proveedor ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.proveedor_nombre ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.bodega ?? "No Aplica"}} </td>
                             <td>@{{ necesidad.tramite_almacen ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.tipo_solicitud ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.numero_entrada_almacen ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.numero_factura ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.fecha_entrada_almacen ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.numero_salida_almacen ?? "No Aplica"}}</td>
                             <td>@{{ necesidad.fecha_salida_almacen ?? "No Aplica"}}</td>

                         </tr>
                         
                     </tbody>
                 </table>
                 
               </div>
           </div>
       </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" v-if="dataShow.tipo_solicitud === 'Activo' && dataShow.valor_total_necesidades_repuestos > 0">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de ingreso al almacén</strong></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                <thead class="text-center bg-green">
                    <tr>
                        <td><strong>Descripción </strong></td>
                        <td><strong>Unidad de medida </strong></td>
                        <td><strong>Cantidad solicitada </strong></td>
                        <td><strong>Código </strong></td>
                        <td><strong>Cantidad ingresada al almacén </strong></td>
                        <td><strong>Unidad convertida</strong></td>
                        <td><strong>Cantidad convertida</strong></td>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(necesity, key) in dataShow.necesidades" v-if="necesity.necesidad === 'Repuestos'">
                        <td>@{{ necesity.descripcion_nombre }}</td>
                        <td>@{{ necesity.unidad_medida }}</td>
                        <td>@{{ necesity.cantidad_solicitada }}</td>
                        <td><input type="text" :value="necesity.codigo" class="form-control" disabled></td>
                        <td><input type="number" :value="necesity.cantidad_entrada" class="form-control" disabled></td>

                            <!-- 🔹 Nuevos valores -->
                        <td>
                            @{{ necesity.unidad_medida_conversion ? necesity.unidad_medida_conversion : "No aplica" }}
                        </td>
                        <td>
                            @{{ necesity.cantidad_solicitada_conversion ? necesity.cantidad_solicitada_conversion : "No aplica" }}
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <strong>Total: $@{{ dataShow.valor_total_necesidades_repuestos }}</strong>
        </div>
    </div>
</div>
<div class="panel" v-if="dataShow.tipo_solicitud === 'Activo'  && dataShow.valor_total_necesidades_actividades > 0">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de actividades</strong> <br><br> <strong>Número de factura: </strong> <span>@{{ dataShow.invoice_no }}</span></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                <thead class="text-center bg-green">
                    <tr>
                        <td><strong>Descripción </strong></td>
                        <td><strong>Unidad de medida </strong></td>
                        <td><strong>Cantidad solicitada </strong></td>
                        <td><strong>Actividad realizada por el proveedor </strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(necesity, key) in dataShow.necesidades" v-if="necesity.necesidad === 'Actividades'">
                        <td>@{{ necesity.descripcion_nombre }}</td>
                        <td>@{{ necesity.unidad_medida }}</td>
                        <td>@{{ necesity.cantidad_solicitada }}</td>
                        <td>@{{ necesity.cantidad_final }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <strong>Total: $@{{ dataShow.valor_total_necesidades_actividades }}</strong>
        </div>
    </div>
</div>

<div class="panel" v-if="dataShow.tipo_solicitud === 'Activo' && dataShow.valor_total_necesidades_repuestos > 0">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información salida del almacén</strong></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                <thead class="text-center bg-green">
                    <tr>
                        <td><strong>Descripción </strong></td>
                        <td><strong>Unidad de medida </strong></td>
                        <td><strong>Cantidad ingresada al almacén </strong></td>
                        <td><strong>Unidad convertida</strong></td>
                        <td><strong>Cantidad convertida</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(necesity, key) in dataShow.necesidades" v-if="necesity.necesidad === 'Repuestos'">
                        <td>@{{ necesity.descripcion_nombre }}</td>
                        <td>@{{ necesity.unidad_medida }}</td>
                        <td><input type="number" :value="necesity.cantidad_entrada" class="form-control" disabled></td>

                         <td>
                            @{{ necesity.unidad_medida_conversion ? necesity.unidad_medida_conversion : "No aplica" }}
                        </td>
                        <td>
                            @{{ necesity.cantidad_solicitada_conversion ? necesity.cantidad_solicitada_conversion : "No aplica" }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <strong>Total: $@{{ dataShow.valor_total_necesidades_repuestos }}</strong>
        </div>
    </div>
</div>

<!-- Panel ordenes-->
<div  v-if="dataShow.version?.length > 0" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Versionamiento identificación de necesidades</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    
                    <table class="table table-hover fix-vertical-table">
                      <thead>
                          <tr>
                            <th>Versión</th>
                              <th>Formato identificación de necesidades</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(version, key) in dataShow.ordenes">
                            
                            <td>@{{ version.created_at ?? "No Aplica"}}</td>
                            <td>@{{ version.consecutivo ?? "No Aplica"}}</td>
                             
 
                          </tr>
                          
                      </tbody>
                  </table>
                  
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
 </div>
 


