<table class="table table-responsive table-hover m-b-0" id="work-hist-pensioners-table">
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
            <th>@lang('Quantity') @lang('of') @lang('documents')</th>

            <th class="w-25">@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(workHistoriesPensioner, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ workHistoriesPensioner.type_document }}</td>
            <td>@{{ workHistoriesPensioner.number_document }}</td>
            <td>@{{ workHistoriesPensioner.name }}</td>
            <td>@{{ workHistoriesPensioner.surname }}</td>
            <td>@{{ workHistoriesPensioner.address }}</td>
            <td>@{{ workHistoriesPensioner.phone }}</td>
            <td>@{{ workHistoriesPensioner.email }}</td>

            <td>@{{ workHistoriesPensioner.total_documents }}</td>

            <td>

            @if(Auth::user()->hasRole('Administrador historias laborales'))     
                <button @click="edit(workHistoriesPensioner)" data-backdrop="static" data-target="#modal-form-work-hist-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>  
            @endif 

                <button @click="show(workHistoriesPensioner)" data-target="#modal-view-work-hist-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
               
            @if(Auth::user()->hasRole('Administrador historias laborales'))     

                <button @click="drop(workHistoriesPensioner[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                <button @click="show(workHistoriesPensioner)" data-target="#modal-view-history-work-histories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Tracing')"><i class="fa fa-history"></i></button>


                <a :href="'{!! url('work-histories/documents-pensioners') !!}?wh=' + workHistoriesPensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Documents')"><i class="fas fa-folder-plus"></i></a>
                <a :href="'{!! url('work-histories/family-pensioners') !!}?wh=' + workHistoriesPensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Family')"><i class="fas fa-users"></i></a>

                <a :href="'{!! url('work-histories/news-histories-pen') !!}?wh=' + workHistoriesPensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Observations')"><i class="fas fa-comment-dots"></i></a>

                <button @click="show(workHistoriesPensioner)" data-backdrop="static" data-target="#modal-history-request" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Ver historial de solicitudes"><i class="fas fa-calendar-check"></i></button> 

                <a :href="'{!! url('work-histories/export-excel-pen') !!}/' + workHistoriesPensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Export')"><i class="fas fa-file-excel"></i></a>
                
                <a :href="'{!! url('work-histories/generate-document-pen') !!}/' + workHistoriesPensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Export') @lang('Work historie')" target="_blank"><i class="fas fa-file-pdf"></i></a>

                @endif 
            </td>
        </tr>
    </tbody>
</table>
