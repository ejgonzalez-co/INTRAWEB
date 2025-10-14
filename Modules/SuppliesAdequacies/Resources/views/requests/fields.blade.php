@role('Funcionario requerimiento gestión recursos')
    <div class="panel">
        <div class="d-flex justify-content-center align-items-center pt-3">
            <div class="alert alert-info rounded-pill text-center" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                Estas solicitudes son especialmente para requerimientos o necesidades de suministros y adecuaciones que
                requieren de la gestión del proceso de Gestión de Recursos.
            </div>
        </div>

        <div class="panel mx-2">
            <div class="panel-body">
                <!-- Subject Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('subject', trans('Subject') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('subject', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.subject }",
                            'v-model' => 'dataForm.subject',
                            'required' => true,
                        ]) !!}
                        <small>@lang('Enter the') @{{ `@lang('Subject')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.subject">
                            <p class="m-b-0" v-for="error in dataErrors.subject">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel mx-2 pb-2">
            <div class="panel-heading">
                <div class="panel-title"><strong>Identificación de necesidades</strong></div>
            </div>
            <div class="panel-body">
                <dynamic-list label-button-add="Agregar necesidad a la lista"
                    :data-list.sync="dataForm.requests_supplies_adjustements_needs"
                    :data-list-options="[
                        { label: 'Necesidad', name: 'need_type', isShow: true },
                        { label: 'Código', name: 'code', isShow: true },
                        { label: 'Unidad de medida', name: 'unit_measure', isShow: true },
                        { label: 'Cantidad solicitada', name: 'request_quantity', isShow: true }
                    ]"
                    class-container="col-md-12" class-table="table table-bordered">
                    <template #fields="scope">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Necesidad:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.need_type" required>
                                <small>Ingrese el tipo de necesidad.</small>
                            </div>
    
                            <label class="col-form-label col-md-2">Código:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.code">
                                <small>Ingrese el código</small>
                            </div>
                        </div>
    
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.unit_measure" required>
                                <small>Ingrese la unidad de medidad.</small>
                            </div>
    
                            <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" v-model="scope.dataForm.request_quantity" required>
                                <small>Ingrese la cantidad solicitada</small>
                            </div>
                        </div>
                    </template>
                </dynamic-list>
            </div>
        </div>

        <div class="panel mx-2">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información general</strong></div>
            </div>
            <div class="panel-body">
                <!-- Need Type Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('need_type', trans('Need Type') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.need_type" class="form-control">
                            <option value="Producto">Producto</option>
                            <option value="Servicio">Servicio</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                        </select>
                        <small>@lang('Enter the') @{{ `@lang('Need Type')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.need_type">
                            <p class="m-b-0" v-for="error in dataErrors.need_type">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Justification Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('justification', trans('Justification') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('justification', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.justification }",
                            'v-model' => 'dataForm.justification',
                            'required' => true,
                        ]) !!}
                        <small>@lang('Enter the') @{{ `@lang('Justification')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.justification">
                            <p class="m-b-0" v-for="error in dataErrors.justification">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15">
                    {!! Form::label('documents', 'Archivos:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <input-file :value="dataForm" name-field="url_documents" :max-files="10"
                            :max-filesize="5"
                            file-path="public/supplies-adequacies/requests/documents_{{ date('Y') }}"
                            message="Arrastre o seleccione los archivos"
                            help-text="Agregue aquí máximo 10 archivos a esta solicitud. Peso máximo es de 5 MB."
                            :is-update="isUpdate" ruta-delete-update="correspondence/internals-delete-file"
                            :id-file-delete="dataForm.id">
                        </input-file>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endrole

@role('Administrador requerimiento gestión recursos')
    <div class="panel" v-if="!isUpdate">
        <div class="d-flex justify-content-center align-items-center pt-3">
            <div class="alert alert-info rounded-pill text-center" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                Estas solicitudes son especialmente para requerimientos o necesidades de suministros y adecuaciones que
                requieren de la gestión del proceso de Gestión de Recursos.
            </div>
        </div>

        <div class="panel mx-2">
            <div class="panel-body">
                <!-- Subject Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('subject', trans('Subject') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('subject', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.subject }",
                            'v-model' => 'dataForm.subject',
                            'required' => true,
                        ]) !!}
                        <small>@lang('Enter the') @{{ `@lang('Subject')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.subject">
                            <p class="m-b-0" v-for="error in dataErrors.subject">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel mx-2 pb-2">
            <div class="panel-heading">
                <div class="panel-title"><strong>Identificación de necesidades</strong></div>
            </div>
            <div class="panel-body">
                <dynamic-list label-button-add="Agregar necesidad a la lista"
                    :data-list.sync="dataForm.requests_supplies_adjustements_needs"
                    :data-list-options="[
                        { label: 'Necesidad', name: 'need_type', isShow: true },
                        { label: 'Código', name: 'code', isShow: true },
                        { label: 'Unidad de medida', name: 'unit_measure', isShow: true },
                        { label: 'Cantidad solicitada', name: 'request_quantity', isShow: true }
                    ]"
                    class-container="col-md-12" class-table="table table-bordered">
                    <template #fields="scope">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Necesidad:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.need_type" required>
                                <small>Ingrese el tipo de necesidad.</small>
                            </div>
    
                            <label class="col-form-label col-md-2">Código:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.code">
                                <small>Ingrese el código</small>
                            </div>
                        </div>
    
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.unit_measure" required>
                                <small>Ingrese la unidad de medidad.</small>
                            </div>
    
                            <label class="col-form-label col-md-2 required">Cantidad solicitada:</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" v-model="scope.dataForm.request_quantity" required>
                                <small>Ingrese la cantidad solicitada</small>
                            </div>
                        </div>
                    </template>
                </dynamic-list>
            </div>
        </div>

        <div class="panel mx-2">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información general</strong></div>
            </div>
            <div class="panel-body">
                <!-- Need Type Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('need_type', trans('Need Type') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.need_type" class="form-control">
                            <option value="Producto">Producto</option>
                            <option value="Servicio">Servicio</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                        </select>
                        <small>@lang('Enter the') @{{ `@lang('Need Type')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.need_type">
                            <p class="m-b-0" v-for="error in dataErrors.need_type">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Justification Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('justification', trans('Justification') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('justification', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.justification }",
                            'v-model' => 'dataForm.justification',
                            'required' => true,
                        ]) !!}
                        <small>@lang('Enter the') @{{ `@lang('Justification')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.justification">
                            <p class="m-b-0" v-for="error in dataErrors.justification">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15">
                    {!! Form::label('documents', 'Archivos:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <input-file :value="dataForm" name-field="url_documents" :max-files="10"
                            :max-filesize="5"
                            file-path="public/supplies-adequacies/requests/documents_{{ date('Y') }}"
                            message="Arrastre o seleccione los archivos"
                            help-text="Agregue aquí máximo 10 archivos a esta solicitud. Peso máximo es de 5 MB."
                            :is-update="isUpdate" ruta-delete-update="correspondence/internals-delete-file"
                            :id-file-delete="dataForm.id">
                        </input-file>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel" v-else>
        <div class="panel-heading">
            <div class="panel-title"><strong>Datos de la solicitud</strong></div>
        </div>
        <div class="panel-body">
            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2 required">Estado</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="dataForm.status" required>
                        <option value="En elaboración">En elaboración</option>
                        <option value="Abierta">Abierta</option>
                        <option value="Asignada">Asignada</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Cerrada">Cerrada</option>
                        <option value="Finalizada">Finalizada</option>
                        <option value="Cancelada">Cancelada</option>
                        <option value="Próxima vigencia">Próxima vigencia</option>
                    </select>
                    <small>Seleccione el estado de la solicitud.</small>
                </div>
            </div>
            <div class="form-group row m-b-15" v-if="dataForm.status == 'Asignada'">
                <label for="" class="col-form-label col-md-2 required">Tipo de requerimiento</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="dataForm.requirement_type" required>
                        <option value="Infraestuctura">Infraestuctura</option>
                        <option value="Suministros de consumo">Suministros de consumo</option>
                        <option value="Suministros devolutivo - Asignación">Suministros devolutivo / Asignación</option>
                    </select>
                    <small>Seleccione el tipo de requerimiento.</small>
                </div>
            </div>
            <div class="form-group row m-b-15" v-if="dataForm.status == 'Asignada'">
                <label for="" class="col-form-label col-md-2 required">Funcionario asignado</label>
                <div class="col-md-9">
                    <select-check css-class="form-control" name-field="assigned_officer_id" reduce-label="name"
                        reduce-key="id" :name-resource="'operators/requirements/' + dataForm.requirement_type"
                        :enable-search="true" :value="dataForm" :key="dataForm.requirement_type">
                    </select-check>
                    <small>Seleccione el funcionario a asignar.</small>
                </div>
            </div>
            <div class="form-group row m-b-15" v-if="dataForm.status == 'Asignada'">
                <label for="" class="col-form-label col-md-2 required">Tipo de plazo</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="dataForm.term_type" required>
                        <option value="Laboral">Laboral</option>
                        <option value="Calendario">Calendario</option>
                    </select>
                    <small>Seleccione el tipo de plazo</small>
                </div>
            </div>
            
            <!-- Subject Field -->
            <div class="form-group row m-b-15" v-if="dataForm.status == 'Asignada'">
                {!! Form::label('quantity_term', trans('Plazo del tipo de solicitud') . ':', [
                    'class' => 'col-form-label col-md-2 required',
                ]) !!}
                <div class="col-md-9">
                    {!! Form::number('quantity_term', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.quantity_term }",
                        'v-model' => 'dataForm.quantity_term',
                        'required' => true,
                    ]) !!}
                    <small>Ingrese en días el plazo de la solicitud. Ejemplo: 10</small>
                    <div class="invalid-feedback" v-if="dataErrors.quantity_term">
                        <p class="m-b-0" v-for="error in dataErrors.quantity_term">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Subject Field -->
            <div class="form-group row m-b-15" v-if="dataForm.status != 'Cancelada' && dataForm.status != 'Próxima vigencia'">
                {!! Form::label('subject', trans('Subject') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('subject', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.subject }",
                        'v-model' => 'dataForm.subject',
                        'required' => true,
                    ]) !!}
                    <small>@lang('Enter the') el asunto de la solicitud.</small>
                    <div class="invalid-feedback" v-if="dataErrors.subject">
                        <p class="m-b-0" v-for="error in dataErrors.subject">@{{ error }}</p>
                    </div>
                </div>
            </div>


            <!-- Justification Field -->
            <div class="form-group row m-b-15" v-if="dataForm.status != 'Cancelada' && dataForm.status != 'Próxima vigencia'">
                {!! Form::label('justification', trans('Description') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('justification', null, [
                        ':class' => "{'form-control':true, 'is-invalid':dataErrors.justification }",
                        'v-model' => 'dataForm.justification',
                        'required' => true,
                    ]) !!}
                    <small>@lang('Enter the') la justificación</small>
                    <div class="invalid-feedback" v-if="dataErrors.justification">
                        <p class="m-b-0" v-for="error in dataErrors.justification">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15" v-if="dataForm.status != 'Cancelada' && dataForm.status != 'Próxima vigencia'">
                <label for="" class="col-form-label col-md-2 required">Centro de costos</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="dataForm.cost_center" required>
                        <option value="Aseo">Aseo</option>
                        <option value="Acueducto">Acueducto</option>
                        <option value="Alcantarillado">Alcantarillado</option>
                        <option value="Procesos">Procesos</option>
                    </select>
                    <small>Seleccione el centro de costos.</small>
                </div>
            </div>
            <div class="form-group row m-b-15" v-if="dataForm.status != 'Cancelada' && dataForm.status != 'Próxima vigencia'">
                <label for="" class="col-form-label col-md-2">Verificación con el proveedor</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="dataForm.supplier_verification">
                        <option value="Verificación realizada">Verificación realizada</option>
                        <option value="Verificación pendiente">Verificación pendiente</option>
                    </select>
                    <small>Seleccione la verificación con el proveedor.</small>
                </div>
            </div>
            <div class="form-group row m-b-15" v-if="dataForm.status != 'Cancelada' && dataForm.status != 'Próxima vigencia'">
                <label for="" class="col-form-label col-md-2">Proveedor</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" v-model="dataForm.supplier_name">
                    <small>Ingrese el nombre del proveedor.</small>
                </div>
            </div>
            <div class="form-group row m-b-15" v-if="dataForm.status != 'Cancelada' && dataForm.status != 'Próxima vigencia'">
                <label for="" class="col-form-label col-md-2">A nombre de</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" v-model="dataForm.creator_name" disabled>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('documents', 'Seguimiento:', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-9">
                    <text-area-editor :value="dataForm" name-field="tracking"
                        :hide-modules="{
                            'bold': true,
                            'image': true,
                            'code': true,
                            'link': true
                        }"
                        placeholder="Ingrese el seguimiento" :numero1="1">
                    </text-area-editor>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('documents', 'Archivos:', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-9">
                    <input-file :value="dataForm" name-field="url_documents" :max-files="10"
                        :max-filesize="5"
                        file-path="public/supplies-adequacies/requests/documents_{{ date('Y') }}"
                        message="Arrastre o seleccione los archivos"
                        help-text="Agregue aquí máximo 10 archivos a esta solicitud. Peso máximo es de 5 MB."
                        :is-update="isUpdate" ruta-delete-update="correspondence/internals-delete-file"
                        :is-removable="false" :id-file-delete="dataForm.id">
                    </input-file>
                </div>
            </div>
        </div>
    </div>
@endrole
@if (Auth::user()->hasRole([
        'Operador Infraestuctura',
        'Operador Suministros de consumo',
        'Operador Suministros devolutivo / Asignación',
    ]))
    <div class="panel" v-if="!isUpdate">
        <div class="d-flex justify-content-center align-items-center pt-3">
            <div class="alert alert-info rounded-pill text-center" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                Estas solicitudes son especialmente para requerimientos o necesidades de suministros y adecuaciones que
                requieren de la gestión del proceso de Gestión de Recursos.
            </div>
        </div>

        <div class="panel mx-2">
            <div class="panel-body">
                <!-- Subject Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('subject', trans('Subject') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('subject', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.subject }",
                            'v-model' => 'dataForm.subject',
                            'required' => true,
                        ]) !!}
                        <small>@lang('Enter the') @{{ `@lang('Subject')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.subject">
                            <p class="m-b-0" v-for="error in dataErrors.subject">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel mx-2 pb-2">
            <div class="panel-heading">
                <div class="panel-title"><strong>Identificación de necesidades</strong></div>
            </div>
            <div class="panel-body">
                <dynamic-list label-button-add="Agregar necesidad a la lista"
                    :data-list.sync="dataForm.requests_supplies_adjustements_needs"
                    :data-list-options="[
                        { label: 'Necesidad', name: 'need_type', isShow: true },
                        { label: 'Código', name: 'code', isShow: true },
                        { label: 'Unidad de medida', name: 'unit_measure', isShow: true },
                        { label: 'Cantidad solicitada', name: 'request_quantity', isShow: true }
                    ]"
                    class-container="col-md-12" class-table="table table-bordered">
                    <template #fields="scope">
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Necesidad:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.need_type" required>
                                <small>Ingrese el tipo de necesidad.</small>
                            </div>
    
                            <label class="col-form-label col-md-2">Código:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.code">
                                <small>Ingrese el código</small>
                            </div>
                        </div>
    
                        <div class="form-group row m-b-15">
                            <label class="col-form-label col-md-2 required">Unidad de medida:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="scope.dataForm.unit_measure" required>
                                <small>Ingrese la unidad de medidad.</small>
                            </div>
    
                            <label class="col-form-label col-md-2 required" required>Cantidad solicitada:</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" v-model="scope.dataForm.request_quantity">
                                <small>Ingrese la cantidad solicitada</small>
                            </div>
                        </div>
                    </template>
                </dynamic-list>
            </div>
        </div>

        <div class="panel mx-2">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información general</strong></div>
            </div>
            <div class="panel-body">
                <!-- Need Type Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('need_type', trans('Need Type') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.need_type" class="form-control">
                            <option value="Producto">Producto</option>
                            <option value="Servicio">Servicio</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                        </select>
                        <small>@lang('Enter the') @{{ `@lang('Need Type')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.need_type">
                            <p class="m-b-0" v-for="error in dataErrors.need_type">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Justification Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('justification', trans('Justification') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('justification', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.justification }",
                            'v-model' => 'dataForm.justification',
                            'required' => true,
                        ]) !!}
                        <small>@lang('Enter the') @{{ `@lang('Justification')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.justification">
                            <p class="m-b-0" v-for="error in dataErrors.justification">@{{ error }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15">
                    {!! Form::label('documents', 'Archivos:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <input-file :value="dataForm" name-field="url_documents" :max-files="10"
                            :max-filesize="5"
                            file-path="public/supplies-adequacies/requests/documents_{{ date('Y') }}"
                            message="Arrastre o seleccione los archivos"
                            help-text="Agregue aquí máximo 10 archivos a esta solicitud. Peso máximo es de 5 MB."
                            :is-update="isUpdate" ruta-delete-update="correspondence/internals-delete-file"
                            :id-file-delete="dataForm.id">
                        </input-file>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel" v-else>
        <div class="panel-heading">
            <div class="panel-title"><strong>Datos de la solicitud</strong></div>
        </div>
        <div class="panel-body">
            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2 required">Estado</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="dataForm.status" required>
                        <option value="Asignada">Asignada</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Cerrada">Cerrada</option>
                    </select>
                    <small>Seleccione el estado de la solicitud.</small>
                </div>
            </div>
            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2 required">Tipo de requerimiento</label>
                <div class="col-md-9">
                    <input type="text" v-model="dataForm.requirement_type" class="form-control" disabled>
                </div>
            </div>
            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2 required">Funcionario asignado</label>
                <div class="col-md-9">
                    <input type="text" value="{{ Auth::user()->name }}" class="form-control" disabled>
                </div>
            </div>

            <!-- Subject Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('subject', trans('Subject') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-9">
                    <input type="text" v-model="dataForm.subject" class="form-control" disabled>
                    <div class="invalid-feedback" v-if="dataErrors.subject">
                        <p class="m-b-0" v-for="error in dataErrors.subject">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Justification Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('justification', trans('Justification') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-9">
                    <textarea cols="30" rows="10" v-model="dataForm.justification" class="form-control" disabled></textarea>
                    <div class="invalid-feedback" v-if="dataErrors.justification">
                        <p class="m-b-0" v-for="error in dataErrors.justification">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2 required">Centro de costos</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="dataForm.cost_center" required>
                        <option value="Aseo">Aseo</option>
                        <option value="Acueducto">Acueducto</option>
                        <option value="Alcantarillado">Alcantarillado</option>
                        <option value="Procesos">Procesos</option>
                    </select>
                    <small>Seleccione el centro de costos.</small>
                </div>
            </div>
            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2">Verificación con el proveedor</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" v-model="dataForm.supplier_verification" disabled>
                </div>
            </div>
            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2 required">Proveedor</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" v-model="dataForm.supplier_name" required>
                    <small>Ingrese el nombre del proveedor.</small>
                </div>
            </div>
            <div class="form-group row m-b-15">
                <label for="" class="col-form-label col-md-2">A nombre de</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" v-model="dataForm.creator_name" disabled>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('documents', 'Seguimiento:', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-9">
                    <text-area-editor :value="dataForm" name-field="tracking"
                        :hide-modules="{
                            'bold': true,
                            'image': true,
                            'code': true,
                            'link': true
                        }"
                        placeholder="Ingrese el seguimiento" :numero1="1">
                    </text-area-editor>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('documents', 'Archivos:', ['class' => 'col-form-label col-md-2']) !!}
                <div class="col-md-9">
                    <input-file :value="dataForm" name-field="url_documents" :max-files="10"
                        :max-filesize="5"
                        file-path="public/supplies-adequacies/requests/documents_{{ date('Y') }}"
                        message="Arrastre o seleccione los archivos"
                        help-text="Agregue aquí máximo 10 archivos a esta solicitud. Peso máximo es de 5 MB."
                        :is-update="isUpdate" ruta-delete-update="correspondence/internals-delete-file"
                        :is-removable="false" :id-file-delete="dataForm.id">
                    </input-file>
                </div>
            </div>
        </div>
    </div>
@endif