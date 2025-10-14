<table class="table table-hover m-b-0" id="pcPreviousStudiesDocuments-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name')</th>
            <th>@lang('Description')</th>
            <th>@lang('Document')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(pcPreviousStudiesDocuments, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ pcPreviousStudiesDocuments.name }}</td>
            <td>@{{ pcPreviousStudiesDocuments.description }}</td>

            <td>
                <a class="col-9 text-truncate" v-if="pcPreviousStudiesDocuments.url_document" :href="'{{ asset('storage') }}/'+pcPreviousStudiesDocuments.url_document" target="_blank">Ver adjunto</a>
            </td>

            <td>
                <button @click="edit(pcPreviousStudiesDocuments)" data-backdrop="static" data-target="#modal-form-pc-previous-studies-documents" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(pcPreviousStudiesDocuments)" data-target="#modal-view-pc-previous-studies-documents" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(pcPreviousStudiesDocuments[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
