<!-- Panel información general -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Información general</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">

            <!-- Dependencias Id Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Proceso'):</dt>
            <dd class="col-3">@{{ dataShow.dependencies.nombre }}.</dd>


            <!-- Name Equipment Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Name Equipment'):</dt>
            <dd class="col-3">@{{ dataShow.name_equipment }}.</dd>


            <!-- No Identification Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Identification'):</dt>
            <dd class="col-3">@{{ dataShow.no_identification }}.</dd>


            <!-- No Inventory Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Inventory'):</dt>
            <dd class="col-3">@{{ dataShow.no_inventory }}.</dd>


            <!-- Mark Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Mark'):</dt>
            <dd class="col-3">@{{ dataShow.mark }}.</dd>


            <!-- Model Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Model'):</dt>
            <dd class="col-3">@{{ dataShow.model }}.</dd>


            <!-- Serie Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Serie'):</dt>
            <dd class="col-3">@{{ dataShow.serie }}.</dd>


            <!-- Ubication Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Ubication'):</dt>
            <dd class="col-3">@{{ dataShow.ubication }}.</dd>


            <!-- Purchase Price Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Purchase Price'):</dt>
            <dd class="col-3">$ @{{ dataShow.purchase_price }}</dd>


            <!-- No Invoice Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Invoice'):</dt>
            <dd class="col-3">@{{ dataShow.no_invoice }}.</dd>

            <!-- Warehouse Entry Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Warehouse Entry Number'):</dt>
            <dd class="col-3">@{{ dataShow.warehouse_entry_number }}.</dd>


            <!-- Type Number The Acquisition Contract Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Acquisition Contract'):</dt>
            <dd class="col-3">@{{ dataShow.type_number_the_acquisition_contract }}.</dd>


            <!-- Equipment Warranty Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Equipment Warranty'):</dt>
            <dd class="col-3">@{{ dataShow.equipment_warranty }}.</dd>


            <!-- Requirement For Operation Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Requirement For Operation'):</dt>
            <dd class="col-3">@{{ dataShow.requirement_for_operation }}.</dd>


            <!-- Description For Operation Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Description For Operation'):</dt>
            <dd class="col-3">@{{ dataShow.description_for_operation }}.</dd>

            <!--Consecutive Field -->
            <dt class="text-inverse text-left col-3 pb-2">Consecutivo:</dt>
            <dd class="col-3">@{{ dataShow.consecutive }}.</dd>

             <!-- fuel_type For Operation Field -->
             <dt class="text-inverse text-left col-3 pb-2">Tipo de gasolina:</dt>
             <dd class="col-3">@{{ dataShow.fuel_type }}.</dd>
        </div>
    </div>
</div>
<!-- Panel características -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Características</strong></h3>
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
                                <th>Partes y/o accesorios</th>
                                <th>Cantidad</th>
                                <th>Referencia o número de parte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.characteristics_equipment">
                                <td>@{{ document.accessory_parts }}</td>
                                <td>@{{ document.amount }}</td>
                                <td>@{{ document.reference_part_number }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Panel Mantenimiento, verificación y estado actual -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Mantenimiento, verificación y estado actual</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Catalog Specifications Id Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Catalog Specifications Id'):</dt>
            <dd class="col-3">@{{ dataShow.catalog_specifications }}.</dd>


            <!-- Location Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Location'):</dt>
            <dd class="col-3">@{{ dataShow.location }}.</dd>


            <!-- Language Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Language'):</dt>
            <dd class="col-3">@{{ dataShow.language }}.</dd>


            <!-- Version Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Version'):</dt>
            <dd class="col-3">@{{ dataShow.version }}.</dd>


            <!-- Technical Verification Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Technical Verification'):</dt>
            <dd class="col-3">@{{ dataShow.technical_verification }}.</dd>


            <!-- Technical Verification Frequency Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Technical Verification Frequency'):</dt>
            <dd class="col-3">@{{ dataShow.technical_verification_frequency }}.</dd>


            <!-- Preventive Maintenance Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Preventive Maintenance'):</dt>
            <dd class="col-3">@{{ dataShow.preventive_maintenance }}.</dd>


            <!-- Preventive Maintenance Frequency Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Preventive Maintenance Frequency'):</dt>
            <dd class="col-3">@{{ dataShow.preventive_maintenance_frequency }}.</dd>


            <!-- Person Responsible Team Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Person Responsible Team'):</dt>
            <dd class="col-3">@{{ dataShow.person_responsible_team }}.</dd>


            <!-- Person Prepares Resume Equipment Machinery Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Person Prepares Resume Equipment Machinery'):</dt>
            <dd class="col-3">@{{ dataShow.person_prepares_resume_equipment_machinery }}.</dd>

        </div>
    </div>
</div>
<!-- Panel información almacén -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Información almacén</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Purchase Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Purchase Date'):</dt>
            <dd class="col-3">@{{ dataShow.purchase_date }}.</dd>


            <!-- Service Start Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Service Start Date'):</dt>
            <dd class="col-3">@{{ dataShow.service_start_date }}.</dd>


            <!-- Retirement Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Retirement Date'):</dt>
            <dd class="col-3">@{{ dataShow.retirement_date }}.</dd>

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
                                <td v-if="document.url_document != null">
                                    <span v-for="documento in document.url_document.split(',')" style="display: inline-block; margin-bottom: 8px; margin-right: -5px;">
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