<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">

                    <!-- Dependencias Id Field -->
                    <div class="form-group row m-b-15">

                        {!! Form::label('dependencias_id','Proceso:', ['class' => 'col-form-label col-md-2 required']) !!}
                        <div class="col-md-4">
                            <select-check v-if="isUpdate"
                                css-class="form-control"
                                name-field="dependencias_id"
                                reduce-label="nombre"
                                name-resource="/intranet/get-dependencies"
                                :value="dataForm"
                                :is-required="true"
                                ref-select-check="dependencias_ref"
                                :ids-to-empty="['tipo_solicitud','tipo_activo','activo_id','kilometraje_horometro']"
                                :enable-search="true"
                                :element-disabled="true"
                            ></select-check>
                            <div v-else>
                                {!!Form::text('dependencia', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.dependencia }", 'v-model' => 'dataForm.dependencia', 'required' => true, 'disabled' => true]) !!}
                            </div>
                            
                            <small>Seleccione la dependencia</small>
                            <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
                                <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
                            </div>
                        </div>

                        {!! Form::label('tipo_solicitud', trans('Tipo Solicitud').':', ['class' => 'col-form-label col-md-2 required']) !!}
                        <div class="col-md-4">
                            <select-check css-class="form-control" 
                                name-field="tipo_solicitud" 
                                reduce-key="value" 
                                reduce-label="name"
                                :name-resource="'/maintenance/request-need-types-request/' + (typeof dataForm.dependencia === 'object' ? dataForm.dependencia.nombre : dataForm.dependencia)" 
                                :value="dataForm" 
                                :is-required="true" 
                                ref-select-check="types_request_ref" 
                                :ids-to-empty="['tipo_activo','activo_id','kilometraje_horometro']"
                                :enable-search="true" 
                            >
                            </select-check>
                            
                            <small>@lang('Seleccione el ') @{{ `@lang('Tipo Solicitud')` | lowercase }}</small>
                            <div class="invalid-feedback" v-if="dataErrors.tipo_solicitud">
                                <p class="m-b-0" v-for="error in dataErrors.tipo_solicitud">@{{ error }}</p>
                            </div>
                        </div>
                </div>

            <div class="form-group row m-b-15" v-if="dataForm.tipo_solicitud=='Activo' || dataForm.tipo_solicitud=='Stock'">
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
                        >
                    </select-check>
                    <small>@lang('Seleccione el ') @{{ `@lang('Tipo Activo')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.tipo_activo">
                        <p class="m-b-0" v-for="error in dataErrors.tipo_activo">@{{ error }}</p>
                    </div>
                </div>

                <!-- Activo Id Field -->
                {!! Form::label('activo_id', 'Activo:', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-4">
            
                    {{-- :reduce-label=['name', 'code'] --}}
                    <select-check
                        css-class="form-control"
                        name-field="activo_id"
                        reduce-label="name" 
                        reduce-key="id"
                        {{-- :name-resource="'get-all-actives-by-type/'+dataForm.tipo_activo+ '/'+ dataForm.dependencia" --}}
                        :name-resource="dataForm.dependencias_id ? 'get-all-actives-by-type/' + dataForm.tipo_activo + '/' + dataForm.dependencias_id : 'get-all-actives-by-type/' + dataForm.tipo_activo + '/' + dataForm.dependencia"
                        :value="dataForm"
                        :is-required="true"
                        :enable-search="true" 
                        :key="dataForm.tipo_activo + dataForm.dependencia"
                        >
                    </select-check>
                    <small>@lang('Enter the') @{{ `@lang('Activo')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.activo_id">
                        <p class="m-b-0" v-for="error in dataErrors.activo_id">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Rubro  Id Field -->
            <div class="form-group row m-b-15">
                <label v-if="dataForm.tipo_activo == 11 || dataForm.tipo_activo == 8 || dataForm.tipo_solicitud == 'Stock'" for="" class="col-form-label col-md-2 required">Kilometraje/ <br> Horómetro actual</label>
                <div v-if="dataForm.tipo_activo == 11 || dataForm.tipo_activo == 8 || dataForm.tipo_solicitud == 'Stock'" class="col-md-10">
                    <input-disabled :name-resource="'active-mileage-or-current-hourmeter/' + dataForm.activo_id" :value="dataForm" name-field="kilometraje_horometro" :key="dataForm.activo_id"></input-disabled>
                    <div class="invalid-feedback" v-if="dataErrors.tipo_activo">
                        <p class="m-b-0" v-for="error in dataErrors.tipo_activo">@{{ error }}</p>
                    </div>
                </div>
                    
                @if ($is_leader)
                    <label for="rubro_id" class="col-form-label col-md-2 required" v-if="(dataForm.tipo_solicitud == 'Activo')">Rubro</label>

                    <div class="col-md-10" v-if="(dataForm.tipo_solicitud == 'Activo')">
                        {{-- Select cuando es para crear o editar desde un lider de proceso de gestion de aseo --}}
                        <select-check
                        css-class="form-control" 
                        name-field="rubro_id" 
                        :reduce-label="['name_heading', 'code_heading','center_cost_name']" 
                        reduce-key="id"
                        :name-resource="dataForm.dependencias_id ? 'get-heading-unity/' + dataForm.activo_id : 'get-heading-unity/' + dataForm.activo_id"
                        :value="dataForm" 
                        :is-required="true"
                        :enable-search="true" 
                        :ids-to-empty="['rubro_objeto_contrato_id','total_solicitud','necesidades']"
                        :key="dataForm.activo_id"
                        ></select-check>
                        <small>Seleccione un rubro</small>
                        <div class="invalid-feedback" v-if="dataErrors.rubro_id">
                            <p class="m-b-0" v-for="error in dataErrors.rubro_id">@{{ error }}</p>
                        </div>

                    </div>
                   
                @endif


                <label for="rubro_objeto_contrato_id" class="col-form-label col-md-2 required" v-if="dataForm.tipo_solicitud=='Activo' || dataForm.tipo_solicitud=='Inventario'">Objeto del Contrato:</label>
                <div class="col-md-10" v-if="dataForm.tipo_solicitud=='Activo' || dataForm.tipo_solicitud=='Inventario'">
                    {{-- Select cuando es para crear o editar desde un lider de proceso de gestion de aseo --}}

                    <select-check   v-if = " (dataForm.dependencia === 'Gestión Aseo' || dataForm.dependencias_id === 19 || dataForm.dependencia === 'Subgerencia de Aseo' || dataForm.dependencias_id === 23)  && dataForm.tipo_solicitud=='Activo' " 
                            css-class="form-control" name-field="rubro_objeto_contrato_id"
                            :reduce-label="['object','contract_number']" 
                            :name-resource="'get-all-contracts-by-rubros/'+dataForm.rubro_id"
                            :value="dataForm"
                            :ids-to-empty="['total_solicitud','necesidades']"
                            :enable-search="true" 
                            :key="dataForm.rubro_id"
                            name-field-object="contrato_datos"
                        ></select-check>

                        <select-check   v-else-if = " (dataForm.dependencia === 'Gestión Aseo' || dataForm.dependencias_id === 19 || dataForm.dependencia === 'Subgerencia de Aseo' || dataForm.dependencias_id === 23)  && dataForm.tipo_solicitud=='Inventario' " 
                            css-class="form-control" 
                            name-field="rubro_objeto_contrato_id"
                            :reduce-label="['object','contract_number']" 
                            :name-resource="'get-aseo-contracts'"
                            :value="dataForm" 
                            :enable-search="true" 
                            :ids-to-empty="['total_solicitud','necesidades']"
                            :key="dataForm.tipo_solicitud"
                            {{-- name-field-object="rubro_aseo_datos" --}}
                        ></select-check>
                    
                        <select-check   v-else
                            css-class="form-control" name-field="rubro_objeto_contrato_id"
                            :reduce-label="['object','contract_number']" 
                            :name-resource="'get-all-contracts-by-rubros/'+dataForm.rubro_id"
                            :value="dataForm"
                            :ids-to-empty="['total_solicitud','necesidades']"
                            :enable-search="true" 
                            :key="dataForm.rubro_id"
                            name-field-object="contrato_datos"
                        ></select-check>

                        <small>Seleccione el objeto del contrato</small>
                        <div class="invalid-feedback" v-if="dataErrors.rubro_objeto_contrato_id">
                            <p class="m-b-0" v-for="error in dataErrors.rubro_objeto_contrato_id">@{{ error }}</p>
                        </div>

                </div>
                {{-- Valor disponible para cuando la solicitud provenga de un lider de aseo --}}
                
                    <label v-if="dataForm.tipo_solicitud && (dataForm.tipo_solicitud=='Activo')" for="valor_disponible" class="col-form-label col-md-2 required" >Valor Disponible: </label>
                    <div class="col-md-4" v-if="dataForm.tipo_solicitud && (dataForm.tipo_solicitud=='Activo')">
                        <input-check 
                        prefix="$"
                        :disabled="true"
                        name-field="valor_disponible"
                        css-class="form-control" 
                        type-input="llenadoPorObjeto"
                        :key="dataForm.rubro_objeto_contrato_id"
                        :value="dataForm"
                        :value-recibido="['contrato_datos','value_avaible']"
                        ></input-check>
                        <small>@{{ `@lang('Valor Disponible')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.valor_disponible">
                            <p class="m-b-0" v-for="error in dataErrors.valor_disponible">@{{ error }}</p>
                        </div>
                    </div>
            </div>

        </div>

</div>
  

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
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :precision="2"
                                        :value-recibido="['descripcion_datos','costo_unitario']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.valor_unitario"  required> --}}
                                        <small>Valor unitario</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="IVA"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :precision="2"
                                        :value-recibido="['descripcion_datos','iva_bd']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.IVA"  required> --}}
                                        <small>IVA</small>
                                    </div>

                             
                                </div>

                                <div class="form-group row m-b-15">
                                    
                                    <label class="col-form-label col-md-2 required">Cantidad física:</label>

                                    <div class="col-md-4">
                                        {{-- <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="physical_quantity"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','cantidad']"
                                        ></input-check> --}}
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
                                        :precision="2"
                                        :key="keyRefresh"
                                        :disabled="true"
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
                                            :reduce-label="['item','description']" 
                                            :name-resource="'get-all-descriptions-by-need/'+scope.dataForm.necesidad+'/'+dataForm.rubro_objeto_contrato_id"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            :key="scope.dataForm.necesidad"
                                            name-field-object="descripcion_datos"
                                            :ids-to-empty="['cantidad_solicitada','valor_total']"
                                            :ids-to-empty-null="true"
                                            reduce-key="id"
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
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :precision="2"
                                        :value-recibido="['descripcion_datos','unit_value']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.valor_unitario"  required> --}}
                                        <small>Valor unitario</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="IVA"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :precision="2"
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
                                                :precision="2"
                                                :key="keyRefresh"
                                                :disabled="true"
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
                                            :reduce-label="['item','description']" 
                                            :name-resource="'get-all-descriptions-by-need/'+scope.dataForm.necesidad+'/'+dataForm.rubro_objeto_contrato_id"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            :key="scope.dataForm.necesidad + dataForm.rubro_objeto_contrato_id"
                                            name-field-object="descripcion_datos"
                                            :ids-to-empty="['cantidad_solicitada','valor_total']"
                                            :ids-to-empty-null="true"
                                            reduce-key="id"
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
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :precision="2"
                                        :value-recibido="['descripcion_datos','unit_value']"
                                        ></input-check>
                                        {{-- <input type="text" class="form-control" v-model="scope.dataForm.valor_unitario"  required> --}}
                                        <small>Valor unitario</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="IVA"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion"
                                        :value="scope.dataForm"
                                        :precision="2"
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
                                                :precision="2"
                                                :key="scope.dataForm.cantidad_solicitada"
                                                :disabled="true"
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

            </div>
        </div>
</div>

