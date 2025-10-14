<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Información general:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Priority Request Field -->
         <dt class="text-inverse text-left col-3">@lang('Priority Request'):</dt>
         <dd class="col-9">@{{ dataShow.priority_request_name }}.</dd>

         <!-- Affair Field -->
         <dt class="text-inverse text-left col-3">@lang('Affair'):</dt>
         <dd class="col-9">@{{ dataShow.affair }}.</dd>

         <!-- Assignment Date Field -->
         <dt class="text-inverse text-left col-3">@lang('Assignment Date'):</dt>
         <dd class="col-9">@{{ dataShow.assignment_date }}</dd>

         <!-- Prox Date To Expire Field -->
         <dt class="text-inverse text-left col-3">@lang('Prox Date To Expire'):</dt>
         <dd class="col-9">@{{ dataShow.prox_date_to_expire }}</dd>

         <!-- Expiration Date Field -->
         <dt class="text-inverse text-left col-3">@lang('Expiration Date'):</dt>
         <dd class="col-9">@{{ dataShow.expiration_date }}</dd>

         <!-- Date Attention Field -->
         <dt class="text-inverse text-left col-3">@lang('Date Attention'):</dt>
         <dd class="col-9">@{{ dataShow.date_attention }}</dd>

         <!-- Closing Date Field -->
         <dt class="text-inverse text-left col-3">@lang('Closing Date'):</dt>
         <dd class="col-9">@{{ dataShow.closing_date }}</dd>

         <!-- Description Field -->
         <dt class="text-inverse text-left col-3">@lang('Description'):</dt>
         <dd class="col-9" style="white-space: break-spaces;">@{{ dataShow.description }}</dd>

         
         <!-- Nombre de usuario que solicita el requerimiento Field -->
         <dt class="text-inverse text-left col-3">@lang('Nombre de usuario que solicita el requerimiento'):</dt>
         <dd class="col-9">@{{ dataShow.username_requesting_requirement }}</dd>

         <!-- Ubicación Field -->
         <dt class="text-inverse text-left col-3">@lang('Ubicación'):</dt>
         <dd class="col-9">@{{ dataShow.location }}</dd>


         <!-- Tracing Field -->
         <dt class="text-inverse text-left col-3">@lang('Tracing'):</dt>
         <dd class="col-9" v-html="dataShow.tracing"></dd>

         <!-- Ht Tic Request Status Id Field -->
         <dt class="text-inverse text-left col-3">@lang('Request Status'):</dt>
         <dd class="col-9">@{{ dataShow.request_status }}</dd>

         <!-- Ht Tic Type Request Id Field -->
         <dt class="text-inverse text-left col-3">@lang('Request Type'):</dt>
         <dd class="col-9">@{{ dataShow.tic_type_request? dataShow.tic_type_request.name : '' }}.</dd>

         <!-- Assigned By Id Field -->
         <dt class="text-inverse text-left col-3">@lang('Assigned By'):</dt>
         <dd class="col-9">@{{ dataShow.assigned_by_name ? dataShow.assigned_by_name : (dataShow.assigned_by ? dataShow.assigned_by.name : '') }}.</dd>

         <!-- Users Id Field -->
         <dt class="text-inverse text-left col-3">@lang('User'):</dt>
         <dd class="col-9">@{{ dataShow.users_name ? dataShow.users_name : (dataShow.users ? dataShow.users.name : '') }}.</dd>

         <!-- Assigned User Id Field -->
         <dt class="text-inverse text-left col-3">@lang('Usuario asignado'):</dt>
         <dd class="col-9">@{{ dataShow.assigned_user_name ? dataShow.assigned_user_name : (dataShow.assigned_user ? dataShow.assigned_user.name : '') }}.</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.tic_request_histories? dataShow.tic_request_histories.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Historial de la solicitud:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <table class="table table-hover m-b-0">
         <thead>
            <tr>
               <!-- <th>ID</th> -->
               <th>@lang('Created_at')</th>
               {{-- <th>@lang('User')</th> --}}
               <th>Usuario</th>
               <th>Administrador</th>
               <th>Tecnico</th>
               <!-- <th>@lang('Priority Request')</th> -->
               <!-- <th>@lang('Affair')</th> -->
               <th>@lang('Expiration Date')</th>
               <th>@lang('Date Attention')</th>
               <th>@lang('Tracing')</th>
               <th>@lang('State')</th>
            </tr>
         </thead>
         <tbody>
            <tr v-for="(ticRequestHistory, key) in dataShow.tic_request_histories" :key="key">
               <!-- <td>@{{ ticRequestHistory.id }}</td> -->
               <td>@{{ ticRequestHistory.created_at }}</td>
               <td>@{{ ticRequestHistory.users_name ? ticRequestHistory.users_name : (ticRequestHistory.users ? ticRequestHistory.users.name : '') }}</td>
               <td>@{{ ticRequestHistory.assigned_by_name ? ticRequestHistory.assigned_by_name : (ticRequestHistory.assigned_by ? ticRequestHistory.assigned_by.name : '') }}</td>
               <td>@{{ ticRequestHistory.assigned_user_name ? ticRequestHistory.assigned_user_name : (ticRequestHistory.assigned_user ? ticRequestHistory.assigned_user.name : '') }}</td>

               <td>@{{ ticRequestHistory.assigned_user_name?.name }}</td>
               <!-- <td>@{{ ticRequestHistory.priority_request? ticRequestHistory.priority_request_name :'' }}</td> -->
               <!-- <td>@{{ ticRequestHistory.affair }}</td> -->
               <td>@{{ ticRequestHistory.expiration_date }}</td>
               <td>@{{ ticRequestHistory.date_attention }}</td>
               <td  v-html="ticRequestHistory.tracing"></td>
               <td>
                  <div class="text-white text-center p-2" :style="'background-color:'+ticRequestHistory.status_color" v-html="ticRequestHistory.status_name"></div>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <!-- end panel-body -->
</div>


<div v-if="dataShow.tic_satisfaction_polls? dataShow.tic_satisfaction_polls.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Encuesta de satisfactión:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <table class="table table-hover m-b-0">
         <thead>
            <tr>
               <th>#</th>
               <th>@lang('Created_at')</th>
               <th>@lang('Question')</th>
               <th>@lang('Reply')</th> 
            </tr>
         </thead>
         <tbody>
            <tr v-for="(ticSatisfactionPoll, key) in dataShow.tic_satisfaction_polls" :key="key">
               <td>Pregunta @{{ key + 1 }}</td>
               <td>@{{ ticSatisfactionPoll.created_at }}</td>
               <td>@{{ ticSatisfactionPoll.question }}</td>
               <td>@{{ ticSatisfactionPoll.reply }}</td>
            </tr>
         </tbody>
      </table>
   </div>
   <!-- end panel-body -->
</div>
