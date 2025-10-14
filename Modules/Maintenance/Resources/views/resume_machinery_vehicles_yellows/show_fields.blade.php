<!-- Panel información inicial -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Información inicial</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Dependencias Id Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Proceso'):</dt>
            <dd class="col-3">@{{ dataShow.dependencies.nombre }}.</dd>

            <!-- Name Vehicle Machinery Field -->
            <dt class="text-inverse text-left col-3 pb-2">Nombre de activo:</dt>
            <dd class="col-3">@{{ dataShow.name_vehicle_machinery }}.</dd>

            <!-- No Inventory Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Inventory'):</dt>
            <dd class="col-3">@{{ dataShow.no_inventory }}.</dd>

            <!-- Purchase Price Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Purchase Price'):</dt>
            <dd class="col-3">$ @{{ dataShow.purchase_price }}</dd>

            <!-- Sheet Elaboration Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Sheet Elaboration Date'):</dt>
            <dd class="col-3">@{{ dataShow.sheet_elaboration_date }}.</dd>

            <!-- Mileage Start Activities Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Mileage Start Activities'):</dt>
            <dd class="col-3">@{{ dataShow.mileage_start_activities }}.</dd>

            <!-- Mark Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Mark'):</dt>
            <dd class="col-3">@{{ dataShow.mark }}.</dd>

            <!-- Mark Field -->
            <dt class="text-inverse text-left col-3 pb-2">Linea:</dt>
            <dd class="col-3">@{{ dataShow.line }}.</dd>

            <!-- Model Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Model'):</dt>
            <dd class="col-3">@{{ dataShow.model }}.</dd>

            <!-- No Motor Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Motor'):</dt>
            <dd class="col-3">@{{ dataShow.no_motor }}.</dd>

            <!-- Invoice Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Invoice Number'):</dt>
            <dd class="col-3">@{{ dataShow.invoice_number }}.</dd>

            <!-- Date Put Into Service Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Date Put Into Service'):</dt>
            <dd class="col-3">@{{ dataShow.date_put_into_service }}.</dd>

            <!-- Warranty Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Warranty Date'):</dt>
            <dd class="col-3">@{{ dataShow.warranty_date }}.</dd>

            <!-- Warranty description Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Warranty description'):</dt>
            <dd class="col-3">@{{ dataShow.warranty_description }}.</dd>

            <!-- Service Retirement Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Service Retirement Date'):</dt>
            <dd class="col-3">@{{ dataShow.service_retirement_date }}.</dd>

            <!-- Warehouse Entry Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Warehouse Entry Number'):</dt>
            <dd class="col-3">@{{ dataShow.warehouse_entry_number }}.</dd>

            <!-- Warehouse Exit Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Warehouse Exit Number'):</dt>
            <dd class="col-3">@{{ dataShow.warehouse_exit_number }}.</dd>

            <!-- Delivery Date Vehicle By Provider Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Delivery Date Vehicle By Provider'):</dt>
            <dd class="col-3">@{{ dataShow.delivery_date_vehicle_by_provider }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Características del vehículo o maquinaria amarilla -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Características del vehículo o maquinaria amarilla</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Plaque Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Plaque'):</dt>
            <dd class="col-3">@{{ dataShow.plaque }}.</dd>

            <!-- Color Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Color'):</dt>
            <dd class="col-3">@{{ dataShow.color }}.</dd>

            <!-- Chassis Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Chassis Number'):</dt>
            <dd class="col-3">@{{ dataShow.chassis_number }}.</dd>

            <!-- Service Class Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Service Class'):</dt>
            <dd class="col-3">@{{ dataShow.service_class }}.</dd>

            <!-- Body Type Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Body Type'):</dt>
            <dd class="col-3">@{{ dataShow.body_type }}.</dd>

            <!-- Transit License Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Transit License Number'):</dt>
            <dd class="col-3">@{{ dataShow.transit_license_number }}.</dd>
    
            <!-- Number Passengers Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Number Passengers'):</dt>
            <dd class="col-3">@{{ dataShow.number_passengers }}.</dd>

            <!-- Fuel Type Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Fuel Type'):</dt>
            <dd class="col-3">@{{ dataShow.fuel_type }}.</dd>

            <!-- Number Tires Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Number Tires'):</dt>
            <dd class="col-3">@{{ dataShow.number_tires }}.</dd>

            <!-- Number Batteries Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Number Batteries'):</dt>
            <dd class="col-3">@{{ dataShow.number_batteries }}.</dd>

            <!-- Battery Reference Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Battery Reference'):</dt>
            <dd class="col-3">@{{ dataShow.battery_reference }}.</dd>

            <!-- Gallon Tank Capacity Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Gallon Tank Capacity'):</dt>
            <dd class="col-3">@{{ dataShow.gallon_tank_capacity }}.</dd>

            <!-- Tons Capacity Load Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Tons Capacity Load'):</dt>
            <dd class="col-3">@{{ dataShow.tons_capacity_load }}.</dd>

            <!-- Cylinder Capacity Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Cylinder Capacity'):</dt>
            <dd class="col-3">@{{ dataShow.cylinder_capacity }}.</dd>

            <!-- Expiration Date Soat Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Expiration Date Soat'):</dt>
            <dd class="col-3">@{{ dataShow.expiration_date_soat }}.</dd>

            <!-- Expiration Date Tecnomecanica Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Expiration Date Tecnomecanica'):</dt>
            <dd class="col-3">@{{ dataShow.expiration_date_tecnomecanica }}.</dd>

            <!-- Person Prepares Resume Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Person Prepares Resume'):</dt>
            <dd class="col-3">@{{ dataShow.person_prepares_resume }}.</dd>

            <!-- Person Reviewed Approved Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Person Reviewed Approved'):</dt>
            <dd class="col-3">@{{ dataShow.person_reviewed_approved }}.</dd>
        </div>
    </div>
</div>

<!-- Panel Características del vehículo o maquinaria amarilla -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">

    <div class="panel-body">
        <div class="row">
            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered" style="text-align: center">
                        <thead>
                            <tr>
                                <td colspan="5">Información general de las llantas</td>
                            </tr>
                            <tr>
                                <td>Código de la llanta</td>
                                <td>Referencia de la llanta</td>
                                <td>Posición</td>
                                <td>Ubicación de la llanta</td>
                                <td>Estado de la llanta</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(information, key) in dataShow.tire_informations" v-if="information.state != 'Dada de baja'">
                                <td>@{{ information.code_tire }}</td>
                                <td>@{{ information.tire_reference }}</td>
                                <td>@{{ information.position_tire }}</td>
                                <td>@{{ information.location_tire }}</td>
                                <td>@{{ information.state }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Panel Proveedor -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Proveedor</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Type Person Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Type Person'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.type_person : '' }}.</dd>

            <!-- Document Type Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Document Type'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.document_type : '' }}.</dd>

            <!-- Identification Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Identification'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.identification : '' }}.</dd>

            <!-- Name Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Name'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.name : '' }}.</dd>

            <!-- Mail Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Mail'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.mail : '' }}.</dd>

            <!-- Regime Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Regime'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.regime : '' }}.</dd>

            <!-- Phone Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Phone'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.phone : '' }}.</dd>

            <!-- Address Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Address'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.address : '' }}.</dd>

            <!-- Municipality Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Municipality'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.municipality : '' }}.</dd>

            <!-- Department Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Department'):</dt>
            <dd class="col-3">@{{ dataShow.provider ? dataShow.provider.department : '' }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Observaciones -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Observaciones</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Observation Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Observation'):</dt>
            <dd class="col-3">@{{ dataShow.observation }}.</dd>

            <!-- Status Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Status'):</dt>
            <dd class="col-3">@{{ dataShow.status }}.</dd>
        </div>
    </div>
</div>

<!-- Panel documentos del activo -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Documentos del activo</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Adjuntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.mant_documents_asset">
                                <td>@{{ document.name }}</td>
                                <td style="white-space: break-spaces;">@{{ document.description }}</td>
                                <td v-if="document.url_document">
                                    <span v-for="documento in document.url_document.split(',')" 
                                          style="display: inline-block; margin-bottom: 8px; margin-right: -5px;">
                                        <viewer-attachement :display-flex="true" :link-file-name="true"  
                                            :ref="document.name" :component-reference="document.name"
                                            :list="documento" 
                                            :key="documento" 
                                            ></viewer-attachement>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.mantenances_actives?.length > 0">
 <div class="panel-heading d-flex align-items-center">
    
    <div class="d-flex align-items-center gap-2">
        <!-- Botón Ver mantenimientos -->
        <button
            class="btn btn-outline-success d-flex align-items-center mr-2"
            data-toggle="collapse"
            href="#collapseMantenimientos"
            role="button"
            aria-expanded="false"
            aria-controls="collapseMantenimientos"
        >
            <i class="fa fa-hand-pointer mr-2"></i>
            <span>Ver mantenimientos</span>
        </button>

        <!-- Botón de descarga -->
        
            <a
            :href="`{{ route('exportMantenances', '') }}/${dataShow.plaque}`"
            class="d-flex align-items-center justify-content-center"
            data-toggle="tooltip"
            title="Descargar mantenimientos"
            style="width: 42px; height: 42px; border: 1px solid #ddd; color: inherit; text-decoration: none;"
            >
            <i class="fa fa-file-download"></i>
            </a>

    </div>

  
</div>


  <!-- Panel de contenido colapsable -->
  <div class="collapse mt-3" id="collapseMantenimientos">
    <div class="panel-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered fix-vertical-table">
          <thead class="text-center text-white" style="background-color: rgb(0, 176, 189);">
            <tr>
              <th>Fecha de ingreso</th>
              <th>Nombre de activo</th>
              <th>Tipo mantenimiento</th>
              <th>Repuesto</th>
              <th>Actividad</th>
              <th>Cantidad</th>
              <th>Unidad de medida</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in dataShow.mantenances_actives" :key="key">
              <td>@{{ item.created_at }}</td>
              <td>@{{ item.nombre_activo }}</td>
              <td>@{{ item.tipo_mantenimiento }}</td>
              <td>@{{ item.repuestos ? item.repuestos.descripcion_nombre : "No aplica"}}</td>
              <td>@{{ item.actividades ? item.actividades.descripcion_nombre : "No aplica" }}</td>
              <td>@{{ item.repuestos ? item.repuestos.cantidad_solicitada : item.actividades?.cantidad_solicitada }}</td>
              <td>@{{ item.unidad_medida }}</td>
              <td>
                <a :href="'/maintenance/asset-managements?qus=' + item.id_encripted" class="btn btn-white btn-icon btn-md" title="Ver detalle" target="_blank">
                      <span class="sr-only">Ver detalle</span>
                    <i class="fa fa-search"></i>
                    </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="panel col-md-12" v-else>
  <div class="alert alert-info d-flex align-items-center" role="alert">
    <i class="fa fa-info-circle mr-2"></i>
    <span>No se registran mantenimientos para la vigencia actual.</span>
  </div>
</div>
