<template>
   <div>
      <form @submit.prevent="save()" id="form-maintenances">
      <div class="panel" data-sortable-id="ui-general-1">
         <!-- begin panel-heading -->
         <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Datos del mantenimiento:</strong></h4>
         </div>
         <!-- end panel-heading -->
         <!-- begin panel-body -->
         <div class="panel-body">
            <div class="row">
               <div class="col-md-12">
                  <!--  Type Maintenance Field -->
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-3 required" for="type_maintenance">{{ 'trans.Type Maintenance' | trans }}:</label>
                     <div class="col-md-9">
                        <select-check
                           css-class="form-control"
                           name-field="type_maintenance"
                           reduce-label="name"
                           reduce-key="id"
                           name-resource="get-constants/type_maintenance_tic"
                           :value="dataForm"
                           :is-required="true">
                        </select-check>
                     </div>
                  </div>
               </div>

               <div class="col-md-12">
                  <!-- Fault Description Field -->
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-3" for="fault_description">{{ 'trans.Fault Description' | trans }}:</label>
                     <div class="col-md-9">
                        <textarea name="fault_description" cols="50" rows="10" id="fault_description" class="form-control" v-model="dataForm.fault_description"></textarea>
                        <small>Ingrese la descripción de la falla o daño.</small>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- Service Start Date Field -->
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-3" for="service_start_date">{{ 'trans.Service Start Date' | trans }}:</label>
                     <div class="col-md-9">
                        <date-picker
                           :value="dataForm"
                           name-field="service_start_date"
                        >
                        </date-picker>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- End Date Service Field -->
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-3" for="end_date_service">{{ 'trans.End Date Service' | trans }}:</label>
                     <div class="col-md-9">
                        <date-picker
                           :value="dataForm"
                           name-field="end_date_service"
                        >
                        </date-picker>
                     </div>
                  </div>
               </div>

               <div class="col-md-12">
                  <!-- Maintenance Description Field -->
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-3" for="maintenance_description">{{ 'trans.Maintenance Description' | trans }}:</label>
                     <div class="col-md-9">
                        <textarea name="maintenance_description" cols="50" rows="10" id="maintenance_description" class="form-control" v-model="dataForm.maintenance_description"></textarea>
                        <small>Ingrese la descripción de la falla o daño.</small>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- Contract Number Field -->
                  <div class="form-group row m-b-15">
                     {!! Form::label('contract_number', trans('Contract Number').':', ['class' => 'col-form-label col-md-3']) !!}
                     <div class="col-md-9">
                           {!! Form::text('contract_number', null, ['class' => 'form-control', 'v-model' => 'dataForm.contract_number']) !!}
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- Cost Field -->
                  <div class="form-group row m-b-15">
                     {!! Form::label('cost', trans('Cost').':', ['class' => 'col-form-label col-md-3']) !!}
                     <div class="col-md-9">
                           {!! Form::number('cost', null, ['class' => 'form-control', 'v-model' => 'dataForm.cost']) !!}
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- Warranty Start Date Field -->
                  <div class="form-group row m-b-15">
                     {!! Form::label('warranty_start_date', trans('Warranty Start Date').':', ['class' => 'col-form-label col-md-3']) !!}
                     <div class="col-md-9">
                           <date-picker
                              :value="dataForm"
                              name-field="warranty_start_date"
                           >
                           </date-picker>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- Warranty End Date Field -->
                  <div class="form-group row m-b-15">
                     {!! Form::label('warranty_end_date', trans('Warranty End Date').':', ['class' => 'col-form-label col-md-3']) !!}
                     <div class="col-md-9">
                           <date-picker
                              :value="dataForm"
                              name-field="warranty_end_date"
                           >
                           </date-picker>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- Dependency Id Field -->
                  <div class="form-group row m-b-15">
                  {!! Form::label('dependency_id', trans('Dependency').':', ['class' => 'col-form-label col-md-3']) !!}
                     <div class="col-md-9">
                           <select-check
                              css-class="form-control"
                              name-field="dependency_id"
                              reduce-label="nombre"
                              reduce-key="id"
                              name-resource="/intranet/get-dependencies"
                              :value="dataForm"
                           >
                           </select-check>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!--  Maintenance Status Field -->
                  <div class="form-group row m-b-15">
                     {!! Form::label('maintenance_status', trans('Maintenance Status').':', ['class' => 'col-form-label col-md-3 required']) !!}
                     <div class="col-md-9">
                           <select-check
                              css-class="form-control"
                              name-field="maintenance_status"
                              reduce-label="name"
                              reduce-key="id"
                              name-resource="get-constants/maintenance_status_tic"
                              :value="dataForm"
                              :is-required="true">
                           </select-check>
                     </div>
                  </div>
               </div>             

               <div class="col-md-6">
                  <!-- Provider Id Field -->
                  <div class="form-group row m-b-15">
                     {!! Form::label('provider_id', trans('Provider').':', ['class' => 'col-form-label col-md-3']) !!}
                     <div class="col-md-9">
                           <select-check
                              css-class="form-control"
                              name-field="provider_id"
                              reduce-label="name"
                              reduce-key="id"
                              name-resource="get-supplier-users-tic"
                              :value="dataForm"
                           >
                           </select-check>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <!-- Request Id Field -->
                  <div class="form-group row m-b-15">
                     {!! Form::label('request_id', trans('Request').':', ['class' => 'col-form-label col-md-3']) !!}
                     <div class="col-md-9">
                           <select-check
                              css-class="form-control"
                              name-field="request_id"
                              reduce-label="affair"
                              reduce-key="id"
                              name-resource="get-requests-tic"
                              :value="dataForm"
                           >
                           </select-check>
                     </div>
                  </div>
               </div>
               
            </div>
         </div>
         <!-- end panel-body -->
      </div>
      </form>
   </div>
</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import * as bootstrap from 'bootstrap';

   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();

   /**
    * Componente para crear un mantenimiento a partir de una solicitud
    *
    * @author Carlos Moises Garcia T. - Nov. 16 - 2020
    * @version 1.0.0
    */
   @Component
   export default class TicHTAssetMaintenance extends Vue {

      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;


      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Nov. 16 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();
         this.dataForm = {};
         this.dataErrors = {};

         this.lang = (this.$parent as any).lang;
      }

   }
</script>