<div class="table-responsive">
    <table-component
        id="contractNews-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="contractNews"
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

        
        <table-column show="created_at" label="Fecha de registro">
            <template slot-scope="row">
                @{{ formatDate(row.created_at) }} 
            </template>
        </table-column>
        <table-column show="novelty_type" label="Tipo de novedad"></table-column>
        <table-column show="consecutive" label="Consecutivo"></table-column>
  
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if= "row.novelty_type != 'Asignación presupuestal'"  @click="edit(row, '/1')" data-backdrop="static" data-target="#modal-form-contractNews" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-contractNews" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if= "row.novelty_type != 'Asignación presupuestal' && row.novelty_type != 'Adición al contrato' " 
                    @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

                {{-- <a  v-if= "row.novelty_type != 'Asignación presupuestal'" :href="'{!! url('maintenance/elimina') !!}?mpc=' + '1' + '&&data=' + row.id" class="btn btn-white btn-icon btn-md"
                data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash"></i></a> --}}

                  <!-- icono Historial que se muestra en las filas de la tabla. -->
                  <button @click="show(row)" data-target="#modal-history" data-toggle="modal"
                  class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                  title="Historial">
                  <i class="fa fa-history"></i>
              </button>
                
            </template>
        </table-column>
    </table-component>
</div>