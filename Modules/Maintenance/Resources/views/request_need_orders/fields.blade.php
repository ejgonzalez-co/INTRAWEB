<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Generar orden</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <div class="form-group row m-b-15">

                        <dynamic-list  label-button-add="Agregar necesidad a la lista" :data-list.sync="dataForm.ordenes_item"
                            :is-required="true"
                            :data-list-options="[
                                { label: 'Descripción', name: 'descripcion_nombre', isShow: true , nameObjectKey: ['descripcion_datos', 'descripcion_nombre'], refList: 'descripcionRef'},
                                { label: 'Descripción de datos', name: 'descripcion_datos', isShow: false },
                                { label: 'Unidad de medida', name: 'unidad', isShow: true },
                                { label: 'Cantidad solicitada', name: 'cantidad', isShow: true },
                                { label: 'Tipo Mantenimiento', name: 'tipo_mantenimiento', isShow: true },
                                { label: 'Código', name: 'codigo_salida', isShow: true },
                                { label: 'mant_sn_request_needs_id', name: 'mant_sn_request_needs_id', isShow: false }

                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">
                                <div class="form-group row m-b-15">
                                
                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-4">

                                           <select-check   
                                            :is-required="true"
                                            :disabled="true"
                                            css-class="form-control" 
                                            name-field="descripcion_nombre"
                                            :reduce-label="['descripcion_nombre','cantidad_solicitada','unidad_medida']"
                                            :name-resource="'get-all-descriptions-by-need-request/'+initValues.mant_sn_request_id"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            name-field-object="descripcion_datos"
                                            ref-select-check="descripcionRef"
                                            :exclude-data="dataForm.ordenes_item"
                                            :key="dataForm.ordenes_item.length"
                                        ></select-check>
                                        

                                        <small>Seleccione la actividad o el repuesto</small>
                                    </div>

                                    
                                    <label class="col-form-label col-md-2 required">Tipo de mantenimiento:</label>
                                
                                    <div class="col-md-4">
                                        <select class="form-control" id="tipo_mantenimiento" v-model="scope.dataForm.tipo_mantenimiento" required>
                                            <option value="Preventivo">Preventivo</option>
                                            <option value="Correctivo">Correctivo</option>
                                        </select>
                                    </div>
                                    
                                </div>

                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="unidad"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion_nombre"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','unidad_medida']"
                                        ></input-check>
                                    </div>

                                    <label class="col-form-label col-md-2 required">Cantidad:</label>
                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="otros"
                                        name-field="cantidad"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion_nombre"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','cantidad_solicitada']"
                                        :disabled="true"
                                        ></input-check>
                                    </div>
                                     <div class="col-md-4" style="display: none">
                                        <input-check 
                                        prefix="otros"
                                        name-field="mant_sn_request_needs_id"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion_nombre"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','mant_sn_request_needs_id']"
                                        ></input-check>
                                    </div>
                                </div>

                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2">Código:</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" v-model="scope.dataForm.codigo_salida">
                                        <div class="custom-tooltip" title="">
                                            <small class="custom-tooltip-trigger">
                                                <i class="fas fa-question-circle"></i> Ayuda
                                            </small>
                                            <div class="custom-tooltip-content">
                                                <small>
                                                    Ingrese el código siempre y cuando vaya a realizar una salida de stock
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2">Observación:</label>
                                    <div class="col-md-10">
                                        {!! Form::textarea('observacion', null, [
                                            'style' => 'height: 60px;', // Ajusta la altura según tus necesidades
                                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.observacion }",
                                            'v-model' => 'scope.dataForm.observacion',
                                            // 'required' => true
                                        ]) !!}
                                    </div>
                                </div>


                                {{-- <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required">Tipo de solicitud:</label>
                                    <div class="col-md-10">
            
                                        <select class="form-control" id="tipo_solicitud" v-model="scope.dataForm.tipo_solicitud" required>
                                            <option value="Producto">Producto</option>
                                            <option value="Servicio">Servicio</option>
                                        </select>
            
                                        <small>Seleccione</small>
                                    </div>
                                </div> --}}
                            </template>
                        </dynamic-list>

                       
                    </div>

                    

                </div>

            </div>
        </div>
</div>
