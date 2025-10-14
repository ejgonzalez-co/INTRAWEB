<div class="panel" data-sortable-id="ui-general-1">
	<!-- begin panel-heading -->
	<div class="panel-heading ui-sortable-handle">
		<h4 class="panel-title"><strong>Datos del mantenimiento</strong></h4>
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
		<div class="row">

			<div class="col-md-12">                  
				<!--  Type Maintenance Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('type_maintenance', trans('Type Maintenance').':', ['class' => 'col-form-label col-md-3 required']) !!}
					<div class="col-md-9">
						<select-check
							css-class="form-control"
							name-field="type_maintenance"
							reduce-label="name"
							reduce-key="id"
							name-resource="/help-table/get-constants/type_maintenance_tic"
							:value="dataForm"
							:is-required="true">
						</select-check>
						<small>@lang('Select the') @{{ `@lang('Type Maintenance')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.type_maintenance">
							<p class="m-b-0" v-for="error in dataErrors.type_maintenance">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<!-- Fault Description Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('fault_description', trans('Fault Description').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						{!! Form::textarea('fault_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fault_description }", 'v-model' => 'dataForm.fault_description']) !!}
						<small>Ingrese la descripción de la falla o daño.</small>
						<div class="invalid-feedback" v-if="dataErrors.fault_description">
							<p class="m-b-0" v-for="error in dataErrors.fault_description">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<!-- Service Start Date Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('service_start_date', trans('Service Start Date').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-3">
						<date-picker
							:value="dataForm"
							name-field="service_start_date"
						>
						</date-picker>
						<small>@lang('Select the') la @{{ `@lang('Service Start Date')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.service_start_date">
							<p class="m-b-0" v-for="error in dataErrors.service_start_date">@{{ error }}</p>
						</div>
					</div>
					{!! Form::label('end_date_service', trans('End Date Service').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-3">
						<date-picker
							:value="dataForm"
							name-field="end_date_service"
						>
						</date-picker>
						<small>@lang('Select the') la @{{ `@lang('End Date Service')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.end_date_service">
							<p class="m-b-0" v-for="error in dataErrors.end_date_service">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<!-- Maintenance Description Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('maintenance_description', trans('Maintenance Description').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						{!! Form::textarea('maintenance_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.maintenance_description }", 'v-model' => 'dataForm.maintenance_description']) !!}
						<small>Ingrese la descripción del mantenimiento.</small>
						<div class="invalid-feedback" v-if="dataErrors.maintenance_description">
							<p class="m-b-0" v-for="error in dataErrors.maintenance_description">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<!-- Contract Number Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('contract_number', trans('Contract Number').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						{!! Form::text('contract_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }", 'v-model' => 'dataForm.contract_number']) !!}
						<small>@lang('Enter the') el @{{ `@lang('Contract Number')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.contract_number">
							<p class="m-b-0" v-for="error in dataErrors.contract_number">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<!-- Cost Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('cost', trans('Cost').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						{!! Form::number('cost', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cost }", 'v-model' => 'dataForm.cost']) !!}
						<small>@lang('Enter the') el @{{ `@lang('Cost')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.cost">
							<p class="m-b-0" v-for="error in dataErrors.cost">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<!-- Warranty Start Date Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('warranty_start_date', trans('Warranty Start Date').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						<date-picker
							:value="dataForm"
							name-field="warranty_start_date"
						>
						</date-picker>
						<small>@lang('Select the') la @{{ `@lang('Warranty Start Date')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.warranty_start_date">
							<p class="m-b-0" v-for="error in dataErrors.warranty_start_date">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<!-- Warranty End Date Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('warranty_end_date', trans('Warranty End Date').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						<date-picker
							:value="dataForm"
							name-field="warranty_end_date"
						>
						</date-picker>
						<small>@lang('Select the') la @{{ `@lang('Warranty End Date')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.warranty_end_date">
							<p class="m-b-0" v-for="error in dataErrors.warranty_end_date">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<!-- Dependencias Id Field -->
				<div class="form-group row m-b-15">
				{!! Form::label('dependencias_id', trans('Dependency').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						<select-check
							css-class="form-control"
							name-field="dependencias_id"
							reduce-label="nombre"
							reduce-key="id"
							name-resource="/intranet/get-dependencies"
							:value="dataForm"
						>
						</select-check>
						<small>@lang('Select the') la @{{ `@lang('Dependencia')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.dependencias_id">
							<p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<!--  Maintenance Status Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('maintenance_status', trans('Maintenance Status').':', ['class' => 'col-form-label col-md-3 required']) !!}
					<div class="col-md-9">
						<select-check
							css-class="form-control"
							name-field="maintenance_status"
							reduce-label="name"
							reduce-key="id"
							name-resource="/help-table/get-constants/maintenance_status_tic"
							:value="dataForm"
							:is-required="true">
						</select-check>
						<small>@lang('Select the') @{{ `@lang('Maintenance Status')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.maintenance_status">
							<p class="m-b-0" v-for="error in dataErrors.maintenance_status">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div>             

			<div class="col-md-6">
				<!-- Request Type Id Field -->
				<div class="form-group row m-b-15">
				   {!! Form::label('assigned_user_name', trans('Proveedor').':', ['class' => 'col-form-label col-md-3']) !!}
				   <div class="col-md-9">
					  <span class="form-control-plaintext">@{{ dataForm.assigned_user_name? dataForm.assigned_user_name: '' }}</span>
				   </div>
				</div>
			 </div>

			{{-- <div class="col-md-6">
				<!-- Ht Tic Requests Id Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('ht_tic_requests_id', trans('Request').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
						<select-check
							css-class="form-control"
							name-field="ht_tic_requests_id"
							reduce-label="affair"
							reduce-key="id"
							name-resource="get-tic-requests"
							:value="dataForm"
							:enable-search="true"
						>
						</select-check>
						<small>@lang('Select the') la @{{ `@lang('Request')` | lowercase }}</small>
						<div class="invalid-feedback" v-if="dataErrors.ht_tic_requests_id">
							<p class="m-b-0" v-for="error in dataErrors.ht_tic_requests_id">@{{ error }}</p>
						</div>
					</div>
				</div>
			</div> --}}
		</div>
	</div>
	<!-- end panel-body -->
</div>
