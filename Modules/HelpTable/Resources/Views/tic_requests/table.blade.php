<div class="table-responsive">
    <table-component
        id="tic-requests-table"
        :data="dataList"
        sort-by="tic-requests"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        :key="keyRefresh"
        >
        <table-column show="id" label="@lang('Number')"></table-column>
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column show="users.dependencies.nombre" label="@lang('Dependency')"></table-column>
        <table-column show="users_name" label="@lang('User')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.users_name ? row.users_name : (row.users ? row.users.name : '') }}
            </template>
        </table-column>
        <table-column show="sede_tic_request" label="@lang('Sede')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.sede_tic_request ? row.sede_tic_request.name : 'Ninguna' }}
            </template>
        </table-column>
        <table-column show="tic_type_request" label="@lang('Request Type')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.tic_type_request? row.tic_type_request.name : '' }}
            </template>
        </table-column>
     
        <table-column show="priority_request_name" label="@lang('Priority Request')"></table-column>

        {{-- <table-column show="priority_request_name" label="Prioridad">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <span 
                    :class="{
                        'text-white text-center p-4 bg-blue states_style': row.priority_request == 1, // Blue
                        'text-black text-center p-4 bg-yellow states_style': row.priority_request == 2,  // Yellow
                        'text-white text-center p-4 bg-red states_style': row.priority_request == 3    // Red
                    }">
                    @{{ row.priority_request_name }}
                </span>
            </template>
        </table-column> --}}
        
                
        <table-column show="affair" label="@lang('Affair')"></table-column>
        <table-column show="tic_type_request" label="@lang('Allotted time')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.tic_type_request? row.tic_type_request.term + ' ' + row.tic_type_request.unit_time_name: '' }}
            </template>
        </table-column>
        <table-column show="support_type_name" label="Tipo de soporte"></table-column>
        <table-column show="assigned_user_name" label="@lang('Usuario asignado')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.assigned_user_name ? row.assigned_user_name : (row.assigned_user ? row.assigned_user.name : '') }}
            </template>
        </table-column>
        <table-column show="expiration_date" label="@lang('Expiration Date')"></table-column>
        <table-column show="date_attention" label="@lang('Date Attention')"></table-column>
        <table-column show="status_name" label="@lang('State')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-2" :style="'background-color:'+row.status_color" v-html=" row.status_name"></div>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">


                @if(Auth::user()->hasRole('Administrador TIC'))
                    <button v-if="row.ht_tic_request_status_id != 5" @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>

                    {{-- <button v-if="row.ht_tic_request_status_id == 1" @click="edit(row)" data-backdrop="static" data-target="#modal-tic-requests-request" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Devolver o Cancelar"><i class="fas fa-sync-alt"></i></button> --}}
                @endif

                @if(Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC'))
                    <button v-if="row.ht_tic_request_status_id == 2 || row.ht_tic_request_status_id == 3 || row.ht_tic_request_status_id == 7" @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Atender')"><i class="fas fa-user-check"></i></button>
                @endif

                @if(Auth::user()->hasRole('Usuario TIC'))
                    {{-- <button v-if="row.ht_tic_request_status_id == 7" @click="edit(row)" data-backdrop="static" data-target="#modal-tic-requests-request" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button> --}}
                    <button v-if="row.ht_tic_request_status_id == 4"  @click="callFunctionComponent('tic-satisfaction-poll', 'loadPollTic', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Encuesta de satisfacción')"><i class="fas fa-poll-h"></i></button>

                    <button v-if="row.ht_tic_request_status_id == 7" @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>

                @endif

                <button @click="show(row)" data-target="#modal-view-tic-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                @if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Administrador TIC'))
                    <a :href="'tic-requests-documents?requests='+row.id_encrypted">
                        <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Documentos"><i class="fas fa-folder"></i></button>
                    </a>
                @else
                    @if (Auth::user()->hasRole('Usuario TIC'))
                        <a :href="'tic-requests-documents?requests='+row.id_encrypted" v-if="row.tic_requests_documents?.length > 0">
                            <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Documentos"><i class="fas fa-folder"></i></button>
                        </a>
                    @endif
                @endif


                @if(Auth::user()->hasRole('Administrador TIC'))
                    <button v-if="row.ht_tic_request_status_id >= 4 && row.tic_knowledge_bases.length <= 0" @click="callFunctionComponent('tic-knowledge-bases', 'loadKnowledgeForm', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Base de conocimiento')"><i class="fas fa-database"></i></button>
                @endif

                @if(Auth::user()->hasRole('Administrador TIC'))
                    <button v-if="row.users_id == '{!! Auth::user()->id !!}'" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif 
                
            </template>
        </table-column>
    </table-component>
</div>
