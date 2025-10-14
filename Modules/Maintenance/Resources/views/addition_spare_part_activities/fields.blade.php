<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.status != 'Solicitud en asignación de costo'">
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

                        <dynamic-list-needs  label-button-add="Agregar necesidad a la lista" :data-list.sync="dataForm.needs" :is-activity="true"
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
                                        <select class="form-control" id="necesidad" v-model="scope.dataForm.need" required>
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
                                    
                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" v-model="scope.dataForm.unit_measurement" required>
                                        <small>Ingrese la unidad de medida de la actividad o repuesto</small>
                                    </div>


                                    <label class="col-form-label col-md-2 required">Valor Unitario:</label>
                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.unit_value"
                                        required="true"
                                        :currency="{'prefix': '$ '}"
                                        locale="es"
                                        class="form-control"
                                        :precision="2"
                                        >
                                        </currency-input>                                        
                                        <small>Ingrese el valor unitario de la actividad o repuesto</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.iva"
                                        required="true"
                                        :currency="{'prefix': '$ '}"
                                        locale="es"
                                        class="form-control"
                                        :precision="2"
                                        >
                                        </currency-input>                                        
                                        <small>Ingrese el IVA de la actividad o repuesto</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                                
                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.amount_requested"
                                        required="true"
                                        :currency="{'prefix': ' '}"
                                        locale="es"
                                        class="form-control"
                                        :precision="6"
                                        :key="keyRefresh"
                                        @keyup="$set(scope.dataForm, 'valor_total',
                                            (parseFloat(scope.dataForm.valor_total) ? 
                                                (parseFloat(scope.dataForm.valor_total) || 0) * (parseFloat(scope.dataForm.amount_requested) || 0) :
                                                ((parseFloat(scope.dataForm.iva) || 0) + (parseFloat(scope.dataForm.unit_value) || 0)) * (parseFloat(scope.dataForm.amount_requested) || 0)
                                            )
                                            )"

                                        :disabled="false"
                                        >
                                        </currency-input>
                                        <small>
                                            Ingrese la cantidad deseada.
                                        </small>
                                    
                                    </div>

                                </div>

                                <div class="form-group row m-b-15">
                        
                                    <label class="col-form-label col-md-2 required">Valor total:</label>
                                    <div class="col-md-4">
                                        <currency-input
                                                v-model="scope.dataForm.valor_total"
                                                required="true"
                                                :currency="{'prefix': '$ '}"
                                                locale="es"
                                                class="form-control"
                                                disabled
                                                :key="keyRefresh"
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
<div class="panel" data-sortable-id="ui-general-1" v-else>
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

                        <dynamic-list-needs  label-button-add="Agregar necesidad a la lista" :data-list.sync="dataForm.needs" :is-activity="true"
                            :data-list-options="[
                                { label: 'Necesidad', name: 'need', isShow: true },
                                { label: 'Descripción', name: 'description', isShow: true },
                                { label: 'Cantidad solicitada', name: 'amount_requested', isShow: true },
                                { label: 'Unidad de medida', name: 'unit_measurement', isShow: true },

                                { label: 'Valor Unitario', name: 'unit_value',isInput:['number','unit_value'], haveCalculate:true,isRequired:true, isShow: true },
                                { label: 'IVA', name: 'iva', isShow: true, isInput:['number','iva'], haveCalculate:true,isRequired:true },
                                { label: 'Valor total', name: 'valor_total', isInput:['number','valor_total'], isDisabled:true, isShow: true },
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
                                    
                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" v-model="scope.dataForm.unit_measurement" required>
                                        <small>Ingrese la unidad de medida de la actividad o repuesto</small>
                                    </div>


                                    <label class="col-form-label col-md-2 required">Valor Unitario:</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" v-model="scope.dataForm.unit_value" required>
                                        <small>Ingrese el valor unitario de la actividad o repuesto</small>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">

                                    <label class="col-form-label col-md-2 required">IVA:</label>

                                    <div class="col-md-4">
                                        <input type="number" class="form-control" v-model="scope.dataForm.iva" required>
                                        <small>Ingrese el IVA de la actividad o repuesto<</small>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                                
                                    <div class="col-md-4">
                                        <currency-input
                                        v-model="scope.dataForm.amount_requested"
                                        required="true"
                                        :currency="{'prefix': ' '}"
                                        locale="es"
                                        class="form-control"
                                        :precision="6"
                                        :key="keyRefresh"
                                        @keyup="$set(scope.dataForm, 'valor_total',
                                            (parseFloat(scope.dataForm.valor_total) ? 
                                                (parseFloat(scope.dataForm.valor_total) || 0) * (parseFloat(scope.dataForm.amount_requested) || 0) :
                                                ((parseFloat(scope.dataForm.iva) || 0) + (parseFloat(scope.dataForm.unit_value) || 0)) * (parseFloat(scope.dataForm.amount_requested) || 0)
                                            )
                                            )"

                                        :disabled="false"
                                        >
                                        </currency-input>
                                        <small>
                                            Ingrese la cantidad deseada.
                                        </small>
                                    
                                    </div>

                                </div>

                                <div class="form-group row m-b-15">
                        
                                    <label class="col-form-label col-md-2 required">Valor total:</label>
                                    <div class="col-md-4">
                                        <currency-input
                                                v-model="scope.dataForm.valor_total"
                                                required="true"
                                                :currency="{'prefix': '$ '}"
                                                locale="es"
                                                class="form-control"
                                                disabled
                                                :key="keyRefresh"
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
                            <input type="text" class="form-control" v-model="dataForm.total_solicitud" disabled>
                          </div>
                        
                    </div>


                </div>

            </div>
        </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            <strong>Observaciones</strong>
        </div>
    </div>
    <div class="panel-body">
        <!-- Provider Observation Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('provider_observation', trans('Observaciones').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('provider_observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.provider_observation }", 'v-model' => 'dataForm.provider_observation', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Provider Observation')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.provider_observation">
                    <p class="m-b-0" v-for="error in dataErrors.provider_observation">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
