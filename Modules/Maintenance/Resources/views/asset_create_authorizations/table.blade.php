<table class="table table-hover m-b-0" id="asset-create-authorizations-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Dependencia')</th>
            <th>@lang('Responsable')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(assetCreateAuthorization, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ (assetCreateAuthorization.dependencias) ? assetCreateAuthorization.dependencias.nombre : '' }}</td>
            <td>@{{ (assetCreateAuthorization.usuarios) ? assetCreateAuthorization.usuarios.name : '' }}</td>
            <td>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(assetCreateAuthorization)" data-backdrop="static" data-target="#modal-form-asset-create-authorizations" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif
                <button @click="show(assetCreateAuthorization)" data-target="#modal-view-asset-create-authorizations" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="drop(assetCreateAuthorization[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif
            </td>
        </tr>
    </tbody>
</table>
