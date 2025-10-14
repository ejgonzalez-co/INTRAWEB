<template>
  <div>
    <!-- begin #modal-form-form-responsable-category -->
    <div
      class="modal fade"
      id="modal-form-categories-ini"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog modal-lg">
        <form @submit.prevent="createAsset()" id="form-categories">
          <div class="modal-content border-0">
            <div class="modal-header bg-blue">
              <h4 class="modal-title text-white">Responsable y Categoría</h4>
              <button
                @click="clearDataForm()"
                type="button"
                class="close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                <i class="fa fa-times text-white"></i>
              </button>
            </div>
            <div class="modal-body">
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-body -->
                <div class="panel-body">
                  <!-- Tipo activo Field -->
                  <div class="form-group row m-b-15">
                    <label
                      class="col-form-label col-md-4 required"
                      for="mant_asset_type_id"
                      >{{ "Tipo de activo" | trans }}:</label
                    >
                    <div class="col-md-8">
                      <select-check
                        css-class="form-control"
                        name-field="mant_asset_type_id"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-type-assets"
                        :value="dataForm"
                        :function-change="loadCategory"
                        ref="german"
                        :is-required="true"
                      >
                      </select-check>

                      <small>Seleccione el tipo de activo.</small>
                    </div>
                  </div>
                  <!-- Category Field -->
                  <div class="form-group row m-b-15">
                    <label
                      class="col-form-label col-md-4 required"
                      for="mant_category_id"
                      >{{ "Categoría" | trans }}:</label
                    >
                    <div class="col-md-8">
                      <select-check
                        css-class="form-control"
                        name-field="mant_category_id"
                        reduce-label="name"
                        reduce-key="id"
                        :name-resource="
                          'get-categories-asset/' + dataForm.mant_asset_type_id
                        "
                        :value="dataForm"
                        :function-change="loadResponsable"
                        :key="categoryKey"
                        :is-required="true"
                      >
                      </select-check>
                      <small>Seleccione la categoría del activo.</small>
                    </div>
                  </div>
                  <!-- Responsable Field -->
                  <div class="form-group row m-b-15">
                    <label
                      class="col-form-label col-md-4 required"
                      for="responsable"
                      >{{ "Responsable" | trans }}:</label
                    >
                    <div class="col-md-8">
                      <select-check
                        css-class="form-control"
                        name-field="responsable"
                        reduce-label="name"
                        reduce-key="id"
                        :name-resource="
                          'get-users-authorized/' +
                          dataForm.mant_asset_type_id +
                          '/' +
                          dataForm.mant_category_id
                        "
                        :value="dataForm"
                        :key="responsableKey"
                        :is-required="true"
                      >
                      </select-check>
                      <small>Seleccione el responsable del activos.</small>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
            </div>
            <div class="modal-footer">
              <button
                @click="clearDataForm()"
                class="btn btn-white"
                data-dismiss="modal"
              >
                <i class="fa fa-times mr-2"></i>Cerrar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-arrow-right mr-2"></i>Continuar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- end #modal-form-responsable-category -->

    <!-- begin #modal-form-resume-machinery-vehicles-yellows -->
    <div
      class="modal fade"
      :id="`modal-form-resume-machinery-vehicles-yellows`"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog modal-xl">
        <form @submit.prevent="save()" id="form-assets-tics">
          <div class="modal-content border-0">
            <div class="modal-header bg-blue">
              <h4 class="modal-title text-white">
                Formulario hoja de vida de vehículos y maquinaria amarilla
              </h4>
              <button
                @click="clearDataForm()"
                type="button"
                class="close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                <i class="fa fa-times text-white"></i>
              </button>
            </div>
            <div class="modal-body">
              <!-- Responsable y categoría -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Responsable y categoría</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Tipo activo Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_asset_type_id"
                          >{{ "Tipo de activo" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_asset_type_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-type-assets-full"
                            :value="dataForm"
                            :function-change="loadCategory"
                            ref="german"
                            :is-required="true"
                            :disabled="true"
                          >
                          </select-check>
                          <small>Seleccione el tipo de activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Category Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_category_id"
                          >{{ "Categoría" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_category_id"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-categories-asset/' +
                              dataForm.mant_asset_type_id
                            "
                            :value="dataForm"
                            :function-change="loadResponsable"
                            :key="categoryKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione la categoría del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Responsable Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="responsable"
                          >{{ "Responsable" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="responsable"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-users-authorized/' +
                              dataForm.mant_asset_type_id +
                              '/' +
                              dataForm.mant_category_id
                            "
                            :value="dataForm"
                            :key="responsableKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el responsable del activos.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin panel responsable -->

              <!-- Información inicial -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Información inicial</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Dependency Id Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="dependencias_id"
                          >{{ "Proceso" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="dependencias_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el proceso del activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Vehicle Machinery Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="name_vehicle_machinery"
                          >Nombre del activo:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="name_vehicle_machinery"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name_vehicle_machinery,
                            }"
                            v-model="dataForm.name_vehicle_machinery"
                            required
                          />
                          <small>Ingrese el nombre del Activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- No Inventory Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="no_inventory"
                          >{{ "trans.No Inventory" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_inventory"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_inventory,
                            }"
                            v-model="dataForm.no_inventory"
                          />
                          <small>Ingrese el número de inventario.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Purchase Price Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="purchase_price"
                          >{{ "trans.Purchase Price" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <currency-input
                            v-model="dataForm.purchase_price"
                            :currency="{ prefix: '$ ' }"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                          >
                          </currency-input>
                          <!-- <input type="text" id="purchase_price" :class="{'form-control':true, 'is-invalid':dataErrors.purchase_price }" v-model="dataForm.purchase_price" >                                        -->
                          <small>Ingrese el precio de la compra.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Sheet Elaboration Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="sheet_elaboration_date"
                          >{{ "trans.Sheet Elaboration Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="sheet_elaboration_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.sheet_elaboration_date,
                            }"
                            v-model="dataForm.sheet_elaboration_date"
                          />
                          <small
                            >Seleccione la fecha de elaboración de la hoja de
                            vida.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Mileage Start Activities Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="mileage_start_activities"
                          >{{
                            "trans.Mileage Start Activities" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="number"
                            step="any"
                            id="mileage_start_activities"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mileage_start_activities,
                            }"
                            v-model="dataForm.mileage_start_activities"
                          />
                          <small
                            >Ingrese el kilometraje inicial en Km/Hr.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mark Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mark"
                          >{{ "trans.Mark" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="mark"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mark,
                            }"
                            v-model="dataForm.mark"
                          />
                          <small>Ingrese la marca.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Mark Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mark"
                          >Linea:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="line"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.line,
                            }"
                            v-model="dataForm.line"
                          />
                          <small>Ingrese la linea.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Model Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="model"
                          >{{ "trans.Model" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="model"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.model,
                            }"
                            v-model="dataForm.model"
                          />
                          <small>Ingrese el modelo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- No Motor Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="no_motor"
                          >{{ "trans.No Motor" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_motor"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_motor,
                            }"
                            v-model="dataForm.no_motor"
                          />
                          <small>Ingrese el número de motor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Invoice Number Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="invoice_number"
                          >{{ "trans.Invoice Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="invoice_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.invoice_number,
                            }"
                            v-model="dataForm.invoice_number"
                          />
                          <small>Ingrese el número de la factura.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Date Put Into Service Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="date_put_into_service"
                          >{{ "trans.Date Put Into Service" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="date_put_into_service"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.date_put_into_service,
                            }"
                            v-model="dataForm.date_put_into_service"
                          />
                          <small
                            >Seleccione la fecha de puesta en servicio.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Warranty Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="warranty_date"
                          >{{ "trans.Warranty Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="warranty_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.warranty_date,
                            }"
                            v-model="dataForm.warranty_date"
                          />
                          <small
                            >Seleccione la fecha final de la garantía del
                            vehículo.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Service Retirement Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="service_retirement_date"
                          >{{ "trans.Service Retirement Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="service_retirement_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.service_retirement_date,
                            }"
                            v-model="dataForm.service_retirement_date"
                          />
                          <small
                            >Seleccione la fecha de retiro del servicio.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Warranty description Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="warranty_description"
                          >{{ "trans.Warranty description" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="warranty_description"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.warranty_description,
                            }"
                            v-model="dataForm.warranty_description"
                          />
                          <small>Ingrese la descripción de la garantía.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Warehouse Entry Number Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="warehouse_entry_number"
                          >{{ "trans.Warehouse Entry Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="warehouse_entry_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.warehouse_entry_number,
                            }"
                            v-model="dataForm.warehouse_entry_number"
                          />
                          <small
                            >Ingrese el número de entrada al almacén.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Warehouse Exit Number Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="warehouse_exit_number"
                          >{{ "trans.Warehouse Exit Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="warehouse_exit_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.warehouse_exit_number,
                            }"
                            v-model="dataForm.warehouse_exit_number"
                          />
                          <small
                            >Ingrese el número de salida del almacén.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Delivery Date Vehicle By Provider Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="delivery_date_vehicle_by_provider"
                          >{{
                            "trans.Delivery Date Vehicle By Provider" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="delivery_date_vehicle_by_provider"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.delivery_date_vehicle_by_provider,
                            }"
                            v-model="dataForm.delivery_date_vehicle_by_provider"
                          />
                          <small
                            >Seleccione la fecha de entrega del vehículo por el
                            proveedor.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin panel inicial -->

                            <!-- Panel de asignacion de rubros -->
                            <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Asignación de rubro y centro de costos</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
            
                  <dynamic-list
                    label-button-add="Agregar ítem a la lista"
                    :data-list.sync="dataForm.rubros_asignados"
                    class-table="table-responsive table-bordered"
                    class-container="w-100 p-10"
                    url-eliminar-registro="delete-validate-rubro"
                    :data-list-options="[
                      {
                        label: 'Nombre del rubro',
                        name: 'rubro_id',
                        isShow: true,
                        // nameObjectKey: ['rubros', 'name_heading'],
                        refList: 'rubrosRef'
                      },

                      { label: 'Código del rubro presupuestal',
                       name: 'rubro_codigo',
                       isShow: true },
                       { label: 'Nombre del centro de costos', name: 'centro_costo_id', isShow: true, 
                      //  nameObjectKey: ['centro_costo_informacion', 'name'],
                       refList: 'centro_costo_informacionRef' },
                      { label: 'Código del centro de costos', name: 'centro_costo_codigo', isShow: true }

                      
                    ]"
                  >
                    <template #fields="scope">
                      <div class="form-group row m-b-15">
                        <!-- Accessory Parts Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="rubro_id"
                          >Nombre del rubro:</label
                        >
                        <div class="col-md-4">
                          <!-- <select-check
                              css-class="form-control"
                              name-field="rubro_id"
                              reduce-label="name_heading"
                              reduce-key="id"
                              name-resource="get-heading"
                              :value="scope.dataForm"
                              :is-required="true"
                              :key="keyRefresh"
                              :enable-search="true"
                              ref-select-check="rubrosRef"
                          >
                          </select-check> -->

                          <select-check css-class="form-control" name-field="rubro_id" reduce-label="name_heading"  name-field-object="rubros" name-resource="get-heading" :value="scope.dataForm" 
                          :enable-search="false" 
                          ref-select-check="rubrosRef"
                          ></select-check>

                          <small>Seleccione nombre del rubro.</small>
                        </div>
                        <!--Código del rubro presupuestal -->
                       
                        <div class="col-md-4">
                         
                          <input-dynamic
                                :v-model="scope.dataForm.rubro_codigo"
                                name-resource="get-cod-item/"
                                :id-resource="parseInt(scope.dataForm.rubro_id)"
                                name-field="rubro_codigo"
                                :value="scope.dataForm"
                                :key="scope.dataForm.rubro_id"
                            ></input-dynamic>
                        </div>
                      </div>

                      <div class="form-group row m-b-15">
                        <!-- Accessory Parts Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="centro_costo_id"
                          >Nombre del centro de costos:</label
                        >
                        <div class="col-md-4">
                          <select-check
                              css-class="form-control"
                              name-field="centro_costo_id"
                              reduce-label="name"
                              reduce-key="id"
                              name-resource="get-center-cost"
                              :value="scope.dataForm"
                              name-field-object="centro_costo_informacion"
                              :enable-search="false"
                              ref-select-check="centro_costo_informacionRef"
                          >
                          </select-check>
                          <small>Seleccione nombre del centro de costos. </small>
                        </div>
                        <!--Código del rubro presupuestal -->
                       
                        <div class="col-md-4">
                         
                          <input-dynamic
                                label-text="Código del centro de costos"
                                :v-model="scope.dataForm.centro_costo_codigo"
                                name-resource="get-cod-center-item/"
                                :id-resource="parseInt(scope.dataForm.centro_costo_id)"
                                name-field="centro_costo_codigo"
                                :value="scope.dataForm"
                                :key="scope.dataForm.centro_costo_id"
                            ></input-dynamic>
                        </div>
                      </div>
                    </template>
                  </dynamic-list>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel asignacion de rubros -->

              <!-- Características del vehículo o maquinaria amarilla -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong
                      >Características del vehículo o maquinaria
                      amarilla</strong
                    >
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Plaque Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="plaque"
                          >{{ "trans.Plaque" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="plaque"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.plaque,
                            }"
                            v-model="dataForm.plaque"
                          />
                          <small>Ingrese la placa.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Color Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="color"
                          >{{ "trans.Color" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="color"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.color,
                            }"
                            v-model="dataForm.color"
                          />
                          <small>Ingrese el color.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Chassis Number Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="chassis_number"
                          >{{ "trans.Chassis Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="chassis_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.chassis_number,
                            }"
                            v-model="dataForm.chassis_number"
                          />
                          <small>Ingrese el número de chasis.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Service Class Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="service_class"
                          >{{ "trans.Service Class" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="service_class"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.service_class,
                            }"
                            v-model="dataForm.service_class"
                          />
                          <small>Ingrese la clase de servicio.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Body Type Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="body_type"
                          >{{ "trans.Body Type" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            v-model="dataForm.body_type"
                            name="body_type"
                            class="form-control"
                          >
                            <option value="Amplirrol">Amplirrol</option>
                            <option value="Camión doble cabina estacas">
                              Camión doble cabina estacas
                            </option>
                            <option value="Camabaja">
                            Camabaja
                            </option>
                            <option value="Camión estacas">
                              Camión estacas
                            </option>
                            <option value="Camión recolector doble troque">
                              Camión recolector doble troque
                            </option>
                            <option value="Camión recolector sencillo">
                              Camión recolector sencillo
                            </option>
                            <option value="Camioneta">Camioneta</option>
                            <option value="Camioneta doble cabina">
                              Camioneta doble cabina
                            </option>
                            <option value="Carrotanque">Carrotanque</option>
                            <option value="Minicargados">Minicargados</option>
                            <option value="Motocarro">Motocarro</option>
                            <option value="Retroexcavadora">
                              Retroexcavadora
                            </option>
                            <option value="Furgón sencillo">
                              Furgón sencillo
                            </option>
                            <option value="Camion presion-succion">Camion presion-succión</option>
                            <option value="Van">Van</option>
                            <option value="Volqueta">Volqueta</option>
                          </select>
                          <small>Seleccione el tipo de carrocería.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Transit License Number Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="transit_license_number"
                          >{{ "trans.Transit License Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="transit_license_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.transit_license_number,
                            }"
                            v-model="dataForm.transit_license_number"
                          />
                          <small
                            >Ingrese el número de la licencia de
                            tránsito.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Number Passengers Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="number_passengers"
                          >{{ "trans.Number Passengers" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="number_passengers"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.number_passengers,
                            }"
                            v-model="dataForm.number_passengers"
                          />
                          <small>Ingrese la cantidad de pasajeros.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Fuel Type Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="fuel_type"
                          >{{ "trans.Fuel Type" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            v-model="dataForm.fuel_type"
                            name="fuel_type"
                            class="form-control"
                          >
                            <option value="Diesel">Diesel</option>
                            <option value="Gasolina">Gasolina</option>
                          </select>
                          <small>Seleccione el tipo de combustible.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Number Tires Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="number_tires"
                          >{{ "trans.Number Tires" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="number_tires"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.number_tires,
                            }"
                            v-model="dataForm.number_tires"
                            disabled
                          />
                          <small>Ingrese la cantidad de llantas.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Front Tire Reference Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="front_tire_reference"
                          >{{ "trans.Front Tire Reference" | trans }}:</label
                        >
                        <div class="col-md-8">
                        <ul>
                            <li v-for="(lt, key) in this.frontReferences" :key="key">
                                {{ lt }}
                            </li>
                        </ul>
                          <small
                            >referencia de las llantas delanteras.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Rear Tire Reference Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="rear_tire_reference"
                          >{{ "trans.Rear Tire Reference" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <ul>
                            <li v-for="(lt, key) in this.rearReferences" :key="key">
                                {{ lt }}
                            </li>
                        </ul>
                          <small
                            >referencia de las llantas traseras.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Number Batteries Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="number_batteries"
                          >{{ "trans.Number Batteries" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="number_batteries"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.number_batteries,
                            }"
                            v-model="dataForm.number_batteries"
                          />
                          <small>Ingrese la cantidad de baterías.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Battery Reference Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="battery_reference"
                          >{{ "trans.Battery Reference" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="battery_reference"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.battery_reference,
                            }"
                            v-model="dataForm.battery_reference"
                          />
                          <small>Ingrese la referencia de las baterías.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Gallon Tank Capacity Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="gallon_tank_capacity"
                          >{{ "trans.Gallon Tank Capacity" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="number"
                            step="0.01"
                            id="gallon_tank_capacity"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.gallon_tank_capacity,
                            }"
                            v-model="dataForm.gallon_tank_capacity"
                          />
                          <small
                            >Ingrese la capacidad del tanque de combustible en
                            galones.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Tons Capacity Load Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="tons_capacity_load"
                          >{{ "trans.Tons Capacity Load" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <currency-input
                            v-model="dataForm.tons_capacity_load"
                            :currency="{ prefix: ' ' }"
                            locale="es"
                            class="form-control"
                            :key="keyRefresh"
                          >
                          </currency-input>
                          <small
                            >Ingrese la capacidad de carga en toneladas.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Cylinder Capacity Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="cylinder_capacity"
                          >{{ "trans.Cylinder Capacity" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="cylinder_capacity"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.cylinder_capacity,
                            }"
                            v-model="dataForm.cylinder_capacity"
                          />
                          <small>Ingrese el cilindraje.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Expiration Date Soat Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="expiration_date_soat"
                          >{{ "trans.Expiration Date Soat" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="expiration_date_soat"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.expiration_date_soat,
                            }"
                            v-model="dataForm.expiration_date_soat"
                          />
                          <small
                            >Seleccione la fecha de vencimiento del SOAT.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Expiration Date Tecnomecanica Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="expiration_date_tecnomecanica"
                          >{{
                            "trans.Expiration Date Tecnomecanica" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="expiration_date_tecnomecanica"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.expiration_date_tecnomecanica,
                            }"
                            v-model="dataForm.expiration_date_tecnomecanica"
                          />
                          <small
                            >Seleccione la fecha de vencimiento de la
                            tecnicomecánicaa.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Person Prepares Resume Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="person_prepares_resume"
                          >{{ "trans.Person Prepares Resume" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="person_prepares_resume"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.person_prepares_resume,
                            }"
                            v-model="dataForm.person_prepares_resume"
                          />
                          <small
                            >Ingrese la persona que elabora la hoja de
                            vida.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Person Reviewed Approved Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="person_reviewed_approved"
                          >{{
                            "trans.Person Reviewed Approved" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="person_reviewed_approved"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.person_reviewed_approved,
                            }"
                            v-model="dataForm.person_reviewed_approved"
                          />
                          <small>Ingrese la persona que reviso y aprobó.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel características -->

              <!-- Proveedor -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Proveedor</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Identification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="identification"
                          >{{ "trans.Identification_nombre" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <autocomplete
                            :is-update="isUpdate"
                            :value-default="dataForm.provider"
                            name-prop="identification"
                            name-field="mant_providers_id"
                            :value="dataForm"
                            name-resource="get-providers"
                            css-class="form-control"
                            :name-labels-display="['identification', 'name']"
                            reduce-key="id"
                            :match-selected="selectProvider"
                            asignar-al-data=""
                            :key="keyRefresh"
                            :is-required="true"
                          >
                          </autocomplete>
                          <small
                            >Ingrese el número de identificación o nombre del
                            proveedor.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Type Person Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="type_person"
                          >{{ "trans.Type Person" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.type_person"
                            name="type_person"
                            id="type_person"
                          >
                            <option value="Natural">Natural</option>
                            <option value="Jurídica">Jurídica</option>
                          </select>
                          <small>Seleccione el tipo de persona.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Document Type Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="document_type"
                          >{{ "trans.Document Type" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.document_type"
                            name="document_type"
                            id="document_type"
                          >
                            <option value="Cédula de ciudadanía">
                              Cédula de ciudadanía
                            </option>
                            <option value="Cédula de extranjería">
                              Cédula de extranjería
                            </option>
                            <option value="NIT">NIT</option>
                          </select>
                          <small>Seleccione el tipo de documento.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="name"
                          >{{ "trans.Name" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="name"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name,
                            }"
                            v-model="dataForm.name"
                          />
                          <small>Ingrese el nombre del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mail Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mail"
                          >{{ "trans.Mail" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="mail"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mail,
                            }"
                            v-model="dataForm.mail"
                          />
                          <small
                            >Ingrese un correo electrónico válido, ej:
                            xxxxx@gmail.com.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Regime Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="regime"
                          >{{ "trans.Regime" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.regime"
                            name="regime"
                            id="regime"
                          >
                            <option value="Simplificado">Simplificado</option>
                            <option value="Común">Común</option>
                            <option value="Ordinario">Ordinario</option>
                          </select>
                          <small>Seleccione el régimen del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Phone Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="phone"
                          >{{ "trans.Phone" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="phone"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.phone,
                            }"
                            v-model="dataForm.phone"
                          />
                          <small>Ingrese el teléfono del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Address Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="address"
                          >{{ "trans.Address" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="address"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.address,
                            }"
                            v-model="dataForm.address"
                          />
                          <small>Ingrese la dirección del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Municipality Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="municipality"
                          >{{ "trans.Municipality" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="municipality"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.municipality,
                            }"
                            v-model="dataForm.municipality"
                          />
                          <small>Ingrese el municipio del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Department Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="department"
                          >{{ "trans.Department" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="department"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.department,
                            }"
                            v-model="dataForm.department"
                          />
                          <small>Ingrese el departamento del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel proveedor -->

              <!-- Observaciones -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Observaciones</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Observation Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="observation"
                          >{{ "trans.Observation" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="observation"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.observation,
                            }"
                            v-model="dataForm.observation"
                          ></textarea>
                          <small>Ingrese alguna observación.</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <!-- Status Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="status"
                          >{{ "trans.Status" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            v-model="dataForm.status"
                            name="status"
                            class="form-control"
                          >
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Dado de baja">Dado de baja</option>
                          </select>
                          <small>Seleccione el estado del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel observaciones -->
            </div>
            <div class="modal-footer">
              <button
                @click="clearDataForm()"
                class="btn btn-white"
                data-dismiss="modal"
              >
                <i class="fa fa-times mr-2"></i>Cerrar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save mr-2"></i>Guardar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- end #modal-form-resume-machinery-vehicles-yellows -->

    <!-- begin #modal-form-resume-equipment-machineries -->
    <div
      class="modal fade"
      :id="`modal-form-resume-equipment-machineries`"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog modal-xl">
        <form @submit.prevent="save()" id="form-assets-tics">
          <div class="modal-content border-0">
            <div class="modal-header bg-blue">
              <h4 class="modal-title text-white">
                Formulario hoja de vida de los equipos menores
              </h4>
              <button
                @click="clearDataForm()"
                type="button"
                class="close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                <i class="fa fa-times text-white"></i>
              </button>
            </div>
            <div class="modal-body">
              <!-- Responsable y categoría -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Responsable y categoría</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Tipo activo Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_asset_type_id"
                          >{{ "Tipo de activo" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_asset_type_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-type-assets-full"
                            :value="dataForm"
                            :function-change="loadCategory"
                            ref="german"
                            :is-required="true"
                            :disabled="true"
                          >
                          </select-check>
                          <small>Seleccione el tipo de activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Category Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_category_id"
                          >{{ "Categoría" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_category_id"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-categories-asset/' +
                              dataForm.mant_asset_type_id
                            "
                            :value="dataForm"
                            :function-change="loadResponsable"
                            :key="categoryKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione la categoría del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Responsable Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="responsable"
                          >{{ "Responsable" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="responsable"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-users-authorized/' +
                              dataForm.mant_asset_type_id +
                              '/' +
                              dataForm.mant_category_id
                            "
                            :value="dataForm"
                            :key="responsableKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el responsable del activos.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin panel responsable -->
    
              <!-- Panel de asignacion de rubros -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Asignación de rubro y centro de costos</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
            
                  <dynamic-list
                    label-button-add="Agregar ítem a la lista"
                    :data-list.sync="dataForm.rubros_asignados"
                    class-table="table-responsive table-bordered"
                    url-eliminar-registro="delete-validate-rubro"
                    class-container="w-100 p-10"
                    :data-list-options="[
                      {
                        label: 'Nombre del rubro',
                        name: 'rubro_id',
                        isShow: true,
                        // nameObjectKey: ['rubros', 'name_heading'],
                        refList: 'rubrosRef'
                      },

                      { label: 'Código del rubro presupuestal',
                       name: 'rubro_codigo',
                       isShow: true },
                       { label: 'Nombre del centro de costos', name: 'centro_costo_id', isShow: true, 
                      //  nameObjectKey: ['centro_costo_informacion', 'name'],
                       refList: 'centro_costo_informacionRef' },
                      { label: 'Código del centro de costos', name: 'centro_costo_codigo', isShow: true }

                      
                    ]"
                  >
                    <template #fields="scope">
                      <div class="form-group row m-b-15">
                        <!-- Accessory Parts Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="rubro_id"
                          >Nombre del rubro:</label
                        >
                        <div class="col-md-4">
                          <!-- <select-check
                              css-class="form-control"
                              name-field="rubro_id"
                              reduce-label="name_heading"
                              reduce-key="id"
                              name-resource="get-heading"
                              :value="scope.dataForm"
                              :is-required="true"
                              :key="keyRefresh"
                              :enable-search="true"
                              ref-select-check="rubrosRef"
                          >
                          </select-check> -->

                          <select-check css-class="form-control" name-field="rubro_id" reduce-label="name_heading"  name-field-object="rubros" name-resource="get-heading" :value="scope.dataForm" 
                          :enable-search="false" 
                          ref-select-check="rubrosRef"
                          ></select-check>

                          <small>Seleccione nombre del rubro.</small>
                        </div>
                        <!--Código del rubro presupuestal -->
                       
                        <div class="col-md-4">
                         
                          <input-dynamic
                                :v-model="scope.dataForm.rubro_codigo"
                                name-resource="get-cod-item/"
                                :id-resource="parseInt(scope.dataForm.rubro_id)"
                                name-field="rubro_codigo"
                                :value="scope.dataForm"
                                :key="scope.dataForm.rubro_id"
                            ></input-dynamic>
                        </div>
                      </div>

                      <div class="form-group row m-b-15">
                        <!-- Accessory Parts Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="centro_costo_id"
                          >Nombre del centro de costos:</label
                        >
                        <div class="col-md-4">
                          <select-check
                              css-class="form-control"
                              name-field="centro_costo_id"
                              reduce-label="name"
                              reduce-key="id"
                              name-resource="get-center-cost"
                              :value="scope.dataForm"
                              name-field-object="centro_costo_informacion"
                              :enable-search="false"
                              ref-select-check="centro_costo_informacionRef"
                          >
                          </select-check>
                          <small>Seleccione nombre del centro de costos. </small>
                        </div>
                        <!--Código del rubro presupuestal -->
                       
                        <div class="col-md-4">
                         
                          <input-dynamic
                                :v-model="scope.dataForm.centro_costo_codigo"
                                name-resource="get-cod-center-item/"
                                :id-resource="parseInt(scope.dataForm.centro_costo_id)"
                                name-field="centro_costo_codigo"
                                :value="scope.dataForm"
                                :key="scope.dataForm.centro_costo_id"
                            ></input-dynamic>
                        </div>
                      </div>
                    </template>
                  </dynamic-list>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel asignacion de rubros -->
              

              <!-- Información general aqui -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Información general</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Dependency Id Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="dependencias_id"
                          >{{ "Proceso" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="dependencias_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el proceso del activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Equipment Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="name_equipment"
                          >{{ "trans.Name Equipment" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="name_equipment"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name_equipment,
                            }"
                            v-model="dataForm.name_equipment"
                          />
                          <small
                            >Ingrese el nombre del equipo o maquinaria.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- No Identification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="no_identification"
                          >{{ "trans.No Identification" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_identification"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_identification,
                            }"
                            v-model="dataForm.no_identification"
                          />
                          <small>Ingrese el número de identificación.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Description For Operation Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="consecutive"
                          >Consecutivo:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="consecutive"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.consecutive,
                            }"
                            v-model="dataForm.consecutive"
                          />
                          <small>Ingrese el consecutivo.</small>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="row">
                                         <div class="col-md-6">
                      <!-- No Inventory Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="no_inventory"
                          >{{ "trans.No Inventory" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_inventory"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_inventory,
                            }"
                            v-model="dataForm.no_inventory"
                          />
                          <small>Ingrese el número de inventario.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Mark Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mark"
                          >{{ "trans.Mark" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="mark"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mark,
                            }"
                            v-model="dataForm.mark"
                          />
                          <small>Ingrese la marca.</small>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="row">
                                         <div class="col-md-6">
                      <!-- Model Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="model"
                          >{{ "trans.Model" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="model"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.model,
                            }"
                            v-model="dataForm.model"
                          />
                          <small>Ingrese el modelo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Serie Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="serie"
                          >{{ "trans.Serie" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="serie"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.serie,
                            }"
                            v-model="dataForm.serie"
                          />
                          <small>Ingrese la serie.</small>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="row">
                                         <div class="col-md-6">
                      <!-- Ubication Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="ubication"
                          >{{ "trans.Ubication" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="ubication"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.ubication,
                            }"
                            v-model="dataForm.ubication"
                          />
                          <small>Ingrese la ubicación del activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Purchase Price Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="purchase_price"
                          >{{ "trans.Purchase Price" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <currency-input
                            v-model="dataForm.purchase_price"
                            :currency="{ prefix: '$ ' }"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                          >
                          </currency-input>
                          <!-- <input type="text" id="purchase_price" :class="{'form-control':true, 'is-invalid':dataErrors.purchase_price }" v-model="dataForm.purchase_price" >                                        -->
                          <small>Ingrese el precio de la compra.</small>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="row">
                                         <div class="col-md-6">
                      <!-- No Invoice Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="no_invoice"
                          >{{ "trans.Invoice Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_invoice"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_invoice,
                            }"
                            v-model="dataForm.no_invoice"
                          />
                          <small>Ingrese el número de la factura.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Warehouse Entry Number Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="warehouse_entry_number"
                          >{{ "trans.Warehouse Entry Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="warehouse_entry_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.warehouse_entry_number,
                            }"
                            v-model="dataForm.warehouse_entry_number"
                          />
                          <small>Ingrese la entrada al almacén.</small>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                                         <div class="col-md-6">
                      <!-- Type Number The Acquisition Contract Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="type_number_the_acquisition_contract"
                          >{{
                            "trans.Type Number The Acquisition Contract"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="type_number_the_acquisition_contract"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.type_number_the_acquisition_contract,
                            }"
                            v-model="
                              dataForm.type_number_the_acquisition_contract
                            "
                          />
                          <small
                            >Ingrese la modalidad y número de contrato de
                            adquisición.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Equipment Warranty Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="equipment_warranty"
                          >{{ "trans.Equipment Warranty" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="equipment_warranty"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.equipment_warranty,
                            }"
                            v-model="dataForm.equipment_warranty"
                          />
                          <small
                            >Seleccione la fecha final de la garantía del
                            equipo.</small
                          >
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                                         <div class="col-md-6">
                      <!-- Requirement For Operation Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="requirement_for_operation"
                          >{{
                            "trans.Requirement For Operation" | trans
                          }}:</label
                        >
                        <div v-if="dataForm.mant_category_id == 104 " class="col-md-8">
                          <select
                            disabled
                            v-model="dataForm.requirement_for_operation"
                            name="requirement_for_operation"
                            class="form-control"
                            id="requirement_for_operation"
                          >
                            <option value="Neumático">Neumático</option>
                            <option value="Energía eléctrica">
                              Energía eléctrica
                            </option>
                            <option value="Combustible">Combustible</option>
                            <option value="Electrónico">Electrónico</option>
                            <option value="Manual">Manual</option>
                          </select>
                          <small
                            >Seleccione el requerimiento para el
                            funcionamiento.</small
                          >
                        </div>


                        <div v-else class="col-md-8">
                          <select
                          
                            v-model="dataForm.requirement_for_operation"
                            name="requirement_for_operation"
                            class="form-control"
                          >
                            <option value="Neumático">Neumático</option>
                            <option value="Energía eléctrica">
                              Energía eléctrica
                            </option>
                            <option value="Combustible">Combustible</option>
                            <option value="Electrónico">Electrónico</option>
                            <option value="Manual">Manual</option>
                          </select>
                          <small
                            >Seleccione el requerimiento para el
                            funcionamiento.</small
                          >
                        </div>



                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Description For Operation Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="description_for_operation"
                          >{{
                            "trans.Description For Operation" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="description_for_operation"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.description_for_operation,
                            }"
                            v-model="dataForm.description_for_operation"
                          />
                          <small
                            >Ingrese la descripción para el
                            funcionamiento.</small
                          >
                        </div>
                      </div>
                    </div>

                  </div>
<!-- ____inicio desarrollador leo -->
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Dependency Id Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="dependencias_id"
                          >Tipo de combustible:</label
                        >
                       
                        <div  class="col-md-8">
                          <select
                            v-model="dataForm.fuel_type"
                            name="fuel_type"
                            class="form-control"
                            >
                            <option value="Gasolina">Gasolina</option>
                            <option value="ACPM">ACPM </option>
                            <option value="No aplica">No aplica</option>
                          </select>
                          <small>Seleccione el tipo de combustible.</small>
                        </div>
                      </div>
                    </div>
                  </div>

<!-- _________fin desarrollador leo_______________ -->



                </div>
              </div>
              <!-- Fin panel general -->

              <!-- Características -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Características</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <dynamic-list
                    label-button-add="Agregar ítem a la lista"
                    :data-list.sync="dataForm.characteristics_equipment"
                    class-table="table-responsive table-bordered"
                    class-container="w-100 p-10"
                    :data-list-options="[
                      {
                        label: 'Partes y/o accesorios',
                        name: 'accessory_parts',
                        isShow: true,
                      },
                      { label: 'Cantidad', name: 'amount', isShow: true },
                      {
                        label: 'Referencia o número de parte',
                        name: 'reference_part_number',
                        isShow: true,
                      },
                    ]"
                  >
                    <template #fields="scope">
                      <div class="form-group row m-b-15">
                        <!-- Accessory Parts Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="accessory_parts"
                          >{{ "trans.Accessory Parts" | trans }}:</label
                        >
                        <div class="col-md-4">
                          <input
                            type="text"
                            id="accessory_parts"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.accessory_parts,
                            }"
                            v-model="scope.dataForm.accessory_parts"
                            required
                          />
                          <small>Ingrese las partes y/o accesorios</small>
                        </div>
                        <!-- Amount Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="amount"
                          >{{ "trans.Amount" | trans }}:</label
                        >
                        <div class="col-md-4">
                          <input
                            type="text"
                            id="amount"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.amount,
                            }"
                            v-model="scope.dataForm.amount"
                            required
                          />
                          <small>Ingrese la cantidad</small>
                        </div>
                      </div>

                      <div class="form-group row m-b-15">
                        <!-- Reference Part Number Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="reference_part_number"
                          >{{ "trans.Reference Part Number" | trans }}:</label
                        >
                        <div class="col-md-4">
                          <input
                            type="text"
                            id="reference_part_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.reference_part_number,
                            }"
                            v-model="scope.dataForm.reference_part_number"
                            required
                          />
                          <small>Ingrese la referencia o numero de la parte</small>
                        </div>
                      </div>
                    </template>
                  </dynamic-list>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel características -->

              <!-- Panel Mantenimiento, verificación y estado actual -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Mantenimiento, verificación y estado actual</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Catalog Specifications Id Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="catalog_specifications"
                          >{{
                            "trans.Catalog Specifications Id" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            v-model="dataForm.catalog_specifications"
                            name="catalog_specifications"
                            class="form-control"
                          >
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                          </select>
                          <small
                            >Seleccione si tiene catálogo o
                            especificaciones.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div
                      class="col-md-6"
                      v-if="dataForm.catalog_specifications == 'Si'"
                    >
                      <!-- Location Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="location"
                          >{{ "trans.Location" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="location"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.location,
                            }"
                            v-model="dataForm.location"
                          />
                          <small>Ingrese la ubicación.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div
                      class="col-md-6"
                      v-if="dataForm.catalog_specifications == 'Si'"
                    >
                      <!-- Language Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="language"
                          >{{ "trans.Language" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="language"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.language,
                            }"
                            v-model="dataForm.language"
                          />
                          <small>Ingrese el Idioma.</small>
                        </div>
                      </div>
                    </div>
                    <div
                      class="col-md-6"
                      v-if="dataForm.catalog_specifications == 'Si'"
                    >
                      <!-- Version Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="version"
                          >{{ "trans.Version" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="version"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.version,
                            }"
                            v-model="dataForm.version"
                          />
                          <small>Ingrese la versión.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Technical Verification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="technical_verification"
                          >{{ "trans.Technical Verification" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            @change="_maintenanceChange($event)"
                            v-model="dataForm.technical_verification"
                            name="technical_verification"
                            class="form-control"
                          >
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                          </select>
                          <small
                            >Seleccione si tiene verificación técnica.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Technical Verification Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="technical_verification_frequency"
                          >{{
                            "trans.Technical Verification Frequency" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            :disabled="dataForm.technical_verification == 'No'"
                            type="text"
                            id="technical_verification_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.technical_verification_frequency,
                            }"
                            v-model="dataForm.technical_verification_frequency"
                          />
                          <small
                            >Ingrese la frecuencia de la verificación
                            técnica.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Preventive Maintenance Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="preventive_maintenance"
                          >{{ "trans.Preventive Maintenance" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            @change="_maintenanceChange($event)"
                            v-model="dataForm.preventive_maintenance"
                            name="preventive_maintenance"
                            class="form-control"
                          >
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                          </select>
                          <small
                            >Seleccione si tiene mantenimiento
                            preventivo.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Preventive Maintenance Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="preventive_maintenance_frequency"
                          >{{
                            "trans.Preventive Maintenance Frequency" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            :disabled="dataForm.preventive_maintenance == 'No'"
                            type="text"
                            id="preventive_maintenance_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.preventive_maintenance_frequency,
                            }"
                            v-model="dataForm.preventive_maintenance_frequency"
                          />
                          <small
                            >Ingrese la frecuencia del mantenimiento
                            preventivo.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Person Responsible Team Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="person_responsible_team"
                          >{{ "trans.Person Responsible Team" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="person_responsible_team"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.person_responsible_team,
                            }"
                            v-model="dataForm.person_responsible_team"
                          />
                          <small
                            >Ingrese la persona responsable del equipo.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Person Prepares Resume Equipment Machinery Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="person_prepares_resume_equipment_machinery"
                          >{{
                            "trans.Person Prepares Resume Equipment Machinery"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="person_prepares_resume_equipment_machinery"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.person_prepares_resume_equipment_machinery,
                            }"
                            v-model="
                              dataForm.person_prepares_resume_equipment_machinery
                            "
                          />
                          <small
                            >Ingrese la persona que elabora la hoja de vida del
                            equipo o maquinaria.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel Mantenimiento, verificación y estado actual -->

              <!-- Panel información almacén -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Información almacén</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Purchase Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="purchase_date"
                          >{{ "trans.Purchase Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="purchase_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.purchase_date,
                            }"
                            v-model="dataForm.purchase_date"
                          />
                          <small>Seleccione la fecha de compra.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Service Start Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="service_start_date"
                          >{{ "trans.Service Start Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="service_start_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.service_start_date,
                            }"
                            v-model="dataForm.service_start_date"
                          />
                          <small
                            >Seleccione la fecha de inicio del servicio.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Retirement Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="retirement_date"
                          >{{ "trans.Retirement Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="retirement_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.retirement_date,
                            }"
                            v-model="dataForm.retirement_date"
                          />
                          <small
                            >Seleccione la fecha de retiro (salida
                            servicio).</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel información almacén -->

              <!-- Proveedor -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Proveedor</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Identification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="identification"
                          >{{ "trans.Identification_nombre" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <autocomplete
                            :is-update="isUpdate"
                            :value-default="dataForm.provider"
                            name-prop="identification"
                            name-field="mant_providers_id"
                            :value="dataForm"
                            name-resource="get-providers"
                            css-class="form-control"
                            :name-labels-display="['identification', 'name']"
                            reduce-key="id"
                            :match-selected="selectProvider"
                            asignar-al-data=""
                            :key="keyRefresh"
                            :is-required="true"
                          >
                          </autocomplete>
                          <small
                            >Ingrese el número de identificación o nombre del
                            proveedor.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Type Person Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="type_person"
                          >{{ "trans.Type Person" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.type_person"
                            name="type_person"
                            id="type_person"
                          >
                            <option value="Natural">Natural</option>
                            <option value="Jurídica">Jurídica</option>
                          </select>
                          <small>Seleccione el tipo de persona.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Document Type Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="document_type"
                          >{{ "trans.Document Type" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.document_type"
                            name="document_type"
                            id="document_type"
                          >
                            <option value="Cédula de ciudadanía">
                              Cédula de ciudadanía
                            </option>
                            <option value="Cédula de extranjería">
                              Cédula de extranjería
                            </option>
                            <option value="NIT">NIT</option>
                          </select>
                          <small>Seleccione el tipo de documento.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="name"
                          >{{ "trans.Name" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="name"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name,
                            }"
                            v-model="dataForm.name"
                          />
                          <small>Ingrese el nombre del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mail Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mail"
                          >{{ "trans.Mail" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="mail"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mail,
                            }"
                            v-model="dataForm.mail"
                          />
                          <small
                            >Ingrese un correo electrónico válido, ej:
                            xxxxx@gmail.com.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Regime Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="regime"
                          >{{ "trans.Regime" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.regime"
                            name="regime"
                            id="regime"
                          >
                            <option value="Simplificado">Simplificado</option>
                            <option value="Común">Común</option>
                            <option value="Ordinario">Ordinario</option>
                          </select>
                          <small>Seleccione el régimen del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Phone Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="phone"
                          >{{ "trans.Phone" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="phone"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.phone,
                            }"
                            v-model="dataForm.phone"
                          />
                          <small>Ingrese el teléfono del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Address Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="address"
                          >{{ "trans.Address" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="address"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.address,
                            }"
                            v-model="dataForm.address"
                          />
                          <small>Ingrese la dirección del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Municipality Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="municipality"
                          >{{ "trans.Municipality" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="municipality"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.municipality,
                            }"
                            v-model="dataForm.municipality"
                          />
                          <small>Ingrese el municipio del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Department Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="department"
                          >{{ "trans.Department" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="department"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.department,
                            }"
                            v-model="dataForm.department"
                          />
                          <small>Ingrese el departamento del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel proveedor -->

              <!-- Observaciones -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Observaciones</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Observation Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="observation"
                          >{{ "trans.Observation" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="observation"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.observation,
                            }"
                            v-model="dataForm.observation"
                          ></textarea>
                          <small>Ingrese alguna observación.</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <!-- Status Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="status"
                          >{{ "trans.Status" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            v-model="dataForm.status"
                            name="status"
                            class="form-control"
                          >
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Obsoleto o depreciado">
                              Obsoleto o depreciado
                            </option>
                            <option value="Dado de baja">Dado de baja</option>
                          </select>
                          <small>Seleccione el estado del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel observaciones -->
            </div>
            <div class="modal-footer">
              <button
                @click="clearDataForm()"
                class="btn btn-white"
                data-dismiss="modal"
              >
                <i class="fa fa-times mr-2"></i>Cerrar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save mr-2"></i>Guardar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- end #modal-form-resume-equipment-machineries -->

    <!-- begin #modal-form-resume-equipment-machinery-lecas -->
    <div
      class="modal fade"
      :id="`modal-form-resume-equipment-machinery-lecas`"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog modal-xl">
        <form @submit.prevent="save()" id="form-assets-tics">
          <div class="modal-content border-0">
            <div class="modal-header bg-blue">
              <h4 class="modal-title text-white">
                Formulario hoja de vida plantas y medidores ICM-R-002
              </h4>
              <button
                @click="clearDataForm()"
                type="button"
                class="close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                <i class="fa fa-times text-white"></i>
              </button>
            </div>
            <div class="modal-body">
              <!-- Responsable y categoría -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Responsable y categoría</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Tipo activo Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_asset_type_id"
                          >{{ "Tipo de activo" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_asset_type_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-type-assets-full"
                            :value="dataForm"
                            :function-change="loadCategory"
                            ref="german"
                            :is-required="true"
                            :disabled="true"
                          >
                          </select-check>
                          <small>Seleccione el tipo de activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Category Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_category_id"
                          >{{ "Categoría" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_category_id"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-categories-asset/' +
                              dataForm.mant_asset_type_id
                            "
                            :value="dataForm"
                            :function-change="loadResponsable"
                            :key="categoryKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione la categoría del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Responsable Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="responsable"
                          >{{ "Responsable" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="responsable"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-users-authorized/' +
                              dataForm.mant_asset_type_id +
                              '/' +
                              dataForm.mant_category_id
                            "
                            :value="dataForm"
                            :key="responsableKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el responsable del activos.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin panel responsable -->

              <!-- Descripción del equipo -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Descripción del equipo</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Dependency Id Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="dependencias_id"
                          >{{ "Proceso" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="dependencias_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el proceso del activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Equipment Machinery Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="name_equipment_machinery"
                          >{{
                            "trans.Name Equipment Machinery" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="name_equipment_machinery"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name_equipment_machinery,
                            }"
                            v-model="dataForm.name_equipment_machinery"
                          />
                          <small
                            >Ingrese el nombre del equipamiento o
                            maquinaria.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- No Identification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="no_identification"
                          >{{ "trans.No Identification" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_identification"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_identification,
                            }"
                            v-model="dataForm.no_identification"
                          />
                          <small>Ingrese el número de identificación.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- No Inventory Epa Esp Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="no_inventory_epa_esp"
                          >{{ "trans.No Inventory Epa Esp" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_inventory_epa_esp"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_inventory_epa_esp,
                            }"
                            v-model="dataForm.no_inventory_epa_esp"
                          />
                          <small>Ingrese el número de inventario.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mark Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mark"
                          >{{ "trans.Mark" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="mark"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mark,
                            }"
                            v-model="dataForm.mark"
                          />
                          <small>Ingrese la marca.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Model Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="model"
                          >{{ "trans.Model" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="model"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.model,
                            }"
                            v-model="dataForm.model"
                          />
                          <small>Ingrese el modelo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Serie Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="serie"
                          >{{ "trans.Serie" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="serie"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.serie,
                            }"
                            v-model="dataForm.serie"
                          />
                          <small>Ingrese la serie.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Location Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="location"
                          >Precio de compra:</label
                        >
                        <div class="col-md-8">
                          <currency-input
                            v-model.number="dataForm.purchase_price"
                            :currency="{'prefix': '$ '}"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                            >
                            </currency-input>
                          
                          <small>Ingrese el precio de compra.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-md-6">
                      <!-- Location Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="location"
                          >{{ "trans.Location" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="location"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.location,
                            }"
                            v-model="dataForm.location"
                          />
                          <small>Ingrese la ubicación del activo.</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <!-- Path Information Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="path_information"
                          >{{ "trans.Path Information" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="path_information"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.path_information,
                            }"
                            v-model="dataForm.path_information"
                          />
                          <small
                            >Ingrese la ruta de acceso a la información.</small
                          >
                        </div>
                      </div>
                    </div>
                    
                  </div>

                  <div class="row">
                    
                    <div class="col-md-6">
                      <!-- Acquisition Contract Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="acquisition_contract"
                          >{{ "trans.Acquisition Contract" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="acquisition_contract"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.acquisition_contract,
                            }"
                            v-model="dataForm.acquisition_contract"
                          />
                          <small>Ingrese el contrato de adquisición.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <!-- Fin descripción del equipo -->

              <!-- Proveedor -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Proveedor</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Identification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="identification"
                          >{{ "trans.Identification_nombre" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <autocomplete
                            :is-update="isUpdate"
                            :value-default="dataForm.provider"
                            name-prop="identification"
                            name-field="mant_providers_id"
                            :value="dataForm"
                            name-resource="get-providers"
                            css-class="form-control"
                            :name-labels-display="['identification', 'name']"
                            reduce-key="id"
                            :match-selected="selectProvider"
                            asignar-al-data=""
                            :key="keyRefresh"
                            :is-required="true"
                          >
                          </autocomplete>
                          <small
                            >Ingrese el número de identificación o nombre del
                            proveedor.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Type Person Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="type_person"
                          >{{ "trans.Type Person" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.type_person"
                            name="type_person"
                            id="type_person"
                          >
                            <option value="Natural">Natural</option>
                            <option value="Jurídica">Jurídica</option>
                          </select>
                          <small>Seleccione el tipo de persona.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Document Type Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="document_type"
                          >{{ "trans.Document Type" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.document_type"
                            name="document_type"
                            id="document_type"
                          >
                            <option value="Cédula de ciudadanía">
                              Cédula de ciudadanía
                            </option>
                            <option value="Cédula de extranjería">
                              Cédula de extranjería
                            </option>
                            <option value="NIT">NIT</option>
                          </select>
                          <small>Seleccione el tipo de documento.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="name"
                          >{{ "trans.Name" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="name"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name,
                            }"
                            v-model="dataForm.name"
                          />
                          <small>Ingrese el nombre del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mail Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mail"
                          >{{ "trans.Mail" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="mail"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mail,
                            }"
                            v-model="dataForm.mail"
                          />
                          <small
                            >Ingrese un correo electrónico válido, ej:
                            xxxxx@gmail.com.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Regime Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="regime"
                          >{{ "trans.Regime" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.regime"
                            name="regime"
                            id="regime"
                          >
                            <option value="Simplificado">Simplificado</option>
                            <option value="Común">Común</option>
                            <option value="Ordinario">Ordinario</option>
                          </select>
                          <small>Seleccione el régimen del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Phone Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="phone"
                          >{{ "trans.Phone" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="phone"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.phone,
                            }"
                            v-model="dataForm.phone"
                          />
                          <small>Ingrese el teléfono del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Address Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="address"
                          >{{ "trans.Address" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="address"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.address,
                            }"
                            v-model="dataForm.address"
                          />
                          <small>Ingrese la dirección del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Municipality Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="municipality"
                          >{{ "trans.Municipality" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="municipality"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.municipality,
                            }"
                            v-model="dataForm.municipality"
                          />
                          <small>Ingrese el municipio del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Department Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="department"
                          >{{ "trans.Department" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="department"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.department,
                            }"
                            v-model="dataForm.department"
                          />
                          <small>Ingrese el departamento del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel proveedor -->

              <!-- Composición del equipamiento o maquinaria -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Composición del equipamiento o maquinaria</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <dynamic-list
                    label-button-add="Agregar ítem a la lista"
                    :data-list.sync="dataForm.composition_equipment_leca"
                    class-table="table-responsive table-bordered"
                    class-container="w-100 p-10"
                    :data-list-options="[
                      {
                        label: 'Partes y/o accesorios',
                        name: 'accessory_parts',
                        isShow: true,
                      },
                      { label: 'Referencia', name: 'reference', isShow: true },
                    ]"
                  >
                    <template #fields="scope">
                      <div class="form-group row m-b-15">
                        <!-- Accessory Parts Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="accessory_parts"
                          >{{ "trans.Accessory Parts" | trans }}:</label
                        >
                        <div class="col-md-4">
                          <input
                            type="text"
                            id="accessory_parts"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.accessory_parts,
                            }"
                            v-model="scope.dataForm.accessory_parts"
                            required
                          />
                          <small>Ingrese las partes y/o accesorios</small>
                        </div>
                        <!-- Reference Field -->
                        <label
                          class="col-form-label col-md-2 required"
                          for="reference"
                          >{{ "trans.Reference" | trans }}:</label
                        >
                        <div class="col-md-4">
                          <input
                            type="text"
                            id="reference"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.reference,
                            }"
                            v-model="scope.dataForm.reference"
                            required
                          />
                          <small>Ingrese la referencia</small>
                        </div>
                      </div>
                    </template>
                  </dynamic-list>
                </div>
                <!-- end panel-body -->
                <div class="panel-body">
                  <div class="form-group row m-b-15" style="padding-left: 10px">
                    <!-- Observation Field -->
                    <label class="col-form-label col-md-2" for="observation"
                      >{{ "trans.Observation" | trans }}:</label
                    >
                    <div class="col-md-4">
                      <input
                        type="text"
                        id="observation"
                        :class="{
                          'form-control': true,
                          'is-invalid': dataErrors.observation_composition,
                        }"
                        v-model="dataForm.observation_composition"
                      />
                      <small>Ingrese alguna observación</small>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin panel composición del equipamiento o maquinaria -->

              <!-- Catálogo o especificaciones técnicas -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Catálogo o especificaciones técnicas</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Apply Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="apply"
                          >{{ "trans.Apply" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            v-model="dataForm.apply"
                            name="apply"
                            class="form-control"
                          >
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                          </select>
                          <small>Seleccione una opción.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" v-if="dataForm.apply == 'Si'">
                      <!-- Location Specification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="location_specification"
                          >{{ "trans.Location Specification" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="location_specification"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.location_specification,
                            }"
                            v-model="dataForm.location_specification"
                          />
                          <small>Ingrese la ubicación.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6" v-if="dataForm.apply == 'Si'">
                      <!-- Language Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="language"
                          >{{ "trans.Language" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="language"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.language,
                            }"
                            v-model="dataForm.language"
                          />
                          <small>Ingrese el Idioma.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" v-if="dataForm.apply == 'Si'">
                      <!-- Version Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="version"
                          >{{ "trans.Version" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="version"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.version,
                            }"
                            v-model="dataForm.version"
                          />
                          <small>Ingrese la versión.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Purchase Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="purchase_date"
                          >{{ "trans.Purchase Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="purchase_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.purchase_date,
                            }"
                            v-model="dataForm.purchase_date"
                          />
                          <small>Seleccione la fecha de compra.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Commissioning Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="commissioning_date"
                          >{{ "trans.Commissioning Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="commissioning_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.commissioning_date,
                            }"
                            v-model="dataForm.commissioning_date"
                          />
                          <small
                            >Seleccione la fecha de puesta en servicio.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Date Withdrawal Service Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="date_withdrawal_service"
                          >{{ "trans.Date Withdrawal Service" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="date_withdrawal_service"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.date_withdrawal_service,
                            }"
                            v-model="dataForm.date_withdrawal_service"
                          />
                          <small
                            >Seleccione la fecha de retiro de servicio.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Observations Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="observations"
                          >{{ "trans.Observations" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="observations"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.observations,
                            }"
                            v-model="dataForm.observations"
                          />
                          <small>Ingrese las observaciones.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Vo Bo Name Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="vo_bo_name"
                          >{{ "trans.Vo Bo Name" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="vo_bo_name"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.vo_bo_name,
                            }"
                            v-model="dataForm.vo_bo_name"
                          />
                          <small>Ingrese el Vo.Bo nombre.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Vo Bo Cargo Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="vo_bo_cargo"
                          >{{ "trans.Vo Bo Cargo" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="vo_bo_cargo"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.vo_bo_cargo,
                            }"
                            v-model="dataForm.vo_bo_cargo"
                          />
                          <small>Ingrese el Vo.Bo cargo.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel catálogo o especificaciones técnicas -->

              <!-- Características -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Características</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Magnitude Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="magnitude"
                          >{{ "trans.Magnitude" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="magnitude"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.magnitude,
                            }"
                            v-model="dataForm.magnitude"
                          />
                          <small>Ingrese la magnitud.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Unit Measurement Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="unit_measurement"
                          >{{ "trans.Unit Measurement" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="unit_measurement"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.unit_measurement,
                            }"
                            v-model="dataForm.unit_measurement"
                          />
                          <small>Ingrese la unidad de medida.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Scale Division Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="scale_division"
                          >{{ "trans.Scale Division" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="scale_division"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.scale_division,
                            }"
                            v-model="dataForm.scale_division"
                          />
                          <small>Ingrese la división escala.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Manufacturer Specification Max Permissible Error Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="manufacturer_specification_max_permissible_error"
                          >{{
                            "trans.Manufacturer Specification Max Permissible Error"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="manufacturer_specification_max_permissible_error"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.manufacturer_specification_max_permissible_error,
                            }"
                            v-model="
                              dataForm.manufacturer_specification_max_permissible_error
                            "
                          />
                          <small
                            >Ingrese el error máximo permisible especificación
                            del fabricante.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Max Permissible Error Technical Standard Process Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="max_permissible_error_technical_standard_process"
                          >{{
                            "trans.Max Permissible Error Technical Standard Process"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="max_permissible_error_technical_standard_process"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.max_permissible_error_technical_standard_process,
                            }"
                            v-model="
                              dataForm.max_permissible_error_technical_standard_process
                            "
                          />
                          <small
                            >Ingrese el error máximo permisible norma técnica o
                            proceso.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Measurement Range Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="measurement_range"
                          >{{ "trans.Measurement Range" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="measurement_range"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.measurement_range,
                            }"
                            v-model="dataForm.measurement_range"
                          />
                          <small>Ingrese el rango de medición.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Operation Range Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="operation_range"
                          >{{ "trans.Operation Range" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="operation_range"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.operation_range,
                            }"
                            v-model="dataForm.operation_range"
                          ></textarea>
                          <small>Ingrese el rango de operación.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Use Parameter Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="use_parameter"
                          >{{ "trans.Use Parameter" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="use_parameter"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.use_parameter,
                            }"
                            v-model="dataForm.use_parameter"
                          />
                          <small>Ingrese el uso de parámetro.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Use Recommendations Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="use_recommendations"
                          >{{ "trans.Use Recommendations" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="use_recommendations"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.use_recommendations,
                            }"
                            v-model="dataForm.use_recommendations"
                          />
                          <small>Ingrese las recomendaciones de uso.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Resolution Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="resolution"
                          >{{ "trans.Resolution" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="resolution"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.resolution,
                            }"
                            v-model="dataForm.resolution"
                          ></textarea>
                          <small>Ingrese la resolución.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Analog Indication Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="analog_indication"
                          >{{ "trans.Analog Indication" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="analog_indication"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.analog_indication,
                            }"
                            v-model="dataForm.analog_indication"
                          />
                          <small>Ingrese la indicación análoga.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Digital Indication Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="digital_indication"
                          >{{ "trans.Digital Indication" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="digital_indication"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.digital_indication,
                            }"
                            v-model="dataForm.digital_indication"
                          />
                          <small>Ingrese la indicación digital.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Wavelength Indication Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="wavelength_indication"
                          >{{ "trans.Wavelength Indication" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="wavelength_indication"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.wavelength_indication,
                            }"
                            v-model="dataForm.wavelength_indication"
                          />
                          <small>Ingrese la indicación longitud de onda.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Adsorption Indication Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="adsorption_indication"
                          >{{ "trans.Adsorption Indication" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="adsorption_indication"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.adsorption_indication,
                            }"
                            v-model="dataForm.adsorption_indication"
                          />
                          <small>Ingrese la indicación de adsorción.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Feeding Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="feeding"
                          >{{ "trans.Feeding" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="feeding"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.feeding,
                            }"
                            v-model="dataForm.feeding"
                          />
                          <small>Ingrese la alimentación.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Voltage Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="voltage"
                          >{{ "trans.Voltage" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="voltage"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.voltage,
                            }"
                            v-model="dataForm.voltage"
                          />
                          <small>Ingrese el voltaje.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- RH Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="RH"
                          >Humedad relativa:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="RH"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.RH,
                            }"
                            v-model="dataForm.RH"
                          />
                          <small>Ingrese la humedad relativa.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Power Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="power"
                          >{{ "trans.Power" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="power"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.power,
                            }"
                            v-model="dataForm.power"
                          />
                          <small>Ingrese la potencia.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Temperature Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="temperature"
                          >{{ "trans.Temperature" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="temperature"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.temperature,
                            }"
                            v-model="dataForm.temperature"
                          />
                          <small>Ingrese la temperatura.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="frequency"
                          >{{ "trans.Frequency" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.frequency,
                            }"
                            v-model="dataForm.frequency"
                          />
                          <small>Ingrese la frecuencia.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Revolutions Per Minute Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="revolutions_per_minute"
                          >{{ "trans.Revolutions Per Minute" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="revolutions_per_minute"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.revolutions_per_minute,
                            }"
                            v-model="dataForm.revolutions_per_minute"
                          />
                          <small>Ingrese las revoluciones por minuto.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Type Protection Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="type_protection"
                          >{{ "trans.Type Protection" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="type_protection"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.type_protection,
                            }"
                            v-model="dataForm.type_protection"
                          />
                          <small>Ingrese el tipo de protección.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Rated Current Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="rated_current"
                          >{{ "trans.Rated Current" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="rated_current"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.rated_current,
                            }"
                            v-model="dataForm.rated_current"
                          />
                          <small>Ingrese la corriente nominal.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Rated Power Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="rated_power"
                          >{{ "trans.Rated Power" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="rated_power"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.rated_power,
                            }"
                            v-model="dataForm.rated_power"
                          />
                          <small>Ingrese la potencia nominal.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Operating Conditions Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="operating_conditions"
                          >{{ "trans.Operating Conditions" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="operating_conditions"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.operating_conditions,
                            }"
                            v-model="dataForm.operating_conditions"
                          />
                          <small>Ingrese las condiciones de operación.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel características-->

              <!-- Mantenimiento, calibración, verificación y/o comprobación -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong
                      >Mantenimiento, calibración, verificación y/o
                      comprobación</strong
                    >
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-3">
                      <!-- Calibration Validation External Verification Field -->
                      <div class="form-group row m-b-15">
                        <b
                          ><label
                            class="col-form-label col-md-8"
                            for="calibration_validation_external_verification"
                            >{{
                              "trans.Calibration Validation External Verification"
                                | trans
                            }}:</label
                          ></b
                        >
                        <!-- <div class="col-md-8">
                                                <input type="text" id="calibration_validation_external_verification"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.calibration_validation_external_verification }"
                                                      v-model="dataForm.calibration_validation_external_verification"
                                                      >
                                                <small>Ingrese la calibración, validación y/o verificación externa.</small>
                                             </div> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Calibration Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="calibration_frequency"
                          >{{ "trans.Calibration Frequency" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="calibration_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.calibration_frequency,
                            }"
                            v-model="dataForm.calibration_frequency"
                          />
                          <small>Ingrese la frecuencia.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <!-- Preventive Maintenance Field -->
                      <div class="form-group row m-b-15">
                        <b
                          ><label
                            class="col-form-label col-md-8"
                            for="preventive_maintenance"
                            >{{
                              "trans.Preventive Maintenance" | trans
                            }}:</label
                          ></b
                        >
                        <!-- <div class="col-md-8">
                                                <input type="text" id="preventive_maintenance"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.preventive_maintenance }"
                                                      v-model="dataForm.preventive_maintenance" >
                                                <small>Seleccione el mantenimiento preventivo.</small>
                                             </div> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Maintenance Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="maintenance_frequency"
                          >{{ "trans.Maintenance Frequency" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="maintenance_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.maintenance_frequency,
                            }"
                            v-model="dataForm.maintenance_frequency"
                          />
                          <small>Ingrese la frecuencia.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <!-- Verification Internal Verification Field -->
                      <div class="form-group row m-b-15">
                        <b
                          ><label
                            class="col-form-label col-md-8"
                            for="verification_internal_verification"
                            >{{
                              "trans.Verification Internal Verification"
                                | trans
                            }}:</label
                          ></b
                        >
                        <!-- <div class="col-md-8">
                                                <input type="text" id="verification_internal_verification"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.verification_internal_verification }"
                                                      v-model="dataForm.verification_internal_verification" >
                                                <small>Ingrese la verificación y/o comprobación interna.</small>
                                             </div> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Verification Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="verification_frequency"
                          >{{ "trans.Verification Frequency" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="verification_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.verification_frequency,
                            }"
                            v-model="dataForm.verification_frequency"
                          />
                          <small>Ingrese la frecuencia.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Procedure Code Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="procedure_code"
                          >{{ "trans.Procedure Code" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="procedure_code"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.procedure_code,
                            }"
                            v-model="dataForm.procedure_code"
                          />
                          <small
                            >Ingrese el código de procedimiento e instructivo de
                            verificación interno (cuando aplique).</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- <div class="row">
                                    <div class="col-md-6">
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-4 " for="calibration_points">{{
                                                'trans.Calibration Points' | trans }}:</label>
                                             <div class="col-md-8">
                                                <textarea id="calibration_points"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.calibration_points }"
                                                      v-model="dataForm.calibration_points" ></textarea>
                                                <small>Ingrese los puntos de calibración, validación, verificación y/o
                                                      comprobación (valor nominal).</small>
                                             </div>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-4 "
                                                for="calibration_under_accreditation">{{ 'trans.Calibration Under Accreditation' | trans }}:</label>
                                             <div class="col-md-8">
                                                <select class="form-control"
                                                      v-model="dataForm.calibration_under_accreditation" name="regime"
                                                      id="calibration_under_accreditation" >
                                                      <option value="Si">Si</option>
                                                      <option value="No">No</option>
                                                </select>
                                                <small>Seleccione la si aplica calibración bajo acreditación.</small>
                                             </div>
                                          </div>
                                    </div>
                                 </div>

                                 <div class="row">
                                    <div class="col-md-6">
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-4 " for="reference_norm">{{
                                                'trans.Reference Norm' | trans }}:</label>
                                             <div class="col-md-8">
                                                <textarea id="reference_norm"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.reference_norm }"
                                                      v-model="dataForm.reference_norm" ></textarea>
                                                <small>Ingrese la norma de referencia de calibración, validación, y/o
                                                      comprobación (cuando aplique).</small>
                                             </div>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-4 " for="measure_pattern">{{
                                                'trans.Measure Pattern' | trans }}:</label>
                                             <div class="col-md-8">
                                                <input type="text" id="measure_pattern"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.measure_pattern }"
                                                      v-model="dataForm.measure_pattern" >
                                                <small>Ingrese el patrón de medida.</small>
                                             </div>
                                          </div>
                                    </div>
                                 </div>

                                 <div class="row">
                                    <div class="col-md-6">
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-4 " for="criteria_acceptance">{{
                                                'trans.Criteria Acceptance' | trans }}:</label>
                                             <div class="col-md-8">
                                                <input type="text" id="criteria_acceptance"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.criteria_acceptance }"
                                                      v-model="dataForm.criteria_acceptance" >
                                                <small>Ingrese los criterios de aceptación de certificado de calibración o
                                                      informe de validación, verificación, y/o comprobación (Datos
                                                      suministrados por la norma de ensayo, calibración o por el
                                                      fabricante).</small>
                                             </div>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="form-group row m-b-15">
                                             <label class="col-form-label col-md-4 " for="calibration_test">{{
                                                'trans.Calibration Test' | trans }}:</label>
                                             <div class="col-md-8">
                                                <input type="text" id="calibration_test"
                                                      :class="{'form-control':true, 'is-invalid':dataErrors.calibration_test }"
                                                      v-model="dataForm.calibration_test" >
                                                <small>Ingrese la prueba de calibración, validación, verificación y/o
                                                      comprobación.</small>
                                             </div>
                                          </div>
                                    </div>
                                 </div> -->
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel mantenimiento, calibración, verificación y/o comprobación -->

              <div class="panel" data-sortable-id="ui-general-1">
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Punto de calibración</strong>
                  </h4>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <dynamic-list
                      label-button-add="Agregar ítem a la lista"
                      :data-list.sync="dataForm.maintenance_equipment_leca"
                      class-table="table-responsive table-bordered"
                      class-container="w-100 p-10"
                      :data-list-options="[
                        {
                          label:
                            'Puntos de Calibración, Validación, verificación y/o Comprobación(valor nominal)',
                          name: 'verification',
                          isShow: true,
                        },
                        {
                          label: 'Calibración Bajo Acreditación',
                          name: 'calibration_under_accreditation',
                          isShow: true,
                        },
                        {
                          label:
                            'Norma de Referencia de Calibración, Validación, verificación y/o Comprobación(cuando aplique)',
                          name: 'rule_reference_calibration',
                          isShow: true,
                        },
                        {
                          label:
                            'Nombre Pruebas de Calibración, Validación, Verificación y/o Comprobación',
                          name: 'name',
                          isShow: true,
                        },
                        {
                          label:
                            'Criterios de aceptación Pruebas de Calibración, Validación, Verificación y/o Comprobación',
                          name: 'acceptance_requirements',
                          isShow: true,
                        },
                        {
                          label: 'Patrón de medida',
                          name: 'measure_standard',
                          isShow: true,
                        },
                        {
                          label:
                            'Criterios de aceptación de certificado de calibración o informe de validación, verificación y/o Comprobación(Datos suministrados por la norma de ensayo, de calibración o por el fabricante)',
                          name: 'criteria_acceptance_certificate',
                          isShow: true,
                        },
                      ]"
                    >
                      <template #fields="scope">
                        <div class="form-group row m-b-15">
                          <!-- Name Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="verification"
                            >Puntos de Calibración, Validación, verificación y/o
                            Comprobación(valor nominal):</label
                          >
                          <div class="col-md-4">
                            <input
                              type="text"
                              id="verification"
                              :class="{
                                'form-control': true,
                                'is-invalid': dataErrors.verification,
                              }"
                              v-model="scope.dataForm.verification"
                              required
                            />
                            <small
                              >Ingrese los Puntos de Calibración, Validación,
                              verificación y/o la comprobación(valor
                              nominal)</small
                            >
                          </div>
                          <!-- Acceptance Requirements Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="calibration_under_accreditation"
                            >Calibración Bajo Acreditación:</label
                          >
                          <div class="col-md-4">
                            <select
                              class="form-control"
                              name="calibration_under_accreditation"
                              id="calibration_under_accreditation"
                              v-model="
                                scope.dataForm.calibration_under_accreditation
                              "
                              required
                            >
                              <option value="Si">Si</option>
                              <option value="No">No</option>
                            </select>
                            <small
                              >Seleccione si tiene o no la calibración bajo
                              acreditación</small
                            >
                          </div>
                        </div>

                        <div class="form-group row m-b-15">
                          <label
                            class="col-form-label col-md-2 required"
                            for="rule_reference_calibration"
                            >Norma de Referencia de Calibración, Validación,
                            verificación y/o Comprobación(cuando
                            aplique):</label
                          >
                          <div class="col-md-4">
                            <textarea
                              id="rule_reference_calibration"
                              :class="{
                                'form-control': true,
                                'is-invalid':
                                  dataErrors.rule_reference_calibration,
                              }"
                              v-model="
                                scope.dataForm.rule_reference_calibration
                              "
                              required
                            ></textarea>
                            <small
                              >Ingrese la norma de referencia de calibración,
                              validación, verificación y/o Comprobación(cuando
                              aplique)</small
                            >
                          </div>

                          <!-- Name Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="name"
                            >Nombre Pruebas de Calibración, Validación,
                            Verificación y/o Comprobación:</label
                          >
                          <div class="col-md-4">
                            <input
                              type="text"
                              id="name"
                              :class="{
                                'form-control': true,
                                'is-invalid': dataErrors.name,
                              }"
                              v-model="scope.dataForm.name"
                              required
                            />
                            <small>Ingrese el nombre</small>
                          </div>
                        </div>

                        <div class="form-group row m-b-15">
                          <!-- Acceptance Requirements Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="acceptance_requirements"
                            >Criterios de aceptación Pruebas de Calibración,
                            Validación, Verificación y/o Comprobación:</label
                          >
                          <div class="col-md-4">
                            <textarea
                              id="acceptance_requirements"
                              :class="{
                                'form-control': true,
                                'is-invalid':
                                  dataErrors.acceptance_requirements,
                              }"
                              v-model="scope.dataForm.acceptance_requirements"
                              required
                            ></textarea>
                            <small>Ingrese los criterios de aceptación</small>
                          </div>

                          <!-- Name Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="measure_standard"
                            >Patrón de medida:</label
                          >
                          <div class="col-md-4">
                            <input
                              type="text"
                              id="measure_standard"
                              :class="{
                                'form-control': true,
                                'is-invalid': dataErrors.measure_standard,
                              }"
                              v-model="scope.dataForm.measure_standard"
                              required
                            />
                            <small>Ingrese el patrón de medida</small>
                          </div>
                        </div>

                        <div class="form-group row m-b-15">
                          <label
                            class="col-form-label col-md-2 required"
                            for="criteria_acceptance_certificate"
                            >Criterios de aceptación de certificado de
                            calibración o informe de validación, verificación
                            y/o Comprobación(Datos suministrados por la norma de
                            ensayo, de calibración o por el fabricante):</label
                          >
                          <div class="col-md-4">
                            <textarea
                              id="criteria_acceptance_certificate"
                              :class="{
                                'form-control': true,
                                'is-invalid':
                                  dataErrors.criteria_acceptance_certificate,
                              }"
                              v-model="
                                scope.dataForm.criteria_acceptance_certificate
                              "
                              required
                            ></textarea>
                            <small
                              >Ingrese los criterios de aceptación de
                              certificado de calibración o informe de
                              validación, verificación y/o Comprobación(Datos
                              suministrados por la norma de ensayo, de
                              calibración o por el fabricante)</small
                            >
                          </div>
                        </div>
                      </template>
                    </dynamic-list>
                  </div>
                </div>
              </div>

              <!-- Observaciones -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Observaciones</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Observation Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="observation"
                          >{{ "trans.Observation" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="observation"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.observation,
                            }"
                            v-model="dataForm.observation"
                          ></textarea>
                          <small>Ingrese alguna observación.</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <!-- Status Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="status"
                          >{{ "trans.Status" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            v-model="dataForm.status"
                            name="status"
                            class="form-control"
                          >
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Dado de baja">Dado de baja</option>
                          </select>
                          <small>Seleccione el estado del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel observaciones -->
            </div>

            <div class="modal-footer">
              <button
                @click="clearDataForm()"
                class="btn btn-white"
                data-dismiss="modal"
              >
                <i class="fa fa-times mr-2"></i>Cerrar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save mr-2"></i>Guardar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- end #modal-form-resume-equipment-machinery-lecas -->

    <!-- begin #modal-form-resume-equipment-lecas -->
    <div
      class="modal fade"
      :id="`modal-form-resume-equipment-lecas`"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog modal-xl">
        <form @submit.prevent="save()" id="form-assets-tics">
          <div class="modal-content border-0">
            <div class="modal-header bg-blue">
              <h4 class="modal-title text-white">
                Formulario hoja de vida del equipamiento (LECA) LECA-R-044
              </h4>
              <button
                @click="clearDataForm()"
                type="button"
                class="close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                <i class="fa fa-times text-white"></i>
              </button>
            </div>
            <div class="modal-body">
              <!-- Responsable y categoría -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Responsable y categoría</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Tipo activo Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_asset_type_id"
                          >{{ "Tipo de activo" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_asset_type_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-type-assets-full"
                            :value="dataForm"
                            :function-change="loadCategory"
                            ref="german"
                            :is-required="true"
                            :disabled="true"
                          >
                          </select-check>
                          <small>Seleccione el tipo de activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Category Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_category_id"
                          >{{ "Categoría" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_category_id"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-categories-asset/' +
                              dataForm.mant_asset_type_id
                            "
                            :value="dataForm"
                            :function-change="loadResponsable"
                            :key="categoryKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione la categoría del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Responsable Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="responsable"
                          >{{ "Responsable" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="responsable"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-users-authorized/' +
                              dataForm.mant_asset_type_id +
                              '/' +
                              dataForm.mant_category_id
                            "
                            :value="dataForm"
                            :key="responsableKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el responsable del activos.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin panel responsable -->

              <!-- Descripción del equipo -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Descripción del equipamiento</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Dependency Id Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="dependencias_id"
                          >{{ "Proceso" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="dependencias_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el proceso del activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Equipment Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="name_equipment"
                          >{{ "trans.Name Equipment" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="name_equipment"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name_equipment,
                            }"
                            v-model="dataForm.name_equipment"
                          />
                          <small>Ingrese el nombre del equipamiento.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Internal Code Leca Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="internal_code_leca"
                          >{{ "trans.Internal Code Leca" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="internal_code_leca"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.internal_code_leca,
                            }"
                            v-model="dataForm.internal_code_leca"
                          />
                          <small>Ingrese el código interno LECA.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Inventory No Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="inventory_no"
                          >{{ "trans.Inventory No" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="inventory_no"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.inventory_no,
                            }"
                            v-model="dataForm.inventory_no"
                          />
                          <small>Ingrese el número de inventario.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mark Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mark"
                          >{{ "trans.Mark" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="mark"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mark,
                            }"
                            v-model="dataForm.mark"
                          />
                          <small>Ingrese la marca.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Serie Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="serie"
                          >{{ "trans.Serie" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="serie"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.serie,
                            }"
                            v-model="dataForm.serie"
                          />
                          <small>Ingrese la serie.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Model Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="model"
                          >{{ "trans.Model" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="model"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.model,
                            }"
                            v-model="dataForm.model"
                          />
                          <small>Ingrese el modelo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Location Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="location"
                          >{{ "trans.Location" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="location"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.location,
                            }"
                            v-model="dataForm.location"
                          />
                          <small>Ingrese la ubicación del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Software Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="software"
                          >{{ "trans.Software" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="software"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.software,
                            }"
                            v-model="dataForm.software"
                          />
                          <small>Ingrese el software.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Purchase Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="purchase_date"
                          >{{ "trans.Purchase Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="purchase_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.purchase_date,
                            }"
                            v-model="dataForm.purchase_date"
                          />
                          <small>Seleccione la fecha de compra.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Commissioning Date Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="commissioning_date"
                          >{{ "trans.Commissioning Date" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="commissioning_date"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.commissioning_date,
                            }"
                            v-model="dataForm.commissioning_date"
                          />
                          <small
                            >Seleccione la fecha de puesta en servicio.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Date Withdrawal Service Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="date_withdrawal_service"
                          >{{ "trans.Date Withdrawal Service" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="date"
                            id="date_withdrawal_service"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.date_withdrawal_service,
                            }"
                            v-model="dataForm.date_withdrawal_service"
                          />
                            
                          <small
                            >Seleccione la fecha de retiro de servicio.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="row">
                    <div class="col-md-6">
                      <!-- Software Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="purchase_price"
                          >Precio de compra:</label
                        >
                        <div class="col-md-8">
                          <!-- <input
                            type="text"
                            id="purchase_price"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.purchase_price,
                            }"
                            v-model="dataForm.purchase_price"
                          /> -->
                          <currency-input
                              v-model="dataForm.purchase_price"
                              required="true"
                              :currency="{'prefix': '$'}"
                              locale="es"
                              :precision="0"
                              class="form-control"
                              :key="keyRefresh"                    
                              >
                            </currency-input>
                          <small>Ingrese precio de compra.</small>
                        </div>
                      </div>
                    </div>
                  </div>  
                </div>
              </div>
              <!-- Fin descripción del equipamiento -->

              <!-- Información del fabricante -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Información del fabricante</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Maker Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="maker"
                          >{{ "trans.Maker" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="maker"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.maker,
                            }"
                            v-model="dataForm.maker"
                          />
                          <small>Ingrese el fabricante.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel Información del fabricante -->

              <!-- Proveedor -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Proveedor</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Identification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="identification"
                          >{{ "trans.Identification_nombre" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <autocomplete
                            :is-update="isUpdate"
                            :value-default="dataForm.provider"
                            name-prop="identification"
                            name-field="mant_providers_id"
                            :value="dataForm"
                            name-resource="get-providers"
                            css-class="form-control"
                            :name-labels-display="['identification', 'name']"
                            reduce-key="id"
                            :match-selected="selectProvider"
                            asignar-al-data=""
                            :key="keyRefresh"
                            :is-required="true"
                          >
                          </autocomplete>
                          <small
                            >Ingrese el número de identificación o nombre del
                            proveedor.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Type Person Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="type_person"
                          >{{ "trans.Type Person" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.type_person"
                            name="type_person"
                            id="type_person"
                          >
                            <option value="Natural">Natural</option>
                            <option value="Jurídica">Jurídica</option>
                          </select>
                          <small>Seleccione el tipo de persona.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Document Type Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="document_type"
                          >{{ "trans.Document Type" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.document_type"
                            name="document_type"
                            id="document_type"
                          >
                            <option value="Cédula de ciudadanía">
                              Cédula de ciudadanía
                            </option>
                            <option value="Cédula de extranjería">
                              Cédula de extranjería
                            </option>
                            <option value="NIT">NIT</option>
                          </select>
                          <small>Seleccione el tipo de documento.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="name"
                          >{{ "trans.Name" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="name"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name,
                            }"
                            v-model="dataForm.name"
                          />
                          <small>Ingrese el nombre del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mail Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mail"
                          >{{ "trans.Mail" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="mail"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mail,
                            }"
                            v-model="dataForm.mail"
                          />
                          <small
                            >Ingrese un correo electrónico válido, ej:
                            xxxxx@gmail.com.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Regime Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="regime"
                          >{{ "trans.Regime" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.regime"
                            name="regime"
                            id="regime"
                          >
                            <option value="Simplificado">Simplificado</option>
                            <option value="Común">Común</option>
                            <option value="Ordinario">Ordinario</option>
                          </select>
                          <small>Seleccione el régimen del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Phone Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="phone"
                          >{{ "trans.Phone" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="phone"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.phone,
                            }"
                            v-model="dataForm.phone"
                          />
                          <small>Ingrese el teléfono del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Address Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="address"
                          >{{ "trans.Address" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="address"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.address,
                            }"
                            v-model="dataForm.address"
                          />
                          <small>Ingrese la dirección del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Municipality Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="municipality"
                          >{{ "trans.Municipality" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="municipality"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.municipality,
                            }"
                            v-model="dataForm.municipality"
                          />
                          <small>Ingrese el municipio del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Department Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="department"
                          >{{ "trans.Department" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="department"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.department,
                            }"
                            v-model="dataForm.department"
                          />
                          <small>Ingrese el departamento del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel proveedor -->

              <!-- Documentación de apoyo técnico -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Documentación de apoyo técnico</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Catalogue Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="apply"
                          >{{ "trans.Catalogue" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            class="form-control"
                            v-model="dataForm.catalogue"
                            name="catalogue"
                            id="catalogue"
                          >
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                          </select>
                          <small>Seleccione si tiene catálogo o no.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" v-if="dataForm.catalogue == 'Si'">
                      <!-- Catalogue Location Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="catalogue_location"
                          >{{ "trans.Catalogue Location" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="catalogue_location"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.catalogue_location,
                            }"
                            v-model="dataForm.catalogue_location"
                          />
                          <small>Ingrese la ubicación del catálogo.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6" v-if="dataForm.catalogue == 'Si'">
                      <!-- Idiom Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="idiom"
                          >{{ "trans.Idiom" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="idiom"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.idiom,
                            }"
                            v-model="dataForm.idiom"
                          />
                          <small>Ingrese el idioma del catálogo.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6" v-if="dataForm.catalogue == 'Si'">
                      <!-- Instructive Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="instructive"
                          >{{ "trans.Instructive" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="instructive"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.instructive,
                            }"
                            v-model="dataForm.instructive"
                          />
                          <small>Ingrese el instructivo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" v-if="dataForm.catalogue == 'Si'">
                      <!-- Instructional Location Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="instructional_location"
                          >{{ "trans.Instructional Location" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="instructional_location"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.instructional_location,
                            }"
                            v-model="dataForm.instructional_location"
                          />
                          <small>Ingrese la ubicación.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel Documentación de apoyo técnico -->

              <!-- Especificaciones técnicas -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Especificaciones técnicas</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Magnitude Control Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="magnitude_control"
                          >{{ "trans.Magnitude Control" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="magnitude_control"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.magnitude_control,
                            }"
                            v-model="dataForm.magnitude_control"
                          ></textarea>
                          <small>Ingrese la magnitud a controlar.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Consumables Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="consumables"
                          >{{ "trans.Consumables" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="consumables"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.consumables,
                            }"
                            v-model="dataForm.consumables"
                          ></textarea>
                          <small>Ingrese los consumibles.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Resolution Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="resolution"
                          >{{ "trans.Resolution" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="resolution"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.resolution,
                            }"
                            v-model="dataForm.resolution"
                          ></textarea>
                          <small>Ingrese la resolución.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Accessories Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="accessories"
                          >{{ "trans.Accessories" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="accessories"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.accessories,
                            }"
                            v-model="dataForm.accessories"
                          ></textarea>
                          <small>Ingrese los accesorios.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Operation Range Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="operation_range"
                          >{{ "trans.Operation Range" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="operation_range"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.operation_range,
                            }"
                            v-model="dataForm.operation_range"
                          ></textarea>
                          <small>Ingrese el rango de operación.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Voltage Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="voltage"
                          >{{ "trans.Voltage" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            id="voltage"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.voltage,
                            }"
                            v-model="dataForm.voltage"
                          />
                          <small>Ingrese el voltaje.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Use Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="use"
                          >{{ "trans.Use" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="use"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.use,
                            }"
                            v-model="dataForm.use"
                          />
                          <small>Ingrese el uso.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Use Range Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="use_range"
                          >{{ "trans.Use Range" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="use_range"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.use_range,
                            }"
                            v-model="dataForm.use_range"
                          />
                          <small>Ingrese el rango de uso.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Allowable Error Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="allowable_error"
                          >{{ "trans.Allowable Error" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="allowable_error"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.allowable_error,
                            }"
                            v-model="dataForm.allowable_error"
                          />
                          <small
                            >Ingrese el error permisible (conforme
                            fabricante).</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Minimum Permissible Error Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="minimum_permissible_error"
                          >{{
                            "trans.Minimum Permissible Error" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="minimum_permissible_error"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.minimum_permissible_error,
                            }"
                            v-model="dataForm.minimum_permissible_error"
                          />
                          <small>Ingrese el error mínimo permisible.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Environmental Operating Conditions Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="environmental_operating_conditions"
                          >{{
                            "trans.Environmental Operating Conditions" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="environmental_operating_conditions"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.environmental_operating_conditions,
                            }"
                            v-model="
                              dataForm.environmental_operating_conditions
                            "
                          />
                          <small
                            >Ingrese las condiciones ambientales de
                            operación.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr />
                  <div class="row">
                    <dynamic-list
                      label-button-add="Agregar ítem a la lista"
                      :data-list.sync="dataForm.specifications_equipment_leca"
                      class-table="table-responsive table-bordered"
                      class-container="w-100 p-10"
                      :data-list-options="[
                        {
                          label:
                            'Puntos de calibración y/o verificación (valor nominal)',
                          name: 'calibration_verification_points',
                          isShow: true,
                        },
                        {
                          label:
                            'Norma de referencia de calibración y/o verificación (cuando aplique)',
                          name: 'reference_standard_calibration_verification',
                          isShow: true,
                        },
                        {
                          label: 'Criterio de aceptación',
                          name: 'acceptance_requirements',
                          isShow: true,
                        },
                      ]"
                    >
                      <template #fields="scope">
                        <div class="form-group row m-b-15">
                          <!-- Calibration Verification Points Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="calibration_verification_points"
                            >{{
                              "trans.Calibration Verification Points" | trans
                            }}:</label
                          >
                          <div class="col-md-4">
                            <textarea
                              id="calibration_verification_points"
                              :class="{
                                'form-control': true,
                                'is-invalid':
                                  dataErrors.calibration_verification_points,
                              }"
                              v-model="
                                scope.dataForm.calibration_verification_points
                              "
                              required
                            ></textarea>
                            <small
                              >Ingrese los puntos de calibración y/o
                              verificación (valor nominal)</small
                            >
                          </div>
                          <!-- Reference Standard Calibration Verification Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="reference_standard_calibration_verification"
                            >{{
                              "trans.Reference Standard Calibration Verification"
                                | trans
                            }}:</label
                          >
                          <div class="col-md-4">
                            <textarea
                              id="reference_standard_calibration_verification"
                              :class="{
                                'form-control': true,
                                'is-invalid':
                                  dataErrors.reference_standard_calibration_verification,
                              }"
                              v-model="
                                scope.dataForm
                                  .reference_standard_calibration_verification
                              "
                              required
                            ></textarea>
                            <small
                              >Ingrese la norma de referencia de calibración y/o
                              verificación (cuando aplique)</small
                            >
                          </div>
                          <!-- Acceptance Requirements Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="acceptance_requirements"
                            >{{
                              "trans.Acceptance Requirements" | trans
                            }}:</label
                          >
                          <div class="col-md-4">
                            <textarea
                              id="acceptance_requirements"
                              :class="{
                                'form-control': true,
                                'is-invalid':
                                  dataErrors.acceptance_requirements,
                              }"
                              v-model="scope.dataForm.acceptance_requirements"
                              required
                            ></textarea>
                            <small>Ingrese el criterio de aceptación</small>
                          </div>
                        </div>
                      </template>
                    </dynamic-list>
                  </div>
                </div>
                <!-- end panel-body -->

                <!-- Observaciones -->
                <div class="panel" data-sortable-id="ui-general-1">
                  <!-- begin panel-heading -->
                  <div class="panel-heading ui-sortable-handle">
                    <h4 class="panel-title"><strong>Observaciones</strong></h4>
                  </div>
                  <!-- end panel-heading -->
                  <!-- begin panel-body -->
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">
                        <!-- Observation Field -->
                        <div class="form-group row m-b-15">
                          <label
                            class="col-form-label col-md-4"
                            for="observation"
                            >{{ "trans.Observation" | trans }}:</label
                          >
                          <div class="col-md-8">
                            <textarea
                              id="observation"
                              :class="{
                                'form-control': true,
                                'is-invalid': dataErrors.observation,
                              }"
                              v-model="dataForm.observation"
                            ></textarea>
                            <small>Ingrese alguna observación.</small>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <!-- Status Field -->
                        <div class="form-group row m-b-15">
                          <label class="col-form-label col-md-4" for="status"
                            >{{ "trans.Status" | trans }}:</label
                          >
                          <div class="col-md-8">
                            <select
                              v-model="dataForm.status"
                              name="status"
                              class="form-control"
                            >
                              <option value="Activo">Activo</option>
                              <option value="Inactivo">Inactivo</option>
                              <option value="Dado de baja">Dado de baja</option>
                            </select>
                            <small>Seleccione el estado del activo.</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end panel-body -->
                </div>
                <!-- Fin panel observaciones -->
              </div>
              <!-- Fin panel Especificaciones técnicas-->
            </div>
            <div class="modal-footer">
              <button
                @click="clearDataForm()"
                class="btn btn-white"
                data-dismiss="modal"
              >
                <i class="fa fa-times mr-2"></i>Cerrar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save mr-2"></i>Guardar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- end #modal-form-resume-equipment-lecas -->

    <!-- begin #modal-form-resume-inventory-lecas -->
    <div
      class="modal fade"
      :id="`modal-form-resume-inventory-lecas`"
      data-keyboard="false"
      data-backdrop="static"
    >
      <div class="modal-dialog modal-xl">
        <form @submit.prevent="save()" id="form-assets-tics">
          <div class="modal-content border-0">
            <div class="modal-header bg-blue">
              <h4 class="modal-title text-white">
                Formulario hoja de vida de inventario y cronograma del
                aseguramiento metrológico
              </h4>
              <button
                @click="clearDataForm()"
                type="button"
                class="close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                <i class="fa fa-times text-white"></i>
              </button>
            </div>
            <div class="modal-body">
              <!-- Responsable y categoría -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Responsable y categoría</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Tipo activo Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_asset_type_id"
                          >{{ "Tipo de activo" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_asset_type_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-type-assets-full"
                            :value="dataForm"
                            :function-change="loadCategory"
                            ref="german"
                            :is-required="true"
                            :disabled="true"
                          >
                          </select-check>
                          <small>Seleccione el tipo de activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Category Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="mant_category_id"
                          >{{ "Categoría" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="mant_category_id"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-categories-asset/' +
                              dataForm.mant_asset_type_id
                            "
                            :value="dataForm"
                            :function-change="loadResponsable"
                            :key="categoryKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione la categoría del activo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Responsable Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="responsable"
                          >{{ "Responsable" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select-check
                            css-class="form-control"
                            name-field="responsable"
                            reduce-label="name"
                            reduce-key="id"
                            :name-resource="
                              'get-users-authorized/' +
                              dataForm.mant_asset_type_id +
                              '/' +
                              dataForm.mant_category_id
                            "
                            :value="dataForm"
                            :key="responsableKey"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el responsable del activos.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin panel responsable -->

              <!-- Generalidad del equipamiento (inventario) -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Generalidad del equipamiento (inventario)</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- No Inventory Epa Esp Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="no_inventory_epa_esp"
                          >{{ "trans.No Inventory Epa Esp" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="no_inventory_epa_esp"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.no_inventory_epa_esp,
                            }"
                            v-model="dataForm.no_inventory_epa_esp"
                          />
                          <small
                            >Ingrese el número de inventario EPA ESP.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Leca Code Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="leca_code"
                          >{{ "trans.Leca Code" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="leca_code"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.leca_code,
                            }"
                            v-model="dataForm.leca_code"
                          />
                          <small>Ingrese el código LECA.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Description Equipment Name Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="description_equipment_name"
                          >{{
                            "trans.Description Equipment Name" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="description_equipment_name"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.description_equipment_name,
                            }"
                            v-model="dataForm.description_equipment_name"
                          />
                          <small
                            >Ingrese la descripción (nombre del
                            equipamiento).</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Maker Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="maker"
                          >{{ "trans.Maker" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="maker"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.maker,
                            }"
                            v-model="dataForm.maker"
                          />
                          <small>Ingrese el fabricante.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Serial Number Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="serial_number"
                          >{{ "trans.Serial Number" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="serial_number"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.serial_number,
                            }"
                            v-model="dataForm.serial_number"
                          />
                          <small>Ingrese el número de serie.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Model Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="model"
                          >{{ "trans.Model" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="model"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.model,
                            }"
                            v-model="dataForm.model"
                          />
                          <small>Ingrese el modelo.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Location Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="location"
                          >{{ "trans.Location" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="location"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.location,
                            }"
                            v-model="dataForm.location"
                          />
                          <small>Ingrese la ubicación del activo.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Measured Used Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="measured_used"
                          >{{ "trans.Measured Used" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="measured_used"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.measured_used,
                            }"
                            v-model="dataForm.measured_used"
                          />
                          <small>Ingrese el mesurado utilizado.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Unit Measurement Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="unit_measurement"
                          >{{ "trans.Unit Measurement" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="unit_measurement"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.unit_measurement,
                            }"
                            v-model="dataForm.unit_measurement"
                          />
                          <small>Ingrese la unidad de medida.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Resolution Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="resolution"
                          >{{ "trans.Resolution" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="resolution"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.resolution,
                            }"
                            v-model="dataForm.resolution"
                          ></textarea>
                          <small>Ingrese la resolución.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Manufacturer Error Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="manufacturer_error"
                          >{{ "trans.Manufacturer Error" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="manufacturer_error"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.manufacturer_error,
                            }"
                            v-model="dataForm.manufacturer_error"
                          />
                          <small>Ingrese el error según fabricante.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Operation Range Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="operation_range"
                          >{{ "trans.Operation Range" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <textarea
                            id="operation_range"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.operation_range,
                            }"
                            v-model="dataForm.operation_range"
                          ></textarea>
                          <small>Ingrese el rango de operación.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Range Use Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="range_use"
                          >{{ "trans.Range Use" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="range_use"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.range_use,
                            }"
                            v-model="dataForm.range_use"
                          />
                          <small>Ingrese el rango de uso.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Operating Conditions Temperature Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="operating_conditions_temperature"
                          >{{
                            "trans.Operating Conditions Temperature" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="operating_conditions_temperature"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.operating_conditions_temperature,
                            }"
                            v-model="dataForm.operating_conditions_temperature"
                          />
                          <small
                            >Ingrese las condiciones de operación de
                            temperatura.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Condition Oper Elative Humidity Hr Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="condition_oper_elative_humidity_hr"
                          >{{
                            "trans.Condition Oper Elative Humidity Hr" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="condition_oper_elative_humidity_hr"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.condition_oper_elative_humidity_hr,
                            }"
                            v-model="
                              dataForm.condition_oper_elative_humidity_hr
                            "
                          />
                          <small
                            >Ingrese la condición oper. Humedad relativa %
                            HR.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Condition Oper Voltage Range Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="condition_oper_voltage_range"
                          >{{
                            "trans.Condition Oper Voltage Range" | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="condition_oper_voltage_range"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.condition_oper_voltage_range,
                            }"
                            v-model="dataForm.condition_oper_voltage_range"
                          />
                          <small
                            >Ingrese la condición de oper. Rango voltaje.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Maintenance Metrological Operation Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="maintenance_metrological_operation_frequency"
                          >{{
                            "trans.Maintenance Metrological Operation Frequency"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="maintenance_metrological_operation_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.maintenance_metrological_operation_frequency,
                            }"
                            v-model="
                              dataForm.maintenance_metrological_operation_frequency
                            "
                          />
                          <small
                            >Ingrese la frecuencia de operación metrológica de
                            mantenimiento (en meses).</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Calibration Metrological Operating Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="calibration_metrological_operating_frequency"
                          >{{
                            "trans.Calibration Metrological Operating Frequency"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="calibration_metrological_operating_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.calibration_metrological_operating_frequency,
                            }"
                            v-model="
                              dataForm.calibration_metrological_operating_frequency
                            "
                          />
                          <small
                            >Ingrese la frecuencia de operación metrológica de
                            calibración (en meses).</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Qualification Metrological Operating Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="qualification_metrological_operating_frequency"
                          >{{
                            "trans.Qualification Metrological Operating Frequency"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="qualification_metrological_operating_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.qualification_metrological_operating_frequency,
                            }"
                            v-model="
                              dataForm.qualification_metrological_operating_frequency
                            "
                          />
                          <small
                            >Ingrese la frecuencia de operación metrológica de
                            calificación (en meses).</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Intermediate Verification Metrological Operating Frequency Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="intermediate_verification_metrological_operating_frequency"
                          >{{
                            "trans.Intermediate Verification Metrological Operating Frequency"
                              | trans
                          }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="intermediate_verification_metrological_operating_frequency"
                            :class="{
                              'form-control': true,
                              'is-invalid':
                                dataErrors.intermediate_verification_metrological_operating_frequency,
                            }"
                            v-model="
                              dataForm.intermediate_verification_metrological_operating_frequency
                            "
                          />
                          <small
                            >Ingrese la frecuencia de operación metrológica de
                            verificación intermedia (en meses).</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fin Generalidad del equipamiento (inventario) -->

              <!-- Proveedor -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Proveedor</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Identification Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4 required"
                          for="identification"
                          >{{ "trans.Identification_nombre" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <autocomplete
                            :is-update="isUpdate"
                            :value-default="dataForm.provider"
                            name-prop="identification"
                            name-field="mant_providers_id"
                            :value="dataForm"
                            name-resource="get-providers"
                            css-class="form-control"
                            :name-labels-display="['identification', 'name']"
                            reduce-key="id"
                            :match-selected="selectProvider"
                            asignar-al-data=""
                            :key="keyRefresh"
                            :is-required="true"
                          >
                          </autocomplete>
                          <small
                            >Ingrese el número de identificación o nombre del
                            proveedor.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Type Person Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="type_person"
                          >{{ "trans.Type Person" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.type_person"
                            name="type_person"
                            id="type_person"
                          >
                            <option value="Natural">Natural</option>
                            <option value="Jurídica">Jurídica</option>
                          </select>
                          <small>Seleccione el tipo de persona.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Document Type Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="document_type"
                          >{{ "trans.Document Type" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.document_type"
                            name="document_type"
                            id="document_type"
                          >
                            <option value="Cédula de ciudadanía">
                              Cédula de ciudadanía
                            </option>
                            <option value="Cédula de extranjería">
                              Cédula de extranjería
                            </option>
                            <option value="NIT">NIT</option>
                          </select>
                          <small>Seleccione el tipo de documento.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Name Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="name"
                          >{{ "trans.Name" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="name"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name,
                            }"
                            v-model="dataForm.name"
                          />
                          <small>Ingrese el nombre del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Mail Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="mail"
                          >{{ "trans.Mail" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="mail"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.mail,
                            }"
                            v-model="dataForm.mail"
                          />
                          <small
                            >Ingrese un correo electrónico válido, ej:
                            xxxxx@gmail.com.</small
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Regime Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="regime"
                          >{{ "trans.Regime" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <select
                            disabled
                            class="form-control"
                            v-model="dataForm.regime"
                            name="regime"
                            id="regime"
                          >
                            <option value="Simplificado">Simplificado</option>
                            <option value="Común">Común</option>
                            <option value="Ordinario">Ordinario</option>
                          </select>
                          <small>Seleccione el régimen del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Phone Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="phone"
                          >{{ "trans.Phone" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="phone"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.phone,
                            }"
                            v-model="dataForm.phone"
                          />
                          <small>Ingrese el teléfono del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Address Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="address"
                          >{{ "trans.Address" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="address"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.address,
                            }"
                            v-model="dataForm.address"
                          />
                          <small>Ingrese la dirección del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Municipality Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="municipality"
                          >{{ "trans.Municipality" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="municipality"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.municipality,
                            }"
                            v-model="dataForm.municipality"
                          />
                          <small>Ingrese el municipio del proveedor.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Department Field -->
                      <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4" for="department"
                          >{{ "trans.Department" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            disabled
                            type="text"
                            id="department"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.department,
                            }"
                            v-model="dataForm.department"
                          />
                          <small>Ingrese el departamento del proveedor.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin panel proveedor -->

              <!-- Cronograma del aseguramiento metrológico -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title">
                    <strong>Cronograma del aseguramiento metrológico</strong>
                  </h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <dynamic-list
                      ref="listado"
                      label-button-add="Agregar ítem a la lista"
                      :data-list.sync="dataForm.schedule_inventory_leca"
                      class-table="table-responsive table-bordered"
                      class-container="w-100 p-10"
                      :data-list-options="[
                        { label: 'Mes', name: 'month', isShow: true },
                        {
                          label: 'Atividad metrológica',
                          name: 'metrological_activity',
                          isShow: true,
                        },
                        {
                          label: 'Descripción',
                          name: 'description',
                          isShow: true,
                        },
                      ]"
                    >
                      <template #fields="scope">
                        <div class="form-group row m-b-15">
                          <!-- Month Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="month"
                            >{{ "trans.Month" | trans }}:</label
                          >
                          <div class="col-md-4">
                            <select
                              name="month"
                              id="month"
                              :class="{
                                'form-control': true,
                                'is-invalid': dataErrors.month,
                              }"
                              v-model="scope.dataForm.month"
                              required
                            >
                              <option>Seleccione el mes</option>
                              <option value="Enero">Enero</option>
                              <option value="Febrero">Febrero</option>
                              <option value="Marzo">Marzo</option>
                              <option value="Abril">Abril</option>
                              <option value="Mayo">Mayo</option>
                              <option value="Junio">Junio</option>
                              <option value="Julio">Julio</option>
                              <option value="Agosto">Agosto</option>
                              <option value="Septiembre">Septiembre</option>
                              <option value="Octubre">Octubre</option>
                              <option value="Noviembre">Noviembre</option>
                              <option value="Diciembre">Diciembre</option>
                            </select>
                            <small>Seleccione el mes</small>
                          </div>
                          <!-- Metrological Activity Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="metrological_activity"
                            >{{ "trans.Metrological Activity" | trans }}:</label
                          >
                          <div class="col-md-4">
                            <select
                              name="metrological_activity"
                              id="metrological_activity"
                              :class="{
                                'form-control': true,
                                'is-invalid': dataErrors.metrological_activity,
                              }"
                              v-model="scope.dataForm.metrological_activity"
                              required
                            >
                              <option>
                                Seleccione la actividad metrológica
                              </option>
                              <option value="Mantenimiento">
                                Mantenimiento
                              </option>
                              <option value="Inspección">Inspección</option>
                              <option value="Verificación intermedia">
                                Verificación intermedia
                              </option>
                              <option value="Calibración">Calibración</option>
                              <option value="Calificación">Calificación</option>
                            </select>
                            <small>Seleccione la actividad metrológica</small>
                          </div>
                          <!-- Description Field -->
                          <label
                            class="col-form-label col-md-2 required"
                            for="description"
                            >{{ "trans.Description" | trans }}:</label
                          >
                          <div class="col-md-4">
                            <input
                              type="text"
                              id="description"
                              :class="{
                                'form-control': true,
                                'is-invalid': dataErrors.description,
                              }"
                              v-model="scope.dataForm.description"
                              required
                            />
                            <small>Ingrese la descripción</small>
                          </div>
                        </div>
                      </template>
                    </dynamic-list>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Total Interventions Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="total_interventions"
                          >{{ "trans.Total Interventions" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="total_interventions"
                            v-bind:value="
                              dataForm.schedule_inventory_leca
                                ? dataForm.schedule_inventory_leca.length
                                : 0
                            "
                            disabled
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.total_interventions,
                            }"
                          />
                          <small
                            >Suma de todas las actividades. Campo no
                            editable</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr />
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Name Elaborated Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="name_elaborated"
                          >{{ "trans.Name Elaborated" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="name_elaborated"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name_elaborated,
                            }"
                            v-model="dataForm.name_elaborated"
                          />
                          <small>Ingrese el nombre de quien elaboró.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Cargo Role Elaborated Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="cargo_role_elaborated"
                          >{{ "trans.Cargo Role Elaborated" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="cargo_role_elaborated"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.cargo_role_elaborated,
                            }"
                            v-model="dataForm.cargo_role_elaborated"
                          />
                          <small
                            >Ingrese el cargo o rol de quien elaboró.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Name Updated Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="name_updated"
                          >{{ "trans.Name Updated" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="name_updated"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.name_updated,
                            }"
                            v-model="dataForm.name_updated"
                          />
                          <small>Ingrese el nombre de quien actualizó.</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Cargo Role Updated Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="cargo_role_updated"
                          >{{ "trans.Cargo Role Updated" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="cargo_role_updated"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.cargo_role_updated,
                            }"
                            v-model="dataForm.cargo_role_updated"
                          />
                          <small
                            >Ingrese el cargo o rol de quien actualizó.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- Technical Director Field -->
                      <div class="form-group row m-b-15">
                        <label
                          class="col-form-label col-md-4"
                          for="technical_director"
                          >{{ "trans.Technical Director" | trans }}:</label
                        >
                        <div class="col-md-8">
                          <input
                            type="text"
                            id="technical_director"
                            :class="{
                              'form-control': true,
                              'is-invalid': dataErrors.technical_director,
                            }"
                            v-model="dataForm.technical_director"
                          />
                          <small
                            >Ingrese el nombre del director técnico que revisó y
                            aprobó.</small
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end panel-body -->
              </div>
              <!-- Fin Cronograma del aseguramiento metrológico -->

              <!-- Convenciones -->
              <div class="panel" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                  <h4 class="panel-title"><strong>Convenciones</strong></h4>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                  <div class="row">
                    <div class="row" style="margin: auto">
                      <div class="table-responsive">
                        <table class="table table-responsive table-bordered">
                          <tbody>
                            <tr>
                              <td>Mc</td>
                              <td>Mantenimiento correctivo</td>
                              <td>D</td>
                              <td>Diaria</td>
                              <td>DQ</td>
                              <td>Calificación de diseño</td>
                            </tr>
                            <tr>
                              <td>Mp</td>
                              <td>Mantenimiento y ajuste preventivo</td>
                              <td>S</td>
                              <td>Semanal</td>
                              <td>IQ</td>
                              <td>Calificación de instalación</td>
                            </tr>
                            <tr>
                              <td>VI</td>
                              <td>Verificación intermedia</td>
                              <td>Q</td>
                              <td>Quincenal</td>
                              <td>OQ</td>
                              <td>Calificación operacional</td>
                            </tr>
                            <tr>
                              <td>Ext</td>
                              <td>Proveedor externo</td>
                              <td>ME</td>
                              <td>Mensual</td>
                              <td>PQ</td>
                              <td>Calificación de desempeño</td>
                            </tr>
                            <tr>
                              <td>INT</td>
                              <td>Interno</td>
                              <td>VF</td>
                              <td colspan="3">
                                Inspección del funcionamiento adecuado del
                                equipamiento
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
              <!-- Fin panel Convenciones -->
            </div>
            <div class="modal-footer">
              <button
                @click="clearDataForm()"
                class="btn btn-white"
                data-dismiss="modal"
              >
                <i class="fa fa-times mr-2"></i>Cerrar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save mr-2"></i>Guardar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- end #modal-form-resume-inventory-lecas -->
  </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import * as bootstrap from "bootstrap";

import utility from "../../utility";

import { Locale } from "v-calendar";
import { count } from "console";
import { jwtDecode } from "jwt-decode";

const locale = new Locale();

/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Carlos Moises Garcia T. - Oct. 13 - 2020
 * @version 1.0.0
 */
@Component
export default class AssetsCreate extends Vue {
  /**
   * Nombre de la entidad a afectar
   */
  @Prop({ type: String, required: true, default: "modal-form-assets-tics" })
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

  public categoryKey: number;
  public responsableKey: number;

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

  public formType: String;

  public fuel_type: String;

  public idForm: number;

  /**
   * Arrays que contienen las referencias de las llantas
   */
  public frontReferences: any;

  public rearReferences: any;

  public tireQuantity: number;

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
    this.categoryKey = 0;
    this.responsableKey = 0;
    this.keySO = 0;
    this.idForm = 0;
    this.frontReferences = [];
    this.rearReferences = [];
    this.tireQuantity = 0;

    this.dataForm = {
      characteristics_equipment: [],
      composition_equipment_leca: [],
      maintenance_equipment_leca: [],
      specifications_equipment_leca: [],
      schedule_inventory_leca: [],
      rubros_asignados: [],

    };

    this.dataErrors = {};
    this.isUpdate = false;

    this.lang = (this.$parent as any).lang;

    this.options = {
      chart: {
        id: "vuechart-example",
      },
      xaxis: {
        categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
      },
    };

    this.series = [
      {
        name: "series-1",
        data: [30, 40, 45, 50, 49, 60, 70, 91],
      },
    ];
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
   * @author German Gonzalez V. - May. 7 - 2021
   * @version 1.0.0
   */
  public clearDataForm(): void {
    // Inicializa valores del dataform
    this.dataForm = {
      characteristics_equipment: [],
      composition_equipment_leca: [],
      maintenance_equipment_leca: [],
      specifications_equipment_leca: [],
      schedule_inventory_leca: [],
      rubros_asignados: [],

    };
    this.frontReferences = [];
    this.rearReferences = [];
    // Limpia errores
    this.dataErrors = {};
    // Actualiza componente de refresco
    this._updateKeyRefresh();
    // Limpia valores del campo de archivos
    $("input[type=file]").val(null);
  }

  public getVersionSO(): void {
    this.keySO += 1;
  }

  /**
   * Actualiza la llave del select categoría
   *
   * @author German Gonzalez V. - Mar. 18 - 2021
   * @version 1.0.0
   *
   */
  public loadCategory(): void {
    this.categoryKey += 1;
  }

  /**
   * Asigna los datos al dataForm del proveedor seleccionado de la búsqueda
   *
   * @author German Gonzalez V. - Mar. 18 - 2021
   * @version 1.0.0
   *
   */
  public selectProvider(data): void {
    this.dataForm.type_person = data.type_person;
    this.dataForm.document_type = data.document_type;
    this.dataForm.name = data.name;
    this.dataForm.mail = data.mail;
    this.dataForm.regime = data.regime;
    this.dataForm.phone = data.phone;
    this.dataForm.address = data.address;
    this.dataForm.municipality = data.municipality;
    this.dataForm.department = data.department;

    // Actualiza todas las variables del componente
    this.$forceUpdate();
  }

  /**
   * Actualiza la llave del select responsable
   *
   * @author German Gonzalez V. - Mar. 18 - 2021
   * @version 1.0.0
   *
   */
  public loadResponsable(): void {
  
    if (this.dataForm.mant_category_id == 104) {
      this.dataForm.requirement_for_operation= "Combustible";
      
    }

    this.responsableKey += 1;
  }

  /**
   * Cargar los datos en modo creación
   *
   * @author Carlos Moises Garcia T. - Oct. 17 - 2020
   * @version 1.0.0
   *
   * @param dataElement elemento de grupo de trabajo
   */
  public createAsset(): void {
    const valueAssetSelected = this.dataForm.mant_asset_type_id;

    const optionList = this.$refs["german"]["optionsList"];

    for (let index = 0; index < optionList.length; index++) {
      if (optionList[index].id == valueAssetSelected) {
        if (optionList[index].form_type == 1) {
          this.name = "resume-machinery-vehicles-yellows";
        } else if (optionList[index].form_type == 2) {
          this.name = "resume-equipment-machineries";
        } else if (optionList[index].form_type == 3) {
          this.name = "resume-equipment-machinery-lecas";
        } else if (optionList[index].form_type == 4) {
          this.name = "resume-equipment-lecas";
        } else if (optionList[index].form_type == 5) {
          this.name = "resume-inventory-lecas";
        }

        break;
      }
    }

    $("#modal-form-categories-ini").modal("toggle");
    $(".modal").css("overflow-y", "auto");
    $(`#modal-form-${this.name}`).modal("show");
  }

  /**
   * Cargar los datos en modo edición
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

      if (this.dataForm.provider) {
        this.dataForm.type_person = this.dataForm.provider.type_person;
        this.dataForm.document_type = this.dataForm.provider.document_type;
        this.dataForm.name = this.dataForm.provider.name;
        this.dataForm.mail = this.dataForm.provider.mail;
        this.dataForm.regime = this.dataForm.provider.regime;
        this.dataForm.phone = this.dataForm.provider.phone;
        this.dataForm.address = this.dataForm.provider.address;
        this.dataForm.municipality = this.dataForm.provider.municipality;
        this.dataForm.department = this.dataForm.provider.department;

        // Actualiza componente de refresco
        this._updateKeyRefresh();
      }

      const valueAssetSelected =
        this.dataForm.mant_category?.mant_asset_type.form_type;

      if (valueAssetSelected == 1) {
        this.name = "resume-machinery-vehicles-yellows";
      } else if (valueAssetSelected == 2) {
        this.name = "resume-equipment-machineries";
      } else if (valueAssetSelected == 3) {
        this.name = "resume-equipment-machinery-lecas";
      } else if (valueAssetSelected == 4) {
        this.name = "resume-equipment-lecas";
      } else if (valueAssetSelected == 5) {
        this.name = "resume-inventory-lecas";
      }

      // detecta el enter para no cerrar el modal sin enviar el formulario
      $(`#modal-form-${this.name}`).on("keypress", (e) => {
        if (e.keyCode === 13) {
          e.preventDefault();
        }
      });

      this.loadCategory();
      this.loadResponsable();
      $(`#modal-form-${this.name}`).modal("show");
    } else {
      this.isUpdate = false;
      $("#modal-form-categories-ini").modal("show");
    }
  }

  /**
   * Realiza una consulta obteniendo el id de la llanta
   *
   * @author Johan david velasco - Noviembre. 11 - 2021
   * @version 1.0.0
   */
  public formId(id): void {
    axios
      .get(`get-resume-machinery/${id}`)
      .then((res) => {
        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
        const request = Object.assign({data:{}}, dataPayload);

        console.log("request", request);

        this.dataForm.number_tires = Array.isArray(request['data']) ? request['data'].length : 0;

        if (Array.isArray(request['data']) && this.dataForm.number_tires != 0) {
          request['data'].forEach(element => {
            if (element.location_tire == 'Delantera') {
                this.frontReferences.push(element.tire_reference +' - Posición: '+ element.position_tire);
            }else{
                this.rearReferences.push(element.tire_reference +' - Posición: '+ element.position_tire);  
            }
          });
        } else {
          this.frontReferences = [];
          this.rearReferences = [];
        }


        //Asigna el valor obtenido por el v-model
        // this.idForm = request.id;
        this.$forceUpdate();
      })
      .catch((error) => {
        console.log(error);
      });
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

        if (typeof data === 'object' && !(data instanceof File || data instanceof Date || Array.isArray(data))) {
            // Maneja objetos específicos como 'metadatos'
            for (const subKey in data) {
                if (data.hasOwnProperty(subKey)) {
                    const subData = data[subKey];
                    // Si es un objeto o un arreglo dentro de 'metadatos'
                    if (typeof subData === 'object') {
                        formData.append(`${key}[${subKey}]`, JSON.stringify(subData));
                    } else {
                        formData.append(`${key}[${subKey}]`, subData);
                    }
                }
            }
        } else {
            // Maneja archivos, fechas y arreglos
            if (Array.isArray(data)) {
                data.forEach((element) => {
                    if (typeof element === 'object') {
                        formData.append(`${key}[]`, JSON.stringify(element));
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
      title: this.lang.get("trans.loading_save"),
      allowOutsideClick: false,
      onBeforeOpen: () => {
        (this.$swal as any).showLoading();
      },
    });

    console.log('leo:',this.name, this.makeFormData())
    // Envia peticion de guardado de datos
    axios
      .post(this.name, this.makeFormData(), {
        headers: { "Content-Type": "multipart/form-data" },
      })
      .then((res) => {
        console.log(this.dataForm["id"]);
        // Agrega elemento nuevo a la lista
        (this.$parent as any).dataList.unshift(res.data.data);

        (this.$swal as any).close();

         if (res.data.type_message == "error") {
          this.$swal({
            icon: res.data.type_message,
            html: res.data.message,
            confirmButtonText: this.lang.get("trans.Accept"),
            allowOutsideClick: false,
          });
        }else{
          // Cierra fomrulario modal
          $(`#modal-form-${this.name}`).modal("toggle");

          // Limpia datos ingresados
          this.clearDataForm();
          // Emite notificacion de almacenamiento de datos
          (this.$parent as any)._pushNotification(res.data.message);
        }
      })
      .catch((err) => {
        (this.$swal as any).close();
        // Issues data storage notification
        (this.$parent as any)._pushNotification(
          "Error al guardar los datos",
          false,
          "Error"
        );
        // Validate if there are errors associated with the form
        if (err.response.data.errors) {
          this.dataErrors = err.response.data.errors;
        }
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
      title: this.lang.get("trans.loading_update"),
      allowOutsideClick: false,
      onBeforeOpen: () => {
        (this.$swal as any).showLoading();
      },
    });

    const formData: FormData = this.makeFormData();
    formData.append("_method", "put");

    console.log("DataForm",formData);

    // Envia peticion de guardado de datos
    axios
      .post(`${this.name}/${this.dataForm["id"]}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
      .then((res) => {
        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
        const dataDecrypted = Object.assign({data:{}}, dataPayload);

        // Valida que se retorne los datos desde el controlador
        if (dataDecrypted.data) {
          // Actualiza elemento modificado en la lista
          Object.assign(
            this._findElementById(
              this.dataForm["id"],
              this.dataForm["created_at"],
              false
            ),
            dataDecrypted.data
          );
        }

        // Cierra el swal de guardando datos
        (this.$swal as any).close();

        if (res.data.type_message == "error") {
          this.$swal({
            icon: res.data.type_message,
            html: res.data.message,
            confirmButtonText: this.lang.get("trans.Accept"),
            allowOutsideClick: false,
          });
        } else {
          // Cierra fomrulario modal
          $(`#modal-form-${this.name}`).modal("toggle");
          // Limpia datos ingresados
          this.clearDataForm();
          // Emite notificacion de actualizacion de datos
          (this.$parent as any)._pushNotification(res.data.message);
        }
      })
      .catch((err) => {
        (this.$swal as any).close();
      })

  }

  /**
   * Busca un elemento de la lista por el id
   *
   * @author German Gonzalez V. - Ago. - 2021
   * @version 1.0.1
   *
   * @param id identificador del elemento a buscar
   * @param created_at fecha de creación del elemento a buscar
   * @param clone valida si el objeto retornado debe ser clonado
   */
  private _findElementById(
    id: number,
    created_at: string,
    clone: boolean
  ): any {
    for (let i = 0; i < (this.$parent as any).dataList.length; i++) {
      const element = (this.$parent as any).dataList[i];
      // Busca el dato a editar
      if (
        element[(this.$parent as any).customId] === id &&
        element["created_at"] === created_at
      ) {
        // Valida si se debe clonar el retorno
        return clone ? utility.clone(element) : element;
      }
    }
  }

  /**
   * Obtiene la lista de datos
   *
   * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
   * @version 1.0.0
   */
  private _getDataListCategory(): void {
    // Envia peticion de obtener todos los datos del recurso de categorias
    // axios.get('get-tic-type-tic-categories')
    // .then((res) => {
    //    this.dataListCategory = res.data.data;
    //    let data = res.data.data;
    // })
    // .catch((err) => {
    //    // console.log('Error al obtener la lista.');
    //    (this.$parent as any)._pushNotification('Error al obtener la lista de datos', false, 'Error');
    // });
  }

  /**
   * Obtiene la lista de datos de los tipos de activos
   *
   * @author Carlos Moises Garcia T. - Oct. 26 - 2020
   * @version 1.0.0
   */
  private _getDataListTypesAssets(): void {
    // Envia peticion de obtener todos los datos del recurso de categorias
    axios
      .get(
        `get-type-assets-tics-by-category/${this.dataForm.type_tic_category}`
      )
      .then((res) => {
        this.dataListTypesAssets = res.data.data;
        let data = res.data.data;
      })
      .catch((err) => {
        // console.log('Error al obtener la lista.');
        (this.$parent as any)._pushNotification(
          "Error al obtener la lista de datos",
          false,
          "Error"
        );
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
    this.keyRefresh++;
  }

  /**
   * Detecta el cambio de selección de los elementos select de la hoja de vida de equipos
   *
   * @author German Gonzalez V. - Abr. 08 - 2021
   * @version 1.0.0
   * @param event Contiene los elementos dela opción seleccionada
   */
  private _maintenanceChange(event) {
    // Condición para validar si el value del select selccionado es No
    if (event.target.value == "No") {
      this.dataForm[event.target.name + "_frequency"] = "No aplica";
    } else if (event.target.value == "Si") {
      this.dataForm[event.target.name + "_frequency"] = "";
    }
  }

  /**
   * Consulta y elimina un tipo de activo si es posible
   *
   * @author German Gonzalez V. - Sep. 14 - 2021
   * @version 1.0.0
   * @param customId Contiene el id del tipo de activo
   */
  private eliminarTipoActivo(customId: number) {
    // Envia peticion de guardado de datos
    axios
      .get("get-use-type-asset?idAssetType=" + customId)
      .then((res) => {
        // Valida la cantidad de veces que se usa el tipo de acivo en las categorías, autorizaciones y hojas de vida
        if (res.data.data > 0) {
          // Abre el swal para mostrar la advertencia de que no se puede eliminar el registro, porque ya esta siendo utilizado
          this.$swal({
            icon: "warning",
            html: res.data.message,
            confirmButtonText: this.lang.get("trans.Accept"),
            allowOutsideClick: false,
          });
        } else {
          // Ejecuta la petición para eliminar el tipo de activo, mandándole como parámetro el id del tipo de activo
          (this.$parent as any)["drop"](customId);
        }
      })
      .catch((err) => {
        // Abre el swal para mostrar los errores de la consulta
        this.$swal({
          icon: "error",
          html: "Error al eliminar el tipo de activo: " + err,
          confirmButtonText: this.lang.get("trans.Accept"),
          allowOutsideClick: false,
        });
      });
  }

  /**
   * Consulta y elimina una categoría si es posible
   *
   * @author German Gonzalez V. - Sep. 14 - 2021
   * @version 1.0.0
   * @param customId Contiene el id de la categoría
   */
  private eliminarCategory(customId: number) {
    // Envia peticion de guardado de datos
    axios
      .get("get-use-category?idCategory=" + customId)
      .then((res) => {
        // Valida la cantidad de veces que se usa la categoría en las autorizaciones y hojas de vida
        if (res.data.data > 0) {
          // Abre el swal para mostrar la advertencia de que no se puede eliminar el registro, porque ya esta siendo utilizado
          this.$swal({
            icon: "warning",
            html: res.data.message,
            confirmButtonText: this.lang.get("trans.Accept"),
            allowOutsideClick: false,
          });
        } else {
          // Ejecuta la petición para eliminar la categoría, mandándole como parámetro el id de la categoría
          (this.$parent as any)["drop"](customId);
        }
      })
      .catch((err) => {
        // Abre el swal para mostrar los errores de la consulta
        this.$swal({
          icon: "error",
          html: "Error al eliminar la categoría: " + err,
          confirmButtonText: this.lang.get("trans.Accept"),
          allowOutsideClick: false,
        });
      });
  }
}
</script>
