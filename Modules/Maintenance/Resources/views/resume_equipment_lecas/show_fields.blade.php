<!-- Panel Descripción del equipamiento -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Descripción del equipamiento</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Name Equipment Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Name Equipment'):</dt>
            <dd class="col-3">@{{ dataShow.name_equipment }}.</dd>


            <!-- Internal Code Leca Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Internal Code Leca'):</dt>
            <dd class="col-3">@{{ dataShow.internal_code_leca }}.</dd>


            <!-- Inventory No Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Inventory No'):</dt>
            <dd class="col-3">@{{ dataShow.inventory_no }}.</dd>


            <!-- Mark Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Mark'):</dt>
            <dd class="col-3">@{{ dataShow.mark }}.</dd>


            <!-- Serie Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Serie'):</dt>
            <dd class="col-3">@{{ dataShow.serie }}.</dd>


            <!-- Model Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Model'):</dt>
            <dd class="col-3">@{{ dataShow.model }}.</dd>


            <!-- Location Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Location'):</dt>
            <dd class="col-3">@{{ dataShow.location }}.</dd>


            <!-- Software Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Software'):</dt>
            <dd class="col-3">@{{ dataShow.software }}.</dd>


            <!-- Purchase Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Purchase Date'):</dt>
            <dd class="col-3">@{{ dataShow.purchase_date }}.</dd>


            <!-- Commissioning Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Commissioning Date'):</dt>
            <dd class="col-3">@{{ dataShow.commissioning_date }}.</dd>


            <!-- Date Withdrawal Service Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Date Withdrawal Service'):</dt>
            <dd class="col-3">@{{ dataShow.date_withdrawal_service }}.</dd>

            <!-- Date Withdrawal Service Field -->
            <dt class="text-inverse text-left col-3 pb-2">Precio de compra:</dt>
            <dd class="col-3">@{{ dataShow.purchase_price }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Información del fabricante -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Información del fabricante</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Maker Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Maker'):</dt>
            <dd class="col-3">@{{ dataShow.maker }}.</dd>
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
<!-- Panel Documentación de apoyo técnico -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Documentación de apoyo técnico</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Catalogue Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Catalogue'):</dt>
            <dd class="col-3">@{{ dataShow.catalogue }}.</dd>


            <!-- Catalogue Location Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Catalogue Location'):</dt>
            <dd class="col-3">@{{ dataShow.catalogue_location }}.</dd>


            <!-- Idiom Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Idiom'):</dt>
            <dd class="col-9">@{{ dataShow.idiom }}.</dd>


            <!-- Instructive Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Instructive'):</dt>
            <dd class="col-3">@{{ dataShow.instructive }}.</dd>


            <!-- Instructional Location Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Instructional Location'):</dt>
            <dd class="col-3">@{{ dataShow.instructional_location }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Especificaciones técnicas -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Especificaciones técnicas</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Magnitude Control Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Magnitude Control'):</dt>
            <dd class="col-3">@{{ dataShow.magnitude_control }}.</dd>


            <!-- Consumables Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Consumables'):</dt>
            <dd class="col-3">@{{ dataShow.consumables }}.</dd>


            <!-- Resolution Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Resolution'):</dt>
            <dd class="col-3">@{{ dataShow.resolution }}.</dd>


            <!-- Accessories Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Accessories'):</dt>
            <dd class="col-3">@{{ dataShow.accessories }}.</dd>


            <!-- Operation Range Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Operation Range'):</dt>
            <dd class="col-3">@{{ dataShow.operation_range }}.</dd>


            <!-- Voltage Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Voltage'):</dt>
            <dd class="col-3">@{{ dataShow.voltage }}.</dd>


            <!-- Use Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Use'):</dt>
            <dd class="col-3">@{{ dataShow.use }}.</dd>


            <dt class="text-inverse text-left col-3 pb-2">@lang('Use Range'):</dt>
            <dd class="col-3">@{{ dataShow.use_range }}.</dd>


            <!-- Allowable Error Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Allowable Error'):</dt>
            <dd class="col-3">@{{ dataShow.allowable_error }}.</dd>


            <!-- Minimum Permissible Error Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Minimum Permissible Error'):</dt>
            <dd class="col-3">@{{ dataShow.minimum_permissible_error }}.</dd>


            <!-- Environmental Operating Conditions Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Environmental Operating Conditions'):</dt>
            <dd class="col-3">@{{ dataShow.environmental_operating_conditions }}.</dd>
            
            <hr style="width: 100%;" />

            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Puntos de calibración y/o verificación</th>
                                <th>Norma de referencia de calibración y/o verificación</th>
                                <th>Criterio de aceptación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.specifications_equipment_leca">
                                <td>@{{ document.calibration_verification_points }}</td>
                                <td>@{{ document.reference_standard_calibration_verification }}</td>
                                <td>@{{ document.acceptance_requirements }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
                                        <br/>
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