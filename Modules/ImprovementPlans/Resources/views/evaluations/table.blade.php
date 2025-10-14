<div class="table-responsive">
    <table-component id="evaluations-table" :data="dataList" sort-by="evaluations" sort-order="asc"
        table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator" :show-caption="false"
        filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column show="type_evaluation" label="@lang('Type Evaluation')"></table-column>
        <table-column show="evaluation_name" label="@lang('Evaluation Name')"></table-column>
        <table-column show="evaluator" label="@lang('Funcionario Evaluador')">
            <template slot-scope="row">
                <p>@{{ row.evaluator ? row.evaluator.name : '' }}</p>
            </template>
        </table-column>
        <table-column show="unit_responsible_for_evaluation" label="@lang('Departamento Evaluado')"></table-column>
        <table-column show="evaluated" label="@lang('Funcionario evaluado')">
            <template slot-scope="row">
                <p>@{{ row.evaluated ? row.evaluated.name : '' }}</p>
            </template>
        </table-column>
        <table-column show="evaluation_start_date" label="@lang('Fecha inicial')">
            <template slot-scope="row">
                <p>@{{ formatDate(row.evaluation_start_date) }}</p>
            </template>
        </table-column>
        <table-column show="status" label="@lang('Estado')">
            
            <template slot-scope="row">
                <p v-if="row.status == 'Cerrada'" class="button__status-approved p-5">
                    @{{ row.status}}</p> 
                <p v-if="row.status == 'Programada'" class="button__status-pending p-5">
                    @{{ row.status}}</p>
                <p v-if="row.status == 'En proceso'" class="button__status-in_review p-5">
                    @{{ row.status}}</p>
            </template>
            
        </table-column>        
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

             
                
                @if(Auth::user()->hasRole('Evaluador'))
                    <button v-if="initValues.can_manage && (row.status == 'Programada' ||  row.status == 'En proceso' || row.status=='Cerrada')" @click="edit(row)" data-backdrop="static" data-target="#modal-form-evaluations"
                        data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    @else   
                    <button v-if="initValues.can_manage && (row.status == 'Programada' ||  row.status == 'En proceso')" @click="edit(row)" data-backdrop="static" data-target="#modal-form-evaluations"
                        data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>                    
                @endif

                <button v-if="initValues.can_manage && row.status != 'Cerrada'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-execute-evaluation-processes" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('Ejecutar proceso de evaluación')"><i class="fas fa-address-book"></i></button>

                {{-- Oportunidades de mejora --}}
                {{-- <a v-if="row.is_accordance == 'No'" :href="'improvement-opportunities?evaluation=' + row.id"> --}}
                <a :href="'improvement-opportunities?evaluation=' + row.id" target="_blank" v-if="row.status != 'Cerrada'" >
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('Ver oportunidades de mejora')">
                        <i class="fas fa-folder-plus"></i>
                    </button>
                </a>

                {{-- Enviar --}}
                <button v-if="row.status == 'En proceso'"
                    @click="callFunctionComponent('send-improvement-plan','openConfirmationModal',row.id)"
                    class="btn btn-white btn-icon btn-md" title="@lang('Enviar plan de mejoramiento al responsable del proceso')">
                    <i class="fas fa-paper-plane"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-evaluations" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="initValues.can_manage && row.status == 'Pendiente'" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                    data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>


            </template>
        </table-column>
    </table-component>
</div>
