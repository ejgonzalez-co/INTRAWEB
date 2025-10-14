
<div v-if="dataShow.novelty_type == 'Asignación presupuestal'" class="panel" data-sortable-id="ui-general-1">

   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      
      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Tipo de novedad:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.novelty_type }}.</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Consecutivo del CDP:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.consecutive_cdp }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Valor del CDP:</dt>
         <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow.value_cdp) }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Valor del contrato:</dt>
         <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow.value_contract) }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">CDP Disponible:</dt>
         <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow.cdp_available) }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Observacion:</dt>
         <dd class="col-9">@{{ dataShow.observation }}.</dd>
      </div>

     
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.novelty_type == 'Adición al contrato'" class="panel" data-sortable-id="ui-general-1">

   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      
      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Número CDP:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.number_cdp }}.</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Valor del CDP:</dt>
         <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow.value_cdp) }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha del CDP:</dt>
         <dd class="col-9 text-truncate"> @{{ dataShow.date_cdp }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha del modificatorio:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.date_modification }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Valor disponible:</dt>
         <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow.cdp_available) }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Observacion:</dt>
         <dd class="col-9 ">@{{ dataShow.observation }}.</dd>
      </div>

         
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.novelty_type == 'Prórroga'" class="panel" data-sortable-id="ui-general-1">

   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      
      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Tiempo de prórroga:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.time_extension }}.</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha terminación de contrato:</dt>
         <dd class="col-9 text-truncate"> @{{ dataShow.date_contract_term }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha del modificatorio:</dt>
         <dd class="col-9 text-truncate"> @{{ dataShow.date_modification }}</dd>
      </div>


      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Observacion:</dt>
         <dd class="col-9">@{{ dataShow.observation }}.</dd>
      </div>

      <div class="row">
      <!-- Attachment Field -->
         <dt class="text-inverse text-left col-3">Adjunto:</dt>
         <dd v-if="dataShow.attachment" class="col-4 text-truncate">
            <span><a :href="'{{ asset('storage') }}/'+  dataShow.attachment " target="_blank">Ver adjunto</a><br /></span>
         </dd>
         <dd  v-else  class="col-4 text-truncate" >Sin adjunto</dd>

      </div>
         
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.novelty_type == 'Suspensión'" class="panel" data-sortable-id="ui-general-1">

   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      
      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Tipo de suspensión:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.type_suspension  }}.</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Tiempo de suspensión:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.time_suspension ? dataShow.time_suspension : 'NA' }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha inicio de suspensión:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.date_start_suspension ? dataShow.date_start_suspension : 'NA' }}</dd>
      </div>     
      
      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha fin de suspensión:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.date_end_suspension ? dataShow.date_end_suspension : 'NA' }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha terminación de contrato:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.date_contract_term ? dataShow.date_contract_term : 'NA' }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha acta de suspensión:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.date_act_suspension ? dataShow.date_act_suspension : 'NA' }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Observacion:</dt>
         <dd class="col-9 ">@{{ dataShow.observation }}.</dd>
      </div>

      <div class="row">
      <!-- Attachment Field -->
         <dt class="text-inverse text-left col-3">Adjunto:</dt>
         <dd v-if="dataShow.attachment" class="col-4 text-truncate">
            <span><a :href="'{{ asset('storage') }}/'+  dataShow.attachment " target="_blank">Ver adjunto</a><br /></span>
         </dd>
         <dd  v-else  class="col-4 text-truncate" >Sin adjunto</dd>

      </div>
         
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.novelty_type == 'Reinicio'" class="panel" data-sortable-id="ui-general-1">

   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      
      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha última suspensión:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.date_last_suspension }}.</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha del modificatorio:</dt>
         <dd class="col-9 text-truncate">@{{ dataShow.date_modification }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Fecha terminación de contrato:</dt>
         <dd class="col-9 text-truncate"> @{{ dataShow.date_contract_term }}</dd>
      </div>

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse  text-left col-3 pb-2">Observación:</dt>
         <dd class="col-9 ">@{{ dataShow.observation }}</dd>
      </div>

      <div class="row">
      <!-- Attachment Field -->
         <dt class="text-inverse text-left col-3">Adjunto:</dt>
         <dd v-if="dataShow.attachment" class="col-4 text-truncate">
            <span><a :href="'{{ asset('storage') }}/'+  dataShow.attachment " target="_blank">Ver adjunto</a><br /></span>
         </dd>
         <dd  v-else  class="col-4 text-truncate" >Sin adjunto</dd>

      </div>
         
   </div>
   <!-- end panel-body -->
</div>