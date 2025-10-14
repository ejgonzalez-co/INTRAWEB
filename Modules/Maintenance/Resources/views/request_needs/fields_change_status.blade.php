<div class="panel" v-if="dataForm.tipo_solicitud === 'Activo' && dataForm.valor_total_necesidades_repuestos > 0">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de ingreso al almacén</strong></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                <thead class="text-center bg-green">
                    <tr>
                        <td><strong>Descripción </strong></td>
                        <td><strong>Unidad de medida </strong></td>
                        <td><strong>Cantidad solicitada </strong></td>
                        <td><strong>Código </strong></td>
                        <td><strong>Cantidad ingresada al almacén </strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(necesity, key) in dataForm.necesidades" v-if="necesity.necesidad === 'Repuestos'">
                        <td>@{{ necesity.descripcion_nombre }}</td>
                        <td>@{{ necesity.unidad_medida }}</td>
                        <td>@{{ necesity.cantidad_solicitada }}</td>
                        <td><input type="text" :value="necesity.codigo" class="form-control" disabled></td>
                        <td><input type="number" :value="necesity.cantidad_entrada" class="form-control" disabled></td>

                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <strong>Total: $@{{ dataForm.valor_total_necesidades_repuestos }}</strong>
        </div>
        {{-- <table class="text-center px-4 py-4" border="1">
            <thead class="bg-green">
                <td>Descripción</td>
                <td>Unidad de medida</td>
                <td>Cantidad solicitada</td>
                <td>Código</td>
                <td>Cantidad ingresada al almacén</td>
            </thead>
            <tbody>
                <tr>
                    <td>Aceite</td>
                    <td>LTS</td>
                    <td>1</td>
                    <td>23344</td>
                    <td>1</td>
                </tr>
            </tbody>
        </table> --}}
    </div>
</div>
<div class="panel" v-if="dataForm.tipo_solicitud === 'Activo' && dataForm.valor_total_necesidades_actividades > 0">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de actividades</strong></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover text-inverse table-bordered" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
                <thead class="text-center bg-green">
                    <tr>
                        <td><strong>Descripción </strong></td>
                        <td><strong>Unidad de medida </strong></td>
                        <td><strong>Cantidad solicitada </strong></td>
                        <td><strong>Actividad realizada por el proveedor </strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(necesity, key) in dataForm.necesidades" v-if="necesity.necesidad === 'Actividades'">
                        <td>@{{ necesity.descripcion_nombre }}</td>
                        <td>@{{ necesity.unidad_medida }}</td>
                        <td>@{{ necesity.cantidad_solicitada }}</td>
                        <td><input type="number" class="form-control" v-model="necesity.cantidad_final" step="any"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>Si el proveedor realizó la actividad solicitada, por favor deja el campo vacío. En caso de que no haya realizado la actividad, ingrese cero para evitar aplicar el descuento del rubro. Si realizó una cantidad menor o mayor, ingrese la cantidad correspondiente en números para ajustar el descuento en el rubro</p>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información general</strong></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <div class="form-group row m-b-15">
                <label for="estado" class="col-form-label col-md-4 required">Estado Solicitud</label>
                <div class="col-md-8">
                    <select class="form-control" v-model="scope.dataForm.estado" name="estado" id="estado" required>
                        <option value="">Seleccione</option>
                        <option value="Finalizada">Finalizar Solicitud</option>
                        <option value="Cancelada">Cancelar Solicitud</option>
        
                    </select>
                </div>
            </div>
        </div>  
        <div class="col-md-12" v-if="scope.dataForm.estado == 'Finalizada'">
            <div class="form-group row m-b-15">
                <label for="danger" class="col-form-label col-md-4 required">Número de factura:</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" v-model="dataForm.invoice_no" />
                    <small>Ingrese el número de la factura</small>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group row m-b-15">
                <label for="danger" class="col-form-label col-md-4 required">Observaciones:</label>
                <div class="col-md-8">
                    <textarea class="form-control" required type="text" v-model="scope.dataForm.observacion_fin" placeholder=""></textarea>
                </div>
            </div>
        </div>
    </div>
</div>



