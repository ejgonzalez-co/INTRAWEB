<table class="table table-responsive table-hover m-b-0" id="quota-parts-pensioners-table">
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
            <th>@lang('Quantity') @lang('of') @lang('Quota parts')</th>

            <th class="w-25">@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(pensioner, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ pensioner.type_document }}</td>
            <td>@{{ pensioner.number_document }}</td>
            <td>@{{ pensioner.name }}</td>
            <td>@{{ pensioner.surname }}</td>
            <td>@{{ pensioner.address }}</td>
            <td>@{{ pensioner.phone }}</td>
            <td>@{{ pensioner.email }}</td>

            <td>@{{ pensioner.total_documents }}</td>

            
            <td>@{{ pensioner.work_histories_cps? pensioner.work_histories_cps.length : '' }}</td>

            <td>

            @if(Auth::user()->hasRole('Administrador historias laborales'))     
                <button @click="edit(pensioner)" data-backdrop="static" data-target="#modal-form-quota-parts-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>  
            @endif 

                <button @click="show(pensioner)" data-target="#modal-view-quota-parts-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
               
            @if(Auth::user()->hasRole('Administrador historias laborales'))     

                <button @click="drop(pensioner[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                <button @click="show(pensioner)" data-target="#modal-view-history-pensioner" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Tracing')"><i class="fa fa-history"></i></button>
                <a :href="'{!! url('work-histories/quota-parts-doc-pensioners') !!}?wh=' + pensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Documents')"><i class="fas fa-folder-plus"></i></a>

                <a :href="'{!! url('work-histories/quota-parts') !!}?wh=' + pensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Gestionar') @lang('Quota parts')"><i class="fas fa-project-diagram"></i></a>


                <!--<a :href="'{!! url('work-histories/quota-parts-news-users') !!}?wh=' + pensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Observations')"><i class="fas fa-comment-dots"></i></a>
    
                <a :href="'{!! url('work-histories/export-excel') !!}/' + pensioner[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Export')"><i class="fas fa-file-excel"></i></a>-->
                @endif 
            </td>
        </tr>
    </tbody>
</table>
