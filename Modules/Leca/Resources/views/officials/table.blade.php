<div class="table-responsive">
    <table-component
        id="officials-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="officials"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >
            <table-column show="identification_number" label="@lang('Identification Number')"></table-column>
            <table-column show="name" label="@lang('Name')"></table-column>
            <table-column show="email" label="@lang('Email')"></table-column>
            <table-column show="telephone" label="@lang('Telephone')"></table-column>
            <table-column show="direction" label="@lang('Direction')"></table-column>
            <table-column show="charge" label="@lang('Charge')"></table-column>
            <table-column show="publication_status" label="@lang('State')"></table-column>
            <table-column show="receptionist" label="@lang('Receptionist')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-officials" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button v-if="row.state==1 " @click="edit(row)" data-backdrop="static" data-target="#modal-inactivate-official"
                data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="Inactivar Funcionario">
                <i class="fas fa-ban"></i>
                </button>

                <button v-if="row.state==2 " @click="edit(row)" data-backdrop="static" data-target="#modal-activate-official"
                data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="Activar funcionario">
                <i class="fas fa-check-circle"></i>
                </button>

                <button v-if="row.receptionist=='No'" @click="edit(row)" data-backdrop="static" data-target="#modal-activate-receptionist"
                data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="Habilitar recepcionista">
                <i class="fas fa-user-check"></i>
                </button>

                <button v-if="row.receptionist=='Si'" @click="edit(row)" data-backdrop="static" data-target="#modal-disable-receptionist"
                data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="Inhabilitar recepcionista">
                <i class="fas fa-user-times"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-officials" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}
                
            </template>
        </table-column>
    </table-component>
</div>