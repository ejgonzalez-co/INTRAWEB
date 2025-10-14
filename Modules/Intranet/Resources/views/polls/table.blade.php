<div class="table-responsive">
    <table-component
        id="polls-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="polls"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="true"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >
            <table-column show="id" label="Número de encuesta"></table-column>
            <table-column show="title" label="@lang('Title')"></table-column>
            <table-column show="date_open" label="@lang('Date Open')"></table-column>
            <table-column show="date_close" label="@lang('Date Close')"></table-column>

            <table-column show="work_groups" label="Dirigido a" cell-class="col-sm-1">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <ul>
                        <li v-for="(group, key) in row.work_groups">
                            @{{ group.name }}
                        </li>
                    </ul>

                    <ul>
                        <li v-for="(group, key) in row.users">
                            @{{ group.users_name }}
                        </li>
                    </ul>
               </template>
            </table-column>


            @if(Auth::user()->hasRole('Administrador intranet de encuestas'))     
            <table-column show="state_name" label="@lang('State')" cell-class="col-sm-1">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <div class="text-white text-center p-2" :style="'background-color:'+row.state_colour" v-html="row.state_name"></div>
               </template>
            </table-column>

            @else
            <table-column show="was_answered" label="@lang('Respuesta')" cell-class="col-sm-1">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <div v-if="row.was_answered" class="text-white text-center p-2 bg-green">Contestada</div>
                    <div v-else class="text-white text-center p-2 bg-warning">Pendiente por respuestas</div>
                </template>
            </table-column>
            @endif

            <table-column show="creator" label="@lang('Creator')"></table-column>

            <table-column label="@lang('crud.action')" :sortable="false" :filterable="false" cell-class="col-2">
                <template slot-scope="row">

                    @if(Auth::user()->hasRole('Administrador intranet de encuestas'))   
                        <button @click="edit(row)" v-if= "row.state == 1" data-backdrop="static" data-target="#modal-form-polls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                            <i class="fas fa-pencil-alt"></i>
                        </button>

                        <button @click="show(row)" data-target="#modal-view-polls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                            <i class="fa fa-search"></i>
                        </button>
                        
                        <a :href="'{!! url('intranet/poll-questions') !!}?poll=' + row[customId]" v-if= "row.state == 1" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Questions')"><i class="fas fa-question-circle"></i></a>
                    
                        <a :href="'{!! url('intranet/poll-answers') !!}?poll=' + row[customId]" v-if= "row.state != 1" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Answers')"><i class="fas fa-poll"></i></a>
                    
                        <button @click="edit(row)" data-backdrop="static" v-if= "row.state == 1 && row.count_questions > 0 && (row.users?.length > 0 || row.work_groups?.length > 0)" data-target="#form-publish-poll" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Send')"><i class="fas fa-share-square"></i></button>

                        <button @click="callFunctionComponent('poll-answer', 'loadPollAnswer', row);" v-if="!row.was_answered && row.state==2" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Responder Encuesta de la entidad')"><i class="fas fa-vote-yea"></i></button>

                        <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                            <i class="fa fa-trash"></i>
                        </button>
                    @endif

                    @if(!Auth::user()->hasRole('Administrador intranet de encuestas'))  
                        <button @click="show(row)" data-target="#modal-view-polls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                            <i class="fa fa-search"></i>
                        </button>

                        <button @click="callFunctionComponent('poll-answer', 'loadPollAnswer', row);" v-if="!row.was_answered && row.state==2 && row.in_range_date" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Responder Encuesta de la entidad')"><i class="fas fa-vote-yea"></i></button>

                    @endif
                    
                </template>
            </table-column>
    </table-component>
</div>