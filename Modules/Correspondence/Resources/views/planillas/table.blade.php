<div class="table-responsive">
    <table-component
        id="planillas-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="planillas"
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
        <table-column show="created_at" label="@lang('Fecha de creación')"></table-column>

        <table-column show="consecutivo" label="@lang('Consecutivo')"></table-column>

        <table-column show="planilla_ruta.nombre_ruta" label="@lang('Nombre de la ruta')"></table-column>

        <table-column show="rango_planilla" label="@lang('Rango de la planilla')"></table-column>

        <table-column show="tipo_correspondencia" label="@lang('Tipo de correspondencia')"></table-column>

        <table-column show="nombre_usuario" label="@lang('Nombre del creador')"></table-column>

        <table-column show="estado" label="@lang('Estado')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- Acción para ver la planilla --}}
                <button @click="searchFields.id = row.id; exportDataTable('pdf'); searchFields.id = '';" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Ver planilla">
                    <i class="fa fa-file-pdf"></i>
                </button>

                {{-- Acción para cambiar el estado de la planilla a público --}}
                <button v-show="row.estado == 'Elaboración'" @click="$swal({
                                    icon: 'info',
                                    title: 'La planilla pasará a estado público',
                                    showCancelButton: true,
                                    buttonsStyling: false,
                                    confirmButtonText: 'Aceptar',
                                    cancelButtonText: 'Cancelar',
                                    customClass: {
                                        confirmButton: 'btn m-r-5 btn-danger',
                                        cancelButton: 'btn btn-default'
                                    }
                                })
                                .then((res) => {
                                    // Valida si la opcion selecionada es positiva
                                    if (res.value) {
                                        dataForm = row;
                                        cerrarModal = false;
                                        update();
                                    }
                                });" 
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Publicar planilla">
                    <i class="fa fa-check-circle"></i>
                </button>

                {{-- Acción para eliminar la planilla --}}
                <button v-show="row.estado == 'Elaboración'" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop') planilla">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>