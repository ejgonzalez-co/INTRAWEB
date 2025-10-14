<table class="table table-hover m-b-0" id="documentsSubstitutes-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Type Document')</th>
            <th>@lang('Description')</th>
            <th>@lang('Sheets')</th>
            <th>@lang('Attached')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(documentsSubstitute, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ documentsSubstitute.work_histories_config_documents ? documentsSubstitute.work_histories_config_documents.name : '' }}</td>
            <td>@{{ documentsSubstitute.description }}</td>
            <td>@{{ documentsSubstitute.sheet }}</td>

            <td><a class="col-9 text-truncate"  v-if="documentsSubstitute.url_document"  :href="'{{ asset('storage') }}/'+documentsSubstitute.url_document" target="_blank">Ver adjunto</a></td>

            <td>
                <button @click="edit(documentsSubstitute)" data-backdrop="static" data-target="#modal-form-documents-substitutes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(documentsSubstitute)" data-target="#modal-view-documents-substitutes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(documentsSubstitute[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
