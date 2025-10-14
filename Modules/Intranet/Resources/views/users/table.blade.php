<div class="table-responsive">
    <table-component id="users-table" :data="dataList" sort-by="users" sort-order="asc"
        table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator" :show-caption="false"
        filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="name" label="@lang('Name')"></table-column>
        <table-column show="email" label="@lang('Email')"></table-column>
        <table-column show="positions" label="Cargo">
            <template slot-scope="row">
                <p>@{{ row.positions ? row.positions.nombre : '' }}</p>
            </template>
        </table-column>
        <table-column show="dependencies" label="Dependencia">
            <template slot-scope="row">
                <p>@{{ row.dependencies ? row.dependencies.nombre : '' }}</p>
            </template>
        </table-column>
        <table-column show="block" label="@lang('Block Account')">
            <template slot-scope="row">
                <p>@{{ row.block ? '@lang('yes')' : '@lang('no')' }}</p>
            </template>
        </table-column>
        <table-column show="email_verified_at" label="@lang('Account Verified')">
            <template slot-scope="row">
                <p>@{{ row.email_verified_at ? '@lang('yes')' : '@lang('no')' }}</p>
            </template>
        </table-column>
    
        <table-column show="ultima_conexion" label="Última conexión">
            <template slot-scope="row">
                <p>@{{ row.ultima_conexion ? row.ultima_conexion : 'Sin conexión' }}</p>
            </template>
        </table-column>

        <table-column show="sendEmail" label="@lang('Sendemail')">
            <template slot-scope="row">
                <p>@{{ row.sendEmail ? '@lang('yes')' : '@lang('no')' }}</p>
            </template>
        </table-column>
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-users" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="edit(row); setPropObject('dataForm', 'change_user', true);" data-backdrop="static" data-target="#modal-form-users" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Editar información de la cuenta del funcionario actual"><i class="fas fa-user-edit"></i></button>
                <button @click="show(row)" data-target="#modal-view-users" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                @if (Auth::user()->hasRole('Admin seven'))
                
                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>

                @endif
            </template>
        </table-column>
    </table-component>
</div>
