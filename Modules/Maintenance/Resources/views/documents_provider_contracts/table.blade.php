<table class="table table-hover m-b-0" id="documents-provider-contracts-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name')</th>
            <th>@lang('Description')</th>
            <th>@lang('Url Document')</th>
            <th>@lang('Contrato')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(documentsProviderContract, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ documentsProviderContract.name }}</td>
            <td style="white-space: break-spaces;">@{{ documentsProviderContract.description }}</td>
            <td><span v-for="documento in documentsProviderContract.url_document.split(',')"><a class="col-9 text-truncate"  v-if="documentsProviderContract.url_document" :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver adjunto</a><br/></span></td>
            <td>@{{ documentsProviderContract.provider_contract.object }}</td>
            <td>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(documentsProviderContract)" data-backdrop="static" data-target="#modal-form-documents-provider-contracts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif
                <button @click="show(documentsProviderContract)" data-target="#modal-view-documents-provider-contracts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="drop(documentsProviderContract[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif
            </td>
        </tr>
    </tbody>
</table>
