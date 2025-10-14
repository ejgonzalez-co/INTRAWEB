<!-- Panel Descripción del equipo -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Descripción del equipo</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Name Equipment Machinery Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Name Equipment Machinery'):</dt>
            <dd class="col-3">@{{ dataShow.name_equipment_machinery }}.</dd>


            <!-- No Identification Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Identification'):</dt>
            <dd class="col-3">@{{ dataShow.no_identification }}.</dd>


            <!-- No Inventory Epa Esp Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Inventory Epa Esp'):</dt>
            <dd class="col-3">@{{ dataShow.no_inventory_epa_esp }}.</dd>


            <!-- Mark Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Mark'):</dt>
            <dd class="col-3">@{{ dataShow.mark }}.</dd>


            <!-- Model Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Model'):</dt>
            <dd class="col-3">@{{ dataShow.model }}.</dd>


            <!-- Serie Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Serie'):</dt>
            <dd class="col-3">@{{ dataShow.serie }}.</dd>

            <!-- purchase_price Field -->
            <dt class="text-inverse text-left col-3 pb-2">Precio de compra:</dt>
            <dd class="col-3">$ @{{ dataShow.purchase_price }}.</dd>

            <!-- Location Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Location'):</dt>
            <dd class="col-3">@{{ dataShow.location }}.</dd>


            <!-- Path Information Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Path Information'):</dt>
            <dd class="col-3">@{{ dataShow.path_information }}.</dd>


            <!-- Acquisition Contract Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Acquisition Contract'):</dt>
            <dd class="col-3">@{{ dataShow.acquisition_contract }}.</dd>


            <!-- Provider Data Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Provider Data'):</dt>
            <dd class="col-3">@{{ dataShow.provider_data }}.</dd>
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
<!-- Panel Composición del equipamiento o maquinaria -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Composición del equipamiento o maquinaria</strong></h3>
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
                                <th>Referencia</th>
                                {{-- <th>Observación</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.composition_equipment_leca">
                                <td>@{{ document.accessory_parts }}</td>
                                <td>@{{ document.reference }}</td>
                                {{-- <td>@{{ document.observation }}</td> --}}
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div style="margin: auto;">
                <div class="table-responsive">

                    <table class="table table-responsive table-bordered ">
                        <thead>
                            <tr>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>@{{ dataShow.observation_composition }}</td>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Panel Catálogo o especificaciones técnicas -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Catálogo o especificaciones técnicas</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Apply Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Apply'):</dt>
            <dd class="col-3">@{{ dataShow.apply }}.</dd>


            <!-- Location Specification Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Location Specification'):</dt>
            <dd class="col-3">@{{ dataShow.location_specification }}.</dd>


            <!-- Language Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Language'):</dt>
            <dd class="col-3">@{{ dataShow.language }}.</dd>


            <!-- Version Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Version'):</dt>
            <dd class="col-3">@{{ dataShow.version }}.</dd>


            <!-- Purchase Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Purchase Date'):</dt>
            <dd class="col-3">@{{ dataShow.purchase_date }}.</dd>


            <!-- Commissioning Date Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Commissioning Date'):</dt>
            <dd class="col-3">@{{ dataShow.commissioning_date }}.</dd>


            <!-- Date Withdrawal Service Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Date Withdrawal Service'):</dt>
            <dd class="col-3">@{{ dataShow.date_withdrawal_service }}.</dd>


            <!-- Observations Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Observations'):</dt>
            <dd class="col-3">@{{ dataShow.observations }}.</dd>


            <!-- Vo Bo Name Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Vo Bo Name'):</dt>
            <dd class="col-3">@{{ dataShow.vo_bo_name }}.</dd>


            <!-- Vo Bo Cargo Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Vo Bo Cargo'):</dt>
            <dd class="col-3">@{{ dataShow.vo_bo_cargo }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Características -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Características</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Magnitude Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Magnitude'):</dt>
            <dd class="col-3">@{{ dataShow.magnitude }}.</dd>


            <!-- Unit Measurement Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Unit Measurement'):</dt>
            <dd class="col-3">@{{ dataShow.unit_measurement }}.</dd>


            <!-- Scale Division Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Scale Division'):</dt>
            <dd class="col-3">@{{ dataShow.scale_division }}.</dd>


            <!-- Manufacturer Specification Max Permissible Error Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Manufacturer Specification Max Permissible Error'):</dt>
            <dd class="col-3">@{{ dataShow.manufacturer_specification_max_permissible_error }}.</dd>


            <!-- Max Permissible Error Technical Standard Process Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Max Permissible Error Technical Standard Process'):</dt>
            <dd class="col-3">@{{ dataShow.max_permissible_error_technical_standard_process }}.</dd>


            <!-- Measurement Range Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Measurement Range'):</dt>
            <dd class="col-3">@{{ dataShow.measurement_range }}.</dd>


            <!-- Operation Range Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Operation Range'):</dt>
            <dd class="col-3">@{{ dataShow.operation_range }}.</dd>


            <!-- Use Parameter Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Use Parameter'):</dt>
            <dd class="col-3">@{{ dataShow.use_parameter }}.</dd>


            <!-- Use Recommendations Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Use Recommendations'):</dt>
            <dd class="col-3">@{{ dataShow.use_recommendations }}.</dd>


            <!-- Resolution Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Resolution'):</dt>
            <dd class="col-3">@{{ dataShow.resolution }}.</dd>


            <!-- Analog Indication Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Analog Indication'):</dt>
            <dd class="col-3">@{{ dataShow.analog_indication }}.</dd>


            <!-- Digital Indication Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Digital Indication'):</dt>
            <dd class="col-3">@{{ dataShow.digital_indication }}.</dd>


            <!-- Wavelength Indication Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Wavelength Indication'):</dt>
            <dd class="col-3">@{{ dataShow.wavelength_indication }}.</dd>


            <!-- Adsorption Indication Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Adsorption Indication'):</dt>
            <dd class="col-3">@{{ dataShow.adsorption_indication }}.</dd>


            <!-- Feeding Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Feeding'):</dt>
            <dd class="col-3">@{{ dataShow.feeding }}.</dd>


            <!-- Voltage Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Voltage'):</dt>
            <dd class="col-3">@{{ dataShow.voltage }}.</dd>


            <!-- Rh Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Rh'):</dt>
            <dd class="col-3">@{{ dataShow.RH }}.</dd>


            <!-- Power Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Power'):</dt>
            <dd class="col-3">@{{ dataShow.power }}.</dd>


            <!-- Temperature Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Temperature'):</dt>
            <dd class="col-3">@{{ dataShow.temperature }}.</dd>


            <!-- Frequency Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Frequency'):</dt>
            <dd class="col-3">@{{ dataShow.frequency }}.</dd>


            <!-- Revolutions Per Minute Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Revolutions Per Minute'):</dt>
            <dd class="col-3">@{{ dataShow.revolutions_per_minute }}.</dd>


            <!-- Type Protection Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Type Protection'):</dt>
            <dd class="col-3">@{{ dataShow.type_protection }}.</dd>


            <!-- Rated Current Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Rated Current'):</dt>
            <dd class="col-3">@{{ dataShow.rated_current }}.</dd>


            <!-- Rated Power Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Rated Power'):</dt>
            <dd class="col-3">@{{ dataShow.rated_power }}.</dd>


            <!-- Operating Conditions Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Operating Conditions'):</dt>
            <dd class="col-3">@{{ dataShow.operating_conditions }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Mantenimiento, calibración, verificación y/o comprobación -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Mantenimiento, calibración, verificación y/o comprobación</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <div class="col-md-3">
                <!-- Calibration Validation External Verification Field -->
                {{-- <dd class="col-3">@{{ dataShow.calibration_validation_external_verification }}.</dd> --}}

                 <!-- Calibration Frequency Field -->
                 <b><label class="col-8 text-left">@lang('Calibration Validation External Verification') </label></b>    
            </div>

            <div class="col-md-7">
                <b><label for="" class="col-4 text-left">@lang('Calibration Frequency'):</label></b>
                <label class="col-3">@{{ dataShow.calibration_frequency }}.</label>
            </div>

            <div class="col-md-3">
                <!-- Preventive Maintenance Field -->
                {{-- <dd class="col-3">@{{ dataShow.preventive_maintenance }}.</dd> --}}

                <!-- Maintenance Frequency Field -->
                <b><label class="col-8 text-left">@lang('Preventive Maintenance')</label></b>
            </div>

            <div class="col-md-7">
                <b><label class="col-4 text-left">@lang('Maintenance Frequency'):</label></b>
                <label class="col-3">@{{ dataShow.maintenance_frequency }}.</label>
            </div>

            <div class="col-md-3">
                <b><label class="col-8 text-left">@lang('Verification Internal Verification')</label></b>
            </div>

            <div class="col-md-6">
                <!-- Verification Internal Verification Field -->
                {{-- <dd class="col-3">@{{ dataShow.verification_internal_verification }}.</dd> --}}
        
                <!-- Verification Frequency Field -->
                <b><label class="col-4 text-left">@lang('Verification Frequency'):</label></b>
                <label class="col-3">@{{ dataShow.verification_frequency }}.</label>
            </div>

            <div class="col-md-10">
                <!-- Procedure Code Field -->
                <b><label class="col-4 text-left">@lang('Procedure Code'):</label></b>
                <label class="col-3">@{{ dataShow.procedure_code }}.</label>

            </div>


            <!-- Calibration Points Field -->
            {{-- <dt class="text-inverse text-left col-3 pb-2">@lang('Calibration Points'):</dt>
            <dd class="col-3">@{{ dataShow.calibration_points }}.</dd> --}}


            <!-- Calibration Under Accreditation Field -->
            {{-- <dt class="text-inverse text-left col-3 pb-2">@lang('Calibration Under Accreditation'):</dt>
            <dd class="col-3">@{{ dataShow.calibration_under_accreditation }}.</dd> --}}


            <!-- Reference Norm Field -->
            {{-- <dt class="text-inverse text-left col-3 pb-2">@lang('Reference Norm'):</dt>
            <dd class="col-3">@{{ dataShow.reference_norm }}.</dd> --}}


            <!-- Measure Pattern Field -->
            {{-- <dt class="text-inverse text-left col-3 pb-2">@lang('Measure Pattern'):</dt>
            <dd class="col-3">@{{ dataShow.measure_pattern }}.</dd> --}}


            <!-- Criteria Acceptance Field -->
            {{-- <dt class="text-inverse text-left col-3 pb-2">@lang('Criteria Acceptance'):</dt>
            <dd class="col-3">@{{ dataShow.criteria_acceptance }}.</dd> --}}


            <!-- Calibration Test Field -->
            {{-- <dt class="text-inverse text-left col-3 pb-2">@lang('Calibration Test'):</dt>
            <dd class="col-3">@{{ dataShow.calibration_test }}.</dd>  --}}
            
        </div>
    </div>
</div>

{{-- Punto de calibración --}}
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Punto de calibración</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        
            <div class="row col-12 mt-3">
                <div class="row" style="margin: auto;">
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>Puntos de Calibración, Validación, verificación y/o Comprobación(valor nominal)</th>
                                    <th>Calibración Bajo Acreditación</th>
                                    <th>Norma de Referencia de Calibración, Validación, verificación y/o Comprobación(cuando aplique)</th>
                                    <th>Nombre Pruebas de Calibración, Validación, Verificación y/o Comprobación</th>
                                    <th>Criterios de aceptación Pruebas de Calibración, Validación, Verificación y/o Comprobación</th>
                                    <th>Patrón de medida</th>
                                    <th>Criterios de aceptación de certificado de calibración o informe de validación, verificación y/o Comprobación(Datos suministrados por la norma de ensayo, de calibración o por el fabricante)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(document, key) in dataShow.maintenance_equipment_leca">
                                    <td>@{{ document.verification }}</td>
                                    <td>@{{ document.calibration_under_accreditation }}</td>
                                    <td>@{{ document.rule_reference_calibration }}</td>
                                    <td>@{{ document.name }}</td>
                                    <td>@{{ document.acceptance_requirements }}</td>
                                    <td>@{{ document.measure_standard }}</td>
                                    <td>@{{ document.criteria_acceptance_certificate }}</td>
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
