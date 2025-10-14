<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Cronograma de ejecución de las actividades a ejecutarse con los recursos asignados para la vigencia en curso:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <dynamic-list
            label-button-add="Agregar ítem a la lista"
            :data-list.sync="scope.dataForm.resource_schedule_current_terms"
            :data-list-options="[
               {label:'Descripción', name:'description', isShow: true},
               {label:'Semanas', name:'week', isShow: true, selectItem: 'name'},
            ]"
            class-container="col-md-12"
            class-table="table table-bordered"
            :key="keyRefresh"
            >
            <template #fields="scope">
                  <div class="row">

                     <div class="col-md-6">
                        <!-- Impact Description Field -->
                        <div class="form-group row m-b-15">
                           {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
                           <div class="col-md-9">
                              {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.description', 'required' => true]) !!}
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6">
                        <!-- Environmental component Field -->
                        <div class="form-group row m-b-15">
                           {!! Form::label('week', trans('Week').':', ['class' => 'col-form-label col-md-3 required']) !!}
                           <div class="col-md-9">
                              {{-- <multiselect-component
                                 name-field="week"
                                 :value="scope.dataForm"
                                 ref="weekRef"
                              >
                              </multiselect-component> --}}

                              <multiselect-component
                                 :close-on-select="false"
                                 :is-group-select="true"
                                 group-values="week"
                                 group-label="month"
                                 name-field="week"
                                 name-resource="get-constants/schedule_month"
                                 :is-multiple="true"
                                 :value="scope.dataForm"
                                 :key="keyRefresh"
                              >
                              </multiselect-component>
                           </div>
                        </div>
                     </div>

                  </div>
               </template>
         </dynamic-list>
      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Cronograma de ejecución de las actividades de gestión o que vienen de la vigencias anteriores y que contribuyen al cumplimiento de las metas propuestas para la vigencia:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <dynamic-list
            label-button-add="Agregar ítem a la lista"
            :data-list.sync="scope.dataForm.schedule_resources_previous_periods"
            :data-list-options="[
               {label:'Descripción', name:'description', isShow: true},
               {label:'Semanas', name:'week', isShow: true, refList: 'weekRef1', selectItem: 'name'},
            ]"
            class-container="col-md-12"
            class-table="table table-bordered"
            :key="keyRefresh"
            >
            <template #fields="scope">
                  <div class="row">

                     <div class="col-md-6">
                        <!-- Impact Description Field -->
                        <div class="form-group row m-b-15">
                           {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
                           <div class="col-md-9">
                              {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'scope.dataForm.description', 'required' => true]) !!}
                           </div>
                        </div>
                     </div>


                     <div class="col-md-6">
                        <!-- Environmental component Field -->
                        <div class="form-group row m-b-15">
                           {!! Form::label('week', trans('Week').':', ['class' => 'col-form-label col-md-3 required']) !!}
                           <div class="col-md-9">
                              {{-- <multiselect-component
                                 name-field="week"
                                 :value="scope.dataForm"
                                 ref="weekRef"
                              >
                              </multiselect-component> --}}
                              <multiselect-component
                                 :close-on-select="false"
                                 :is-group-select="true"
                                 group-values="week"
                                 group-label="month"
                                 name-field="week"
                                 name-resource="get-constants/schedule_month"
                                 :is-multiple="true"
                                 :value="scope.dataForm"
                                 :key="keyRefresh"
                              >
                              </multiselect-component>
                           </div>
                        </div>
                     </div>
                  </div>
            </template>
         </dynamic-list>

      </div>
   </div>
   <!-- end panel-body -->
</div>