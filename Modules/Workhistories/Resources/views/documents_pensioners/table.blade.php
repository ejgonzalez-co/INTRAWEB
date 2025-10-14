<table class="table table-hover m-b-0" id="documents-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Type Document')</th>
            <th>@lang('Description')</th>
            <th>@lang('Sheets')</th>
            <th>@lang('Date')</th>

            <th>@lang('Attached')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(documents, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ documents.work_histories_p_config_documents ? documents.work_histories_p_config_documents.name : '' }}</td>
            <td>@{{ documents.description }}</td>
            <td>@{{ documents.sheet }}</td>
            <td>@{{ documents.document_date }}</td>

            <td><a class="col-9 text-truncate"  v-if="documents.url_document"  :href="'{{ asset('storage') }}/'+documents.url_document" target="_blank">Ver adjunto</a></td>

            <td>
                <button @click="edit(documents)" data-backdrop="static" data-target="#modal-form-documents-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(documents)" data-target="#modal-view-documents" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(documents[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
