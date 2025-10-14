<div class="panel">
    <div class="panel mx-2">
        <div class="panel-body">
            <div class="row">
                <!-- Subject Field -->
                <strong class="text-inverse text-left col-3 text-break">@lang('Subject'):</strong>
                <span class="col-9 text-break">@{{ dataShow.subject }}.</span>
            </div>
        </div>
    </div>
    <div class="panel mx-2 mb-2">
        <div class="panel-heading">
            <div class="panel-title"><strong>Identificación de necesidades</strong></div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">

                        <table class="table table-hover fix-vertical-table">
                            <thead>
                                <tr>
                                    <th>Necesidad</th>
                                    <th>Código</th>
                                    <th>Unidad de medida</th>
                                    <th>Cantidad solicitada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(need, key) in dataShow.requests_supplies_adjustements_needs">
                                    <td>@{{ need.need_type }}</td>
                                    <td>@{{ need.code }}</td>
                                    <td>@{{ need.unit_measure }}</td>
                                    <td>@{{ need.request_quantity }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel mx-2">
        <div class="panel-heading">
            <div class="panel-title"><strong>Información general</strong></div>
        </div>
        <div class="panel-body">
            <div class="row mb-3">
                <!-- Need Type Field -->
                <strong class="text-inverse text-left col-3 text-break">@lang('Need Type'):</strong>
                <span class="col-9 text-break">@{{ dataShow.need_type }}.</span>
            </div>


            <div class="row mb-3">
                <!-- Justification Field -->
                <strong class="text-inverse text-left col-3 text-break">@lang('Description'):</strong>
                <span class="col-9 text-break">@{{ dataShow.justification }}.</span>
            </div>

            @if (!Auth::user()->hasRole("Funcionario requerimiento gestión recursos"))                
                <div class="row mb-3">
                    <!-- Justification Field -->
                    <strong class="text-inverse text-left col-3 text-break">@lang('Centro de costos'):</strong>
                    <span class="col-9 text-break">@{{ dataShow.cost_center }}.</span>
                </div>

                <div class="row mb-3">
                    <!-- Justification Field -->
                    <strong class="text-inverse text-left col-3 text-break">@lang('Verificación con el proveedor'):</strong>
                    <span class="col-9 text-break">@{{ dataShow.supplier_verification ?? "N/E" }}.</span>
                </div>

                <div class="row mb-3">
                    <!-- Justification Field -->
                    <strong class="text-inverse text-left col-3 text-break">@lang('Proveedor'):</strong>
                    <span class="col-9 text-break">@{{ dataShow.supplier_name }}.</span>
                </div>
                
                <div class="row mb-3">
                    <!-- Justification Field -->
                    <strong class="text-inverse text-left col-3 text-break">@lang('A nombre de'):</strong>
                    <span class="col-9 text-break">@{{ dataShow.creator_name }}.</span>
                </div>

                <div class="row mb-3">
                    <!-- Justification Field -->
                    <strong class="text-inverse text-left col-3 text-break">@lang('Seguimiento'):</strong>
                    <span class="col-9 text-break" v-html="dataShow.tracking"></span>
                </div>
            @endif


            <div class="row">
                <!-- Url Documents Field -->
                <strong class="text-inverse text-left col-3 text-break">@lang('Archivos'):</strong>
                <viewer-attachement :list="dataShow.url_documents" :key="dataShow.url_documents" name="Adjunto"></viewer-attachement>
            </div>
        </div>
    </div>
    <div v-if="dataShow.histories? dataShow.histories.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
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
                        <th>@lang('Created_at')</th>
                        <th>Usuario</th>
                        <th>@lang('Expiration Date')</th>
                        <th>@lang('Date Attention')</th>
                        <th>@lang('Tracing')</th>
                        <th>@lang('State')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(history, key) in dataShow.histories" :key="key">
                        <td>@{{ history.created_at }}</td>
                        <td>@{{ history.user_creator ? history.user_creator.name : "N/E" }}</td>
                        <td>@{{ history.expiration_date ? formatDate(history.expiration_date) : "N/E" }}</td>
                        <td>@{{ history.date_attention ?? "N/E" }}</td>
                        <td v-html="history.tracking"></td>
                        <td>
                            <div v-if="history.status == 'En elaboración'" class="text-white text-center p-2" style="background-color:#6fb6f0"
                            v-html="history.status"></div>
                            <div v-if="history.status == 'Abierta'" class="text-white text-center p-2"
                                style="background-color:#17a2b8"v-html="history.status"></div>
                            <div v-if="history.status == 'Asignada'" class="text-white text-center p-2"
                                style="background-color:#FFC107" v-html="history.status"></div>
                            <div v-if="history.status == 'En proceso'" class="text-white text-center p-2"
                                style="background-color:#FD7E14" v-html="history.status"></div>
                            <div v-if="history.status == 'Próxima vigencia'" class="text-white text-center p-2"
                                style="background-color:#e97878" v-html="history.status"></div>
                            <div v-if="history.status == 'Cerrada'" class="text-white text-center p-2" style="background-color:rgb(151, 227, 159)"
                                v-html="history.status"></div>
                            <div v-if="history.status == 'Finalizada'" class="text-white text-center p-2" style="background-color:rgb(31, 168, 46)"
                                v-html="history.status"></div>
                            <div v-if="history.status == 'Cancelada'" class="text-white text-center p-2" style="background-color:#d11414"
                                v-html="history.status"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- end panel-body -->
    </div>
</div>
