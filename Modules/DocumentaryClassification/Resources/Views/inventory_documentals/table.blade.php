<div class="table-responsive">
    <table-component
        id="inventoryDocumentals-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="inventoryDocumentals"
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

        <table-column show="created_at" label="Fecha de creación"></table-column>
        <table-column label="Oficina Productora">
            <template slot-scope="row">
                <div class="text-left font-weight-bold">
                  <span v-html="row.dependencia.nombre"></span> - <span v-html="row.dependencia.codigo_oficina_productora"></span>
                </div>
              </template>              
        </table-column>

        <table-column label="Serie/subserie documental">
            <template slot-scope="row">
                <div class="text-left font-weight-bold">
                  <span v-html="row.series_osubseries.no_serie"></span>
                  <span v-if="row.series_osubseries.type === 'Serie'"> - <span v-html="row.series_osubseries.name_serie"></span></span>
                  <span v-else> - <span v-html="row.series_osubseries.no_subserie"></span> - <span v-html="row.series_osubseries.name_subserie"></span></span>
                </div>
              </template>
        </table-column>
        <table-column show="description_expedient" label="Descripción"></table-column>
        <table-column label="Sig topográfica">
            <template slot-scope="row">
                <div class="bg">
                    <Strong>Estantería:</Strong>@{{ row.shelving ? row.shelving : 'N/A' }}
                </div>
                <div>
                    <strong>Bandeja: </strong>@{{ row.tray ? row.tray : 'N/A' }}
                </div>
                <div>
                    <strong>Caja: </strong>@{{ row.box ? row.box : 'N/A' }}
                </div>
                <div>
                    <strong>Carpeta: </strong>@{{ row.file ? row.file : 'N/A' }}
                </div>
                <div>
                    <strong>Libro: </strong>@{{ row.book ? row.book : 'N/A' }}
                </div>
            </template>
        </table-column>
        <table-column show="date_initial" label="Fecha extrema inicial"></table-column>
        <table-column show="date_finish" label="Fecha extrema final"></table-column>
        <table-column show="folios" label="Folios"></table-column>
        <table-column show="soport" label="Soportes"></table-column>
        <table-column label="Cantidad de documentos digitales">
            <template slot-scope="row">
                <div class="text-center font-weight-bold"
                    v-html="row.total_documents"></div>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-inventoryDocumentals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-inventoryDocumentals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="callFunctionComponent('metadatos_ref','edit',row)" class="btn btn-white btn-icon btn-md" data-target="#modal-form-metadatos" data-toggle="modal" data-backdrop="static" data-placement="top" title="@lang('message-metadata')">
                    <i class="fa fa-file-invoice"></i>
                </button>
                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('deleteFromRegisterDocumental')">
                    <i class="fa fa-trash"></i>
                </button>

              
                
            </template>
        </table-column>
    </table-component>
</div>