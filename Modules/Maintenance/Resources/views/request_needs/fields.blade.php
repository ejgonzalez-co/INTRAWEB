<!-- PANEL CONTENEDOR: Información General -->
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <div class="panel-body">
        <!-- SECCIÓN 1: Selección de Proceso y Tipo de Solicitud -->
        <div class="form-group row m-b-15">
            
            <!-- Campo: Proceso (Dependencia) -->
            {!! Form::label('dependencias_id','Proceso:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <!-- Componente personalizado para seleccionar una dependencia -->
                <select-check
                    css-class="form-control"
                    name-field="dependencia"
                    reduce-label="nombre"
                    name-resource="/intranet/get-dependencies"
                    :value="dataForm"
                    :is-required="true"
                    ref-select-check="dependencias_ref"
                    :ids-to-empty="['tipo_solicitud','tipo_activo','activo_id','kilometraje_horometro']"
                    :enable-search="true"
                ></select-check>
                <small>Seleccione la dependencia</small>
                <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
                    <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
                </div>
            </div>

            <!-- Campo: Tipo de Solicitud -->
            {!! Form::label('tipo_solicitud', trans('Tipo Solicitud').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <!-- Este campo depende de la dependencia seleccionada -->
                <select-check
                    css-class="form-control"
                    name-field="tipo_solicitud"
                    reduce-key="value"
                    reduce-label="name"
                    :name-resource="'/maintenance/request-need-types-request/' + dataForm.dependencia"
                    :value="dataForm"
                    :is-required="true"
                    ref-select-check="types_request_ref"
                    :ids-to-empty="['tipo_activo','activo_id','kilometraje_horometro']"
                    :enable-search="true"
                ></select-check>
                <small>@lang('Seleccione el ') @{{ `@lang('Tipo Solicitud')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.tipo_solicitud">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_solicitud">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: Tipo de Activo y Activo (solo si tipo_solicitud es 'Activo' o 'Stock') -->
        <div class="form-group row m-b-15" v-if="dataForm.tipo_solicitud=='Activo' || dataForm.tipo_solicitud=='Stock'">
    
            <!-- Campo: Tipo de Activo -->
            {!! Form::label('tipo_activo', trans('Tipo Activo').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select-check
                    css-class="form-control"
                    name-field="tipo_activo"
                    reduce-label="name"
                    reduce-key="id"
                    name-resource="get-type-assets"
                    :value="dataForm"
                    :is-required="true"
                    :key="dataForm.dependencia"
                ></select-check>
                <small>@lang('Seleccione el ') @{{ `@lang('Tipo Activo')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.tipo_activo">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_activo">@{{ error }}</p>
                </div>
            </div>

            <!-- Campo: Activo -->
            {!! Form::label('activo_id', 'Activo:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select-check
                    css-class="form-control"
                    name-field="activo_id"
                    reduce-label="name"
                    reduce-key="id"
                    :name-resource="dataForm.dependencias_id 
                        ? 'get-all-actives-by-type/' + dataForm.tipo_activo + '/' + dataForm.dependencias_id 
                        : 'get-all-actives-by-type/' + dataForm.tipo_activo + '/' + dataForm.dependencia"
                    :value="dataForm"
                    :is-required="true"
                    :enable-search="true"
                    :key="dataForm.tipo_activo + dataForm.dependencia"
                ></select-check>
                <small>@lang('Enter the') @{{ `@lang('Activo')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.activo_id">
                    <p class="m-b-0" v-for="error in dataErrors.activo_id">@{{ error }}</p>
                </div>
            </div>
        </div>
        
        <!-- SECCIÓN 3: Kilometraje u Horómetro actual (solo para tipo activo 8 o 11 o si es tipo_solicitud 'Stock') -->
        <div class="form-group row m-b-15" v-if="dataForm.tipo_activo == 11 || dataForm.tipo_activo == 8 || dataForm.tipo_solicitud == 'Stock'">
            {!! Form::label('kilometraje_horometro', 'Kilometraje / Horómetro actual', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-10">
                <input-disabled
                    :name-resource="'active-mileage-or-current-hourmeter/' + dataForm.activo_id"
                    :value="dataForm"
                    name-field="kilometraje_horometro"
                    :key="dataForm.activo_id"
                ></input-disabled>
                <div class="invalid-feedback" v-if="dataErrors.tipo_activo">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_activo">@{{ error }}</p>
                </div>
            </div>
        </div>


        <!-- SECCIÓN 4: Selección del Rubro (dependiendo del tipo de solicitud) -->
        <div class="form-group row m-b-15" 
            v-if="dataForm.tipo_solicitud == 'Activo' || dataForm.tipo_solicitud == 'Inventario'">

            <!-- Label del rubro principal -->
            {!! Form::label('rubro_id', 'Rubro:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-10">
                <!-- Selector Inventario -->
                <select-check v-if="dataForm.tipo_solicitud == 'Inventario'"
                    css-class="form-control"
                    name-field="rubro_id"
                    :reduce-label="['name_heading', 'code_heading','cost_center_name']"
                    reduce-key="id_combinado"
                    :name-resource="!dataForm.rubro_id && dataForm.rubro_objeto_contrato_id 
                        ? 'get-rubros-by-contrato/' + dataForm.rubro_objeto_contrato_id 
                        : (dataForm.dependencias_id 
                            ? 'get-heading-unity-aseo/' + dataForm.dependencias_id 
                            : 'get-heading-unity-aseo/' + dataForm.dependencia)"
                    :value="dataForm"
                    :is-required="true"
                    :enable-search="true"
                    name-field-object="rubro_datos"
                    :key="keyRefresh"
                ></select-check>

                <!-- Selector Activo -->
                <select-check v-else
                    css-class="form-control"
                    name-field="rubro_id"
                    :reduce-label="['name_heading', 'code_heading','center_cost_name']"
                    reduce-key="id"
                    :name-resource="'get-heading-unity/' + dataForm.activo_id"
                    :value="dataForm"
                    :is-required="true"
                    :enable-search="true"
                    :ids-to-empty="['rubro_objeto_contrato_id','total_solicitud','necesidades']"
                    :key="dataForm.activo_id"
                ></select-check>

                <!-- Botón agregar/quitar segundo rubro -->
                <div class="form-group m-t-10" v-if="dataForm.tipo_solicitud === 'Activo'">
                    <button type="button" class="btn btn-sm btn-primary"
                        v-if="!dataForm.mostrarSegundoRubro && !dataForm.second_rubro_id"
                        @click="$set(dataForm,'mostrarSegundoRubro',true)">
                        <i class="fa fa-plus"></i> Agregar segundo rubro
                    </button>
                    <button type="button" class="btn btn-sm btn-link"
                        v-else
                        @click="
                            $set(dataForm,'mostrarSegundoRubro',false);
                            $set(dataForm,'second_rubro_id',null);
                            $set(dataForm,'second_rubro_datos',null);
                        ">
                        Quitar segundo rubro
                    </button>
                </div>
            </div>
        </div>

        <!-- SEGUNDO RUBRO - AL MISMO NIVEL -->
        <div class="form-group row m-b-15" v-if="dataForm.mostrarSegundoRubro || dataForm.second_rubro_id">
            {!! Form::label('second_rubro_id', 'Segundo Rubro:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-10">
                <select-check
                    css-class="form-control"
                    name-field="second_rubro_id"
                    :reduce-label="['name_heading', 'code_heading','center_cost_name']"
                    reduce-key="id"
                    :name-resource="'get-heading-unity/' + dataForm.activo_id"
                    :value="dataForm"
                    :is-required="true"
                    :enable-search="true"
                    :ids-to-empty="['rubro_objeto_contrato_id','total_solicitud','necesidades']"
                    :key="dataForm.activo_id"
                ></select-check>
            </div>
        </div>



        <!-- ====================== SECCIÓN 5: Objeto del Contrato ====================== -->
        <div class="form-group row m-b-15"
        v-if="dataForm.tipo_solicitud == 'Activo' || dataForm.tipo_solicitud == 'Inventario'">

            <!-- Etiqueta del campo -->
            <label for="rubro_objeto_contrato_id" class="col-form-label col-md-2 required">
            Objeto del Contrato:
            </label>

            <!-- Contenedor del select -->
            <div class="col-md-10">
                <!-- Si está en modo actualización -->
                <div v-if="isUpdate">
                    <select-check v-if="dataForm.tipo_solicitud === 'Activo'"
                        css-class="form-control"
                        name-field="rubro_objeto_contrato_id"
                        :reduce-label="['object','contract_number']"
                        {{-- :name-resource="'get-all-contracts-by-rubros/' + dataForm.rubro_id" --}}

                        :name-resource="dataForm.second_rubro_id 
                        ? 'get-all-contracts-by-rubros/' + dataForm.rubro_id + '/' + dataForm.second_rubro_id
                        : 'get-all-contracts-by-rubros/' + dataForm.rubro_id"


                        :value="dataForm"
                        :enable-search="true"
                        :ids-to-empty="['total_solicitud','necesidades']"
                        :key="'contrato-' + (dataForm.rubro_id || '') + '-' + (dataForm.second_rubro_id || '')"
                        name-field-object="rubro_aseo_datos">
                    </select-check>

                    <select-check v-else
                        css-class="form-control"
                        name-field="rubro_objeto_contrato_id"
                        :reduce-label="['object','contract_number']"
                        :name-resource="dataForm.rubro_id 
                            ? 'get-all-contracts-by-rubros-inventario/' + dataForm.rubro_id 
                            : 'get-contracts-by-dependence/' + dataForm.dependencias_id"
                        :value="dataForm"
                        :enable-search="true"
                        :ids-to-empty="['total_solicitud','necesidades']"
                        :key="dataForm.rubro_id"
                        name-field-object="contrato_datos">
                    </select-check>
                </div>

                <!-- Si está creando nueva solicitud -->
                <div v-else>
                    <!-- Aseo (dep 19 o 23) y tipo Activo -->
                    <select-check v-if="(dataForm.dependencia === 23 || dataForm.dependencias_id === 19 || dataForm.dependencia === 19 || dataForm.dependencias_id === 23) && dataForm.tipo_solicitud=='Activo'"
                        css-class="form-control"
                        name-field="rubro_objeto_contrato_id"
                        :reduce-label="['object','contract_number']"
                        {{-- :name-resource="'get-all-contracts-by-rubros/' + dataForm.rubro_id" --}}

                        :name-resource="dataForm.second_rubro_id 
                        ? 'get-all-contracts-by-rubros/' + dataForm.rubro_id + '/' + dataForm.second_rubro_id
                        : 'get-all-contracts-by-rubros/' + dataForm.rubro_id"
                        :value="dataForm"
                        :enable-search="true"
                        :ids-to-empty="['total_solicitud','necesidades']"
                        :key="'contrato-' + (dataForm.rubro_id || '') + '-' + (dataForm.second_rubro_id || '')"
                        name-field-object="rubro_aseo_datos">
                    </select-check>

                    <!-- Aseo y tipo Inventario -->
                    <select-check v-else-if="(dataForm.dependencia === 23 || dataForm.dependencias_id === 19 || dataForm.dependencia === 19 || dataForm.dependencias_id === 23) && dataForm.tipo_solicitud=='Inventario'"
                        css-class="form-control"
                        name-field="rubro_objeto_contrato_id"
                        :reduce-label="['object','contract_number']"
                        :name-resource="'get-aseo-contracts-by-rubros/' + dataForm.rubro_id"
                        :value="dataForm"
                        :enable-search="true"
                        :ids-to-empty="['total_solicitud','necesidades']"
                        :key="dataForm.rubro_id"
                        name-field-object="rubro_aseo_datos">
                    </select-check>

                    <!-- Otras dependencias -->
                    <select-check v-else-if="(dataForm.dependencias_id != 19 || dataForm.dependencias_id != 23) && (dataForm.tipo_solicitud == 'Activo' || dataForm.tipo_solicitud == 'Inventario')"
                        css-class="form-control"
                        name-field="rubro_objeto_contrato_id"
                        :reduce-label="['object','contract_number']"
                        {{-- :name-resource="dataForm.tipo_solicitud == 'Activo'
                            ? 'get-all-contracts-by-rubros/' + dataForm.rubro_id
                            : 'get-all-contracts-by-rubros-inventario/' + dataForm.rubro_id" --}}

                        :name-resource="dataForm.tipo_solicitud == 'Activo'
                        ? (dataForm.second_rubro_id 
                            ? 'get-all-contracts-by-rubros/' + dataForm.rubro_id + '/' + dataForm.second_rubro_id
                            : 'get-all-contracts-by-rubros/' + dataForm.rubro_id)
                        : 'get-all-contracts-by-rubros-inventario/' + dataForm.rubro_id"
                        :value="dataForm"
                        :enable-search="true"
                        :ids-to-empty="['total_solicitud','necesidades']"
                        :key="'contrato-' + (dataForm.rubro_id || '') + '-' + (dataForm.second_rubro_id || '')"
                        name-field-object="contrato_datos">
                    </select-check>
                </div>

                <!-- Mensaje e errores -->
                <small>Seleccione el objeto del contrato</small>
                <div class="invalid-feedback" v-if="dataErrors.rubro_objeto_contrato_id">
                    <p class="m-b-0" v-for="error in dataErrors.rubro_objeto_contrato_id">@{{ error }}</p>
                </div>
            </div>
        </div>


        <!-- ====================== SECCIÓN 6: Valor Disponible ====================== -->
        <div class="form-group row m-b-15">
            <label class="col-form-label col-md-2 required">Valor Disponible:</label>

            <!-- Si es de aseo y hay rubro -->
            <div class="col-md-3"
                v-if="(dataForm.dependencia === 19 || dataForm.dependencia === 23 || dataForm.dependencias_id === 19 || dataForm.dependencias_id === 23) && dataForm.rubro_id">

                <!-- Tipo Activo -->
                <input-check v-if="dataForm.tipo_solicitud == 'Activo'"
                    prefix="$"
                    :disabled="true"
                    name-field="valor_disponible"
                    css-class="form-control"
                    type-input="llenadoPorObjeto"
                    :key="dataForm.rubro_id + dataForm.rubro_objeto_contrato_id"
                    :value="dataForm"
                    :precision=0
                    :value-recibido="['rubro_aseo_datos','value_avaible']">
                </input-check>

                <!-- Otro tipo -->
                <input-check v-else
                    prefix="$"
                    :disabled="true"
                    name-field="valor_disponible"
                    css-class="form-control"
                    type-input="llenadoPorObjeto"
                    :key="dataForm.rubro_objeto_contrato_id"
                    :value="dataForm"
                    :precision=0
                    :value-recibido="Object.keys(dataForm.rubro_aseo_datos ?? {}).length > 0 
                        ? ['rubro_aseo_datos','value_avaible'] 
                        : ['contrato_datos','value_avaible']">
                </input-check>

                <small>@{{ `@lang('Valor Disponible')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.valor_disponible">
                    <p class="m-b-0" v-for="error in dataErrors.valor_disponible">@{{ error }}</p>
                </div>
            </div>

            <!-- Si no es de aseo o no hay rubro -->
            <div class="col-md-3" v-else>
                <template v-if="!dataForm.rubro_id">
                    <!-- Mensaje informativo -->
                    <div class="form-control-plaintext text-secondary fst-italic small">
                        <i class="fas fa-info-circle me-1"></i>
                        @lang('Seleccione un rubro para ver el valor disponible')
                    </div>
                </template>

                <template v-else>
                    <!-- En edición -->
                    <input-check v-if="isUpdate"
                        prefix="$"
                        :disabled="true"
                        name-field="valor_disponible"
                        css-class="form-control"
                        type-input="llenadoPorObjeto"
                        :key="dataForm.rubro_objeto_contrato_id"
                        :value="dataForm"
                        :value-recibido="dataForm.rubro_aseo_datos?.hasOwnProperty('value_avaible') 
                            ? ['rubro_aseo_datos', 'value_avaible'] 
                            : ['contrato_datos', 'value_avaible']">
                    </input-check>

                    <!-- En creación -->
                    <input-check v-else
                        prefix="$"
                        :disabled="true"
                        name-field="valor_disponible"
                        css-class="form-control"
                        type-input="llenadoPorObjeto"
                        :key="dataForm.rubro_objeto_contrato_id"
                        :value="dataForm"
                        :value-recibido="['contrato_datos','value_avaible']">
                    </input-check>

                    <small>@{{ `@lang('Valor Disponible')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.valor_disponible">
                        <p class="m-b-0" v-for="error in dataErrors.valor_disponible">@{{ error }}</p>
                    </div>
                </template>
            </div>
        </div>

        
    </div> <!-- panel-body -->
</div> <!-- panel -->



<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.tipo_solicitud == 'Stock'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Identificación de necesidades</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">

                        <dynamic-list  label-button-add="Agregar necesidad a la lista" :data-list.sync="dataForm.necesidades" :is-stock="true"
                            :data-list-options="[
                                { label: 'Descripción', name: 'descripcion', isShow: true, nameObjectKey: ['descripcion_datos', 'articulo'] , refList: 'descripcionRef'},
                                { label: 'Unidad de medida', name: 'unidad_medida', isShow: true },
                                { label: 'Valor Unitario', name: 'valor_unitario', isShow: true },
                                { label: 'IVA', name: 'IVA', isShow: true },
                                { label: 'Cantidad solicitada', name: 'cantidad_solicitada', isShow: true },
                                { label: 'Valor total', name: 'valor_total', isShow: true },
                                { label: 'Tipo de mantenimiento', name: 'tipo_mantenimiento', isShow: true }

                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">
                                <div class="form-group row m-b-15">                                
                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-4">

                                        <select-check  
                                            css-class="form-control" 
                                            name-field="descripcion"
                                            :reduce-label="['articulo','codigo']" 
                                            :name-resource="'get-all-descriptions-stock/' + (dataForm.dependencia == 'Subgerencia de Aseo' || dataForm.dependencia == 'Gestión Aseo' || dataForm.dependencia == 19 || dataForm.dependencia == 23 ? 'Aseo' : 'CAM')"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            :key="scope.dataForm.necesidad"
                                            reduce-key="id"
                                            :ids-to-empty="['cantidad_solicitada','valor_total']"
                                            :ids-to-empty-null="true"
                                            name-field-object="descripcion_datos"
                                            ref-select-check="descripcionRef"
                                        ></select-check>

                                        <small>Seleccione la actividad o el repuesto</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>

                                    <div class="col-md-4" v-if="scope.dataForm.necesidad=='Actividades'">

                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unidad_medida"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unit_measurement']"
                                        ></input-check>
                                        <small>Unidad de medida</small>
                                    </div>
                                    <div v-else class="col-md-4">
                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unidad_medida"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unidad_medida']"
                                        ></input-check>
                                        <small>Unidad de medida</small>
                                    </div>
                                    
                                </div>

                                <div class="form-group row m-b-15">
                                    
                                    <label class="col-form-label col-md-2 required">Valor Unitario:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="valor_unitario"
                                        css-class="form-control" 
                                        :is-readonly="false"
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','costo_unitario']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.valor_unitario"  required> --}}
                                        <small>Valor unitario</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :is-readonly="false"
                                        name-field="IVA"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','iva_bd']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.IVA"  required> --}}
                                        <small>IVA</small>
                                    </div>
                                   

                                </div>          

                                <div class="form-group row m-b-15">
                                    
                                    <label class="col-form-label col-md-2 required">Cantidad física:</label>

                                    <div class="col-md-4">
                                        <input-disabled :is-disabled="true" :name-resource="'physical-quantity-stock/' + scope.dataForm.descripcion" :value="scope.dataForm" name-field="physical_quantity" :key="scope.dataForm.descripcion"></input-disabled>
                                        <small>Cantidad física</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Cantidad disponible:</label>

                                    <div class="col-md-4">
                                        <input-disabled :is-disabled="true" :name-resource="'available-quantity-stock/' + scope.dataForm.descripcion" :value="scope.dataForm" name-field="current_mileages" :key="scope.dataForm.descripcion"></input-disabled>
                                        <small>Cantidad disponible</small>
                                    </div>
                                </div>


                                <div class="form-group row m-b-15">
                        
                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                                
                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.cantidad_solicitada"
                                        required="true"
                                        :currency="{'prefix': ' '}"
                                        locale="es"
                                        class="form-control"
                                        :precision="6"
                                        :key="keyRefresh"
                                        
                                        @keyup="$set(scope.dataForm,'valor_total', ((parseFloat(scope.dataForm.IVA) || 0) + (parseFloat(scope.dataForm.valor_unitario) || 0)) * (parseFloat(scope.dataForm.cantidad_solicitada) || 0))"

                                        :disabled="false"
                                        >
                                        </currency-input>
                                     
                                        <small>
                                            Ingrese la cantidad deseada.
                                        </small>
                                    
                                    </div>

                                    <label class="col-form-label col-md-2 required">Valor total:</label>
                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.valor_total"
                                        required="true"
                                        :currency="{'prefix': '$ '}"
                                        locale="es"
                                        class="form-control"
                                        
                                        :key="keyRefresh"
                                        :disabled="false"
                                        >
                                        </currency-input>
                                        <div class="custom-tooltip" title="">
                                            <small class="custom-tooltip-trigger">
                                                <i class="fas fa-question-circle"></i> Ayuda
                                            </small>
                                            <div class="custom-tooltip-content">
                                                <small>
                                                    El valor total se calcula a partir del valor unitario más el IVA, multiplicado por la cantidad solicitada.
                                                    Valor Total=(Valor Unitario+IVA)×Cantidad Solicitada
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required">Tipo de mantenimiento</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="scope.dataForm.tipo_mantenimiento" required>
                                            <option value="Preventivo">Preventivo</option>
                                            <option value="Correctivo">Correctivo</option>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </dynamic-list>
                    </div>
                    <div class="">
                        <p style="font-size:16px;"><b>Valor total de la solicitud</b></p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" v-model="dataForm.total_solicitud" disabled>
                          </div>
                    </div>


                </div>

            </div>
        </div>
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.tipo_solicitud == 'Activo'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Identificación de necesidades</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">

                        <dynamic-list  label-button-add="Agregar necesidad a la lista" :data-list.sync="dataForm.necesidades" :is-activity="true"
                            :data-list-options="[
                                { label: 'Necesidad', name: 'necesidad', isShow: true },
                                { label: 'Descripción', name: 'descripcion', isShow: true, nameObjectKey: ['descripcion_datos', 'description'], refList: 'descripcionRef'},
                                { label: 'Cantidad solicitada', name: 'cantidad_solicitada', isShow: true },
                                { label: 'Unidad de medida', name: 'unidad_medida', isShow: true },

                                { label: 'Valor Unitario', name: 'valor_unitario', isShow: true },
                                { label: 'IVA', name: 'IVA', isShow: true },
                                { label: 'Valor total', name: 'valor_total', isShow: true },
                                { label: 'Tipo de mantenimiento', name: 'tipo_mantenimiento', isShow: true }

                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">
                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Necesidad:</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="necesidad" v-model="scope.dataForm.necesidad">
                                            <option value="Actividades">Actividades</option>
                                            <option value="Repuestos">Repuestos</option>
                                        </select>
                                        <small>Seleccione el tipo de necesidad.</small>
                                    </div>
                                
                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-4">

                                        <select-check  
                                            css-class="form-control" 
                                            name-field="descripcion"
                                            :reduce-label="['item','description','unit_measurement']" 
                                            :name-resource="'get-all-descriptions-by-need/'+scope.dataForm.necesidad+'/'+dataForm.rubro_objeto_contrato_id"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            :key="scope.dataForm.necesidad + dataForm.rubro_objeto_contrato_id"
                                            name-field-object="descripcion_datos"
                                            reduce-key="id"
                                            :ids-to-empty="['cantidad_solicitada','valor_total']"
                                            :ids-to-empty-null="true"
                                            ref-select-check="descripcionRef"
                                        ></select-check>

                                        <small>Seleccione la actividad o el repuesto</small>
                                    </div>
                                    
                                </div>

                                <div class="form-group row m-b-15">
                                    
                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>

                                    <div class="col-md-4" v-if="scope.dataForm.necesidad=='Actividades'">

                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unidad_medida"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unit_measurement']"
                                        ></input-check>
                                        <small>Unidad de medida</small>
                                    </div>
                                    <div v-else class="col-md-4">
                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unidad_medida"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unit_measure']"
                                        ></input-check>
                                        <small>Unidad de medida</small>
                                    </div>


                                    <label class="col-form-label col-md-2 required">Valor Unitario:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="valor_unitario"
                                        :is-readonly="false"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','unit_value']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.valor_unitario"  required> --}}
                                        <small>Valor unitario</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :is-readonly="false"
                                        name-field="IVA"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','iva']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.IVA"  required> --}}
                                        <small>IVA</small>
                                    </div>


                                    <label class="col-form-label col-md-2 required">Valor Unitario Total:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :is-readonly="false"
                                        name-field="total_value"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','total_value']"
                                        ></input-check>
                                        <small>Valor unitario total</small>
                                    </div>

                                </div>

                        

                                <div class="form-group row m-b-15">
                        
                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                                
                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.cantidad_solicitada"
                                        required="true"
                                        :currency="{'prefix': ' '}"
                                        locale="es"
                                        class="form-control"
                                        :precision="6"
                                        :key="keyRefresh"
                                        @keyup="$set(scope.dataForm, 'valor_total',
                                            (parseFloat(scope.dataForm.total_value) ? 
                                                (parseFloat(scope.dataForm.total_value) || 0) * (parseFloat(scope.dataForm.cantidad_solicitada) || 0) :
                                                ((parseFloat(scope.dataForm.IVA) || 0) + (parseFloat(scope.dataForm.valor_unitario) || 0)) * (parseFloat(scope.dataForm.cantidad_solicitada) || 0)
                                            )
                                            )"

                                        :disabled="false"
                                        >
                                        </currency-input>
                                        <small>
                                            Ingrese la cantidad deseada.
                                        </small>
                                    
                                    </div>

                                    <label class="col-form-label col-md-2 required">Valor total:</label>
                                    <div class="col-md-4">
                                        <currency-input
                                                v-model="scope.dataForm.valor_total"
                                                required="true"
                                                :currency="{'prefix': '$ '}"
                                                locale="es"
                                                class="form-control"
                                                
                                                :key="keyRefresh"
                                                >
                                        </currency-input>
                                        {{-- <input required type="number" class="form-control" v-model="scope.dataForm.valor_total" min="1"> --}}
                                        <div class="custom-tooltip" title="">
                                            <small class="custom-tooltip-trigger">
                                                <i class="fas fa-question-circle"></i> Ayuda
                                            </small>
                                            <div class="custom-tooltip-content">
                                                <small>
                                                    El valor total se calcula a partir del valor unitario más el IVA, multiplicado por la cantidad solicitada.
                                                    Valor Total=(Valor Unitario+IVA)×Cantidad Solicitada
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required">Tipo de mantenimiento</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="scope.dataForm.tipo_mantenimiento" required>
                                            <option value="Preventivo">Preventivo</option>
                                            <option value="Correctivo">Correctivo</option>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </dynamic-list>
                    </div>


                    <div class="">
                        <p style="font-size:16px;"><b>Valor total de la solicitud</b></p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" v-model="dataForm.total_solicitud" disabled>
                          </div>
                        
                    </div>


                </div>

            </div>
        </div>
</div>

{{-- Formulario para cuando el tipo de solicitud proviene de Compra/Almacen --}}
<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.tipo_solicitud == 'Inventario'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Identificación de necesidades</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">

                        <dynamic-list  label-button-add="Agregar necesidad a la lista" :data-list.sync="dataForm.necesidades" :is-activity="true"
                            :data-list-options="[
                                { label: 'Necesidad', name: 'necesidad', isShow: true },
                                { label: 'Descripción', name: 'descripcion', isShow: true, nameObjectKey: ['descripcion_datos', 'description'], refList: 'descripcionRef'},
                                { label: 'Cantidad solicitada', name: 'cantidad_solicitada', isShow: true },
                                { label: 'Unidad de medida', name: 'unidad_medida', isShow: true },

                                { label: 'Valor Unitario', name: 'valor_unitario', isShow: true },
                                { label: 'IVA', name: 'IVA', isShow: true },
                                { label: 'Valor total', name: 'valor_total', isShow: true }

                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">
                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Necesidad:</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="necesidad" v-model="scope.dataForm.necesidad='Repuestos'">
                                            <option value="Repuestos">Repuestos</option>
                                        </select>
                                        <small>Seleccione el tipo de necesidad.</small>
                                    </div>
                                
                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-4">

                                        <select-check  
                                            css-class="form-control" 
                                            name-field="descripcion"
                                            :reduce-label="['item','description','unit_measurement']" 
                                            :name-resource="'get-all-descriptions-by-need/'+scope.dataForm.necesidad+'/'+dataForm.rubro_objeto_contrato_id"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            :key="scope.dataForm.necesidad + dataForm.rubro_objeto_contrato_id"
                                            name-field-object="descripcion_datos"
                                            reduce-key="id"
                                            :ids-to-empty="['cantidad_solicitada','valor_total']"
                                            :ids-to-empty-null="true"
                                            ref-select-check="descripcionRef"
                                        ></select-check>

                                        <small>Seleccione la actividad o el repuesto</small>
                                    </div>
                                    
                                </div>

                                <div class="form-group row m-b-15">
                                    
                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>

                                    <div class="col-md-4" v-if="scope.dataForm.necesidad=='Actividades'">

                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unidad_medida"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unit_measurement']"
                                        ></input-check>
                                        <small>Unidad de medida</small>
                                    </div>
                                    <div v-else class="col-md-4">
                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unidad_medida"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unit_measure']"
                                        ></input-check>
                                        <small>Unidad de medida</small>
                                    </div>


                                    <label class="col-form-label col-md-2 required">Valor Unitario:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="valor_unitario"
                                        :is-readonly="false"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','unit_value']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.valor_unitario"  required> --}}
                                        <small>Valor unitario</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :is-readonly="false"
                                        name-field="IVA"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','iva']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.IVA"  required> --}}
                                        <small>IVA</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Valor Unitario Total:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        name-field="total_value"
                                        :is-readonly="false"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        
                                        :value-recibido="['descripcion_datos','total_value']"
                                        ></input-check>
                                        <small>Valor unitario total</small>
                                    </div>
                                </div>


                                <div class="form-group row m-b-15">
                        
                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                                
                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.cantidad_solicitada"
                                        required="true"
                                        :currency="{'prefix': ' '}"
                                        locale="es"
                                        class="form-control"
                                        :precision="6"
                                        :key="keyRefresh"
                                        @keyup="$set(scope.dataForm, 'valor_total',
                                            (parseFloat(scope.dataForm.total_value) ? 
                                                (parseFloat(scope.dataForm.total_value) || 0) * (parseFloat(scope.dataForm.cantidad_solicitada) || 0) :
                                                ((parseFloat(scope.dataForm.IVA) || 0) + (parseFloat(scope.dataForm.valor_unitario) || 0)) * (parseFloat(scope.dataForm.cantidad_solicitada) || 0)
                                            )
                                            )"

                                        :disabled="false"
                                        >
                                        </currency-input>
                                        <small>
                                            Ingrese la cantidad deseada.
                                        </small>
                                    
                                    </div>

                                    <label class="col-form-label col-md-2 required">Valor total:</label>
                                    <div class="col-md-4">
                                        <currency-input
                                                v-model="scope.dataForm.valor_total"
                                                required="true"
                                                :currency="{'prefix': '$ '}"
                                                locale="es"
                                                class="form-control"
                                                
                                                :key="scope.dataForm.cantidad_solicitada"
                                                >
                                            </currency-input>
                                        {{-- <input required type="number" class="form-control" v-model="scope.dataForm.valor_total" min="1"> --}}
                                        <div class="custom-tooltip" title="">
                                            <small class="custom-tooltip-trigger">
                                                <i class="fas fa-question-circle"></i> Ayuda
                                            </small>
                                            <div class="custom-tooltip-content">
                                                <small>
                                                    El valor total se calcula a partir del valor unitario más el IVA, multiplicado por la cantidad solicitada.
                                                    Valor Total=(Valor Unitario+IVA)×Cantidad Solicitada
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </dynamic-list>
                    </div>


                    <div class="">
                        <p style="font-size:16px;"><b>Valor total de la solicitud</b></p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" v-model="dataForm.total_solicitud" disabled>
                          </div>
                        
                    </div>


                </div>

            </div>
        </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Documentos de la solicitud</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('documents', 'Documentos de la solicitud:', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <input-file :value="dataForm" name-field="url_documents" :max-files="10"
                                :max-filesize="5" file-path="public/maintenance/identification_needs/documents_{{ date('Y') }}"
                                message="Arrastre o seleccione los archivos" help-text="Utilice este campo para cargar un documento de la solicitud. El tamaño máximo permitido es de 5 MB."
                                :is-update="isUpdate"  ruta-delete-update="correspondence/internals-delete-file" :id-file-delete="dataForm.id">
                            </input-file>
                        </div>
                    </div>

                </div>

            </div>
        </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Observaciones</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('observacion', trans('Observacion').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('observacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observacion }", 'v-model' => 'dataForm.observacion']) !!}
                            <small>@lang('Enter the') @{{ `@lang('Observacion')` | lowercase }}</small>
                            <div class="invalid-feedback" v-if="dataErrors.observacion">
                                <p class="m-b-0" v-for="error in dataErrors.observacion">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('supervisor_observation', trans('Observaciones del supervisor').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('supervisor_observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.supervisor_observation }", 'v-model' => 'dataForm.supervisor_observation']) !!}
                            <small>Ingrese observaciones para el proveedor externo.</small>
                            <div class="invalid-feedback" v-if="dataErrors.supervisor_observation">
                                <p class="m-b-0" v-for="error in dataErrors.supervisor_observation">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>                

            </div>
        </div>
</div>

