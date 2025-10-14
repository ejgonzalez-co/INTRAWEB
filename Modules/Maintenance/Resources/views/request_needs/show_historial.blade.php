<!-- Panel historiales-->
<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Seguimiento</strong></h3>
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
                             <th>Nombre</th>
                             <th>Estado</th>
                             <th>Acción realizada</th>
                             <th>Observación</th>

                         </tr>
                     </thead>
                     <tbody>
                         <tr v-for="(historial, key) in dataShow.historial">
                             <td>@{{ historial.created_at }}</td>
                             <td>@{{ historial.users_nombre }}</td>
                             <td>@{{ historial.estado }}</td>
                             <td>@{{ historial.accion }}</td>
                             <td>@{{ historial.observacion ?   historial.observacion  : 'NA'}}</td>
                         </tr>
                        
                     </tbody>
                 </table>
                 
               </div>
           </div>
       </div>
   </div>
   <!-- end panel-body -->
</div>


