<div class="table-responsive">
    <table-component
        id="needs-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="needs"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >
        <table-column show="paa_calls.name" label="@lang('Paa Call')"></table-column>
        <table-column show="paa_calls.start_date" label="@lang('Start Date')"></table-column>
        <table-column show="paa_calls.closing_date" label="@lang('Closing Date')"></table-column>
        <table-column show="paa_calls.state_name" label="@lang('Call Status')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <span :style="{ 'background-color': row.paa_calls.state_colour, 'color': '#FFFFFF' }" class="p-5">
                    @{{ row.paa_calls.state_name }}
                </span>
            </template>
        </table-column>
        <table-column show="name_process" label="@lang('Name Process')"></table-column>
        {{-- <table-column show="total_value_paa" label="@lang('Total Value Paa')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ '$ '+currencyFormat(row.total_value_paa) }}
            </template>
        </table-column> --}}
        <table-column show="state_name" label="Estado PAA" cell-class="text-center">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <span :style="{ 'background-color': row.state_colour, 'color': '#FFFFFF' }" class="p-5">
                    @{{ row.state_name }}
                </span>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">


                @if(Auth::user()->hasRole(['PC Gestor de recursos', 'PC Gestor planeación', 'PC Gestor presupuesto']))
                <!-- <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                
                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button> -->
                
                {{-- Abre la vista de las necesidades de funcionamiento --}}
                <a :href="'functioning-needs?need='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Functioning Needs')"><i class="fas fa-hand-holding-usd"></i></button>
                </a>
                {{-- Abre la vista de las fichas de inversion --}}
                <a :href="'investment-technical-sheets?need='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Investment Needs')"><i class="fas fa-file-invoice-dollar"></i></button>
                </a>
                
                {{-- Abre el modal para aprobar las necesidades --}}
                <button v-if="row.state == 3 && row.assigned_user_id == '{!! Auth::user()->id  !!}'" @click="callFunctionComponent('assess-needs-paa', 'loadData', row);"  class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Approve')">
                    <i class="fas fa-thumbs-up"></i>
                </button>

                {{-- Abre el modal para procesar la solicitud de modificacion de las necesidades --}}
                <button  v-if="row.state == 6 && row.user_modification_request" @click="callFunctionComponent('process-modification-request-paa', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Process Modification Request')"><i class="fas fa-cogs"></i></button>

                
                @endif

                @if(Auth::user()->hasRole('PC Líder de proceso'))

                {{-- Abre la vista de las necesidades de funcionamiento --}}
                <a :href="'functioning-needs?need='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Functioning Needs')"><i class="fas fa-hand-holding-usd"></i></button>
                </a>

                {{-- Abre la vista de las fichas de inversion --}}
                <a :href="'investment-technical-sheets?need='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Investment Needs')">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </button>
                </a>

                

                {{-- Abre el modal para solicitar la modificacion de las necesidades --}}
                <button  v-if="'{!! Auth::user()->id !!}' == row.process_leaders.users_id && (row.state == 5)" @click="callFunctionComponent('request-modification-paa', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Request Modification')"><i class="fas fa-sync-alt"></i></button>

                {{-- Ejecuta la funcion de enviar las necesidades a revision --}}
                <execution-from-action
                    v-if="'{!! Auth::user()->id !!}' == row.process_leaders.users_id && (row.state < 3 || row.state == 4 || row.state == 7) && row.in_range_date"
                    :value="row"
                    route="send-review-needs"
                    field-update="state"
                    value-update="3"
                    css-class="fas fa-paper-plane"
                    title="@lang('Submit a review')"
                    >
                </execution-from-action>

                @endif

                {{-- Abre el modal para adjuntar documentos al proceso --}}
                <button @click="callFunctionComponent('paa-process-attachment', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Agregar documentos"><i class="fas fa-folder-open"></i></button>

                {{-- Abre el modal para ver las novedades de la necesidad --}}
                <button @click="callFunctionComponent('novelties-paa', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Novelties') PAA"><i class="fas fa-list-alt"></i></button>

                {{-- Abre el modal para ver los detalles de las necesidades --}}
                <button @click="show(row)" data-target="#modal-view-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                {{-- Ejecuta la accion de exportar las necesidades en excel --}}
                <a v-if="row.state == 5" :href="'export-approved-needs/'+row.id">
                    <button class="btn btn-white btn-icon btn-md" title="Exportar necesidades aprobadas"><i class="fas fa-file-excel"></i></button>
                </a>
                
            </template>
        </table-column>
    </table-component>
</div>