<div class="table-responsive">
    <table-component
        id="documentOrders-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="documentOrders"
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
        <table-column show="nombre" label="Nombre"></table-column>
        <table-column show="adjunto" label="Adjunto">
            <template slot-scope="row">
                <a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+row.adjunto" target="_blank">Ver adjunto</a>
            </template>
        </table-column>


        {{-- <table-column show="mant_sn_orders_id" label="@lang('Mant Sn Orders Id')"></table-column>
            <table-column show="users_id" label="@lang('Users Id')"></table-column>
            <table-column show="estado" label="@lang('Estado')"></table-column> --}}
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-documentOrders" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-documentOrders" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>