<div class="table-responsive">
    <table-component
        id="listado-correos-checkeos-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="listado-correos-checkeos"
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

        <table-column show="fecha_verificacion" label="@lang('Fecha de verificación')"></table-column>

        <table-column show="email" label="@lang('Correo')"></table-column>

                    <table-column show="user_name" label="@lang('Usuario')"></table-column>
                    
                    <table-column label="Estado"> 
                        <template slot-scope="row">
                            <div v-if="row.estado == 'Valida'" class="bg-green"
                                style="text-align: center; width: 200px; color: white;">
                                <strong>Válido</strong>
                            </div>
    
                            <div v-else class="bg-red"
                                style="text-align: center; width: 200px; color: white;">
                                <strong>No válido</strong>
                            </div>
                        </template>
                        </table-column>


        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-listado-correos-checkeos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-listado-correos-checkeos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>