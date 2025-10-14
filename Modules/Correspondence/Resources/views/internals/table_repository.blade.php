<div class="table-responsive">
    <table-component
        id="p-q-r-s-table"
        :data="dataList"
        sort-by="p-q-r-s"
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
            

        <table-column show="consecutivo" label="@lang('Consecutive')"></table-column>
        <table-column show="titulo" label="@lang('Title')"></table-column>

        <table-column show="estado" label="@lang('State')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado" v-if="row.estado=='Elaboración'"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.estado" v-if="row.estado=='Revisión'"></div>
                <div class="text-white text-center p-4 bg-cyan states_style" v-html="row.estado" v-if="row.estado=='Aprobación'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado" v-if="row.estado=='Pendiente de firma'"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.estado" v-if="row.estado=='Devuelto para modificaciones'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado" v-if="row.estado=='Público'"></div>

                {{-- <div class="button__status-in_review states_style" v-html="row.state" v-if="row.state=='Elaboración'"></div>
                <div class="button__status-assigned states_style" v-html="row.state" v-if="row.state=='Revisión'"></div>
                <div class="button__status-pending_approval states_style" v-html="row.state" v-if="row.state=='Aprobación'"></div>
                <div class="button__status-pending states_style" v-html="row.state" v-if="row.state=='Pendiente de firma'"></div>
                <div class="button__status-cancelled states_style" v-html="row.state" v-if="row.state=='Devuelto para modificaciones'"></div>
                <div class="button__status-approved states_style" v-html="row.state" v-if="row.state=='Público'"></div> --}}
            </template>
        </table-column>

        <table-column show="funcionario_remitente" label="Enviado por"></table-column>
        <table-column show="funcionario_destinatario" label="Enviado a"></table-column>
        <table-column show="dependencia_remitente" label="Dependencia"></table-column>

        
        <table-column show="cf_created" label="@lang('Created_at')"></table-column>

        <table-column show="pdf" label="Adjuntos" cell-class="col-sm-2">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.pdf">

                    <viewer-attachement :link-file-name="true" type="only-link" 
                    :ref="row.consecutivo" :component-reference="row.consecutivo"
                    :list="row.pdf" 
                    :key="row.pdf" 
                    :name="row.consecutivo"></viewer-attachement>

                </div>
                    <div v-else>
                        <span>No tiene adjuntos</span>
                    </div>
                </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                {{-- Valida que el usuario que esta en sesión NO sea un consultor de requerimientos --}}

                <button @click="show(row);" data-target="#modal-view-p-q-r-s" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>