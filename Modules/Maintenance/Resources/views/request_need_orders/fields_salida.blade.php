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

                    
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        
                                        <table class="table table-hover fix-vertical-table">
                                        <thead>
                                            <tr>
                                                <th>Código inventario</th>
                                                <th>Descripción</th>
                                                <th>Unidad de medida</th>
                                                <th>Cantidad</th>
                                                <th>Tipo mantenimiento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(necesidad, key) in dataForm.ordenes_item">
                                                <td>@{{ necesidad.codigo_salida ?? necesidad.codigo_entrada }}</td>
                                                <td>@{{ necesidad.descripcion_nombre }}</td>
                                                <td>@{{ necesidad.unidad }}</td>
                                                <td>@{{ necesidad.cantidad }}</td>
                                                <td>@{{ necesidad.tipo_mantenimiento }}</td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                      
                    </div>

                    

                </div>

            </div>
        </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de salida al almacén</strong></h4>
    </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">

                    <div class="form-group row m-b-15">

                        <label class="col-form-label col-md-2 required">Número de salida almacén:</label>
                        <div class="col-md-10">

                            {!! Form::text('numero_salida_almacen', null, ['class' => 'form-control', 'v-model' => 'dataForm.numero_salida_almacen', 'required' => true]) !!}
                            <small></small>
                        </div>
                    </div>


                
                    <div class="form-group row m-b-15">

                        <label class="col-form-label col-md-2 required">Fecha de salida almacén:</label>
                        <div class="col-md-10">
                            {!! Form::date('fecha_salida_almacen', null, ['class' => 'form-control', 'id' => 'fecha_salida_almacen', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.fecha_entrada_almacen', 'required' => true]) !!}

                            <small></small>
                        </div>
                    </div>
                    <div class="form-group row m-b-15">

                        <label class="col-form-label col-md-2 required">Kilometraje de salida del almacén:</label>
                        <div class="col-md-10">
                          <input-disabled :name-resource="'fual-management-by-date/' + dataForm.id  + '/' + dataForm.fecha_entrada_almacen" :value="dataForm" name-field="mileage_out_stock" name-prop="current_hourmeter_or_mileage" :key="dataForm.fecha_entrada_almacen"></input-disabled>

                            <small></small>
                        </div>
                    </div>
                    

                </div>

            </div>
        </div>
</div>