<table class="table table-hover m-b-0" id="substitutes-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Category')</th>
            <th>@lang('Pensioner') @lang('Deceased')</th>
            <th>@lang('Number Document') @lang('Pensioner') @lang('Deceased')</th>
            <th>@lang('State')</th>
            <th>@lang('Type Document')</th>
            <th>@lang('Number Document')</th>
            <th>@lang('Name')</th>
            <th>@lang('Address')</th>
            <th>@lang('Phone')</th>
            <th>@lang('Type') @lang('Substitute')</th>
            <th>@lang('Quantity') @lang('of') @lang('documents')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(substitute, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ substitute.category }}</td>
            <td v-if="substitute.category=='Pensionado'">@{{ substitute.work_histories_p ? substitute.work_histories_p.name: '' }} @{{ substitute.work_histories_p ? substitute.work_histories_p.surname: '' }}</td>
            <td v-else>@{{ substitute.work_histories_cp ? substitute.work_histories_cp.name: '' }} @{{ substitute.work_histories_cp ? substitute.work_histories_cp.surname: '' }}</td>

            <td v-if="substitute.category=='Pensionado'">@{{ substitute.work_histories_p ? substitute.work_histories_p.number_document: ''}}</td>
            <td v-else>@{{ substitute.work_histories_cp ? substitute.work_histories_cp.number_document: '' }}</td>

            <td class="w-25" v-if="substitute.state==1"><p class="bg-green rounded text-center">Pensión activa</p></td>
            <td class="w-25"  v-else><p class="bg-orange rounded text-center">Pensión inactiva</p></td>
            
            <td>@{{ substitute.type_document }}</td>
            <td>@{{ substitute.number_document }}</td>
            <td>@{{ substitute.name }} @{{ substitute.surname }}</td>
            <td>@{{ substitute.address }}</td>
            <td>@{{ substitute.phone }}</td>
            <td>@{{ substitute.type_substitute }}</td>
            <td>@{{ substitute.total_documents }}</td>

            <td>
                <button @click="edit(substitute)" data-backdrop="static" data-target="#modal-form-substitutes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(substitute)" data-target="#modal-view-substitutes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <a :href="'{!! url('work-histories/documents-substitutes') !!}?wh=' + substitute[customId]" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Documents')"><i class="fas fa-folder-plus"></i></a>
                <button @click="show(substitute)" data-target="#modal-view-history-substitute" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Tracing')"><i class="fa fa-history"></i></button>
                <button @click="drop(substitute[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>

            </td>
        </tr>
    </tbody>
</table>
