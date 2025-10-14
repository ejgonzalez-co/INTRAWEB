<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general de la solicitud: @{{ dataShow.id }}</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- Priority Request Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Priority Request'):</dt>
                <dd>@{{ dataShow.priority_request_name || '-' }}</dd>
            </div>

            <!-- Affair Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Affair'):</dt>
                <dd>@{{ dataShow.affair || '-' }}</dd>
            </div>

            <!-- Assignment Date Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Assignment Date'):</dt>
                <dd>@{{ dataShow.assignment_date || '-' }}</dd>
            </div>

            <!-- Prox Date To Expire Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Prox Date To Expire'):</dt>
                <dd>@{{ dataShow.prox_date_to_expire || '-' }}</dd>
            </div>

            <!-- Expiration Date Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Expiration Date'):</dt>
                <dd>@{{ dataShow.expiration_date || '-' }}</dd>
            </div>

            <!-- Date Attention Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Date Attention'):</dt>
                <dd>@{{ dataShow.date_attention || '-' }}</dd>
            </div>

            <!-- Closing Date Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Closing Date'):</dt>
                <dd>@{{ dataShow.closing_date || '-' }}</dd>
            </div>

         <!-- Closing Date Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">Acceso remoto: </dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.acceso_remoto == 1 ? 'Si' : 'No' }}</dd>

                           <!-- Closing Date Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">Código conexión:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{  dataShow.codigo_conexion ?? 'N/A' }}</dd>

         <!-- Closing Date Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">Clave conexión:</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.clave_conexion ?? 'N/A' }}</dd>


         <!-- Description Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Description'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9" style="white-space: break-spaces;">@{{ dataShow.description }}</dd>

            <!-- Ubicación Field -->
            {{-- <div class="col-12 col-md-6">
            <dt class="text-inverse text-left">@lang('Ubicación'):</dt>
            <dd>@{{ dataShow.location || '-' }}</dd>
         </div> --}}

            <!-- Tracing Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Tracing'):</dt>
                <dd v-html="dataShow.tracing || '-'"></dd>
            </div>

            <!-- Ht Tic Request Status Id Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Request Status'):</dt>
                <dd>@{{ dataShow.request_status || '-' }}</dd>
            </div>

            <!-- Ht Tic Type Request Id Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Request Type'):</dt>
                <dd>@{{ dataShow.tic_type_request ? dataShow.tic_type_request.name : '-' }}</dd>
            </div>

            <!-- Assigned By Id Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Assigned By'):</dt>
                <dd>@{{ dataShow.assigned_by_name ? dataShow.assigned_by_name : (dataShow.assigned_by ? dataShow.assigned_by.name : '-') }}</dd>
            </div>

            <!-- Users Id Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('User'):</dt>
                <dd>@{{ dataShow.users_name ? dataShow.users_name : (dataShow.users ? dataShow.users.name : '-') }}</dd>
            </div>

            <!-- Assigned User Id Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Usuario asignado'):</dt>
                <dd>@{{ dataShow.assigned_user_name ? dataShow.assigned_user_name : (dataShow.assigned_user ? dataShow.assigned_user.name : '-') }}</dd>
            </div>

            <!-- Nombre de la sede -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Sede'):</dt>
                <dd>@{{ dataShow.sede_tic_request ? dataShow.sede_tic_request.name : 'Ninguna' }}</dd>
            </div>

            <!-- Dependencia -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Dependencia'):</dt>
                <dd>@{{ dataShow.dependencia_tic_request ? dataShow.dependencia_tic_request.name : 'Ninguna' }}</dd>
            </div>

            <!-- Ht Tic categoria Request Id Field -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Category'):</dt>
                <dd>@{{ dataShow.tic_type_tic_categories ? dataShow.tic_type_tic_categories.name : '-' }}</dd>
            </div>

            <!-- Type -->
            <div class="col-12 col-md-6">
                <dt class="text-inverse text-left">@lang('Type'):</dt>
                <dd>@{{ dataShow.tic_type_assets ? dataShow.tic_type_assets.name : '-' }}</dd>
            </div>

            <!-- Description Field -->
            <div class="col-12">
                <dt class="text-inverse text-left">@lang('Description'):</dt>
                <dd style="white-space: break-spaces;">@{{ dataShow.description || '-' }}</dd>
            </div>

            <!-- Document pdf Field -->
            <div class="col-12">
                <dt class="text-inverse text-left">Archivos:</dt>
                <dd v-if="dataShow.url_documents && dataShow.url_documents.length > 0">
                    <viewer-attachement :link-file-name="true" v-if="dataShow.url_documents"
                        :list="dataShow.url_documents"></viewer-attachement>
                </dd>
                <dd v-else>
                    <span>No tiene adjunto</span>
                </dd>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>
<div class="panel" data-sortable-id="ui-general-1"
    v-if="dataShow.tic_maintenances && dataShow.tic_maintenances.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos del mantenimiento</strong></h4>
    </div>
    <!-- end panel-heading -->

    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Tipo de Mantenimiento -->
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Type Maintenance') }}:</strong>
                    <span class="col-md-7">@{{ dataShow.tic_maintenances[0]?.type_maintenance_name || '-' }}</span>
                </div>
            </div>

            <!-- Estado del Mantenimiento -->
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Maintenance Status') }}:</strong>
                    <span class="col-md-7">@{{ dataShow.tic_maintenances[0].maintenance_status_name || '-' }}</span>
                </div>
            </div>
            <!-- Proveedor -->
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Proveedor') }}:</strong>
                    <span class="col-md-7">@{{ dataShow.assigned_user_name || '-' }}</span>
                </div>
            </div>


            <!-- Fechas de Servicio -->
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Service Start Date') }}:</strong>
                    <span class="col-md-7">
                        @{{ dataShow.tic_maintenances[0]?.service_start_date ? formatDate(dataShow.tic_maintenances[0].service_start_date) : '-' }}
                    </span>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('End Date Service') }}:</strong>
                    <span class="col-md-7">@{{ formatDate(dataShow.tic_maintenances[0]?.end_date_service) || '-' }}</span>
                </div>
            </div>

            <!-- Dependencia -->
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Dependency') }}:</strong>
                    <span class="col-md-7">@{{ dataShow.tic_maintenances[0]?.dependencias?.nombre || '-' }}</span>
                </div>
            </div>


            <!-- Información de Contrato -->
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Contract Number') }}:</strong>
                    <span class="col-md-7">@{{ dataShow.tic_maintenances[0]?.contract_number || '-' }}</span>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Cost') }}:</strong>
                    <span class="col-md-7">@{{ dataShow.tic_maintenances[0]?.cost ? '$' + dataShow.tic_maintenances[0]?.cost : '-' }}</span>
                </div>
            </div>

            <!-- Información de Garantía -->
            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Warranty Start Date') }}:</strong>
                    <span class="col-md-7">@{{ formatDate(dataShow.tic_maintenances[0]?.warranty_start_date) || '-' }}</span>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="d-flex">
                    <strong class="col-md-5">{{ trans('Warranty End Date') }}:</strong>
                    <span class="col-md-7">@{{ formatDate(dataShow.tic_maintenances[0]?.warranty_end_date) || '-' }}</span>
                </div>
            </div>


            <!-- Descripción de la Falla -->
            <div class="col-12 mb-3">
                <div class="d-flex">
                    <strong class="col-md-2">{{ trans('Fault Description') }}:</strong>
                    <span class="col-md-10" style="white-space: pre-line">@{{ dataShow.tic_maintenances[0]?.fault_description || '-' }}</span>
                </div>
            </div>
            <!-- Descripción del Mantenimiento -->
            <div class="col-12 mb-3">
                <div class="d-flex">
                    <strong class="col-md-2">{{ trans('Maintenance Description') }}:</strong>
                    <span class="col-md-10" style="white-space: pre-line">@{{ dataShow.tic_maintenances[0]?.maintenance_description || '-' }}</span>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>
<div class="panel" v-if="dataShow.tic_maintenances && dataShow.tic_maintenances.length > 0"
    data-sortable-id="ui-general-1">
    <div class="panel-heading">
        <div class="panel-title"><strong>Lista de chequeo mantenimiento</strong></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="40%"><strong>Mantenimiento</strong></th>
                        <th width="20%"><strong>Realizado</strong></th>
                        <th width="40%"><strong>Observación</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Has Internal And External Hardware Cleaning -->
                    <tr>
                        <td>{{ trans('Has Internal And External Hardware Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_internal_and_external_hardware_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_internal_and_external_hardware_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Ram Cleaning -->
                    <tr>
                        <td>{{ trans('Has Ram Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_ram_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_ram_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Board Memory Cleaning -->
                    <tr>
                        <td>{{ trans('Has Board Memory Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_board_memory_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_board_memory_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Power Supply Cleaning -->
                    <tr>
                        <td>{{ trans('Has Power Supply Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_power_supply_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_power_supply_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Dvd Drive Cleaning -->
                    <tr>
                        <td>{{ trans('Has Dvd Drive Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_dvd_drive_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_dvd_drive_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Monitor Cleaning -->
                    <tr>
                        <td>{{ trans('Has Monitor Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_monitor_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_monitor_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Keyboard Cleaning -->
                    <tr>
                        <td>{{ trans('Has Keyboard Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_keyboard_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_keyboard_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Mouse Cleaning -->
                    <tr>
                        <td>{{ trans('Has Mouse Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_mouse_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_mouse_cleaning || '-' }}</td>
                    </tr>

                    <!-- Has Thermal Paste Change -->
                    <tr>
                        <td>{{ trans('Has Thermal Paste Change') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_thermal_paste_change || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_thermal_paste_change || '-' }}</td>
                    </tr>

                    <!-- Has Heatsink Cleaning -->
                    <tr>
                        <td>{{ trans('Has Heatsink Cleaning') }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.has_heatsink_cleaning || '-' }}</td>
                        <td>@{{ dataShow.tic_maintenances[0]?.observation_heatsink_cleaning || '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Technical Report -->
        <div class="form-group row m-b-15">
            <label class="col-form-label col-md-3">{{ trans('Technical Report') }}:</label>
            <div class="col-md-8">
                <p class="form-control-static">@{{ dataShow.tic_maintenances[0]?.technical_report || '-' }}</p>
            </div>
        </div>

        <!-- General Observation -->
        <div class="form-group row m-b-15">
            <label class="col-form-label col-md-3">{{ trans('Observation') }}:</label>
            <div class="col-md-8">
                <p class="form-control-static">@{{ dataShow.tic_maintenances[0]?.observation || '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div v-if="dataShow.tic_request_histories? dataShow.tic_request_histories.length > 0 : ''" class="panel"
    data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Historial de la solicitud:</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <table class="table table-hover m-b-0">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>@lang('Created_at')</th>
                    <th>@lang('User')</th>
                    <!-- <th>@lang('Priority Request')</th> -->
                    <!-- <th>@lang('Affair')</th> -->
                    <th>@lang('Expiration Date')</th>
                    <th>@lang('Date Attention')</th>
                    <th>@lang('Tracing')</th>
                    <th>@lang('State')</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(ticRequestHistory, key) in dataShow.tic_request_histories" :key="key">
                    <!-- <td>@{{ ticRequestHistory.id }}</td> -->
                    <td>@{{ ticRequestHistory.created_at }}</td>
                    <td>@{{ ticRequestHistory.users_name ? ticRequestHistory.users_name : (ticRequestHistory.users ? ticRequestHistory.users.name : '') }}</td>
                    <!-- <td>@{{ ticRequestHistory.priority_request ? ticRequestHistory.priority_request_name : '' }}</td> -->
                    <!-- <td>@{{ ticRequestHistory.affair }}</td> -->
                    <td>@{{ ticRequestHistory.expiration_date }}</td>
                    <td>@{{ ticRequestHistory.date_attention }}</td>
                    <td class="historial-html" v-html="ticRequestHistory.tracing"></td>
                    <td>
                        <div class="text-white text-center p-2"
                            :style="'background-color:' + ticRequestHistory.status_color"
                            v-html="ticRequestHistory.status_name"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- end panel-body -->
</div>


<div v-if="dataShow.tic_satisfaction_polls? dataShow.tic_satisfaction_polls.length > 0 : ''" class="panel"
    data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Encuesta de satisfactión:</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <table class="table table-hover m-b-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('Created_at')</th>
                    <th>@lang('Question')</th>
                    <th>@lang('Reply')</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(ticSatisfactionPoll, key) in dataShow.tic_satisfaction_polls" :key="key">
                    <td>Pregunta @{{ key + 1 }}</td>
                    <td>@{{ ticSatisfactionPoll.created_at }}</td>
                    <td>@{{ ticSatisfactionPoll.question }}</td>
                    <td>@{{ ticSatisfactionPoll.reply }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- end panel-body -->
</div>
