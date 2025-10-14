<div class="panel" data-sortable-id="ui-general-1">
	<!-- begin panel-heading -->
	<div class="panel-heading ui-sortable-handle">
		<h4 class="panel-title"><strong>Detalles del proveedor</strong></h4>
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
		<div class="row">

            <div class="col-md-6">
                <!--  Type Person Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('type_person', trans('Type Person').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="type_person"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/type_person"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
						<small>@lang('Select the') el @{{ `@lang('Type Person')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.type_person">
							<p class="m-b-0" v-for="error in dataErrors.type_person">@{{ error }}</p>
						</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Document Type Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('document_type', trans('Document Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="document_type"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/identification_type"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
						<small>@lang('Select the') el @{{ `@lang('Document Type')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.document_type">
							<p class="m-b-0" v-for="error in dataErrors.document_type">@{{ error }}</p>
						</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Identification Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('identification', trans('Identification').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('identification', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.identification }", 'v-model' => 'dataForm.identification', 'required' => true]) !!}
						<small>@lang('Enter the') la @{{ `@lang('Identification')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.identification">
							<p class="m-b-0" v-for="error in dataErrors.identification">@{{ error }}</p>
						</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Name Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- Password Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('password', trans('Password').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::password('password', [':class' => "{'form-control':true, 'is-invalid':dataErrors.password }", 'v-model' => 'dataForm.password', ':required' => 'dataForm.change_user']) !!}
                        <small>Ingrese una contraseña que contenga mínimo 6 caracteres.</small>
                        <div class="invalid-feedback" v-if="dataErrors.password">
                            <p class="m-b-0" v-for="error in dataErrors.password">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('password_confirmation', trans('Confirm Password').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::password('password_confirmation', [':class' => "{'form-control':true, 'is-invalid':dataErrors.password }", 'v-model' => 'dataForm.password_confirmation', ':required' => 'dataForm.change_user']) !!}
                        <small>Por favor confirme la contraseña que ingresó.</small>
                    </div>
                </div>
            </div>

            <!-- Email Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::email('email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.email }", 'v-model' => 'dataForm.email', 'required' => true]) !!}
                        <small>Ingrese un correo electrónico válido, ej: xxxxx@gmail.com</small>
                        <div class="invalid-feedback" v-if="dataErrors.email">
                            <p class="m-b-0" v-for="error in dataErrors.email">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('block', trans('Block').':', ['class' => 'col-form-label col-md-3']) !!}
                    <!-- switcher -->
                    <div class="switcher col-md-9 m-t-5">
                        <input type="checkbox" name="block" id="block" v-model="dataForm.block">
                        <label for="block"></label>
                        <small>Si bloquea la cuenta el usuario no podrá ingresar a ninguno de los sistemas.</small>
                    </div>
                </div>
            </div>

            <!-- Url Img Profile Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('url_img_profile', trans('Url Img Profile').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::file('url_img_profile', ['accept' => 'image/*', '@change' => 'inputFile($event, "url_img_profile")', 'required' => false]) !!}
                        <small>Relacione una imagen al perfil de la cuenta.</small>
                    </div>
                </div>
            </div>

            <!-- Sendemail Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('sendEmail', trans('Sendemail').':', ['class' => 'col-form-label col-md-3']) !!}
                    <!-- Sendemail switcher -->
                    <div class="switcher col-md-6 m-t-5">
                        <input type="checkbox" name="sendEmail" id="sendEmail" v-model="dataForm.sendEmail">
                        <label for="sendEmail"></label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Regime Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('regime', trans('Regime').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="regime"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/regime_type"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Address Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('address', trans('Address').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('address', null, ['class' => 'form-control', 'v-model' => 'dataForm.address', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Phone Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('phone', trans('Phone').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('phone', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  State Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="state"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/provider_state"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
				<!-- Warranty End Date Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('contract_start', trans('Fecha de inicio de contrato').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						<input 
                        type="date" 
                        v-model="dataForm.contract_start"
                        class="vc-appearance-none vc-text-base vc-text-gray-800 vc-bg-white vc-border vc-border-gray-400 vc-rounded vc-w-full vc-py-2 vc-px-3 vc-leading-tight focus:vc-outline-none focus:vc-shadow"
                        required
                        />
						<small>@lang('Select the') la @{{ `@lang('Fecha de inicio de contrato')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.contract_start">
							<p class="m-b-0" v-for="error in dataErrors.contract_start">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

            <div class="col-md-6">
				<!-- Warranty End Date Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('contract_end', trans('Fecha final de contrato').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						<input 
                        type="date" 
                        v-model="dataForm.contract_end"
                        class="vc-appearance-none vc-text-base vc-text-gray-800 vc-bg-white vc-border vc-border-gray-400 vc-rounded vc-w-full vc-py-2 vc-px-3 vc-leading-tight focus:vc-outline-none focus:vc-shadow"
                        required
                        />
						<small>@lang('Select the') la @{{ `@lang('Fecha final de contrato')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.contract_end">
							<p class="m-b-0" v-for="error in dataErrors.contract_end">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

          
        </div>
    </div>
	<!-- end panel-body -->
</div>