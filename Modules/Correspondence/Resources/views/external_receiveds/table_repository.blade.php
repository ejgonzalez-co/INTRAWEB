<div class="table-responsive">
    <table-component
        id="external-receiveds-table"
        :data="dataList"
        sort-by="external-receiveds"
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
            
        <table-column show="cf_created" label="Fecha creación"></table-column>

        <table-column show="consecutivo" label="@lang('Consecutive')"></table-column>

        <table-column show="tipodoc" label="@lang('Tipo documento')"></table-column>

        <table-column show="estado" label="@lang('State')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado" v-if="row.estado=='Elaboración'"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.estado" v-if="row.estado=='Revisión'"></div>
                <div class="text-white text-center p-4 bg-cyan states_style" v-html="row.estado" v-if="row.estado=='Aprobación'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado" v-if="row.estado=='Pendiente de firma'"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.estado" v-if="row.estado=='Devuelto para modificaciones'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado" v-if="row.estado=='Público'"></div>

            </template>
        </table-column>
        <table-column show="canal" label="@lang('Canal')"></table-column>


    
        <table-column show="recibidode" label="Ciudadano"></table-column>
        
        <table-column show="dependencia_destinataria" label="Dependencia"></table-column>

        <table-column show="funcionario_destinatario" label="Funcionario Destinatario"></table-column>

        <table-column show="radicadopor" label="Radicó"></table-column>


        <table-column show="adjunto" label="Adjuntos" cell-class="col-sm-2">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.adjunto">

                    <viewer-attachement :link-file-name="true" type="only-link" 
                    :ref="row.consecutivo" :component-reference="row.consecutivo"
                    :list="row.adjunto" 
                    :key="row.adjunto" 
                    :name="row.consecutivo"></viewer-attachement>

                </div>
                    <div v-else>
                        <span>No tiene adjuntos</span>
                    </div>
                </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                <button @click="show(row);" data-target="#modal-view-external-receiveds" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
            </template>
        </table-column>
    </table-component>
</div>