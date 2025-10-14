<div class="table-responsive">
    <table class="table table-hover m-b-0" id="headquarters-table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('Nombre')</th>
            <th>@lang('Descripcion')</th>
            <th>@lang('Codigo')</th>
                <th>@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(headquarter, key) in advancedSearchFilterPaginate()" :key="key">
                <td>@{{ getIndexItem(key) }}</td>
                <td>@{{ headquarter.nombre }}</td>
                <td>@{{ headquarter.descripcion }}</td>
                <td>@{{ headquarter.codigo }}</td>
                <td>
                    <button @click="edit(headquarter)" data-backdrop="static" data-target="#modal-form-headquarters" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                    <button @click="show(headquarter)" data-target="#modal-view-headquarters" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                    <button @click="drop(headquarter[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
