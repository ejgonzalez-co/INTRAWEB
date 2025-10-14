<div class="table-responsive">
    <table-component id="consecutiveSettings-table" :data="advancedSearchFilterPaginate()" sort-by="consecutiveSettings"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">


        {{-- muestra el nuevo consecutivo de partida --}}
        <table-column label="@lang('Consecutivo nuevo')">
            <template slot-scope="row">
                <div v-if="row.nex_consecutiveIE != null ">
                    @{{ 'IE-' + row.nex_consecutiveIE + '-22' }}
                </div>

                <div v-if="row.nex_consecutiveIC != null ">
                    @{{ 'IC-' + row.nex_consecutiveIC + '-22' }}
                </div>
            </template>
        </table-column>
        {{-- muestra la fecha de la creacion del nuevo consecutivo de partida --}}
        </table-column>
        <table-column label="@lang('Fecha')">
            <template slot-scope="row">
                @{{ formatDate(row.created_at) }}
            </template>
        </table-column>
        {{-- muestra el responsable o la persona que creo el nuevo consecutivo de partida --}}
        <table-column show="user_name" label="@lang('Nombre del responsable')"></table-column>
        {{-- Muestra la justificacion, por la cual se genero el nuevo consecutivo --}}
        <table-column show="coments_consecutive" label="@lang('Justificación de cambio')">
            <template slot-scope="row">
                @{{ row.coments_consecutive }}
            </template>
        </table-column>

        {{-- muestra la lista de acciones --}}
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-consecutiveSettings"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button> --}}

                {{-- accion de ver detalla --}}
                <button @click="show(row)" data-target="#modal-view-consecutiveSettings" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}

            </template>
        </table-column>

    </table-component>
</div>
