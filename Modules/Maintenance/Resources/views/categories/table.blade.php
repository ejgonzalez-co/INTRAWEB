<table class="table table-hover m-b-0" id="categories-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Tipo de activo')</th>
            <th>@lang('Nombre de la categoría')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(category, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ (category.mant_asset_type)? category.mant_asset_type.name : '' }}</td>
            <td>@{{ category.name }}</td>
            <td>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(category)" data-backdrop="static" data-target="#modal-form-categories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif
                <button @click="show(category)" data-target="#modal-view-categories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="callFunctionComponent('assets', 'eliminarCategory', category[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif
            </td>
        </tr>
    </tbody>
</table>

<!-- Se importa el componente de activos, solo para la validación y eliminación de la categoría -->
<assets-create ref="assets" name="resume-machinery-vehicles-yellows"></assets-create>