<div class="" v-if="isUpdate">
    <div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Identificación de necesidades</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                        {!! Form::label('activity_weigth', trans('Valor disponible') . ':', [
                            'class' => 'col-form-label col-md-2 required',
                        ]) !!}
                        <div class="col-md-4">
                            <div class="d-flex">
                                <input-disabled name-prop="value_avaible"
                                    :name-resource="'item-value-available/' + dataForm.request_need.mant_administration_cost_items_id"
                                    :value="dataForm" name-field="valor_disponible"
                                    :key="keyRefresh"></input-disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">

                        <dynamic-list-needs label-button-add="Agregar necesidad a la lista"
                            :data-list.sync="dataForm.needs" :is-activity="true"
                            :data-list-options="[
                                { label: 'Necesidad', name: 'need', isShow: true },
                                { label: 'Descripción', name: 'description', isShow: true },
                                { label: 'Cantidad solicitada', name: 'amount_requested', isShow: true },
                                { label: 'Unidad de medida', name: 'unit_measurement', isShow: true },
                            
                                { label: 'Valor Unitario', name: 'unit_value', isShow: true },
                                { label: 'IVA', name: 'iva', isShow: true },
                                { label: 'Valor total', name: 'valor_total', isShow: true },
                                { label: 'Tipo de mantenimiento', name: 'maintenance_type', isShow: true },
                                {
                                    label: 'Aprobar o devolver al proveedor',
                                    name: 'is_approved',
                                    isInput: ['checkbox',
                                        'is_approved'
                                    ],
                                    isShow: true
                                }
                            
                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">
                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Necesidad:</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="necesidad" v-model="scope.dataForm.need">
                                            <option value="Actividades">Actividades</option>
                                            <option value="Repuestos">Repuestos</option>
                                        </select>
                                        <small>Seleccione el tipo de necesidad.</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" v-model="scope.dataForm.description"
                                            required>
                                        <small>Ingrese el nombre de la actividad o repuesto que desea agregar a esta
                                            solicitud</small>
                                    </div>

                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"
                                            v-model="scope.dataForm.unit_measurement" required>
                                        <small>Ingrese la unidad de medida de la actividad o repuesto</small>
                                    </div>


                                    <label class="col-form-label col-md-2 required">Valor Unitario:</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" v-model="scope.dataForm.unit_value"
                                            required>
                                        <small>Ingrese el valor unitario de la actividad o repuesto</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input type="number" class="form-control" v-model="scope.dataForm.iva"
                                            required>
                                        <small>Ingrese el IVA de la actividad o repuesto</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>

                                    <div class="col-md-4">
                                        <currency-input v-model="scope.dataForm.amount_requested" required="true"
                                            :currency="{ 'prefix': ' ' }" locale="es" class="form-control"
                                            :precision="6" :key="keyRefresh"
                                            @keyup="$set(scope.dataForm, 'valor_total',
                                            (parseFloat(scope.dataForm.valor_total) ? 
                                                (parseFloat(scope.dataForm.valor_total) || 0) * (parseFloat(scope.dataForm.amount_requested) || 0) :
                                                ((parseFloat(scope.dataForm.iva) || 0) + (parseFloat(scope.dataForm.unit_value) || 0)) * (parseFloat(scope.dataForm.amount_requested) || 0)
                                            )
                                            )"
                                            :disabled="false">
                                        </currency-input>
                                        <small>
                                            Ingrese la cantidad deseada.
                                        </small>

                                    </div>

                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Valor total:</label>
                                    <div class="col-md-4">
                                        <currency-input v-model="scope.dataForm.valor_total" required="true"
                                            :currency="{ 'prefix': '$ ' }" locale="es" class="form-control" disabled
                                            :key="keyRefresh">
                                        </currency-input>
                                        <div class="custom-tooltip" title="">
                                            <small class="custom-tooltip-trigger">
                                                <i class="fas fa-question-circle"></i> Ayuda
                                            </small>
                                            <div class="custom-tooltip-content">
                                                <small>
                                                    El valor total se calcula a partir del valor unitario más el IVA,
                                                    multiplicado por la cantidad solicitada.
                                                    Valor Total=(Valor Unitario+IVA)×Cantidad Solicitada
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Tipo de mantenimiento</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="scope.dataForm.maintenance_type" required>
                                            <option value="Preventivo">Preventivo</option>
                                            <option value="Correctivo">Correctivo</option>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </dynamic-list-needs>
                    </div>


                    <div class="">
                        <p style="font-size:16px;"><b>Valor total de la solicitud</b></p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <currency-input
                                    v-model="dataForm.total_solicitud"
                                    required="true"
                                    :currency="{'prefix': ''}"
                                    locale="es"
                                    class="form-control"
                                    disabled
                                    >
                            </currency-input> 
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title">
                <strong>Tramitar solicitud</strong>
            </div>
        </div>
        <div class="panel-body">
            <!-- Provider Observation Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('status', trans('Estado') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select name="" id="" class="form-control" v-model="dataForm.status" required>
                        <option value="Aprobada">Aprobar</option>
                        <option value="En trámite devuelto">Devolver para ajustes</option>
                        <option value="Cancelada">Cancelar</option>
                    </select>
                    <div class="invalid-feedback" v-if="dataErrors.status">
                        <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Provider Observation Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('admin_observation', trans('Observaciones') . ':', [
                    'class' => 'col-form-label col-md-3 required',
                ]) !!}
                <div class="col-md-9">
                    {!! Form::textarea('admin_observation', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.admin_observation }",
                        'v-model' => 'dataForm.admin_observation',
                        'required' => true,
                    ]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Provider Observation')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.admin_observation">
                        <p class="m-b-0" v-for="error in dataErrors.admin_observation">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="" v-else>
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title"><strong>Información general</strong></div>
        </div>
        <div class="panel-body">
            <div class="form-group row m-b-15">
                {!! Form::label('activity_weigth', trans('Tipo de solicitud') . ':', [
                    'class' => 'col-form-label col-md-2 required',
                ]) !!}
                <div class="col-md-4">
                    <select class="form-control" v-model="dataForm.type_request" required>
                        <option value="Adición">Adición</option>
                        <option value="Solicitud validación costo">Solicitud validación costo</option>
                    </select>
                    <small>Seleccione el tipo de solicitud que desea realizar.</small>
                </div>

                <label v-if="dataForm.type_request == 'Adición'" class="col-form-label col-md-2" for="">Valor disponible: </label>
                <div class="col-md-4" v-if="dataForm.type_request == 'Adición'">
                    <div class="d-flex">
                        <input-disabled name-prop="value_avaible"
                            :name-resource="'item-value-available/' + initValues.mant_administration_cost_items_id"
                            :value="dataForm" name-field="valor_disponible"
                            :key="dataForm.type_request"></input-disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.type_request == 'Adición'">
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

                        <dynamic-list-needs label-button-add="Agregar necesidad a la lista"
                            :data-list.sync="dataForm.needs" :is-activity="true"
                            :data-list-options="[
                                { label: 'Necesidad', name: 'need', isShow: true },
                                { label: 'Descripción', name: 'description',nameObjectKey: ['descripcion_datos', 'description'], isShow: true },
                                { label: 'Cantidad solicitada', name: 'amount_requested', isShow: true },
                                { label: 'Unidad de medida', name: 'unit_measurement', isShow: true },
                            
                                { label: 'Valor Unitario', name: 'unit_value', isShow: true },
                                { label: 'IVA', name: 'iva', isShow: true },
                                { label: 'Valor total', name: 'valor_total', isShow: true },
                                { label: 'Tipo de mantenimiento', name: 'maintenance_type', isShow: true }
                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">
                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Necesidad:</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="necesidad" v-model="scope.dataForm.need">
                                            <option value="Actividades">Actividades</option>
                                            <option value="Repuestos">Repuestos</option>
                                        </select>
                                        <small>Seleccione el tipo de necesidad.</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-10">
                                        <select-check  
                                            css-class="form-control" 
                                            name-field="description"
                                            :reduce-label="['item','description']" 
                                            :name-resource="'get-all-descriptions-by-need/'+scope.dataForm.need+ '/'+ initValues.rubro_objeto_contrato_id"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            :key="scope.dataForm.need"
                                            name-field-object="descripcion_datos"
                                            reduce-key="id"
                                            :ids-to-empty="['cantidad_solicitada','valor_total']"
                                            :ids-to-empty-null="true"
                                        ></select-check>
                                        <small>Ingrese el nombre de la actividad o repuesto que desea agregar a esta
                                            solicitud</small>
                                    </div>

                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unit_measurement"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.description"
                                        :value="scope.dataForm"
                                        :value-recibido="scope.dataForm.need == 'Actividades' ? ['descripcion_datos','unit_measurement'] : ['descripcion_datos','unit_measure']"
                                        ></input-check>                                        
                                        <small>Ingrese la unidad de medida de la actividad o repuesto</small>
                                    </div>


                                    <label class="col-form-label col-md-2 required">Valor Unitario:</label>
                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="unit_value"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.description"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unit_value']"
                                        ></input-check>                                        
                                        <small>Ingrese el valor unitario de la actividad o repuesto</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="$"
                                        :disabled="true"
                                        name-field="iva"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.description"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','iva']"
                                        ></input-check>                                        
                                        <small>Ingrese el IVA de la actividad o repuesto</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>

                                    <div class="col-md-4">
                                        <currency-input v-model="scope.dataForm.amount_requested" required="true"
                                            :currency="{ 'prefix': ' ' }" locale="es" class="form-control"
                                            :precision="6" :key="keyRefresh"
                                            @keyup="$set(scope.dataForm, 'valor_total',
                                            (parseFloat(scope.dataForm.valor_total) ? 
                                                (parseFloat(scope.dataForm.valor_total) || 0) * (parseFloat(scope.dataForm.amount_requested) || 0) :
                                                ((parseFloat(scope.dataForm.iva) || 0) + (parseFloat(scope.dataForm.unit_value) || 0)) * (parseFloat(scope.dataForm.amount_requested) || 0)
                                            )
                                            )"
                                            :disabled="false">
                                        </currency-input>
                                        <small>
                                            Ingrese la cantidad deseada.
                                        </small>

                                    </div>

                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Valor total:</label>
                                    <div class="col-md-4">
                                        <currency-input v-model="scope.dataForm.valor_total" required="true"
                                            :currency="{ 'prefix': '$ ' }" locale="es" class="form-control"
                                            disabled :key="keyRefresh">
                                        </currency-input>
                                        <div class="custom-tooltip" title="">
                                            <small class="custom-tooltip-trigger">
                                                <i class="fas fa-question-circle"></i> Ayuda
                                            </small>
                                            <div class="custom-tooltip-content">
                                                <small>
                                                    El valor total se calcula a partir del valor unitario más el IVA,
                                                    multiplicado por la cantidad solicitada.
                                                    Valor Total=(Valor Unitario+IVA)×Cantidad Solicitada
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Tipo de mantenimiento</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="scope.dataForm.maintenance_type"
                                            required>
                                            <option value="Preventivo">Preventivo</option>
                                            <option value="Correctivo">Correctivo</option>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </dynamic-list-needs>
                    </div>


                    <div class="">
                        <p style="font-size:16px;"><b>Valor total de la solicitud</b></p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <currency-input
                                    v-model="dataForm.total_solicitud"
                                    required="true"
                                    :currency="{'prefix': ''}"
                                    locale="es"
                                    class="form-control"
                                    disabled
                                    >
                            </currency-input> 
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </div>
    <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.type_request == 'Solicitud validación costo'">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Generar orden</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <!-- Observacion Field -->
                    <div class="form-group row m-b-15">

                        <dynamic-list-needs label-button-add="Agregar necesidad a la lista"
                            :data-list.sync="dataForm.needs" :is-activity="true"
                            :data-list-options="[
                                { label: 'Necesidad', name: 'need', isShow: true },
                                { label: 'Descripción', name: 'description', isShow: true },
                                { label: 'Cantidad solicitada', name: 'amount_requested', isShow: true },
                                { label: 'Unidad de medida', name: 'unit_measurement', isShow: true },
                            
                                { label: 'Valor Unitario', name: 'unit_value', isShow: true },
                                { label: 'IVA', name: 'iva', isShow: true },
                                { label: 'Valor total', name: 'valor_total', isShow: true },
                                { label: 'Tipo de mantenimiento', name: 'maintenance_type', isShow: true }
                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">
                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Necesidad:</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="necesidad" v-model="scope.dataForm.need">
                                            <option value="Actividades">Actividades</option>
                                            <option value="Repuestos">Repuestos</option>
                                        </select>
                                        <small>Seleccione el tipo de necesidad.</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">
                                
                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" v-model="scope.dataForm.description" required>
                                        <small>Ingrese el nombre de la actividad o repuesto que desea agregar a esta solicitud</small>
                                    </div>
                                    
                                </div>

                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required">Tipo de mantenimiento</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="scope.dataForm.maintenance_type" required>
                                            <option value="Preventivo">Preventivo</option>
                                            <option value="Correctivo">Correctivo</option>
                                        </select>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" v-model="scope.dataForm.unit_measurement" required>
                                        <small>Ingrese la unidad de medida de la actividad o repuesto</small>
                                    </div>

                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" v-model="scope.dataForm.amount_requested" required>
                                        <small>Ingrese la cantidad deseada</small>
                                    </div>
                                </div>
                            </template>
                        </dynamic-list-needs>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
