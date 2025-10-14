<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de ingreso al almacén</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2">Información de factura:</label>
                        <div class="col-md-10">
                             <!-- Checkbox -->
                            <div class="form-check mt-2">
                                <input type="checkbox" 
                                    v-model="dataForm.no_factura"
                                    name="no_factura"
                                    :true-value="1"  
                                    :false-value="0" 
                                    class="form-check-input" 
                                    id="no_factura">

                                <label class="form-check-label" for="no_factura">
                                    Aún no cuento con el número de factura y número de entrada almacén
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-b-15" v-if="dataForm.no_factura == 0">

                        <label class="col-form-label col-md-2 required">Número de entrada almacén:</label>
                        <div class="col-md-10">

                            {!! Form::text('numero_entrada_almacen', null, ['class' => 'form-control', 'v-model' => 'dataForm.numero_entrada_almacen', 'required' => true]) !!}
                            <small></small>
                        </div>
                    </div>


                    <div class="form-group row m-b-15" v-if="dataForm.no_factura == 0">
                        <label class="col-form-label col-md-2 required">Número de factura:</label>
                        <div class="col-md-10">
                            
                            <!-- Campo de número de factura (se oculta si se marca el checkbox) -->
                            <div>
                                {!! Form::text('numero_factura', null, [
                                    'class' => 'form-control',
                                    'v-model' => 'dataForm.numero_factura',
                                     'required' => true
                                ]) !!}
                                <small></small>
                            </div>

                        </div>
                    </div>



                    <div class="form-group row m-b-15">

                        <label class="col-form-label col-md-2 required">Fecha de entrada almacén:</label>
                        <div class="col-md-10">
                            {!! Form::date('fecha_entrada_almacen', null, ['class' => 'form-control', 'id' => 'fecha_entrada_almacen', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.fecha_entrada_almacen', 'required' => true]) !!}

                            <small></small>
                        </div>
                    </div>
                    

                </div>

            </div>
        </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de ingreso al almacén</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <div class="form-group row m-b-15">

                        <dynamic-list-editable class-table-header="text-center bg-green" :has-actions="false" label-button-add="Agregar a la lista" :is-delete="false" :data-list.sync="dataForm.ordenes_entradas" :is-adding="false"
                            :data-list-options="[
                                { label: 'Descripción', name: 'descripcion_nombre', isShow: true },
                                { label: 'Unidad de medida', name: 'unidad', isShow: true },
                                { label: 'Cantidad solicitada', name: 'cantidad', isShow: true },
                                { label: 'Código', name: 'codigo_entrada', isShow: true, isEditable:true, isRequired:true },
                                { label: 'Cantidad ingresada al almacén', name: 'cantidad_entrada', isShow: true,isEditable:true, inputType:'number', eventoInput: true},
                                { label: 'Unidad de Medida Ingreso', name: 'unidad_medida_conversion', isShow: true, type: 'conversion-unit-select',isEditable:true},
                                { label: 'Conversión', name: 'conversion_feedback_text', isShow: true }
                            ]"
                            :key="keyRefresh"
                            class-container="col-md-12" class-table="table table-bordered">
                            {{-- <template #fields="scope">
                                <div class="form-group row m-b-15">
                                
                                    <label class="col-form-label col-md-2 required">Descripción:</label>
                                    <div class="col-md-4">

                                        <select-check  
                                            :is-required="true"
                                            css-class="form-control" 
                                            name-field="descripcion_nombre"
                                            reduce-label='descripcion_nombre' 
                                            :name-resource="'get-all-descriptions-by-need-request-id/'+dataForm.id"
                                            :value="scope.dataForm" 
                                            :enable-search="true" 
                                            name-field-object="descripcion_datos"
                                            reduce-key="descripcion_nombre"
                                            :exclude-data="dataForm.ordenes_entradas"
                                            ref-select-check="descripcionRef"
                                            :key="scope.dataForm + dataForm.ordenes_entradas"
                                        ></select-check>

                                        <small>Seleccione la actividad o el repuesto</small>
                                    </div>

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
                                    
                                </div>

                                <div class="form-group row m-b-15">
                                    
                                    <label class="col-form-label col-md-2 required">Código:</label>
                                
                                    <div class="col-md-4">
                                        {!! Form::text('codigo_entrada', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.codigo_entrada', 'required' => true]) !!}
                                    </div>
                                    

                                    <label class="col-form-label col-md-2 required">Cantidad:</label>

                                    <div class="col-md-4">
                                        <input-check 
                                        prefix="otros"
                                        :disabled="true"
                                        name-field="cantidad_entrada"
                                        css-class="form-control" 
                                        type-input="llenadoPorObjeto"
                                        :key="scope.dataForm.descripcion_nombre"
                                        :value="scope.dataForm"
                                        :value-recibido="['descripcion_datos','cantidad_solicitada']"
                                        ></input-check>
                                    </div>

                                </div>


                                
                            </template> --}}
                        </dynamic-list-editable>
                     
                    </div>

                </div>

            </div>
            <p>Si ingreso la cantidad solicitada , por favor deja el campo vacío. En caso de que no haya ingresado algún repuesto, ingrese cero para evitar aplicar el descuento del rubro. Si ingresó una cantidad menor o mayor, ingrese la cantidad correspondiente en números para ajustar el descuento en el rubro</p>
        </div>
</div>
