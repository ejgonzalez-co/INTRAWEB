<!-- Panel Generalidad del equipamiento (inventario) -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Generalidad del equipamiento (inventario)</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- No Inventory Epa Esp Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('No Inventory Epa Esp'):</dt>
            <dd class="col-3">@{{ dataShow.no_inventory_epa_esp }}.</dd>


            <!-- Leca Code Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Leca Code'):</dt>
            <dd class="col-3">@{{ dataShow.leca_code }}.</dd>


            <!-- Description Equipment Name Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Description Equipment Name'):</dt>
            <dd class="col-3">@{{ dataShow.description_equipment_name }}.</dd>


            <!-- Maker Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Maker'):</dt>
            <dd class="col-3">@{{ dataShow.maker }}.</dd>


            <!-- Serial Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Serial Number'):</dt>
            <dd class="col-3">@{{ dataShow.serial_number }}.</dd>


            <!-- Model Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Model'):</dt>
            <dd class="col-3">@{{ dataShow.model }}.</dd>


            <!-- Location Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Location'):</dt>
            <dd class="col-3">@{{ dataShow.location }}.</dd>


            <!-- Measured Used Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Measured Used'):</dt>
            <dd class="col-3">@{{ dataShow.measured_used }}.</dd>


            <!-- Unit Measurement Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Unit Measurement'):</dt>
            <dd class="col-3">@{{ dataShow.unit_measurement }}.</dd>


            <!-- Resolution Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Resolution'):</dt>
            <dd class="col-3">@{{ dataShow.resolution }}.</dd>


            <!-- Manufacturer Error Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Manufacturer Error'):</dt>
            <dd class="col-3">@{{ dataShow.manufacturer_error }}.</dd>


            <!-- Operation Range Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Operation Range'):</dt>
            <dd class="col-3">@{{ dataShow.operation_range }}.</dd>


            <!-- Range Use Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Range Use'):</dt>
            <dd class="col-3">@{{ dataShow.range_use }}.</dd>


            <!-- Operating Conditions Temperature Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Operating Conditions Temperature'):</dt>
            <dd class="col-3">@{{ dataShow.operating_conditions_temperature }}.</dd>


            <!-- Condition Oper Elative Humidity Hr Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Condition Oper Elative Humidity Hr'):</dt>
            <dd class="col-3">@{{ dataShow.condition_oper_elative_humidity_hr }}.</dd>


            <!-- Condition Oper Voltage Range Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Condition Oper Voltage Range'):</dt>
            <dd class="col-3">@{{ dataShow.condition_oper_voltage_range }}.</dd>


            <!-- Maintenance Metrological Operation Frequency Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Maintenance Metrological Operation Frequency'):</dt>
            <dd class="col-3">@{{ dataShow.maintenance_metrological_operation_frequency }}.</dd>


            <!-- Calibration Metrological Operating Frequency Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Calibration Metrological Operating Frequency'):</dt>
            <dd class="col-3">@{{ dataShow.calibration_metrological_operating_frequency }}.</dd>


            <!-- Qualification Metrological Operating Frequency Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Qualification Metrological Operating Frequency'):</dt>
            <dd class="col-3">@{{ dataShow.qualification_metrological_operating_frequency }}.</dd>


            <!-- Intermediate Verification Metrological Operating Frequency Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Intermediate Verification Metrological Operating Frequency'):</dt>
            <dd class="col-3">@{{ dataShow.intermediate_verification_metrological_operating_frequency }}.</dd>
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
<!-- Panel Cronograma del aseguramiento metrológico -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Cronograma del aseguramiento metrológico</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Actividad metrológica</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.schedule_inventory_leca">
                                <td>@{{ document.month }}</td>
                                <td>@{{ document.metrological_activity }}</td>
                                <td>@{{ document.description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Total Interventions Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Total Interventions'):</dt>
            <dd class="col-3">@{{ dataShow.total_interventions }}.</dd>
        </div>
        <hr style="width: 100%;" />
        <div class="form-group row m-b-15">
            <!-- Name Elaborated Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Name Elaborated'):</dt>
            <dd class="col-3">@{{ dataShow.name_elaborated }}.</dd>


            <!-- Cargo Role Elaborated Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Cargo Role Elaborated'):</dt>
            <dd class="col-3">@{{ dataShow.cargo_role_elaborated }}.</dd>


            <!-- Name Updated Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Name Updated'):</dt>
            <dd class="col-3">@{{ dataShow.name_updated }}.</dd>


            <!-- Cargo Role Updated Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Cargo Role Updated'):</dt>
            <dd class="col-3">@{{ dataShow.cargo_role_updated }}.</dd>


            <!-- Technical Director Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Technical Director'):</dt>
            <dd class="col-3">@{{ dataShow.technical_director }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Convenciones -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Convenciones</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <div class="row" style="margin: auto;">
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
                            <td>MP</td>
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
                            <td colspan="3">Inspección del funcionamiento adecuado del equipamiento</td>
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