<table class="table table-responsive table-hover m-b-0" id="work-histories-actives-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Type Document')</th>
            <th>@lang('Number Document')</th>
            <th>@lang('Name')</th>
            <th>@lang('Surname')</th>
            <th>@lang('Address')</th>
            <th>@lang('Phone')</th>
            <th>@lang('Email')</th>
            <th>@lang('State')</th>
            <th>@lang('Quantity') @lang('of') @lang('documents')</th>

            <th class="w-25">@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(workHistoriesActive, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ workHistoriesActive.type_document }}</td>
            <td>@{{ workHistoriesActive.number_document }}</td>
            <td>@{{ workHistoriesActive.name }}</td>
            <td>@{{ workHistoriesActive.surname }}</td>
            <td>@{{ workHistoriesActive.address }}</td>
            <td>@{{ workHistoriesActive.phone }}</td>
            <td>@{{ workHistoriesActive.email }}</td>

            <td class="w-25" v-if="workHistoriesActive.state==1"><p class="bg-green rounded text-center">Historia laboral - Activo</p></td>
            <td class="w-25"  v-else><p class="bg-orange rounded text-center">Historia laboral - Retirado</p></td>
            <td>@{{ workHistoriesActive.total_documents }}</td>

            <td>

            @if(Auth::user()->hasRole('Administrador historias laborales'))     
                <button @click="edit(workHistoriesActive)" data-backdrop="static" data-target="#modal-form-work-histories-actives" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>  
            @endif 

                <button @click="show(workHistoriesActive)" data-target="#modal-view-work-histories-actives" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
               
            @if(Auth::user()->hasRole('Administrador historias laborales'))     

                <button @click="drop(workHistoriesActive[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                <button @click="show(workHistoriesActive)" data-target="#modal-view-history-work-histories-actives" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Tracing')"><i class="fa fa-history"></i></button>

        
                <a :href="'{!! url('work-histories/documents') !!}?wh=' + workHistoriesActive[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Documents')"><i class="fas fa-folder-plus"></i></a>
                <a :href="'{!! url('work-histories/family') !!}?wh=' + workHistoriesActive[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Family')"><i class="fas fa-users"></i></a>

                <a :href="'{!! url('work-histories/news') !!}?wh=' + workHistoriesActive[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Observations')"><i class="fas fa-comment-dots"></i></a>


                <a :href="'{!! url('work-histories/export-excel') !!}/' + workHistoriesActive[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Export')"><i class="fas fa-file-excel"></i></a>
               
                <a :href="'{!! url('work-histories/generate-document') !!}/' + workHistoriesActive[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Export') @lang('Work historie')" target="_blank"><i class="fas fa-file-pdf"></i></a>

                <button @click="show(workHistoriesActive)" data-backdrop="static" data-target="#modal-history-request" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Ver historial de solicitudes"><i class="fas fa-calendar-check"></i></button>  

                <execution-from-action v-if="workHistoriesActive.state==1"
                    :value="workHistoriesActive"
                    route="work-histories-actives"
                    field-update="state"
                    value-update="2"
                    css-class="fa fa-exchange-alt"
                    title="Cambiar estado"
                    >
                </execution-from-action>

                <execution-from-action v-else
                    :value="workHistoriesActive"
                    route="work-histories-actives"
                    field-update="state"
                    value-update="1"
                    css-class="fa fa-exchange-alt"
                    title="Cambiar estado"
                    >
                </execution-from-action>

                @endif 
            </td>
        </tr>
    </tbody>
</table>
