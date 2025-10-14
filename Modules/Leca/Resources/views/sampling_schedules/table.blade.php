<div class="table-responsive">
    <table-component
        id="samplingSchedules-table"
        :data="dataList"
        sort-by="samplingSchedules"
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
            <table-column show="sampling_date" label="@lang('Sampling Date')">
                <template slot-scope="row">
                    @{{ formatDate(row.sampling_date) }}
                </template>
            </table-column>
            <table-column show="lc_sample_points.point_location" label="@lang('Lc Sample Points Id')"></table-column>
            {{-- <table-column show="direction" label="@lang('Direction')"></table-column> --}}
            <table-column show="users_name" label="@lang('Lc Officials Id')"></table-column>
            <table-column show="duplicado" label="¿ Aplica para duplicado ?"></table-column>
            <table-column show="user_creador" label="Usuario creador"></table-column>
            {{-- <table-column show="users.name" label="@lang('Lc Officials Id')"></table-column> --}}
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- @if(Auth::user()->hasRole('Administrador Leca')) --}}
                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-samplingSchedules" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-samplingSchedules" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                @if(Auth::user()->hasRole('Administrador Leca'))
                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                @endif
                
            </template>
        </table-column>
    </table-component>
</div>