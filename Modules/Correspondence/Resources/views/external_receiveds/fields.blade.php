<ul class="nav nav-tabs" data-tabs="tabs">
	<li class="nav-item active" id="tab-radication">
		<button class="nav-link active" id="radication-button" data-toggle="tab" data-target="#radication" @click="radicatied=false;$refs.rotulo_recibida_fields.limpiarDatos()" type="button" role="tab">
			1. Nueva Radicación
		</button>
	</li>
	<li class="nav-item" id="tab-rotule">
		<button class="nav-link" id="rotule-button" data-toggle="tab" data-target="#rotule" type="button" role="tab" :disabled="!radicatied" :title="!radicatied ? 'Para generar el rótulo, primero debe radicar' : ''" :style="!radicatied ? 'cursor: no-drop;' : 'cursor: pointer;'">
			2. Rótulo <i class="fa fa-stamp"></i>
		</button>
	</li>
</ul>


<div class="tab-content">
	<div class="tab-pane active" id="radication" v-if="!radicatied">
		<div class="panel" data-sortable-id="ui-general-1">
			<!-- begin panel-heading -->
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title"><strong>Datos de origen</strong></h4>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<!-- Citizen Field -->

						<div class="form-group row m-b-15">
							{!! Form::label('citizen_name', trans('Nombre del ciudadano').':', ['class' => 'col-form-label col-md-3 required']) !!}
							<div class="col-md-9">
								<autocomplete
									:is-update="isUpdate"
									{{-- :value-default="dataForm.citizen_id" --}}
									name-prop="name"
									name-field="citizen_id"
									:value='dataForm'
									name-resource="/correspondence/get-citizens-by-name"
									css-class="form-control"
									:name-labels-display="['document_number', 'name']"
									:fields-change-values="['citizen_document:document_number', 'citizen_email:email', 'citizen_name:name','citizen_users_id:user_id']"
									reduce-key="user_id"
									:min-text-input="2"
									:is-required="true"
									:load-more-on-scroll="true"
									name-field-edit="citizen_name">
								</autocomplete>
								<small>Ingrese el nombre del ciudadano y luego seleccione. Por ejemplo: Maria. Si el ciudadano no existe utilice la opción "Nuevo Ciudadano"</small>
								<div class="invalid-feedback" v-if="dataErrors.citizen_name">
									<p class="m-b-0" v-for="error in dataErrors.citizen_name">@{{ error }}</p>
								</div>
							</div>

						</div>

						<div id="ciudadano_personalizado">
							<!-- Nombre Ciudadano Field -->
							<div class="form-group row m-b-15">

								<!-- Documento Ciudadano Field -->
								{!! Form::label('citizen_document', trans('Documento del ciudadano/empresa').':', ['class' => 'col-form-label col-md-3']) !!}
								<div class="col-md-4">
									{!! Form::text('citizen_document', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.citizen_document }", 'v-model' => 'dataForm.citizen_document', 'required' => false]) !!}
									<small>@lang('Enter the') el número de identificación del ciudadano o nit de la empresa.</small>
									<div class="invalid-feedback" v-if="dataErrors.citizen_document">
										<p class="m-b-0" v-for="error in dataErrors.citizen_document">@{{ error }}</p>
									</div>
								</div>

								{!! Form::label('citizen_email', trans('Correo del ciudadano').':', ['class' => 'col-form-label col-md-1']) !!}
								<div class="col-md-4">
									{!! Form::email('citizen_email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.citizen_email }", 'v-model' => 'dataForm.citizen_email', 'required' => false]) !!}
									<small>@lang('Enter the') @{{ `@lang('Email Ciudadano')` | lowercase }}.</small>
									<div class="invalid-feedback" v-if="dataErrors.citizen_email">
										<p class="m-b-0" v-for="error in dataErrors.citizen_email">@{{ error }}</p>
									</div>
								</div>
							</div>
						</div>

						<button class="btn btn-primary" type="button" onclick="jQuery('#crear_ciudadano').toggle(350);"><i class="fa fa-user mr-2" aria-hidden="true"></i>Nuevo ciudadano</button>
						<div id="crear_ciudadano" style="background-color: #40b0a612!important; padding-left: 1px; display: none;">
							<hr />
							<h4>Formulario para crear un ciudadano</h4>
							@include('intranet::citizens.fields_form')
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" @click="callFunctionComponent('received_ref','addCiudadano');"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="panel" data-sortable-id="ui-general-1">
			<!-- begin panel-heading -->
			<div class="panel-heading ui-sortable-handle">
					<h4 class="panel-title"><strong>Datos de destino</strong></h4>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div class="row">

					<div class="col-md-12">
						<!-- Funcionario Field -->
						<div class="form-group row m-b-15">
							{!! Form::label('functionary_id', trans('Funcionario') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
							<div class="col-md-9">

								<autocomplete
									name-prop="fullname"
									name-field="functionary_id"
									:value='dataForm'
									name-resource="/correspondence/get-only-users"
									css-class="form-control"
									:name-labels-display="['fullname']"
									reduce-key="id"
									:is-required="true"
									name-field-edit="functionary_name"
									:fields-change-values="['dependency_id:id_dependencia']"
									>
								</autocomplete>

                                <small>Ingrese el nombre del funcionario y luego seleccione. Por ejemplo: Camilo.</small>
								<div class="invalid-feedback" v-if="dataErrors.functionary_id">
									<p class="m-b-0" v-for="error in dataErrors.functionary_id">
										@{{ error }}
									</p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<!-- Funcionario Field -->
						<div class="form-group row m-b-15">
							{!! Form::label('dependency_id', 'Dependencia:', ['class' => 'col-form-label col-md-3 required']) !!}
							<div class="col-md-9">

									<select-check
										css-class="form-control"
										name-field="dependency_id"
										reduce-label="nombre"
										reduce-key="id"
										name-resource="/intranet/get-dependencies"
										:value="dataForm"
										:is-required="true"
										:enable-search="true"
										name-field-object="dependencia_informacion"
										ref-select-check="dependencia_ref"
										>
									</select-check>


                                <small>Seleccione una dependencia.</small>
								<div class="invalid-feedback" v-if="dataErrors.functionary_id">
									<p class="m-b-0" v-for="error in dataErrors.functionary_id">
										@{{ error }}
									</p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<!--  Other officials Field destination-->
						<div class="form-group row m-b-15">
							{!! Form::label('share_users', 'Enviar copia a:', ['class' => 'col-form-label col-md-3']) !!}
							<div class="col-md-9">

								<add-list-autocomplete :value="dataForm" name-prop="nameFalse"
									name-field-autocomplete="recipient_autocomplete" name-field="copies_users"
									name-resource="/correspondence/get-only-users"
									name-options-list="external_copy" :name-labels-display="['fullname']" name-key="users_id"
									help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
									>
								</add-list-autocomplete>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="panel" data-sortable-id="ui-general-1">
			<!-- begin panel-heading -->
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title"><strong>Datos generales de la correspondencia</strong></h4>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div class="row">

					<!-- Type Document Field -->
					<div class="form-group row m-b-15 col-md-12">
						{!! Form::label('type_documentary_id', trans('Type Document').':', ['class' => 'col-form-label col-md-3 required']) !!}
						<div class="col-md-9">
							<div style="display: flex;">
								<select-check
									css-class="form-control"
									name-field="type_documentary_id"
									reduce-label="name"
									reduce-key="id"
									name-resource="get-types-documentaries-actives"
									:value="dataForm"
									:key="keyRefresh"
									:is-required="true" :enable-search="true">
								</select-check>
								<a href="#" class="fa fa-sync-alt" @click="_updateKeyRefresh();" style="margin: auto; margin-left: 10px;"></a>
							</div>
							<small>Seleccione el tipo de documento. Estos tipos de documentos son configurados desde la opción <a href="{{ route('types-documentaries.index') }}" target="_blank">Tipos documentales Recibida</a></small>
							<div class="invalid-feedback" v-if="dataErrors.type_documentary_id">
								<p class="m-b-0" v-for="error in dataErrors.type_documentary_id">
									@{{ error }}
								</p>
							</div>
						</div>
					</div>

					<div class="form-group row m-b-15 col-sm-12">
						{!! Form::label('issue', 'Asunto:', ['class' => 'col-form-label col-md-3 required']) !!}
						<div class="col-md-9">
							{!! Form::textarea('issue', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.issue }", 'v-model' => 'dataForm.issue', 'required' => true, 'rows' => '3']) !!}
							<small>@lang('Enter the') el asunto.</small>
							<div class="invalid-feedback" v-if="dataErrors.issue">
								<p class="m-b-0" v-for="error in dataErrors.issue">
									@{{ error }}
								</p>
							</div>
						</div>
					</div>

					<div class="form-group row m-b-15 col-sm-12">

						{!! Form::label('channel', trans('Canal') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
						<div class="col-md-4">
							{{-- <select-check
								css-class="form-control"
								name-field="channel"
								reduce-label="name"
								reduce-key="id"
								name-resource="get-constants/external_received_channels"
								:value="dataForm"
								:is-required="true">
							</select-check> --}}

							<select name="channel" required="required" class="form-control" v-model="dataForm.channel">
								<option value="7">Buzón de sugerencias</option>
								<option value="1">Correo certificado</option>
								<option value="2">Correo electrónico</option>
								<option value="3">Fax</option>
								<option value="4">Personal</option>
								<option value="5">Telefónico</option>
								<option value="6">Web</option>
								<option value="8">Verbal</option>
							</select>

							<small>@lang('Enter the') @{{ `@lang('Canal')` | lowercase }}</small>

						</div>
						{!! Form::label('folio', trans('Folios') . ':', ['class' => 'col-form-label col-md-1 required']) !!}
						<div class="col-md-4">
							{!! Form::text('folio', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.folio }", 'v-model' => 'dataForm.folio', 'required' => true]) !!}
							<small>@lang('Enter the') @{{ `@lang('Folios')` | lowercase }}</small>

						</div>
{{--
						{!! Form::label('annexed', trans('Anexos') . ':', ['class' => 'col-form-label col-md-1 required']) !!}
						<div class="col-md-4">
							{!! Form::text('annexed', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexed }", 'v-model' => 'dataForm.annexed', 'required' => true]) !!}
							<small>@lang('Enter the') @{{ `@lang('Anexos')` | lowercase }}</small>
							<div class="invalid-feedback" v-if="dataErrors.annexed">
								<p class="m-b-0" v-for="error in dataErrors.annexed">
									@{{ error }}
								</p>
							</div>
						</div> --}}
					</div>



					<div class="form-group row m-b-15 col-sm-12">
						{!! Form::label('state', trans('State') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
						<div class="col-md-9">
							{{-- <select-check
								css-class="form-control"
								name-field="state"
								reduce-label="name"
								reduce-key="id"
								name-resource="get-constants/external_received_states_two"
								:value="dataForm"
								:is-required="true">
							</select-check> --}}
							<select name="state" required="required" class="form-control"  v-model="dataForm.state">
								<option value="1">Devuelto</option>
								<option selected="selected" value="3">Público</option>
							</select>

							<small>@lang('Select the') el @{{ `@lang('State')` | lowercase }}</small>
							<div class="invalid-feedback" v-if="dataErrors.state">
								<p class="m-b-0" v-for="error in dataErrors.state">
									@{{ error }}
								</p>
							</div>
						</div>
					</div>

					<div class="form-group row m-b-15 col-sm-12">
						{!! Form::label('physical_address', 'Dirección física:', ['class' => 'col-form-label col-md-3']) !!}
						<div class="col-md-9">
							{!! Form::text('physical_address', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.physical_address }", 'v-model' => 'dataForm.physical_address', 'required' => false, 'rows' => '3']) !!}
							<small>Ingrese la dirección física.</small>
							<div class="invalid-feedback" v-if="dataErrors.physical_address">
								<p class="m-b-0" v-for="error in dataErrors.physical_address">
									@{{ error }}
								</p>
							</div>
						</div>
					</div>					

					<div class="form-group row m-b-15 col-sm-12">
						{!! Form::label('novelty', 'Novedad:', ['class' => 'col-form-label col-md-3']) !!}
						<div class="col-md-9">
							{!! Form::textarea('novelty', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.novelty }", 'v-model' => 'dataForm.novelty', 'required' => false, 'rows' => '3']) !!}
							<small>@lang('Enter the') @{{ `@lang('Novedad')` | lowercase }}.</small>
							<div class="invalid-feedback" v-if="dataErrors.novelty">
								<p class="m-b-0" v-for="error in dataErrors.novelty">
									@{{ error }}
								</p>
							</div>
						</div>
					</div>

					<div class="form-group row m-b-15 col-sm-12">
						{!! Form::label('observation', 'Observación:', ['class' => 'col-form-label col-md-3']) !!}
						<div class="col-md-9">
							{!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => false, 'rows' => '3']) !!}
							<small>@lang('Enter the') @{{ `@lang('Observación')` | lowercase }}.</small>
							<div class="invalid-feedback" v-if="dataErrors.observation">
								<p class="m-b-0" v-for="error in dataErrors.observation">
									@{{ error }}
								</p>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>

		<div class="panel" data-sortable-id="ui-general-1" >
			<!-- begin panel-heading -->
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title"><strong>Datos generales del PQR</strong></h4>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div class="row">

					<div class="form-group row m-b-15 col-sm-12" v-if="!dataForm.pqr">
						<div class="col-md-9">

							<div class="form-group row m-b-15">
								{!! Form::label('npqr', trans('Nuevo PQR').':', ['class' => 'col-form-label col-md-4']) !!}
								<!-- switcher -->
								<div class="switcher col-md-8 m-t-5">
									<input type="checkbox" name="npqr" id="npqr" v-model="dataForm.npqr">
									<label for="npqr"></label>
									<small>Chequee si desea generar un nuevo PQR. Por defecto el PQR será creado en estado "Abierto"</small>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group row m-b-15 col-sm-12" v-else>
						<div class="col-md-9">

							<div class="form-group row m-b-15">
								<label class="col-form-label col-md-4 text-black-transparent-7" v-if="dataForm.pqr"><strong>No. radicado del PQRS:</strong></label>
								<label class="col-form-label col-md-8">  <a class="col-9 text-truncate" :href="'{{ url('/') }}/pqrs/p-q-r-s?qder='+dataForm.recibida_pqr_encrypted_id.pqr_id">@{{ dataForm.pqr }}</a> </label>
							</div>
						</div>
					</div>

					{{-- <div class="form-group row m-b-15 col-sm-12" v-if="dataForm.npqr || dataForm.npqr == 1">
						<div class="col-md-9">

							<div class="form-group row m-b-15">
								{!! Form::label('auto_assign','Asignar PQR:', ['class' => 'col-form-label col-md-4']) !!}
								<!-- switcher -->
								<div class="switcher col-md-8 m-t-5">
									<input type="checkbox" name="auto_assign" id="auto_assign" v-model="dataForm.auto_assign">
									<label for="auto_assign"></label>
									<small>Chequee si desea asignar el PQR a un funcionario y tiempo de respuesta al PQR.</small>
								</div>
							</div>
						</div>
					</div> --}}

					{{-- Datos del pqr --}}


					<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.npqr && !dataForm.pqr">

						<!-- begin panel-heading -->
						<div class="panel-heading ui-sortable-handle">
							<h4 class="panel-title"><strong>Tiempos de respuesta (opcional)</strong></h4>
						</div>
						<!-- Funcionario Destinatario Field -->
						<div class="form-group row m-b-15 col-sm-12" v-if="dataForm.auto_assign">
							{!! Form::label('funcionario_users_id', trans('Funcionario Destinatario del PQR').':', ['class' => 'col-form-label col-md-3 required']) !!}
							<div class="col-md-9">
								<autocomplete
									name-prop="name"
									name-field="funcionario_users_id"
									:value='dataForm'
									name-resource="/intranet/get-users"
									css-class="form-control"
									:is-required="true"
									:name-labels-display="['name', 'email']"
									reduce-key="id"

									:fields-change-values="['funcionario_destinatario:name']"
									name-field-edit="funcionario_destinatario">
								</autocomplete>
								<small>@lang('Enter the') el funcionario destinatario</small>
								<div class="invalid-feedback" v-if="dataErrors.funcionario_users_id">
									<p class="m-b-0" v-for="error in dataErrors.funcionario_users_id">@{{ error }}</p>
								</div>
							</div>
						</div>


						<!-- end panel-heading -->
						<!-- begin panel-body -->
						<div class="panel-body">
							<!-- Pqr Eje Tematico Id Field -->
							<div class="form-group row m-b-15">
								{!! Form::label('pqr_eje_tematico_id', trans('Eje temático').':', ['class' => 'col-form-label col-md-3']) !!}
								<div class="col-md-9">
									<autocomplete
										name-prop="nombre"
										name-field="pqr_eje_tematico_id"
										:value='dataForm'
										name-resource="/pqrs/get-p-q-r-eje-tematicos-radicacion"
										css-class="form-control"
										:name-labels-display="['nombre', 'plazo', 'plazo_unidad']"
										:fields-change-values="['nombre_ejetematico:nombre', 'tipo_plazo:tipo_plazo', 'plazo:plazo', 'temprana:temprana']"
										reduce-key="id"
										:is-required="false"
										name-field-edit="nombre_ejetematico">
									</autocomplete>
									<small>Ingrese el nombre del eje temático y luego seleccionelo. Si no existe complete los demás campos del eje temático</small>
									<div class="invalid-feedback" v-if="dataErrors.pqr_eje_tematico_id">
										<p class="m-b-0" v-for="error in dataErrors.pqr_eje_tematico_id">@{{ error }}</p>
									</div>
								</div>
							</div>

							<div class="form-group row m-b-15">
								<!-- Tipo Plazo Field -->
								{!! Form::label('tipo_plazo', trans('Tipo de plazo').':', ['class' => 'col-form-label col-md-3']) !!}
								<div class="col-md-3">
									{!! Form::select('tipo_plazo', ["Laboral" => "Laboral", "Calendario" => "Calendario"], 'Laboral', [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_plazo }", 'v-model' => 'dataForm.tipo_plazo', 'required' => false]) !!}
									<small>@lang('Select the') el tipo de plazo</small>
									<div class="invalid-feedback" v-if="dataErrors.tipo_plazo">
										<p class="m-b-0" v-for="error in dataErrors.tipo_plazo">@{{ error }}</p>
									</div>
								</div>
								<!-- Plazo Field -->
								{!! Form::label('plazo', trans('Plazo').':', ['class' => 'col-form-label col-md-2']) !!}
								<div class="col-md-4">
									{!! Form::number('plazo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.plazo }", 'v-model' => 'dataForm.plazo', 'required' => false]) !!}
									<small>@lang('Enter the') el plazo</small>
									<div class="invalid-feedback" v-if="dataErrors.plazo">
										<p class="m-b-0" v-for="error in dataErrors.plazo">@{{ error }}</p>
									</div>
								</div>
							</div>
							<div class="form-group row m-b-15">
								<!-- Temprana Field -->
								{!! Form::label('temprana', trans('Alerta temprana').':', ['class' => 'col-form-label col-md-3']) !!}
								<div class="col-md-9">
									{!! Form::number('temprana', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.temprana }", 'v-model' => 'dataForm.temprana', 'required' => false]) !!}
									<small>@lang('Enter the') el tiempo en dias que deben transcurrir para que empiecen a generarse alertas antes que se venza el plazo, el tiempo de alerta temprana debe ser menor o igual al plazo</small>
									<div class="invalid-feedback" v-if="dataErrors.temprana">
										<p class="m-b-0" v-for="error in dataErrors.temprana">@{{ error }}</p>
									</div>
								</div>
							</div>

							<!-- Pqr Tipo Solicitud Id Field -->
							<div class="form-group row m-b-15">
								{!! Form::label('pqr_tipo_solicitud_id', trans('Tipo de PQR').':', ['class' => 'col-form-label col-md-3 required']) !!}
								<div class="col-md-9">
									<select-check
										css-class="form-control"
										name-field="pqr_tipo_solicitud_id"
										reduce-label="nombre"
										reduce-key="id"
										name-resource="/pqrs/get-p-q-r-tipo-solicituds-radicacion"
										:value="dataForm"
										:is-required="true">
									</select-check>
									<small>@lang('Select the') el tipo de PQR</small>
									<div class="invalid-feedback" v-if="dataErrors.pqr_tipo_solicitud_id">
										<p class="m-b-0" v-for="error in dataErrors.pqr_tipo_solicitud_id">@{{ error }}</p>
									</div>
								</div>
							</div>

							<div class="form-group row m-b-15">
								{!! Form::label('respuesta_correo', 'Autorizo recibir la respuesta por correo electrónico:', ['class' => 'col-form-label col-md-3']) !!}
								<!-- switcher -->
								<div class="switcher col-md-9 m-t-5">
									<input type="checkbox" name="respuesta_correo" id="respuesta_correo" v-model="dataForm.respuesta_correo">
									<label for="respuesta_correo"></label>
									<small>El campo "Autorizo recibir la respuesta por correo electrónico" se utiliza únicamente con fines informativo.</small>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>


		<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.document_pdf">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Documento principal de la correspondencia</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('url', 'Documento de la correspondencia radicada:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <input-file :file-name-real="true":value="dataForm" name-field="document_pdf" :max-files="30"
                                    :max-filesize="11" file-path="public/container/external_{{ date('Y') }}"
                                    message="Arrastre o seleccione los archivos" help-text="Utilice este campo para cargar una copia electrónica de una carta u otro documento recibido\. El tamaño máximo permitido es de 10 MB."
                                    :is-update="isUpdate"  ruta-delete-update="correspondence/internals-delete-file" :id-file-delete="dataForm.id">
                                </input-file>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end panel-body -->
        </div>
		<div class="panel" data-sortable-id="ui-general-1">
			<!-- begin panel-heading -->
			<div class="panel-heading ui-sortable-handle">
				 <h4 class="panel-title"><strong>Adjuntar anexos</strong></h4>
			</div>
			<!-- end panel-heading -->
			<!-- begin panel-body -->
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<!--  Other officials Field destination-->
						<div class="form-group row m-b-15">
								{!! Form::label('url', 'Lista de archivos anexos:', ['class' => 'col-form-label col-md-3']) !!}
								<div class="col-md-9">

									<input-file :file-name-real="true" :value="dataForm" name-field="attached_document" :max-files="30"
                                      :max-filesize="11" file-path="public/container/external_received_{{ date('Y') }}"
                                      message="Arrastre o seleccione los archivos" help-text="Lista de archivos anexos. El tamaño máximo permitido es de 10 MB."
                                      :is-update="isUpdate"  ruta-delete-update="correspondence/external_received-delete-file" :id-file-delete="dataForm.id">
                                    </input-file>

								</div>
						</div>
						<div class="col-md-12">
							<!-- Annexes Description Field -->
							<div class="form-group row m-b-15">
								{!! Form::label('annexed', 'Descripción de anexos:', ['class' => 'col-form-label col-md-3']) !!}
								<div class="col-md-9">
									{!! Form::textarea('annexed', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexed }", 'v-model' => 'dataForm.annexed', 'required' => false, 'rows' => '3']) !!}
									<small>Ingrese una descripción de los anexos</small>
									<div class="invalid-feedback" v-if="dataErrors.annexed">
										<p class="m-b-0" v-for="error in dataErrors.annexed">
											@{{ error }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				 </div>
			</div>
			<!-- end panel-body -->
	  </div>

        {{-- Clasificacion documental --}}
        <div class="panel" data-sortable-id="ui-general-1" id="clasificacion">
            @include('correspondence::internals.field_clasificacion_documental')
        </div>


   </div>

	<div class="tab-pane" id="rotule" v-show="radicatied">

		<rotule-component
			execute-url-axios-preview="document-preview-external-received/"
			execute-url-axios-rotular="document-with-rotule-external-received/"
			execute-url-save="update-received-rotule"
			:update-props="{{ Auth::user()->hasRole('Correspondencia Recibida Admin') ? 'true' : 'false' }}"
			ref="rotulo_recibida_fields"
			type-call="rotule_fields"
			name='{{ config('app.name') }}'>
		</rotule-component>

		{{-- @include('correspondence::external_receiveds.rotule') --}}
  	</div>
</div>
