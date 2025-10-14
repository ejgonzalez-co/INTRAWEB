<div class="table-responsive">
    <table class="table table-hover m-b-0" id="dependencies-table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('headquarter')</th>
                <th>@lang('Code')</th>
                <th>@lang('Name')</th>
                <th>@lang('Siglas del proceso')</th>
                <th>@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(dependency, key) in advancedSearchFilterPaginate()" :key="key">
                <td>@{{ getIndexItem(key) }}</td>
                <td>@{{ dependency.headquarters.nombre }}</td>
                <td>@{{ dependency.codigo }}</td>
                <td>@{{ dependency.nombre }}</td>
                <td>@{{ dependency.codigo_oficina_productora }}</td>
                <td>
                    <button @click="edit(dependency)" data-backdrop="static" data-target="#modal-form-dependencies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                    <button @click="show(dependency)" data-target="#modal-view-dependencies" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                    <button @click="drop(dependency[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
