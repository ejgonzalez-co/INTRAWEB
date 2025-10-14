<div class="table-responsive">
    <table class="table table-hover m-b-0" id="positions-table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('Nombre')</th>
            <th>@lang('Descripcion')</th>
                <th>@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(position, key) in advancedSearchFilterPaginate()" :key="key">
                <td>@{{ getIndexItem(key) }}</td>
                <td>@{{ position.nombre }}</td>
                <td>@{{ position.descripcion }}</td>
                <td>
                    <button @click="edit(position)" data-backdrop="static" data-target="#modal-form-positions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                    <button @click="show(position)" data-target="#modal-view-positions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                    <button @click="drop(position[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
