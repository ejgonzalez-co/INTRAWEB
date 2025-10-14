<div class="panel" data-sortable-id="ui-general-1">
	<!-- begin panel-heading -->
	<div class="panel-heading ui-sortable-handle">
		<h4 class="panel-title"><strong>Categoría</strong></h4>
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <!--  Type Category Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('type_category', trans('Type Category').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="type_category"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/event_category_type"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('Type Category')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.type_category">
                            <p class="m-b-0" v-for="error in dataErrors.type_category">@{{ error }}</p>
                        </div>
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
		<h4 class="panel-title"><strong>Información general</strong></h4>
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <!-- Intranet Type Events Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('intranet_type_events_id', trans('Type Events').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="intranet_type_events_id"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-type-events-condition"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('Type Events')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.intranet_type_events_id">
                            <p class="m-b-0" v-for="error in dataErrors.intranet_type_events_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Title Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('title', trans('Title').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.title }", 'v-model' => 'dataForm.title', 'required' => true]) !!}
                        <small>@lang('Enter the') el  @{{ `@lang('Title')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.title">
                            <p class="m-b-0" v-for="error in dataErrors.title">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Description Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description']) !!}
                        <small>@lang('Enter the') la @{{ `@lang('Description')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.description">
                            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Initial Date Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('initial_date', trans('Initial Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <date-picker
                            :value="dataForm"
                            name-field="initial_date"
                            :min-date="new Date()"
                            :input-props="{required: true}"
                        >
                        </date-picker>
                        <small>@lang('Enter the') la @{{ `@lang('Initial Date')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.initial_date">
                            <p class="m-b-0" v-for="error in dataErrors.initial_date">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Initial Hour Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('initial_hour', trans('Initial Hour').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::time('initial_hour', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.initial_hour }", 'v-model' => 'dataForm.initial_hour', 'required' => true]) !!}
                        <small>@lang('Enter the') la @{{ `@lang('Initial Hour')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.initial_hour">
                            <p class="m-b-0" v-for="error in dataErrors.initial_hour">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  Duration Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('duration', trans('Duration').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="duration"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/duration_event"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') la @{{ `@lang('Duration')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.duration">
                            <p class="m-b-0" v-for="error in dataErrors.duration">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!--  State Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check
                            css-class="form-control"
                            name-field="state"
                            reduce-label="name"
                            reduce-key="id"
                            name-resource="get-constants/event_status"
                            :value="dataForm"
                            :is-required="true">
                        </select-check>
                        <small>@lang('Select the') el @{{ `@lang('State')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.state">
                            <p class="m-b-0" v-for="error in dataErrors.state">@{{ error }}</p>
                        </div>
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
		<h4 class="panel-title"><strong>Participantes</strong></h4>
	</div>
	<!-- end panel-heading -->
	<!-- begin panel-body -->
	<div class="panel-body">
		<div class="row">

            <div class="col-md-12">
                <!--  Work groups Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('work_groups', trans('work_groups').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
                        <add-list-autocomplete  
                            :value="dataForm"
                            name-prop="name"
                            name-field-autocomplete="search_field"
                            name-field="calendar_groups"
                            name-resource="get-work-groups-poll" 
                            name-options-list="work_groups"
                            :name-labels-display="['name']"
                            name-key="id"
                            help="Ingrese el nombre del grupo de trabajo en la caja y seleccione para agregar a la lista"
                            :key="keyRefresh"
                            >
                        </add-list-autocomplete>
						{{-- <add-list-autocomplete  
							:value="dataForm"
							name-prop="name"
							name-field-autocomplete="search_field"
							name-field="calendar_groups"
							name-resource="get-work-groups" 
							name-options-list="intranet_calendar_work_groups"
							:name-labels-display="['name']"
							name-key="id"
							help="Ingrese el nombre del grupo de trabajo en la caja y seleccione para agregar a la lista"
							:key="keyRefresh"
							>
						</add-list-autocomplete> --}}
					</div>
				</div>
            </div>

            <div class="col-md-12">
                <!--  Other officials Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('users', trans('Otros funcionarios').':', ['class' => 'col-form-label col-md-3']) !!}
					<div class="col-md-9">
                        <add-list-autocomplete  
                            :value="dataForm"
                            name-prop="name"
                            name-field-autocomplete="search_field"
                            name-field="calendar_users"
                            name-resource="get-users-poll" 
                            name-options-list="users"
                            :name-labels-display="['users_name']"
                            name-key="id"
                            help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                            :key="keyRefresh"
                            >
                        </add-list-autocomplete>
						{{-- <add-list-autocomplete  
							:value="dataForm"
							name-prop="name"
							name-field-autocomplete="search_field"
							name-field="calendar_users"
							name-resource="get-users" 
							name-options-list="intranet_calendar_users"
							:name-labels-display="['name']"
							name-key="id"
							help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
							:key="keyRefresh"
							>
						</add-list-autocomplete> --}}
					</div>
				</div>
            </div>

            <div class="col-md-12">
                <!--  notification Field -->
				<div class="form-group row m-b-15">
					{!! Form::label('notification', trans('¿Quiere notificar el evento por correo electrónico?').':', ['class' => 'col-form-label col-md-3']) !!}
					<!-- notification switcher -->
					<div class="switcher col-md-9">
						<input type="checkbox" name="notification" id="notification"  v-model="dataForm.notification"  :value="true" :checked="false">
						<label for="notification"></label>
					</div>
				</div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>


