<table class="table table-hover m-b-0" id="quotaParts-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name Company')</th>
            <th>@lang('Time work')</th>
            <th>@lang('Observation')</th>
            <th>@lang('Attached')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(quotaParts, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ quotaParts.name_company }}</td>
            <td>@{{ quotaParts.time_work }}</td>
            <td>@{{ quotaParts.observation }}</td>
            <td><a class="col-9 text-truncate"  v-if="quotaParts.url_document"  :href="'{{ asset('storage') }}/'+quotaParts.url_document" target="_blank">Ver adjunto</a></td>
            <td>
                <button @click="edit(quotaParts)" data-backdrop="static" data-target="#modal-form-quota-parts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(quotaParts)" data-target="#modal-view-quota-parts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(quotaParts[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
