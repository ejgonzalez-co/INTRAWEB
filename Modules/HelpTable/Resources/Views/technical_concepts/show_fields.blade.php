@if (Auth::user()->hasRole('Usuario TIC'))
    <div class="">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información del equipo</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Equipment Type Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment type'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_type }}.</p>

                    <!-- Equipment Mark Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment mark'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_mark }}.</p>
                </div>

                <div class="row">
                    <!-- Equipment Model Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment model'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_model }}.</p>

                    <!-- Equipment Serial Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Serial'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_serial }}.</p>
                </div>

                <div class="row">
                    <!-- Inventory Plate Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Inventory Plate'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.inventory_plate }}.</p>

                    <!-- Inventory Manager Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('In the inventory of'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.inventory_manager }}.</p>
                </div>

            </div>
        </div>
    </div>
@endif

@if (Auth::user()->hasRole('Administrador TIC'))
    <div class="">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Asignación del funcionario</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Inventory Plate Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Nombre usuario'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.staff_member ? dataShow.staff_member.name : "" }}.</p>

                    <!-- Inventory Manager Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Dependencia'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.staff_member ? dataShow.staff_member.dependencies.nombre : "" }}.</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información del equipo</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Equipment Type Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment type'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_type }}.</p>

                    <!-- Equipment Mark Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment mark'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_mark }}.</p>
                </div>

                <div class="row">
                    <!-- Equipment Model Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment model'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_model }}.</p>

                    <!-- Equipment Serial Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Serial'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_serial }}.</p>
                </div>

                <div class="row">
                    <!-- Inventory Plate Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Inventory Plate'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.inventory_plate }}.</p>

                    <!-- Inventory Manager Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('In the inventory of'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.inventory_manager }}.</p>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Asignación del funcionario Técnico</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Inventory Plate Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Officer'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.technicians ? dataShow.technicians.name : 'No establecido'  }}.</p>

                    <strong class="text-inverse text-left col-2 text-break">@lang('Fecha de vencimiento'):</strong>
                    <p v-if="dataShow.expiration_date > new Date().toISOString().split('T')[0]" class="col-2 text-break button__status-approved">@{{ dataShow.expiration_date ? formatDate(dataShow.expiration_date) : 'N/E'  }}.</p>
                    <p v-else class="col-2 text-break button__status-cancelled">@{{ dataShow.expiration_date ? formatDate(dataShow.expiration_date) : 'N/E'  }}.</p>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->hasRole('Soporte TIC')|| Auth::user()->hasRole('Revisor concepto técnico TIC') || Auth::user()->hasRole('Aprobación concepto técnico TIC'))
    <div class="">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Asignación del funcionario</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Inventory Plate Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Nombre usuario'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.staff_member ? dataShow.staff_member.name : "" }}.</p>

                    <!-- Inventory Manager Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Dependencia'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.staff_member ? dataShow.staff_member.dependencies.nombre : "" }}.</p>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Información del equipo</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Equipment Type Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment type'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_type }}.</p>

                    <!-- Equipment Mark Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment mark'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_mark }}.</p>
                </div>

                <div class="row">
                    <!-- Equipment Model Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Equipment model'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_model }}.</p>

                    <!-- Equipment Serial Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Serial'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.equipment_serial }}.</p>
                </div>

                <div class="row">
                    <!-- Inventory Plate Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('Inventory Plate'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.inventory_plate }}.</p>

                    <!-- Inventory Manager Field -->
                    <strong class="text-inverse text-left col-2 text-break">@lang('In the inventory of'):</strong>
                    <p class="col-4 text-break">@{{ dataShow.inventory_manager }}.</p>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Conceptó técnico</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <p class="col-12 text-break" style="text-align:justify;">@{{ dataShow.technical_concept ? dataShow.technical_concept : ''  }}.</p>
                </div>
            </div>
        </div>
        
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><strong>Observaciones</strong></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <p class="col-12 text-break" style="text-align:justify;">@{{ dataShow.observations ? dataShow.observations : ''  }}.</p>
                </div>
            </div>
        </div>

        <div class="panel" v-if="dataShow.url_attachments">
            <div class="panel-heading">
                <div class="panel-title"><strong>Adjuntos</strong></div>
            </div>
            <div class="panel-body">
                <li v-for="url_attachment in dataShow.url_attachments.split(',')">
                    <a :href="'{{ asset('storage') }}/' + url_attachment" target="_blank">Adjunto</a>
                    <br/>
                </li>
            </div>
        </div>
    </div>
@endif