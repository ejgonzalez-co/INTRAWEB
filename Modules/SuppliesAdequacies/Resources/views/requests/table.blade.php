<div class="table-responsive">
    <table-component id="requests-table" :data="dataList" sort-by="requests" sort-order="asc"
        table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator" :show-caption="false"
        filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="consecutive" label="@lang('Número')"></table-column>
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column show="need_type" label="@lang('Need Type')"></table-column>
        <table-column show="subject" label="@lang('Subject')"></table-column>
        <table-column show="quantity_term" label="@lang('Tiempo asignado')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="p-2">@{{ row.quantity_term ? (row.quantity_term > 1 ? row.quantity_term + " días" : row.quantity_term + " día") : "No definido" }}</div>
            </template>
        </table-column>
        @if (Auth::user()->hasRole(["Administrador requerimiento gestión recursos","Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación"]))            
            <table-column show="user_creator" label="@lang('Funcionario quien realizo la solicitud')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <div class="p-2">@{{ row.user_creator ? row.user_creator.name + " - " +  row.user_creator.dependencies.nombre : "No definido" }}</div>
                </template>
            </table-column>
        @endif
        <table-column show="need_type" label="@lang('Usuario asignado')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="p-2">@{{ row.assigned_officer ? row.assigned_officer.name : "No definido" }}</div>
            </template>
        </table-column>
        <table-column show="expiration_date" label="@lang('Fecha de vencimiento')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="p-2">@{{ row.expiration_date ? formatDate(row.expiration_date) : "No definido" }}</div>
            </template>
        </table-column>
        <table-column show="date_attention" label="@lang('Fecha de atención')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="p-2">@{{ row.date_attention ? row.date_attention : "No definido" }}</div>
            </template>
        </table-column>
        <table-column show="status" label="@lang('Status')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.status == 'En elaboración'" class="text-white text-center p-2" style="background-color:#6fb6f0"
                    v-html="row.status"></div>
                <div v-if="row.status == 'Abierta'" class="text-white text-center p-2" style="background-color:#17a2b8"
                    v-html="row.status"></div>
                <div v-if="row.status == 'Asignada'" class="text-white text-center p-2" style="background-color:#ffc107"
                    v-html="row.status"></div>
                <div v-if="row.status == 'En proceso'" class="text-white text-center p-2"
                    style="background-color:#fd7e14" v-html="row.status"></div>
                <div v-if="row.status == 'Próxima vigencia'" class="text-white text-center p-2" style="background-color:#e97878">Próxima vigencia</div>
                <div v-if="row.status == 'Cancelada'" class="text-white text-center p-2" style="background-color:#d11414" v-html="row.status"></div>
                <div v-if="row.status == 'Cerrada'" class="text-white text-center p-2" style="background-color:rgb(151, 227, 159)"
                    v-html="row.status"></div>
                <div v-if="row.status == 'Finalizada'" class="text-white text-center p-2"
                    style="background-color:rgb(31, 168, 46)" v-html="row.status"></div>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if (Auth::user()->hasRole([
                    'Operador Infraestuctura',
                    'Operador Suministros de consumo',
                    'Operador Suministros devolutivo / Asignación',
                    'Administrador requerimiento gestión recursos'
                ]))
                    <button v-if="row.status == 'En elaboración' || row.status == 'Próxima vigencia'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @else
                    <button v-if="row.status == 'En elaboración'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif

                <button @click="show(row)" data-target="#modal-view-requests" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                
                @role('Administrador requerimiento gestión recursos')
                    <button v-if="row.status != 'Finalizada' && row.status != 'Cancelada'" @click="edit(row)"
                        data-backdrop="static" data-target="#modal-form-requests" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-knowledge-base"
                        data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="Crear base de conocimiento">
                        <i class="fas fa-database"></i>
                    </button>

                    <a target="_blank" class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip"
                    data-placement="top" title="Anotaciones Pendiente"
                    :key="row.annotations?.length"
                    @click="$refs.annotations.abrirModal(row);executeEndpoint('request/read/annotations',row[customId]);">
                    <!-- Maneja el evento click -->
                    <i class="fas fa-comment"></i> <!-- Icono -->
                    <span v-if="row.pending_annotations?.length > 0"
                        class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                        @{{ row.pending_annotations?.length }}
                    </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                </a>
                @endrole

                @if (Auth::user()->hasRole([
                        'Operador Infraestuctura',
                        'Operador Suministros de consumo',
                        'Operador Suministros devolutivo / Asignación',
                    ]))
                    <button v-if="row.status == 'Asignada' || row.status == 'En proceso'" @click="edit(row)"
                        data-backdrop="static" data-target="#modal-form-requests" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-knowledge-base"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="Crear base de conocimiento">
                    <i class="fas fa-database"></i>
                    </button>

                    <a target="_blank" class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip"
                    data-placement="top" title="Anotaciones Pendiente"
                    :key="row.annotations?.length"
                    @click="$refs.annotations.abrirModal(row);executeEndpoint('request/read/annotations',row[customId]);">
                    <!-- Maneja el evento click -->
                    <i class="fas fa-comment"></i> <!-- Icono -->
                    <span v-if="row.pending_annotations?.length > 0"
                        class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                        @{{ row.pending_annotations?.length }}
                    </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                </a>
                @endif

                <a :href="'export-VIG/' + row.encrypted_id">
                    <button class="btn btn-white btn-icon btn-md" title="Formato Identificación de Necesidades">
                        <i class="fa fa-file-excel"></i>
                    </button>
                </a>

            </template>
        </table-column>
    </table-component>
</div>
