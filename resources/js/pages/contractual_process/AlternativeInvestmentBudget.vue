<template>
   <div>
      <!-- begin #modal-view-alternative-budget -->
      <div class="modal fade" id="modal-form-alternative-budget" data-backdrop="static" data-keyboard="false">
         <div class="modal-dialog modal-xl">
            <form @submit.prevent="save()" id="form-alternative-budget" enctype="multipart/form-data">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">Presupuesto alternativo</h4>
                     <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body hljs-wrapper">

                     <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title">
                              <strong>Nota:</strong> <i>Las unidades del presupuesto de la alternativa seleccionada en Construcción, Reposición y Rehabilitación de Tuberías deben ser en m. En los casos de Prestación de Servicios la unidad se definirá en meses. Los mantenimientos no deben incluir la adquisición de los elementos o accesorios requeridos para la actividad.</i>
                              <hr>
                              <strong>Presupuesto:</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <dynamic-list
                                 label-button-add="Agregar ítem a la lista"
                                 :data-list.sync="dataForm.alternative_budgets"
                                 @addedToListEvent="calculateDirectTotalCost"
                                 :data-list-options="[
                                       {label:'Descripción', name:'description', isShow: true},
                                       {label:'Unidad', name:'unit', isShow: true},
                                       {label:'Cantidad', name:'quantity', isShow: true},
                                       {label:'V/r Unitario', name:'unit_value', isShow: true},
                                       {label:'V/r Total', name:'total_value', isShow: true},
                                       {label:'Acueducto', name:'aqueduct', isShow: true},
                                       {label:'%', name:'percentage_aqueduct', isShow: true},
                                       {label:'Alcantarillado', name:'sewerage', isShow: true},
                                       {label:'%', name:'percentage_sewerage', isShow: true},
                                       {label:'Aseo', name:'cleanliness', isShow: true},
                                       {label:'%', name:'percentage_cleanliness', isShow: true},
                                 ]"
                                 class-container="col-md-12"
                                 class-table="table table-bordered"
                                 ref="dynamic_list_alternative_budget"
                                 :is-recompute="true"
                                 >
                                 <template #fields="scope">
                                    <div class="row">

                                       <div class="col-md-6">
                                          <!-- Impact Description Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="description">{{ 'trans.Description' | trans }}:</label>
                                             <div class="col-md-9">
                                                <textarea name="description" cols="50" rows="5" id="description" class="form-control" v-model="scope.dataForm.description"></textarea>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Unit Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3 required" for="unit">{{ 'trans.Unit' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" id="unit" :class="{'form-control':true, 'is-invalid':dataErrors.unit}" v-model="scope.dataForm.unit" required>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Quantity Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3 required" for="quantity">{{ 'trans.Quantity' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="number" id="quantity" :class="{'form-control':true, 'is-invalid':dataErrors.quantity }" v-model="scope.dataForm.quantity" required
                                                @keyup="calculateTotal()">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Unit value Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3 required" for="unit_value">{{ 'trans.Unit value' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.unit_value"
                                                   required="true"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateTotal()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Total value Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3 required" for="total_value">{{ 'trans.Total value' | trans }}:</label>
                                             <div class="col-md-9">
                                                <!-- <input type="text" v-model="scope.dataForm.total_value">-->
                                                <currency-input
                                                   v-model="scope.dataForm.total_value"
                                                   required="true"
                                                   readonly="true"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <p class="col-md-12"><strong>Distribución del costo final por servicio:</strong></p>
                                       <div class="col-md-6">
                                          <!-- Aqueduct Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="aqueduct">{{ 'trans.Aqueduct' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.aqueduct"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateCostDistribution()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Percentage Aqueduct Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="percentage_aqueduct">% {{ 'trans.Aqueduct' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="scope.dataForm.percentage_aqueduct" readonly="true" required="true">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Sewerage Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="sewerage">{{ 'trans.Sewerage' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.sewerage"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateCostDistribution()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Percentage Sewerage Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="percentage_sewerage">% {{ 'trans.Sewerage' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="scope.dataForm.percentage_sewerage" readonly="true" required="true">
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="col-md-6">
                                          <!-- Cleanliness Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="cleanliness">{{ 'trans.Cleanliness' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.cleanliness"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateCostDistribution()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Percentage Cleanliness Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="percentage_cleanliness">% {{ 'trans.Cleanliness' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="scope.dataForm.percentage_cleanliness" readonly="true" required="true">
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
                           <h4 class="panel-title"><strong>Costo total directo</strong>:</h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12">
                                 <!-- Total Direct Cost Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="total_direct_cost">Resultado {{ 'trans.Total direct cost' | trans }}:</label>
                                    <div class="col-md-9">
                                       <currency-input
                                          v-model="dataForm.total_direct_cost"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Aqueduct Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="aqueduct">{{ 'trans.Aqueduct' | trans }}:</label>
                                    <div class="col-md-9">
                                       <currency-input
                                          v-model="dataForm.total_direct_aqueduct"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          @keyup="calculateCostType()"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                       <small></small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Percentage Aqueduct Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="total_direct_percentage_aqueduct">% {{ 'trans.Aqueduct' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" v-model="dataForm.total_direct_percentage_aqueduct" readonly="true" required="true">
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Sewerage Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="total_direct_sewerage">{{ 'trans.Sewerage' | trans }}:</label>
                                    <div class="col-md-9">
                                       <currency-input
                                          v-model="dataForm.total_direct_sewerage"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          @keyup="calculateCostType()"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Percentage Sewerage Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="total_direct_percentage_sewerage">% {{ 'trans.Sewerage' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" v-model="dataForm.total_direct_percentage_sewerage" readonly="true" required="true">
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Cleanliness Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="total_direct_cleanliness">{{ 'trans.Cleanliness' | trans }}:</label>
                                    <div class="col-md-9">
                                       <currency-input
                                          v-model="dataForm.total_direct_cleanliness"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          @keyup="calculateCostType()"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Percentage Cleanliness Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="total_direct_percentage_cleanliness">% {{ 'trans.Cleanliness' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" v-model="dataForm.total_direct_percentage_cleanliness" readonly="true" required="true">
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                     <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Tipos de costos</strong> <i>(Tenga en cuenta que la información diligenciada será validada por un funcionario de Planeación, si los costos no estan bien distrubuidos será devuelta la ficha técnica)</i>:</h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <dynamic-list
                                 label-button-add="Agregar ítem a la lista"
                                 :data-list.sync="dataForm.types_costs"
                                 @addedToListEvent="calculateTotalCostProject"
                                 :data-list-options="[
                                       {label:'Tipo de costo', name:'cost_type', isShow: true, refList: 'costTypeRef'},
                                       {label:'V/r Total', name:'total_value', isShow: true},
                                       {label:'Acueducto', name:'aqueduct', isShow: true},
                                       {label:'%', name:'percentage_aqueduct', isShow: true},
                                       {label:'Alcantarillado', name:'sewerage', isShow: true},
                                       {label:'%', name:'percentage_sewerage', isShow: true},
                                       {label:'Aseo', name:'cleanliness', isShow: true},
                                       {label:'%', name:'percentage_cleanliness', isShow: true},
                                 ]"
                                 class-container="col-md-12"
                                 class-table="table table-bordered"
                                 ref="dynamic_list_types_costs"
                                 :is-recompute="true"
                                 >
                                 <template #fields="scope">
                                    <div class="row">

                                       <div class="col-md-9">
                                          <!-- Cost Type Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-2 required" for="sewerage">{{ 'trans.Cost type' | trans }}:</label>
                                             <div class="col-md-9">
                                                <select-check
                                                   css-class="form-control"
                                                   name-field="cost_type"
                                                   reduce-label="name"
                                                   reduce-key="id"
                                                   name-resource="get-constants-active/cost_type"
                                                   :value="scope.dataForm"
                                                   :is-required="true"
                                                   ref-select-check="costTypeRef"
                                                   >
                                                </select-check>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-12">
                                          <!-- Total value Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-2 required" for="total_value">{{ 'trans.Total value' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.total_value"
                                                   required="true"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateCostType()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Aqueduct Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="aqueduct">{{ 'trans.Aqueduct' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.aqueduct"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateCostType()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                                <small></small>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Percentage Aqueduct Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="percentage_aqueduct">% {{ 'trans.Aqueduct' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="scope.dataForm.percentage_aqueduct" readonly="true" required="true">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Sewerage Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="sewerage">{{ 'trans.Sewerage' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.sewerage"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateCostType()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Percentage Sewerage Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="percentage_sewerage">% {{ 'trans.Sewerage' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="scope.dataForm.percentage_sewerage" readonly="true" required="true">
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Cleanliness Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="cleanliness">{{ 'trans.Cleanliness' | trans }}:</label>
                                             <div class="col-md-9">
                                                <currency-input
                                                   v-model="scope.dataForm.cleanliness"
                                                   :currency="{'prefix': '$ '}"
                                                   locale="es"
                                                   class="form-control"
                                                   @keyup="calculateCostType()"
                                                   :key="keyRefresh"
                                                   >
                                                </currency-input>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Percentage Cleanliness Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="percentage_cleanliness">% {{ 'trans.Cleanliness' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="scope.dataForm.percentage_cleanliness" readonly="true" required="true">
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
                           <h4 class="panel-title"><strong>Total del proyecto</strong>:</h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12">
                                 <!-- Total Project Cost Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required" for="total_project_cost">{{ 'trans.Total project cost' | trans }}:</label>
                                    <div class="col-md-10">
                                       <currency-input
                                          v-model="dataForm.total_project_cost"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Aqueduct Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="aqueduct">{{ 'trans.Aqueduct' | trans }}:</label>
                                    <div class="col-md-9">
                                       <currency-input
                                          v-model="dataForm.total_project_aqueduct"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          @keyup="calculateCostType()"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                       <small></small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Percentage Aqueduct Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="percentage_aqueduct">% {{ 'trans.Aqueduct' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" v-model="dataForm.total_project_percentage_aqueduct" readonly="true" required="true">
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Sewerage Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="sewerage">{{ 'trans.Sewerage' | trans }}:</label>
                                    <div class="col-md-9">
                                       <currency-input
                                          v-model="dataForm.total_project_sewerage"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          @keyup="calculateCostType()"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Percentage Sewerage Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="percentage_sewerage">% {{ 'trans.Sewerage' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" v-model="dataForm.total_project_percentage_sewerage" readonly="true" required="true">
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Cleanliness Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="cleanliness">{{ 'trans.Cleanliness' | trans }}:</label>
                                    <div class="col-md-9">
                                       <currency-input
                                          v-model="dataForm.total_project_cleanliness"
                                          required="true"
                                          readonly="true"
                                          :currency="{'prefix': '$ '}"
                                          locale="es"
                                          class="form-control"
                                          @keyup="calculateCostType()"
                                          :key="keyRefresh"
                                          >
                                       </currency-input>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Percentage Cleanliness Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="percentage_cleanliness">% {{ 'trans.Cleanliness' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" v-model="dataForm.total_project_percentage_cleanliness" readonly="true" required="true">
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                  </div>
                  <div class="modal-footer">
                     <button  class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- end #modal-view-alternative-budget -->
</div>
</template>
<script lang="ts">
   import axios from "axios";
   import { Component, Vue, Watch } from "vue-property-decorator";
   import utility from '../../utility';
   import { Locale } from "v-calendar";

   const locale = new Locale();
   
   /**
    * Componente para la gestion del presupuesto alternativo de inversion
    *
    * @author Carlos Moises Garcia T. - Feb. 23 - 2021
    * @version 1.0.0
    */
   @Component
   export default class AlternativeInvestmentBudget extends Vue {
      
      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

      /**
       * Key autoincrementable y unico para
       * ayudar a refrescar un componente
       */
      public keyRefresh: number;

      /**
       * Valida si los valores del formulario
       * son para actualizar o crear
       */
      public isUpdate: boolean;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Feb. 23 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         // Inicializa valores del dataform
         this.dataForm = {
            alternative_budgets: [],
            types_costs: []

         };
         this.dataErrors = {};
         this.keyRefresh = 0;
         this.isUpdate = false;

         this.lang = (this.$parent as any).lang;
      }

      @Watch('dataForm.alternative_budgets')  
      onMensajeChanged(nuevoValor: object, valorAnterior: object) {

         console.log(nuevoValor);

         this.calculateDirectTotalCost(this.dataForm.alternative_budgets);
      }

      /**
       * Calcula el valor total del presupuesto alternativo
       *
       * @author Carlos Moises Garcia T. - Feb. 23 - 2021
       * @version 1.0.0
       *
       */
      public calculateTotal(): void {

         // Obtiene los datos del componente
         let dataForm = this.$refs['dynamic_list_alternative_budget']['dataForm'];
         // Obtiene los valores de la distribucion del presupuesto
         let quantity  = dataForm.quantity? Number(dataForm.quantity) : 0;
         let unitValue = dataForm.unit_value? Number(dataForm.unit_value) : 0;
         // Calcula el total
         let totalValue = quantity * unitValue;
         // Asigna los valores al campo
         dataForm.total_value = totalValue;
         // Fuerza la actualizacion del componente
         (this.$refs['dynamic_list_alternative_budget'] as any).$forceUpdate();
      }

      /**
       * Calcula el el porcentaje de distrubucion por servicio
       *
       * @author Carlos Moisés García T. - Mar - 02 - 2021
       * @version 1.0.0
       *
       */
      public calculateCostDistribution(): void {
         // Obtiene los datos del componente
         let dataForm = this.$refs['dynamic_list_alternative_budget']['dataForm'];

          // Obtiene los valores del tipo de costo
         let totalValue  = dataForm.total_value? Number(dataForm.total_value) : 0;
         let aqueduct    = dataForm.aqueduct? Number(dataForm.aqueduct) : 0;
         let sewerage    = dataForm.sewerage? Number(dataForm.sewerage) : 0;
         let cleanliness = dataForm.cleanliness? Number(dataForm.cleanliness) : 0;

         // Valida si el costo es mayor al valor total
         if (aqueduct > totalValue) {
            // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor del tipo de costo de acueducto no puede ser mayor al valor total.',
            });
            dataForm.aqueduct = null;
            dataForm.percentage_aqueduct = 0;
         } 
         else if (sewerage > totalValue) {
            // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor del tipo de costo de alcantarillado no puede ser mayor al valor total.',
            });
            dataForm.sewerage = null;
            dataForm.percentage_sewerage = 0;
         }
         else if (cleanliness > totalValue) {
            // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor del tipo de costo de aseo no puede ser mayor al valor total.',
            });
            dataForm.cleanliness  = null;
            dataForm.percentage_cleanliness = 0;
         }
         else if ((aqueduct + sewerage + cleanliness) > totalValue) {
             // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor total de los tipos de costo no pueden superar el valor total.',
            });
            dataForm.aqueduct    = 0;
            dataForm.sewerage    = 0;
            dataForm.cleanliness = 0;
            dataForm.percentage_aqueduct    = 0;
            dataForm.percentage_sewerage    = 0;
            dataForm.percentage_cleanliness = 0;
         } else {


            // Calcula los porcentajes del tipo de costo
            let percentageAqueduct    = (aqueduct / totalValue) * 100;
            let percentageSewerage    = (sewerage / totalValue) * 100;
            let percentageCleanliness = (cleanliness / totalValue) * 100;

            // Asigna los porcentajes a los campos
            dataForm.percentage_aqueduct    = Math.round(percentageAqueduct);
            dataForm.percentage_sewerage    = Math.round(percentageSewerage);
            dataForm.percentage_cleanliness = Math.round(percentageCleanliness);
         }
         // Actualiza el componente
         (this.$refs['dynamic_list_alternative_budget'] as any).$forceUpdate();
      }

      /**
       * Calcula el valor total del presupuesto alternativo
       *
       * @author Carlos Moises Garcia T. - Feb. 23 - 2021
       * @version 1.0.0
       *
       */
      public calculateCostType(): void {
         // Obtiene los datos del componente
         let dataForm = this.$refs['dynamic_list_types_costs']['dataForm'];
         // Obtiene los valores del tipo de costo
         let totalValue       = dataForm.total_value? Number(dataForm.total_value) : 0;
         let aqueduct         = dataForm.aqueduct? Number(dataForm.aqueduct) : 0;
         let sewerage         = dataForm.sewerage? Number(dataForm.sewerage) : 0;
         let cleanliness      = dataForm.cleanliness? Number(dataForm.cleanliness) : 0;
         
         // Valida que el valor de acueducto no sea mayor al total
         if (aqueduct > totalValue) {
            // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor del tipo de costo de acueducto no puede ser mayor al valor total.',
            });
            dataForm.aqueduct = null;
            dataForm.percentage_aqueduct = 0;
         } 
         // Valida que el valor de alcantarillado no sea mayor al total
         else if (sewerage > totalValue) {
            // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor del tipo de costo de alcantarillado no puede ser mayor al valor total.',
            });
            dataForm.sewerage = null;
            dataForm.percentage_sewerage = 0;
         }
         // Valida que el valor de aseo no sea mayor al total
         else if (cleanliness > totalValue) {
            // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor del tipo de costo de aseo no puede ser mayor al valor total.',
            });
            dataForm.cleanliness = null;
            dataForm.percentage_cleanliness = 0;
         }
         // Valida que el total de acuducto, alcantarillado y aseo no sea mayor al total
         else if ((aqueduct + sewerage + cleanliness) > totalValue) {
             // Abre el swal
            this.$swal({
               icon: 'warning',
               title: '¡Advertencia!',
               text: 'El valor total de los tipos de costo no pueden superar el valor total.',
            });
            dataForm.aqueduct    = 0;
            dataForm.sewerage    = 0;
            dataForm.cleanliness = 0;
            dataForm.percentage_aqueduct    = 0;
            dataForm.percentage_sewerage    = 0;
            dataForm.percentage_cleanliness = 0;
         } else {
            // Calcula los porcentajes del tipo de costo
            let percentageAqueduct    = (aqueduct / totalValue) * 100;
            let percentageSewerage    = (sewerage / totalValue) * 100;
            let percentageCleanliness = (cleanliness / totalValue) * 100;
            // Asigna los porcentajes a los campos
            dataForm.percentage_aqueduct    = percentageAqueduct.toFixed(2);
            dataForm.percentage_sewerage    = percentageSewerage.toFixed(2);
            dataForm.percentage_cleanliness = percentageCleanliness.toFixed(2);
         }
         // Actualiza el componente
         (this.$refs['dynamic_list_types_costs'] as any).$forceUpdate();
      }

      /**
       * Calcula el costo total directo
       *
       * @author Carlos Moisés García T. - Mar - 02 - 2021
       * @version 1.0.0
       *
       */
      public calculateDirectTotalCost(alternativeBudget) {
         let totalDirectCost = 0;
         let aqueduct        = 0;
         let sewerage        = 0;
         let cleanliness     = 0;

         // console.log(t);
         
         // Recorre los elementos de la lista dinamica
         alternativeBudget.forEach((budget) => {
            // Asigna el total del elemento de la lista
            totalDirectCost += budget.total_value;
            aqueduct += budget.aqueduct;
            sewerage += budget.sewerage;
            cleanliness += budget.cleanliness;
         });
         let percentageAqueduct    = (aqueduct / totalDirectCost) * 100;
         let percentageSewerage    = (sewerage / totalDirectCost) * 100;
         let percentageCleanliness = (cleanliness / totalDirectCost) * 100;
         // Asigna el total al campo
         this.dataForm.total_direct_cost        = totalDirectCost? totalDirectCost: 0;
         this.dataForm.total_direct_aqueduct    = aqueduct? aqueduct: 0;
         this.dataForm.total_direct_sewerage    = sewerage? sewerage: 0;
         this.dataForm.total_direct_cleanliness = cleanliness? cleanliness: 0;
         this.dataForm.total_direct_percentage_aqueduct    = percentageAqueduct? percentageAqueduct.toFixed(2): 0;
         this.dataForm.total_direct_percentage_sewerage    = percentageSewerage? percentageSewerage.toFixed(2): 0;
         this.dataForm.total_direct_percentage_cleanliness = percentageCleanliness? percentageCleanliness.toFixed(2): 0;
         // Llama la funcion que calcula el total del proyecto
         this.calculateTotalCostProject(this.$refs['dynamic_list_types_costs']['dataList']);
         // Fuerza la actualizacion  el componente
         this.$forceUpdate();
      }

      /**
       * Calcula el total del proyecto
       *
       * @author Carlos Moisés García T. - Mar - 02 - 2021
       * @version 1.0.0
       *
       */
      public calculateTotalCostProject(typesCosts) {
         let totalDirectCost        = this.dataForm.total_direct_cost? Number(this.dataForm.total_direct_cost) : 0;
         let totalDirectAqueduct    = this.dataForm.total_direct_aqueduct? Number(this.dataForm.total_direct_aqueduct) : 0;
         let totalDirectSewerage    = this.dataForm.total_direct_sewerage? Number(this.dataForm.total_direct_sewerage) : 0;
         let totalDirectCleanliness = this.dataForm.total_direct_cleanliness? Number(this.dataForm.total_direct_cleanliness) : 0;
         let total       = 0;
         let aqueduct    = 0;
         let sewerage    = 0;
         let cleanliness = 0;
         // Recorre los elementos de la lista dinamica
         typesCosts.forEach((budget) => {
            // Asigna el total del elemento de la lista
            total += budget.total_value;
            aqueduct += budget.aqueduct;
            sewerage += budget.sewerage;
            cleanliness += budget.cleanliness;
         });
         total       += totalDirectCost;
         aqueduct    += totalDirectAqueduct;
         sewerage    += totalDirectSewerage;
         cleanliness += totalDirectCleanliness;
         let percentageAqueduct    = (aqueduct / total) * 100;
         let percentageSewerage    = (sewerage / total) * 100;
         let percentageCleanliness = (cleanliness / total) * 100;
         // Asigna el total al campo
         this.dataForm.total_project_cost = total;
         this.dataForm.total_project_aqueduct    = aqueduct;
         this.dataForm.total_project_sewerage    = sewerage;
         this.dataForm.total_project_cleanliness = cleanliness;
         this.dataForm.total_project_percentage_aqueduct    = percentageAqueduct.toFixed(2);
         this.dataForm.total_project_percentage_sewerage    = percentageSewerage.toFixed(2);
         this.dataForm.total_project_percentage_cleanliness = percentageCleanliness.toFixed(2);
         this.$forceUpdate();
      }

      
      /**
       * Limpia los datos del fomulario
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del formulario
         this.initValues();
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Feb. 23 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadInvestment(dataElement: object): void {
         // console.log(dataElement);
         // Valida que exista datos
         if (dataElement) {
            // Habilita actualizacion de datos
            this.isUpdate = false;

            let data = utility.clone(dataElement);
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm.pc_investment_technical_sheets_id = data.id;
            // console.log(data.alternative_budgets);
            if (data.alternative_budgets) {
               if (data.alternative_budgets.length > 0) {
                  this.dataForm = data.alternative_budgets[0];
                  // data.alternative_budgets.forEach((element) => {
                  //    console.log(element.budgets);
                  //    this.dataForm.alternative_budgets.append(element.budgets);
                  // });

                  this.dataForm.alternative_budgets = data.alternative_budgets[0].budgets;
                  this.dataForm.types_costs = data.alternative_budgets[0].budget_types_costs;
               }
            }

            $(`#modal-form-alternative-budget`).modal('show');
         } else{
            this.isUpdate = false;
            $('#modal-form-categories').modal('show');
         }
      }

      /**
       * Crea el formulario de datos para guardar
       *
       * @author Jhoan Sebastian Chilito S. - Jun. 26 - 2020
       * @version 1.0.0
       */
      public makeFormData(): FormData {
         let formData = new FormData();
         // Recorre los datos del formulario
         for (const key in this.dataForm) {
               if (this.dataForm.hasOwnProperty(key)) {
                  const data = this.dataForm[key];
                  // Valida si no es un objeto y si es un archivo
                  if ( typeof data != 'object' || data instanceof File || data instanceof Date || data instanceof Array) {
                     // Valida si es un arreglo
                     if (Array.isArray(data)) {
                           data.forEach((element) => {
                              if (typeof element == 'object') {
                                 formData.append(`${key}[]`,JSON.stringify(element));
                              } else {
                                 formData.append(`${key}[]`, element);
                              }
                           });
                     } else if (data instanceof Date) {
                           formData.append(key, locale.format(data, "YYYY-MM-DD hh:mm:ss"));
                     } else {
                           formData.append(key, data);
                     }
                  }
               }
         }
         return formData;
      }

      /**
       * Visualiza notificacion por accion
       *
       * @author Jhoan Sebastian Chilito S. - May. 09 - 2020
       * @version 1.0.0
       *
       * @param message mesaje de notificacion
       * @param isPositive valida si la notificacion debe ser posiva o negativa
       * @param title titulo de notificacion
       */
      public pushNotification(title: string = 'OK', message: string, isPositive: boolean = true): void {
         // Datos de notificacion (Por defecto guardar)
         const toastOptions = {
               closeButton: true,
               closeMethod: 'fadeOut',
               timeOut: 3000,
               tapToDismiss: false
         };
         // Valida el tipo de toast que se debe visualiza
         if (isPositive) {
               // Visualiza toast positivo
               toastr.success(message, title, toastOptions);
         } else {
               toastOptions.timeOut = 0;
               // Visualiza toast negativo
               toastr.error(message, title, toastOptions);
         }
      }

      /**
       * Almacena informacion del formulario
       *
       * @author Jhoan Sebastian Chilito S. - Ene. 18 - 2021
       * @version 1.0.0
       */
      public save(): void {

         if (this.dataForm.total_project_cost) {
            // Almacena la informacion del formulario
            this.store();
         } else {
            // Abre el swal
            this.$swal({
               icon: 'warning',
               text: 'Debe ingresar un item al presupuesto',
               allowOutsideClick: false,
            });
         }
      }

      /**
       * Guarda la informacion del formulario dinamico
       *
       * @author Jhoan Sebastian Chilito S. - Ene. 23 - 2021
       * @version 1.0.0
       */
      public store() {
         // Abre el swal de guardando datos
         this.$swal({
            title: 'Guardando',
            allowOutsideClick: false,
            onBeforeOpen: () => {
               (this.$swal as any).showLoading();
            },
         });
         // Construye los datos del formulario
         const formData: FormData = this.makeFormData();
         // Valida que el metodo http sea PUT
         if (this.isUpdate) {
            formData.append('_method', 'put');
         }
         // Envia peticion de guardado de datos
         axios.post('alternative-budget', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {


            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            // Valida el tipo de alerta que de debe mostrar
            if (res.data.type_message) {

               // Valida que el tipo de respuesta sea exitoso
               if (res.data.type_message == "success") {
                  // Cierra fomrulario modal
                  $('#modal-form-alternative-budget').modal('hide');
                  // Limpia datos ingresados
                  this.clearDataForm();
                  // Actualiza elemento modificado en la lista
                  Object.assign((this.$parent as any)._findElementById(res.data.data.id, false), res.data.data);
               }
               // Abre el swal de la respusta de la peticion
               this.$swal({
                  icon: res.data.type_message,
                  html: res.data.message,
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get('trans.Accept')
               });
            } else {
               // Cierra fomrulario modal
               $('#modal-form-alternative-budget').modal('hide');
               // Limpia datos ingresados
               this.clearDataForm();

              // Actualiza elemento modificado en la lista
               Object.assign((this.$parent as any)._findElementById(res.data.data.id, false), res.data.data);
               // Emite notificacion de almacenamiento de datos
               (this.$parent as any)._pushNotification(res.data.message);
            }






            // Valida que se retorne los datos desde el controlador
            // if (res.data.data) {
            //       // Emite evento de guardado para quien lo solicite
            //       this.$emit('saved', { 'data': res.data.data, 'isUpdate': this.isUpdate });
            // }
            // // Cierra el swal de guardando datos
            // (this.$swal as any).close();
            // // Cierra fomrulario modal
            // $('#modal-form-alternative-budget').modal('hide');
            // // Limpia datos ingresados
            // this.clearDataForm();
            // // Emite notificacion de almacenamiento de datos
            // this.pushNotification('Ok', res.data.message, true);



         })
         .catch((err) => {
            (this.$swal as any).close();

            let errors = '';

            // Valida si hay errores asociados al formulario
            if (err.response.data.errors) {
               this.dataErrors = err.response.data.errors;
               // Reocorre la lista de campos del formulario
               for (const key in this.dataForm) {
                  // Valida si existe un error relacionado al campo
                  if (err.response.data.errors[key]) {
                        // Agrega el error a la lista de errores
                        errors += '<br>'+err.response.data.errors[key][0];
                  }
               }
            }
            else if (err.response.data) {
               errors += '<br>'+err.response.data.message;
            }
            // Abre el swal para mostrar los errores
            this.$swal({
               icon: 'error',
               html: this.lang.get('trans.Failed to save data')+errors,
               confirmButtonText: this.lang.get('trans.Accept'),
               allowOutsideClick: false,
            });
         });
      }

      /**
       * Inicializa valores del dataform
       *
       * @author Carlos Moises Garcia T. - Feb. 23 - 2021
       * @version 1.0.0
       */
      public initValues(): void {
         this.dataForm = utility.clone((this.$parent as any).initValues);
         this.dataForm.alternative_budgets = [];
         this.dataForm.types_costs = [];
      }
   }

</script>