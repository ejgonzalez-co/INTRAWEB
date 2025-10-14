<div class="table-responsive">
    <table-component id="technicalConcepts-table" :data="advancedSearchFilterPaginate()" sort-by="technicalConcepts"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        @if (Auth::user()->hasRole('Usuario TIC'))
            <table-column show="consecutive" label="@lang('Consecutive')">
                <template slot-scope="row">
                    <p v-if="row.consecutive < 10">@{{ row.consecutive ? "00" + row.consecutive : "" }}</p>
                    <p v-else-if="row.consecutive >= 10 && row.consecutive < 100">@{{ row.consecutive ? "0" + row.consecutive : "" }}</p>
                    <p v-else>@{{ row.consecutive ? row.consecutive : "" }}</p>
                </template>
            </table-column>
            <table-column show="created_at" label="@lang('Created_at')"></table-column>
            <table-column show="equipment_type" label="@lang('Equipment type')"></table-column>
            <table-column show="equipment_model" label="@lang('Equipment model')"></table-column>
        @endif
        @if (Auth::user()->hasRole('Administrador TIC') || Auth::user()->hasRole('Soporte TIC'))
            <table-column show="consecutive" label="@lang('Consecutive')">
                <template slot-scope="row">
                    <p v-if="row.consecutive < 10">@{{ row.consecutive ? "00" + row.consecutive : "" }}</p>
                    <p v-else-if="row.consecutive >= 10 && row.consecutive < 100">@{{ row.consecutive ? "0" + row.consecutive : "" }}</p>
                    <p v-else>@{{ row.consecutive ? row.consecutive : "" }}</p>
                </template>
            </table-column>
            <table-column show="created_at" label="@lang('Created_at')"></table-column>
            <table-column show="equipment_type" label="@lang('Dependence')">
                <template slot-scope="row">
                    <p>@{{ row.staff_member ? row.staff_member.dependencies.nombre : '' }}</p>
                </template>
            </table-column>
            <table-column show="equipment_model" label="@lang('User')">
                <template slot-scope="row">
                    <p>@{{ row.staff_member ? row.staff_member.name : '' }}</p>
                </template>
            </table-column>
            <table-column show="equipment_model" label="@lang('Asignated user')">
                <template slot-scope="row">
                    <p>@{{ row.technicians ? row.technicians.name : '' }}</p>
                </template>
            </table-column>
            <table-column show="expiration_date" label="@lang('Fecha de vencimiento')">
                <template slot-scope="row">
                    <p v-if="row.expiration_date > new Date().toISOString().split('T')[0]" class="button__status-approved">@{{ row.expiration_date ? formatDate(row.expiration_date) : '' }}</p>
                    <p v-else-if="row.expiration_date < new Date().toISOString().split('T')[0]" class="button__status-cancelled">@{{ row.expiration_date ? formatDate(row.expiration_date) : '' }}</p>
                    <p v-else></p>
                </template>
            </table-column>
        @endif
        @if (Auth::user()->hasRole('Revisor concepto técnico TIC'))
            <table-column show="consecutive" label="@lang('Consecutive')">
                <template slot-scope="row">
                    <p v-if="row.consecutive < 10">@{{ row.consecutive ? "00" + row.consecutive : "" }}</p>
                    <p v-else-if="row.consecutive >= 10 && row.consecutive < 100">@{{ row.consecutive ? "0" + row.consecutive : "" }}</p>
                    <p v-else>@{{ row.consecutive ? row.consecutive : "" }}</p>
                </template>
            </table-column>
            <table-column show="created_at" label="@lang('Created_at')"></table-column>
            <table-column show="equipment_type" label="@lang('Dependence')">
                <template slot-scope="row">
                    <p>@{{ row.staff_member ? row.staff_member.dependencies.nombre : '' }}</p>
                </template>
            </table-column>
            <table-column show="equipment_model" label="@lang('User')">
                <template slot-scope="row">
                    <p>@{{ row.staff_member ? row.staff_member.name : '' }}</p>
                </template>
            </table-column>
            <table-column show="equipment_model" label="@lang('Asignated user')">
                <template slot-scope="row">
                    <p>@{{ row.technicians ? row.technicians.name : '' }}</p>
                </template>
            </table-column>
            <table-column show="expiration_date" label="@lang('Fecha de vencimiento')">
                <template slot-scope="row">
                    <p v-if="row.expiration_date > new Date().toISOString().split('T')[0]" class="button__status-approved">@{{ row.expiration_date ? formatDate(row.expiration_date) : '' }}</p>
                    <p v-else-if="row.expiration_date < new Date().toISOString().split('T')[0]" class="button__status-cancelled">@{{ row.expiration_date ? formatDate(row.expiration_date) : '' }}</p>
                    <p v-else></p>
                </template>
            </table-column>
        @endif
        @if (Auth::user()->hasRole('Aprobación concepto técnico TIC'))
            <table-column show="consecutive" label="@lang('Consecutive')">
                <template slot-scope="row">
                    <p v-if="row.consecutive < 10">@{{ row.consecutive ? "00" + row.consecutive : "" }}</p>
                    <p v-else-if="row.consecutive >= 10 && row.consecutive < 100">@{{ row.consecutive ? "0" + row.consecutive : "" }}</p>
                    <p v-else>@{{ row.consecutive ? row.consecutive : "" }}</p>
                </template>
            </table-column>
            <table-column show="created_at" label="@lang('Created_at')"></table-column>
            <table-column show="equipment_type" label="@lang('Dependencia')">
                <template slot-scope="row">
                    <p>@{{ row.staff_member ? row.staff_member.dependencies.nombre : '' }}</p>
                </template>
            </table-column>
            <table-column show="equipment_model" label="@lang('User')">
                <template slot-scope="row">
                    <p>@{{ row.staff_member ? row.staff_member.name : '' }}</p>
                </template>
            </table-column>
            <table-column show="equipment_model" label="@lang('Asignated user')">
                <template slot-scope="row">
                    <p>@{{ row.technicians ? row.technicians.name : '' }}</p>
                </template>
            </table-column>
            <table-column show="expiration_date" label="@lang('Fecha de vencimiento')">
                <template slot-scope="row">
                    <p v-if="row.expiration_date > new Date().toISOString().split('T')[0]" class="button__status-approved">@{{ row.expiration_date ? formatDate(row.expiration_date) : '' }}</p>
                    <p v-else-if="row.expiration_date < new Date().toISOString().split('T')[0]" class="button__status-cancelled">@{{ row.expiration_date ? formatDate(row.expiration_date) : '' }}</p>
                    <p v-else></p>
                </template>
            </table-column>
        @endif
        <table-column show="status" label="@lang('Status')">
            <template slot-scope="row">
                <p v-if="row.status === 'Pendiente'" class="button__status-pending">
                    @{{ row.status ? row.status : "N/A" }}</p>
                <p v-if="row.status === 'Asignado'" class="button__status-assigned">
                    @{{ row.status ? row.status : "N/A" }}</p>
                <p v-if="row.status === 'En revisión'" class="button__status-in_review">
                    @{{ row.status ? row.status : "N/A" }}</p>
                <p v-if="row.status === 'Aprobación pendiente'" class="button__status-pending_approval">
                    @{{ row.status ? row.status : "N/A" }}</p>
                <p v-if="row.status === 'Devolver al técnico'" class="button__status-cancelled">
                    @{{ row.status ? "Devuelto" : "N/A" }}</p>
                <p v-if="row.status === 'Devolver al revisor'" class="button__status-cancelled">
                    @{{ row.status ? "Devuelto" : "N/A" }}</p>
                <p v-if="row.status === 'Aprobado'" class="button__status-approved">
                    @{{ row.status ? row.status : "N/A" }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @if (Auth::user()->hasRole('Administrador TIC'))
                    <button v-if="row.status === 'Pendiente'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-technicalConcepts" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif

                @if (Auth::user()->hasRole('Revisor concepto técnico TIC'))
                    <button v-if="row.status === 'En revisión' || row.status === 'Devolver al revisor'" @click="edit(row)"
                        data-backdrop="static" data-target="#modal-form-technicalConcepts" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                    <button v-if="row.status === 'En revisión' || row.status === 'Devolver al revisor'" @click="edit(row)"
                        data-backdrop="static" data-target="#modal-form-send-approval" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('Enviar a aprobación')"><i class="fas fa-paper-plane"></i></button>
                @endif

                @if (Auth::user()->hasRole('Soporte TIC'))
                    <button v-if="row.status === 'Asignado' || row.status === 'Devolver al técnico'" @click="edit(row)"
                        data-backdrop="static" data-target="#modal-form-technicalConcepts" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>

                    <button v-if="row.status === 'Asignado' || row.status === 'Devolver al técnico'" @click="edit(row)"
                        data-backdrop="static" data-target="#modal-form-send-review" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('Enviar a revisión')"><i class="fas fa-paper-plane"></i></button>
                @endif

                @if (Auth::user()->hasRole('Aprobación concepto técnico TIC'))
                    <button v-if="row.status === 'Aprobación pendiente'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-technicalConcepts" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>

                    <button v-if="row.status === 'Aprobación pendiente'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-approve-request" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('Aprobar')"><i class="fas fa-paper-plane"></i></button>
                @endif

                <a :href="'certificate/' + row.id" target="_blank" v-if="row.status === 'Aprobado'"
                    data-toggle="tooltip" data-placement="top" title="Ver documento">
                    <button class="btn btn-white btn-icon btn-md">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                </a>

                <button @click="show(row)" data-target="#modal-view-technicalConcepts" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-technical-concept-history" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('Show history')">
                    <i class="fa fa-history"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
