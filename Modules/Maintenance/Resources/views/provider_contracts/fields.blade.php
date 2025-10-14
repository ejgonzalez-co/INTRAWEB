<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">
            <div class="col-md-6">
                <!-- Identification Field -->
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 required" for="identification">@lang('Identification'):</label>
                    <div class="col-md-8">
                        <autocomplete :is-required="true" name-prop="identification" name-field="mant_providers_id" :value='dataForm'
                            name-resource="get-providers" css-class="form-control"
                            :name-labels-display="['identification', 'name']" reduce-key="id"
                            name-field-object="provedor"
                            :key="keyRefresh">
                        </autocomplete>
                        <small>Ingrese el número de identificación del proveedor.</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
        <div v-if="dataForm.providers">
            <div class="row">
                <div class="col-md-6">
                    <!-- Type Person Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="type_person">@lang('Type Person' ):</label>
                        <div class="col-md-8">
                            <select disabled class="form-control"
                                v-model="dataForm?.provedor?.type_person ? dataForm?.provedor?.type_person : dataForm.providers.type_person"
                                name="type_person" id="type_person" required>
                                <option value="Natural">Natural</option>
                                <option value="Jurídica">Jurídica</option>
                            </select>
                            <small>Seleccione el tipo de persona.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Document Type Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="document_type">@lang('Document Type'):</label>
                        <div class="col-md-8">
                            <select disabled class="form-control"
                                v-model="dataForm?.provedor?.document_type ? dataForm?.provedor?.document_type : dataForm.providers.document_type"
                                name="document_type" id="document_type" required>
                                <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                <option value="Cédula de extranjería">Cédula de extranjería</option>
                                <option value="NIT">NIT</option>
                            </select>
                            <small>Seleccione el tipo de documento.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <!-- Name Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="name">@lang('Name'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="name"
                                :class="{'form-control':true, 'is-invalid':dataErrors.name }"
                                v-model="dataForm?.provedor?.name ? dataForm?.provedor?.name : dataForm.providers.name" required>
                            <small>Ingrese el nombre del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Mail Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="mail">@lang('Mail'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="mail"
                                :class="{'form-control':true, 'is-invalid':dataErrors.mail }"
                                v-model="dataForm?.provedor?.mail ? dataForm?.provedor?.mail : dataForm.providers.mail" required>
                            <small>Ingrese un correo electrónico válido, ej: xxxxx@gmail.com.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Regime Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="regime">@lang('Regime'):</label>
                        <div class="col-md-8">
                            <select disabled class="form-control"
                                v-model="dataForm?.provedor?.regime ? dataForm?.provedor?.regime : dataForm.providers.regime" name="regime"
                                id="regime" required>
                                <option value="Simplificado">Simplificado</option>
                                <option value="Común">Común</option>
                            </select>
                            <small>Seleccione el régimen del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Phone Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="phone">@lang('Phone'):</label>
                        <div class="col-md-8">
                            <input disabled type="number" id="phone"
                                :class="{'form-control':true, 'is-invalid':dataErrors.phone }"
                                v-model="dataForm?.provedor?.phone ? dataForm?.provedor?.phone : dataForm.providers.phone" required>
                            <small>Ingrese el teléfono del proveedor.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Address Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="address">@lang('Address'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="address"
                                :class="{'form-control':true, 'is-invalid':dataErrors.address }"
                                v-model="dataForm?.provedor?.address ? dataForm?.provedor?.address : dataForm.providers.address" required>
                            <small>Ingrese la dirección del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Municipality Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required"
                            for="municipality">@lang('Municipality'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="municipality"
                                :class="{'form-control':true, 'is-invalid':dataErrors.municipality }"
                                v-model="dataForm?.provedor?.municipality ? dataForm?.provedor?.municipality : dataForm.providers.municipality"
                                required>
                            <small>Ingrese el municipio del proveedor.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Department Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="department">@lang('Department'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="department"
                                :class="{'form-control':true, 'is-invalid':dataErrors.department }"
                                v-model="dataForm?.provedor?.department ? dataForm?.provedor?.department : dataForm.providers.department"
                                required>
                            <small>Ingrese el departamento del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>

        <div v-if="dataForm.providers==null">
            <div class="row">
                <div class="col-md-6">
                    <!-- Type Person Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="type_person">@lang('Type Person' ):</label>
                        <div class="col-md-8">
                            <select disabled class="form-control"
                                v-model="dataForm?.provedor.type_person"
                                name="type_person" id="type_person" required>
                                <option value="Natural">Natural</option>
                                <option value="Jurídica">Jurídica</option>
                            </select>
                            <small>Seleccione el tipo de persona.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Document Type Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="document_type">@lang('Tipo de
                            documento'):</label>
                        <div class="col-md-8">
                            <select disabled class="form-control"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.document_type : dataForm?.provedor.document_type"
                                name="document_type" id="document_type" required>
                                <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                <option value="Cédula de extranjería">Cédula de extranjería</option>
                                <option value="NIT">NIT</option>
                            </select>
                            <small>Seleccione el tipo de documento.</small>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <!-- Name Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="name">@lang('Name'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="name"
                                :class="{'form-control':true, 'is-invalid':dataErrors.name }"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.name : dataForm?.provedor.name" required>
                            <small>Ingrese el nombre del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Mail Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="mail">@lang('Mail'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="mail"
                                :class="{'form-control':true, 'is-invalid':dataErrors.mail }"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.mail : dataForm?.provedor.mail" required>
                            <small>Ingrese un correo electrónico válido, ej: xxxxx@gmail.com.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Regime Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="regime">@lang('Regime'):</label>
                        <div class="col-md-8">
                            <select disabled class="form-control"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.regime : dataForm?.provedor.regime" name="regime"
                                id="regime" required>
                                <option value="Simplificado">Simplificado</option>
                                <option value="Común">Común</option>
                            </select>
                            <small>Seleccione el régimen del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Phone Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="phone">@lang('Phone'):</label>
                        <div class="col-md-8">
                            <input disabled type="number" id="phone"
                                :class="{'form-control':true, 'is-invalid':dataErrors.phone }"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.phone : dataForm?.provedor.phone" required>
                            <small>Ingrese el teléfono del proveedor.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Address Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="address">@lang('Address'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="address"
                                :class="{'form-control':true, 'is-invalid':dataErrors.address }"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.address : dataForm?.provedor.address" required>
                            <small>Ingrese la dirección del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Municipality Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required"
                            for="municipality">@lang('Municipality'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="municipality"
                                :class="{'form-control':true, 'is-invalid':dataErrors.municipality }"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.municipality : dataForm?.provedor.municipality"
                                required>
                            <small>Ingrese el municipio del proveedor.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Department Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 required" for="department">@lang('Department'):</label>
                        <div class="col-md-8">
                            <input disabled type="text" id="department"
                                :class="{'form-control':true, 'is-invalid':dataErrors.department }"
                                v-model="dataForm?.provedor.providers ? dataForm?.provedor.providers.department : dataForm?.provedor.department"
                                required>
                            <small>Ingrese el departamento del proveedor.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>


        <!-- Object Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('type_contract', trans('Tipo de Contrato') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::select('type_contract', ['Contrato a Término Fijo' => 'Contrato a Término Fijo', 'Contrato a término indefinido' => 'Contrato a término indefinido', 'Contrato de Obra o labor' => 'Contrato de Obra o labor', 'Contrato civil por prestación de servicios' => 'Contrato civil por prestación de servicios', 'Contrato de aprendizaje' => 'Contrato de aprendizaje', 'Contrato ocasional de trabajo' => 'Contrato ocasional de trabajo', 'Consultoría' => 'Consultoría', 'Arrendamiento' => 'Arrendamiento', 'Compraventa' => 'Compraventa', 'Suministros' => 'Suministros', 'Prestación de servicios' => 'Prestación de servicios', 'Clausulado simplificado de compraventa' => 'Clausulado simplificado de compraventa', 'Clausulado simplificado de prestación de servicios' => 'Clausulado simplificado de prestación de servicios', 'Clausulado simplificado de suministros' => 'Clausulado simplificado de suministros'], null, ['class' => 'form-control', 'v-model' => 'dataForm.type_contract', 'required' => true]) !!}
                <small>Ingrese el tipo de contrato.</small>
            </div>



            {!! Form::label('contract_number', trans('Número de contrato') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('contract_number', null, ['class' => 'form-control', 'v-model' => 'dataForm.contract_number', 'required' => true]) !!}
                <small>Ingrese el número de contrato.</small>
            </div>
        </div>

        <!-- Contract Number Field -->
        <div class="form-group row m-b-15">


            {!! Form::label('start_date', trans('Fecha de acta de inicio') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::date('start_date', null, ['class' => 'form-control', 'id' => 'start_date', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.start_date', 'required' => true]) !!}
                <small>Seleccione la fecha de inicio del acta.</small>
            </div>

            {!! Form::label('execution_time', trans('Plazo de ejecución') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('execution_time', null, ['class' => 'form-control', 'v-model' => 'dataForm.execution_time', 'required' => true]) !!}
                <small>Ingrese el plazo de ejecución.</small>
            </div>
        </div>

        <!-- Contract Value Field -->
        <div class="form-group row m-b-15">


            {!! Form::label('closing_date', trans('Fecha final del contrato') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::date('closing_date', null, ['class' => 'form-control', 'id' => 'closing_date', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.closing_date', 'required' => true]) !!}
                <small>Seleccione la fecha final del contrato del contrato.</small>
            </div>
        </div>



          <div class="form-group row m-b-15">

            {!! Form::label('future_validity','Vigencia futura', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                    <select 
                    class="form-control"
                    v-model="dataForm.future_validity"
                    name="future_validity"
                    id="future_validity" 
                    required>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
                <small>¿El contrato tiene vigencia futura?</small>
                <div class="invalid-feedback" v-if="dataErrors.future_validity">
                    <p class="m-b-0" v-for="error in dataErrors.future_validity">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('advance_payment',  'Pago anticipado o anticipo:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select 
                    class="form-control"
                    v-model="dataForm.advance_payment"
                    name="advance_payment"
                    id="advance_payment" 
                    required>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
                <small>¿El contrato tiene un pago anticipado o anticipo?</small>
                <div class="invalid-feedback" v-if="dataErrors.advance_payment">
                    <p class="m-b-0" v-for="error in dataErrors.advance_payment">@{{ error }}</p>
                </div>
            </div>
        </div>


        <div  v-if="dataForm.advance_payment == 'Si'" class="form-group row m-b-15">

            {!! Form::label('advance_value',  'Valor anticipado o anticipo:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <div>
                    {{-- {!! Form::number('advance_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.advance_value', 'required' => true]) !!} --}}
                    <currency-input
                        v-model="dataForm.advance_value"
                        required="true"
                        :currency="{'prefix': '$ '}"
                        locale="es"
                        class="form-control"
                        :key="keyRefresh"
                        >
                    </currency-input>
                    <small>Valor anticipado o anticipo.</small>
                </div>
                <small>¿El contrato tiene un pago anticipado o anticipo?</small>
                <div class="invalid-feedback" v-if="dataErrors.advance_payment">
                    <p class="m-b-0" v-for="error in dataErrors.advance_payment">@{{ error }}</p>
                </div>
            </div>

            <div class="col-md-4">
            </div>

        </div>



        <div class="form-group row m-b-15">

            {!! Form::label('dependencias_id', trans('Dependencia del supervisor') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select-check css-class="form-control" name-field="dependencias_id" reduce-label="nombre"
                    reduce-key="id" name-resource="get-dependency" :value="dataForm" :is-required="true"
                    :key="keyRefresh">
                </select-check>
                <small>Seleccione la dependencia del supervisor</small>
                <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
                    <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('users_id', trans('Supervisor del contrato') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select-check css-class="form-control" name-field="users_id" reduce-label="name" reduce-key="id"
                    :name-resource="'get-users-dependency/'+dataForm.dependencias_id" :value="dataForm"
                    :is-required="true" :key="dataForm.dependencias_id">
                </select-check>
                <small>Seleccione el nombre del supervisor del contrato</small>
                <div class="invalid-feedback" v-if="dataErrors.users_id">
                    <p class="m-b-0" v-for="error in dataErrors.users_id">@{{ error }}</p>
                </div>
            </div>
        </div>


        <!-- Contract Value Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('object', trans('Objeto') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('object', null, ['class' => 'form-control', 'v-model' => 'dataForm.object', 'required' => true]) !!}
                <small>Ingrese el objeto del contrato.</small>
            </div>
        </div>
    </div>
</div>
