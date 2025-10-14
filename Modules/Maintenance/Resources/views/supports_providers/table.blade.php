<table class="table table-hover m-b-0" id="supports-providers-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name')</th>
            <th>@lang('Description')</th>
            <th>@lang('Url Document')</th>
            <th>@lang('Proveedor')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(supportsProvider, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ supportsProvider.name }}</td>
            <td style="white-space: break-spaces;">@{{ supportsProvider.description }}</td>
            <td><span v-for="documento in supportsProvider.url_document.split(',')"><a class="col-9 text-truncate"  v-if="supportsProvider.url_document" :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver adjunto</a><br/></span></td>
            <td>@{{ supportsProvider.mant_providers ? supportsProvider.mant_providers.name : '' }}</td>
            <td>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(supportsProvider)" data-backdrop="static" data-target="#modal-form-supports-providers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif

                <button @click="show(supportsProvider)" data-target="#modal-view-supports-providers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="drop(supportsProvider[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif
            </td>
        </tr>
    </tbody>
</table>
