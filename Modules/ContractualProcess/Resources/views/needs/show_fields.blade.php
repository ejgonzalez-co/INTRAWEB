<!-- State Field -->
<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Información de la convocatoría:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Name Field -->
         <dt class="text-inverse text-left col-3">@lang('Name'):</dt>
         <dd class="col-9">@{{ dataShow.paa_calls? dataShow.paa_calls.name: '' }}.</dd>

         <!-- Start Date Field -->
         <dt class="text-inverse text-left col-3">@lang('Start Date'):</dt>
         <dd class="col-9">@{{ dataShow.paa_calls? dataShow.paa_calls.start_date: '' }}.</dd>

         <!-- Closing Date Field -->
         <dt class="text-inverse text-left col-3">@lang('Closing Date'):</dt>
         <dd class="col-9">@{{ dataShow.paa_calls? dataShow.paa_calls.closing_date: '' }}.</dd>


         <!-- Observation Message Field -->
         <dt class="text-inverse text-left col-3">@lang('Observation Message'):</dt>
         <dd class="col-9">@{{ dataShow.paa_calls? dataShow.paa_calls.observation_message: '' }}.</dd>


         <!-- State Field -->
         <dt class="text-inverse text-left col-3">@lang('State'):</dt>
         <dd class="col-9">@{{ dataShow.paa_calls? dataShow.paa_calls.state_name: '' }}.</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>


<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Información de la necesidad:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Name Process Field -->
         <dt class="text-inverse text-left col-3">@lang('Total Operating Value'):</dt>
         <dd class="col-9">@{{ dataShow.name_process }}.</dd>


         <!-- Total Operating Value Field -->
         <dt class="text-inverse text-left col-3">@lang('Total Operating Value'):</dt>
         <dd class="col-9">@{{ dataShow.total_operating_value? '$ '+currencyFormat(dataShow.total_operating_value): '' }}.</dd>


         <!-- Total Investment Value Field -->
         <dt class="text-inverse text-left col-3">@lang('Total Investment Value'):</dt>
         <dd class="col-9">@{{ dataShow.total_investment_value? '$ '+currencyFormat(dataShow.total_investment_value): '' }}.</dd>


         <!-- Total Value Paa Field -->
         <dt class="text-inverse text-left col-3">@lang('Total Value Paa'):</dt>
         <dd class="col-9">@{{ dataShow.total_value_paa? '$ '+currencyFormat(dataShow.total_value_paa): '' }}.</dd>


         <!-- Future Validity Status Field -->
         <dt class="text-inverse text-left col-3">@lang('Future Validity Status'):</dt>
         <dd class="col-9">@{{ dataShow.future_validity_status }}.</dd>

         <!-- State Field -->
         <dt class="text-inverse text-left col-3">@lang('State'):</dt>
         <dd class="col-9">@{{ dataShow.state_name }}.</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>


<div v-if="dataShow.pc_functioning_needs? dataShow.pc_functioning_needs.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Necesidades de funcionamiento:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <table class="table table-hover m-b-0">
            <thead>
               <tr>
                  <th>#</th>
                  <th>@lang('Created_at')</th>
                  <th>@lang('Description')</th>
                  <th>@lang('Estimated Total Value')</th>
                  <th>@lang('Observation')</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(pcFunctioningNeeds, key) in dataShow.pc_functioning_needs" :key="key">
                  <td>@{{ getIndexItem(key) }}</td>
                  <td>@{{ pcFunctioningNeeds.created_at }}</td>
                  <td>@{{ pcFunctioningNeeds.description }}</td>
                  <td>@{{ '$ '+currencyFormat(pcFunctioningNeeds.estimated_total_value) }}</td>
                  <td>@{{ pcFunctioningNeeds.observation }}</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <!-- end panel-body -->
</div>


<div v-if="dataShow.pc_investment_needs? dataShow.pc_investment_needs.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Necesidades de inversión:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <table class="table table-hover m-b-0">
            <thead>
               <tr>
                  <th>#</th>
                  <th>@lang('Created_at')</th>
                  <th>@lang('Description')</th>
                  <th>@lang('Estimated Value')</th>
                  <th>@lang('Observation')</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(pcInvestmentNeeds, key) in dataShow.pc_investment_needs" :key="key">
                  <td>@{{ getIndexItem(key) }}</td>
                  <td>@{{ pcInvestmentNeeds.created_at }}</td>
                  <td>@{{ pcInvestmentNeeds.description }}</td>
                  <td>@{{ '$ '+currencyFormat(pcInvestmentNeeds.estimated_value) }}</td>
                  <td>@{{ pcInvestmentNeeds.observation }}</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.paa_process_attachments? dataShow.paa_process_attachments.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Documentos adjuntos:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <table class="table table-hover m-b-0">
            <thead>
               <tr>
                  <th>#</th>
                  <th>@lang('Name')</th>
                  <th>@lang('Description')</th>
                  <th>@lang('Attached')</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(attachments, key) in dataShow.paa_process_attachments" :key="key">
                  <td>@{{ getIndexItem(key) }}</td>
                  <td>@{{ attachments.name }}</td>
                  <td>@{{ attachments.description }}</td>
                  <td>
                     <div v-if="attachments.attached">
                        <span v-for="attached in attachments.attached.split(',')" style="margin-left: -15px;"><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+attached" target="_blank">Ver adjunto</a><br/></span>
                     </div>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <!-- end panel-body -->
</div>