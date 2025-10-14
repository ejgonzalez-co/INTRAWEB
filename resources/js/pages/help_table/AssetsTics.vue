<template>
   <div>
      <!-- begin #modal-form-form-categories -->
      <div class="modal fade" id="modal-form-categories">
         <div class="modal-dialog modal-lg">
            <form @submit.prevent="createAsset()" id="form-categories">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Categoría y tipo de activo</h4>
                        <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body">

                     <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Seleccione la categoría y el tipo de activo</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12">
                                 <!-- Category Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="type_tic_category">{{ 'trans.Category' | trans }}:</label>
                                    <div class="col-md-9">
                                       <!-- <select
                                          class="form-control"
                                          v-model="dataForm.type_tic_category"
                                          name="type_tic_category"
                                          @input="loadAssetTypes()"
                                          required>
                                          <option v-for="(category, key) in dataListCategory" :value="category.id" :key="key">{{ category.name }}</option>
                                       </select> -->
                                       <select-check
                                          css-class="form-control"
                                          name-field="type_tic_category"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-tic-type-tic-categories"
                                          :value="dataForm"
                                          :is-required="true"
                                          :function-change="loadAssetTypes"
                                          >
                                       </select-check>
                                       <small>Seleccione una categoría</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-12" v-if="dataForm.type_tic_category">
                                 <!-- Period Validity Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="ht_tic_type_assets_id">{{ 'trans.Tic Type Assets' | trans }}:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="ht_tic_type_assets_id"
                                          reduce-label="name"
                                          reduce-key="id"
                                          :name-resource="'get-tic-type-assets-by-category/'+dataForm.type_tic_category"
                                          :value="dataForm"
                                          :is-required="true"
                                          :key="componentKey"
                                          >
                                       </select-check>
                                       <small>Seleccione el tipo del activo</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>
                  </div>
                  <div class="modal-footer">
                        <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right mr-2"></i>Continuar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- end #modal-form-categories -->

      <!-- begin #modal-form-assets-tics -->
      <div class="modal fade" :id="`modal-form-${this.name}`">
         <div class="modal-dialog modal-xl">
            <form @submit.prevent="save()" id="form-assets-tics">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Formulario de Activos TIC</h4>
                        <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body">

                     <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Datos del activo TIC</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <!-- Period Validity Id Field -->
                              <!--
                              <div class="col-md-6">
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="ht_tic_period_validity_id">{{ 'trans.Tic Period Validities' | trans }}:</label>
                                    <div class="col-md-9">
                                          <select-check
                                             css-class="form-control"
                                             name-field="ht_tic_period_validity_id"
                                             reduce-label="name"
                                             reduce-key="id"
                                             name-resource="get-tic-period-validities"
                                             :value="dataForm"
                                             :is-required="true">
                                          </select-check>
                                          <small>Seleccione la vigencia del activo</small>
                                    </div>
                                 </div>
                              </div>
                              -->

                              <div class="col-md-6">
                                 <!-- Dependency Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="dependencias_id" >{{ 'trans.Dependency' | trans }}:</label>
                                    <div class="col-md-9">
                                          <select-check
                                             css-class="form-control"
                                             name-field="dependencias_id"
                                             reduce-label="nombre"
                                             reduce-key="id"
                                             name-resource="/intranet/get-dependencies"
                                             :value="dataForm"
                                             :enable-search="true"
                                             :is-required="true">
                                          </select-check>
                                          <small>Seleccione la dependencia donde se encuentra ubicado el activo</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Location Address Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="location_address">{{ 'trans.Address' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="location_address" :class="{'form-control':true, 'is-invalid':dataErrors.location_address }" v-model="dataForm.location_address">
                                       <small>Ingrese la dirección donde se encuentra ubicado el activo</small>
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
                           <h4 class="panel-title"><strong>Datos generales del activo Tic</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-6">
                                 <!-- Name Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="name">{{ 'trans.Name' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="name" :class="{'form-control':true, 'is-invalid':dataErrors.name }" v-model="dataForm.name" required>
                                       <small>Ingrese el nombre del activo</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Inventory Plate Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="inventory_plate">{{ 'trans.Inventory Plate' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="inventory_plate" :class="{'form-control':true, 'is-invalid':dataErrors.inventory_plate }" v-model="dataForm.inventory_plate" required>
                                       <small>Ingrese la placa del inventario</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Serial Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="serial">{{ 'trans.Serial' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="serial" :class="{'form-control':true, 'is-invalid':dataErrors.serial }" v-model="dataForm.serial" required>
                                       <small>Ingrese el serial del activo</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Brand Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="brand">{{ 'trans.Brand' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="brand" :class="{'form-control':true, 'is-invalid':dataErrors.brand }" v-model="dataForm.brand" required>
                                       <small>Ingrese la marca del activo</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Model Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="model">{{ 'trans.Model' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="model" :class="{'form-control':true, 'is-invalid':dataErrors.model }" v-model="dataForm.model" required>
                                       <small>Ingrese el modelo del activo</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- General Description Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="general_description">{{ 'trans.General Description' | trans }}:</label>
                                    <div class="col-md-9">
                                       <textarea name="general_description" cols="50" rows="5" id="general_description" class="form-control" v-model="dataForm.general_description"></textarea>
                                       <small>Ingrese una descripción general</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                     <div v-if="dataForm.type_tic_category === 1" class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Datos del Computador</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">
                              <dynamic-list
                                 label-button-add="Agregar ítem a la lista"
                                 :data-list.sync="dataForm.tic_assets_component"
                                 :data-list-options="[
                                    {label:'Nombre', name:'name', isShow: true},
                                    {label:'Fecha de compra', name:'date_purchase', isShow: true},
                                    {label:'Proveedor', name:'provider_name', isShow: true},
                                    {label:'Serial', name:'serial', isShow: true},
                                    {label:'Descripción', name:'description', isShow: true},
                                 ]"
                                 class-container="col-md-12"
                                 class-table="table table-bordered"
                                 :is-remove="false"
                              >
                                 <template #fields="scope">
                                    <div class="row">

                                       <div class="col-md-6">
                                          <!-- Name Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3 required" for="name">{{ 'trans.Name' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" id="name" class="form-control" v-model="scope.dataForm.name" required>
                                                <small>Ingrese el nombre del componente.</small>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Date Purchase Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="date_purchase">{{ 'trans.Purchase Date' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="date" id="date_purchase" class="form-control" v-model="scope.dataForm.date_purchase">
                                                <small>Seleccione la fecha de comprar.</small>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Provider Name Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="provider_name">{{ 'trans.provider_name' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" id="provider_name" class="form-control" v-model="scope.dataForm.provider_name">
                                                <small>Ingrese el nombre del proveedor.</small>
                                             </div>
                                          </div>
                                       </div>


                                       <div class="col-md-6">
                                          <!-- Serial Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="serial">{{ 'trans.Serial' | trans }}:</label>
                                             <div class="col-md-9">
                                                <input type="text" id="serial" class="form-control" v-model="scope.dataForm.serial">
                                                <small>Ingrese el serial del componente si es el caso.</small>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <!-- Description  Field -->
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-3" for="description">{{ 'trans.Description' | trans }}:</label>
                                             <div class="col-md-9">
                                                <textarea name="description" cols="50" rows="5" id="description" class="form-control" v-model="scope.dataForm.description"></textarea>
                                                <small>Ingrese una descripción.</small>
                                             </div>
                                          </div>
                                       </div>

                                    </div>
                                 </template>
                              </dynamic-list>

                              <div class="col-md-6">
                                 <!-- Processor Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="processor">{{ 'trans.Processor' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="processor" :class="{'form-control': true, 'is-invalid': dataErrors.processor}" v-model="dataForm.processor" required>
                                       <small>Ingrese el procesador del computador</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Ram Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="ram">{{ 'trans.Ram' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="ram" :class="{'form-control':true, 'is-invalid':dataErrors.ram }" v-model="dataForm.ram" required>
                                       <small>Ingrese la memoria RAM del computador</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Hdd Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="hdd">{{ 'trans.Hdd' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="hdd" :class="{'form-control':true, 'is-invalid':dataErrors.hdd }" v-model="dataForm.hdd" required>
                                       <small>Ingrese la capacidad del disco duro del computador</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>


                     <div v-if="dataForm.type_tic_category === 1 || dataForm.type_tic_category === 15" class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Descripción del software</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12">
                                 <!--  Operating System Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="operating_system">{{ 'trans.Operating System' | trans }}:</label>
                                    <div class="col-md-12">
                                       <select-check
                                          css-class="form-control"
                                          name-field="operating_system"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-constants/operating_systems"
                                          :value="dataForm"
                                          :is-required="true"
                                          :function-change="getVersionSO"
                                          >
                                       </select-check>
                                       <small>Seleccione el sistema operativo que tiene instalado el computador</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6" v-if="dataForm.operating_system">
                                 <!-- License Microsoft Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="operating_system_version">{{ 'trans.Operating system version' | trans }}:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="operating_system_version"
                                          reduce-label="name"
                                          reduce-key="id"
                                          :name-resource="'get-operating-systems-by-so/'+dataForm.operating_system"
                                          :value="dataForm"
                                          :is-required="true"
                                          :key="keySO"
                                          >
                                       </select-check>
                                       <small>Seleccione la versión del sistema operativo.</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Operating System Serial Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="operating_system_serial">{{ 'trans.Serial Microsoft License' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="operating_system_serial" :class="{'form-control':true, 'is-invalid':dataErrors.operating_system_serial }" v-model="dataForm.operating_system_serial" required>
                                       <small>Ingrese el serial de la licencia</small>
                                    </div>
                                 </div>
                              </div>


                              <div class="col-md-6">
                                 <!--  License Microsoft Office Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="license_microsoft_office">{{ 'trans.License Microsoft Office' | trans }}:</label>
                                    <div class="col-md-9">
                                          <select-check
                                          css-class="form-control"
                                          name-field="license_microsoft_office"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-constants/office_automation_versions"
                                          :value="dataForm"
                                          :is-required="true"
                                          >
                                       </select-check>
                                       <small>Seleccione el tipo de licencia.</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Serial Licencia Microsoft Office Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="serial_licencia_microsoft_office">{{ 'trans.Serial Licencia Microsoft Office' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="text" id="serial_licencia_microsoft_office" :class="{'form-control':true, 'is-invalid':dataErrors.serial_licencia_microsoft_office }" v-model="dataForm.serial_licencia_microsoft_office" required>
                                       <small>Ingrese el serial de la licencia de office</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                     <div v-if="dataForm.type_tic_category === 1 || dataForm.type_tic_category === 15" class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Datos del monitor (Opcional)</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-6">
                                 <!-- Monitor Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="monitor_id">{{ 'trans.Monitor' | trans }}:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="monitor_id"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-tic-assets-by-category/2"
                                          :value="dataForm"
                                          >
                                       </select-check>
                                       <small>Seleccione el monitor asociado al computador.</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                     <div v-if="dataForm.type_tic_category === 1 || dataForm.type_tic_category === 15" class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Datos del teclado (Opcional)</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-6">
                                 <!-- Keyboard Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="keyboard_id">{{ 'trans.Keyboard' | trans }}:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="keyboard_id"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-tic-assets-by-category/13"
                                          :value="dataForm"
                                          >
                                       </select-check>
                                       <small>Seleccione el teclado asociado al computador.</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                     <div v-if="dataForm.type_tic_category === 1 || dataForm.type_tic_category === 15" class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Datos del mouse (Opcional)</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-6">
                                 <!-- Mouse Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="mouse_id">{{ 'trans.Mouse' | trans }}:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="mouse_id"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-tic-assets-by-category/14"
                                          :value="dataForm"
                                          >
                                       </select-check>
                                       <small>Seleccione el mouse asociado al computador.</small>
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
                           <h4 class="panel-title"><strong>Datos extra del activo Tic</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-6">
                                 <!-- Provider Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="ht_tic_provider_id">{{ 'trans.provider_name' | trans }}:</label>
                                    <div class="col-md-9">
                                       <autocomplete
                                          name-prop="name"
                                          name-field="ht_tic_provider_id"
                                          :value='dataForm'
                                          name-resource="get-supplier-users-tic"
                                          css-class="form-control"
                                          :name-labels-display="['name']"
                                          reduce-key="users_id"
                                       >
                                       </autocomplete>
                                       <small>Seleccione el proveedor del activo</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- Purchase Date Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="purchase_date">{{ 'trans.Purchase Date' | trans }}:</label>
                                    <div class="col-md-9">
                                       <date-picker
                                          :value="dataForm"
                                          name-field="purchase_date"
                                       >
                                       </date-picker>
                                       <small>Seleccione la fecha de compra.</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!-- User Id Field -->
                                 <div class="form-group row m-b-15">
                                 <label class="col-form-label col-md-3" for="user_id">{{ 'trans.Functionary' | trans }}:</label>
                                    <div class="col-md-9">
                                       <autocomplete
                                          name-prop="name"
                                          name-field="users_id"
                                          :value='dataForm'
                                          name-resource="/intranet/get-users"
                                          css-class="form-control"
                                          :name-labels-display="['name']"
                                          reduce-key="id"
                                          :key="keyRefresh"
                                       >
                                       </autocomplete>
                                       <small>Seleccione el funcionario del activo</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <!--  State Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3 required" for="state">{{ 'trans.State' | trans }}:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="state"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-constants/state_assets_tic"
                                          :value="dataForm"
                                          :is-required="true"
                                          >
                                       </select-check>
                                       <small>Seleccione el estado.</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                  </div>
                  <div class="modal-footer">
                     <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- end #modal-form-assets-tics -->
   </div>
</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import { jwtDecode } from 'jwt-decode';

   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();

   /**
   * Componente para agregar activos tic a la mesa de ayuda
   *
   * @author Carlos Moises Garcia T. - Oct. 13 - 2020
   * @version 1.0.0
   */
   @Component
   export default class AssetsTics extends Vue {

      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String, required: true, default: 'modal-form-assets-tics' })
      public name: string;

      /**
       * Lista de elementos
       */
      public dataListCategory: any;


      public dataListTypesAssets: any;

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

      public componentKey: number;

      public keySO: number;

      public options: any;

      public series: any;

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
       * @author Carlos Moises Garcia T. - Oct. 13 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();
         this.dataListCategory = {};
         this.dataListTypesAssets = {};
         this.keyRefresh = 0;
         this.componentKey = 0;
         this.keySO = 0;
         this.dataForm = {};
         this.dataErrors = {};
         this.isUpdate = false;

         this.lang = (this.$parent as any).lang;

         this.options= {
            chart: {
               id: 'vuechart-example'
            },
            xaxis: {
               categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998]
            }
         };

         this.series= [{
            name: 'series-1',
            data: [30, 40, 45, 50, 49, 60, 70, 91]
         }];
      }

      /**
       * Se ejecuta cuando el componente ha sido creado
       */
      created() {
         // Carga la lista de elementos de categorias
         this._getDataListCategory();
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del dataform
         this.dataForm = {};
         // Limpia errores
         this.dataErrors = {};
         // Actualiza componente de refresco
         this._updateKeyRefresh();
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
      }

      public getVersionSO(): void {
         this.keySO += 1;
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadAssetTypes(): void {
         this.dataForm.type_assets_tic_id = null;
         this.componentKey += 1;
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public createAsset(): void {
         $('#modal-form-categories').modal('toggle');
         $(".modal").css("overflow-y","auto");
         $(`#modal-form-${this.name}`).modal('show');
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadAssets(dataElement: object): void {

         // Valida que exista datos
         if (dataElement) {
            // Habilita actualizacion de datos
            this.isUpdate = true;
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = utility.clone(dataElement);

            if (this.dataForm.ht_tic_type_assets) {
               this.dataForm.type_tic_category = this.dataForm.tic_type_assets.ht_tic_type_tic_categories_id;
            }

            $(`#modal-form-${this.name}`).modal('show');
         } else {
            this.dataForm.tic_assets_components = [];
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
                     formData.append(key,  locale.format(data, "YYYY-MM-DD hh:mm:ss"));
                  } else {
                     formData.append(key, data);
                  }
               }
            }
         }
         return formData;
      }

      /**
       * Guarda los datos del formulario
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
       * @version 1.0.0
       */
      public save(): void {
         // Limpia los errores anteriores
         this.dataErrors = {};
         // Valida si los datos son para actualizar
         if (this.isUpdate) {
            // Actualiza un registro existente
            this.update();
         } else {
            // Almacena un nuevo registro
            this.store();
         }
      }

      /**
       * Guarda la informacion en base de datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       */
      public store(): void {
         this.$swal({
				title: this.lang.get('trans.loading_save'),
				allowOutsideClick: false,
				onBeforeOpen: () => {
               (this.$swal as any).showLoading();
				},
			});

         // Envia peticion de guardado de datos
         axios.post(this.name, this.makeFormData(), { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({data:{}}, dataPayload);

            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            // Valida el tipo de alerta que de debe mostrar
            if (res.data.type_message) {

               // Valida que el tipo de respuesta sea exitoso
               if (res.data.type_message == "success") {
                  // Cierra fomrulario modal
                  $(`#modal-form-${this.name}`).modal('toggle');
                  // Limpia datos ingresados
                  this.clearDataForm();
                  // Agrega elemento nuevo a la lista
                  (this.$parent as any).dataList.unshift(dataDecrypted.data);
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
               $(`#modal-form-${this.name}`).modal('toggle');
               // Limpia datos ingresados
               this.clearDataForm();

               // Agrega elemento nuevo a la lista
               (this.$parent as any).dataList.unshift(dataDecrypted.data);
               // Emite notificacion de almacenamiento de datos
               (this.$parent as any)._pushNotification(res.data.message);
            }
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
               allowOutsideClick: false,
            });
         });
      }

      /**
       * Actualiza la informacion en base de datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       */
      public update(): void {
         // Abre el swal de guardando datos
         this.$swal({
            title: this.lang.get('trans.loading_update'),
            allowOutsideClick: false,
            onBeforeOpen: () => {
               (this.$swal as any).showLoading();
            },
         });

         const formData: FormData = this.makeFormData();
         formData.append('_method', 'put');

         // Envia peticion de guardado de datos
         axios.post(`${this.name}/${this.dataForm['id']}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({data:{}}, dataPayload);

            // Cierra el swal de guardando datos
            (this.$swal as any).close();

            // Valida el tipo de alerta que de debe mostrar
            if (res.data.type_message) {

               // Valida que el tipo de respuesta sea exitoso
               if (res.data.type_message == "success") {
                  // Cierra fomrulario modal
                  $(`#modal-form-${this.name}`).modal('toggle');
                  // Agrega elemento nuevo a la lista
                  Object.assign((this.$parent as any)._findElementById(this.dataForm['id'], false), dataDecrypted?.data);

                  // Limpia datos ingresados
                  this.clearDataForm();
               }
               // Abre el swal de guardando datos
               this.$swal({
                  icon: res.data.type_message,
                  html: res.data.message,
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get('trans.Accept')
               });
            } else {
               // Cierra fomrulario modal
               $(`#modal-form-${this.name}`).modal('toggle');

               // Agrega elemento nuevo a la lista
               Object.assign((this.$parent as any)._findElementById(this.dataForm['id'], false), dataDecrypted?.data);

               // Limpia datos ingresados
               this.clearDataForm();
               // Emite notificacion de almacenamiento de datos
               (this.$parent as any)._pushNotification(res.data.message);
            }

            // Valida que se retorne los datos desde el controlador
            // if (res.data.data) {
            //    // Actualiza elemento modificado en la lista
            //    Object.assign((this.$parent as any)._findElementById(this.dataForm['id'], false), res.data.data);
            // }
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
               allowOutsideClick: false,
            });
         });
      }

      /**
       * Obtiene la lista de datos
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
       * @version 1.0.0
       */
      private _getDataListCategory(): void {
         // Envia peticion de obtener todos los datos del recurso de categorias
         axios.get('get-tic-type-tic-categories')
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({data:{}}, dataPayload);

            this.dataListCategory = dataDecrypted?.data;
            let data = dataDecrypted?.data;
         })
         .catch((err) => {
            // console.log('Error al obtener la lista.');
            (this.$parent as any)._pushNotification('Error al obtener la lista de datos', false, 'Error');
         });
      }

      /**
       * Obtiene la lista de datos de los tipos de activos
       *
       * @author Carlos Moises Garcia T. - Oct. 26 - 2020
       * @version 1.0.0
       */
      private _getDataListTypesAssets(): void {
         // Envia peticion de obtener todos los datos del recurso de categorias
         axios.get(`get-type-assets-tics-by-category/${this.dataForm.type_tic_category}`)
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({data:{}}, dataPayload);

            this.dataListTypesAssets = dataDecrypted?.data;
            let data = dataDecrypted?.data;
         })
         .catch((err) => {
            // console.log('Error al obtener la lista.');
            (this.$parent as any)._pushNotification('Error al obtener la lista de datos', false, 'Error');
         });
      }

      /**
       * Actualiza el componente que utilize el key
       * cada vez que se cambia de editar a actualizar
       * y borrado de campos de formulario
       *
       * @author Jhoan Sebastian Chilito S. - Jul. 06 - 2020
       * @version 1.0.0
       */
      private _updateKeyRefresh(): void {
         this.keyRefresh ++;
      }
   }
</script>
