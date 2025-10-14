<div class="table-responsive">
    <table-component
        id="paa-calls-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="paa-calls"
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
        <table-column show="validity" label="@lang('Validity')"></table-column>
        <table-column show="name" label="@lang('Name')"></table-column>
        <table-column show="start_date" label="Fecha de inicio"></table-column>
        <table-column show="closing_alert_date" label="Fecha de alerta de cierre"></table-column>
        <table-column show="closing_date" label="Fecha de cierre"></table-column>
        <table-column show="state_name" label="Estado">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <span :style="{ 'background-color': row.state_colour, 'color': '#FFFFFF' }" class="p-5">
                    @{{ row.state_name }}
                </span>
            </template>
        </table-column>
        <table-column label="Acciones" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @if(Auth::user()->hasRole('PC Gestor de recursos'))
                    {{-- Abre el modal para edita la convocatoria --}}
                    <button v-if="row.state != 2" @click="edit(row)" data-backdrop="static" data-target="#modal-form-paa-calls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                    
                    {{-- Ejecuta la accion de eliminar la convocatoria --}}
                    <button v-if="row.state != 2" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>

                    {{-- Abre el modal para ver los procesos con necesidades pendientes --}}
                    <button v-if="row.state != 2" @click="callFunctionComponent('unassessed-needs-paa', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Needs') PAA"><i class="far fa-list-alt"></i></button>

                    {{-- Abre el modal para aprobar el PAA --}}
                    <button v-if="row.state != 2 && row.pending_needs == 0" @click="callFunctionComponent('approve-call-paa', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Approve') PAA"><i class="fas fa-thumbs-up"></i></button>

                    {{-- Abre el modal para cambiar de version el PAA --}}
                    <button v-if="row.state == 2 && row.pending_needs == 0" @click="callFunctionComponent('change-version-paa', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Change Version') PAA"><i class="fas fa-sync-alt"></i></button>


                    {{-- Abre el modal para ver las versiones del PAA --}}
                    <button v-if="row.state == 2 && row.pending_needs == 0" @click="callFunctionComponent('paa-versions', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Versiones PAA"><i class="fas fa-list"></i></button>
                    
                    
                @endif

                {{-- Abre la vista de las necesidades --}}
                <a :href="'needs?call='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Needs')"><i class="fas fa-tools"></i></button>
                </a>

                {{-- Ejecuta la accion de exportar el PAA con las necesidades en excel --}}
                <a v-if="row.state == 2" :href="'export-paa-call/'+row.id">
                    <button class="btn btn-white btn-icon btn-md" title="Exportar PAA"><i class="fas fa-file-excel"></i></button>
                </a>
                
                {{-- Abre el modal de los detalles de la convocatoria --}}
                <button @click="show(row)" data-target="#modal-view-paa-calls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
            </template>
        </table-column>
    </table-component>
</div>