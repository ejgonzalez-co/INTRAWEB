<div class="table-responsive">
    <table-component id="nonConformingCriterias-table" :data="advancedSearchFilterPaginate()"
        sort-by="nonConformingCriterias" sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false"
        :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="name_opportunity_improvement" label="Oportunidad de mejora"></table-column>
        <table-column show="description_opportunity_improvement" label="Descripción"></table-column>
        <table-column show="weight" label="Peso de la oportunidad de mejora con respecto al plan">
            <template slot-scope="row">
                <p>@{{ row.weight ? row.weight + "%" : "No establecido" }}</p>
            </template>
        </table-column>
        {{-- <table-column show="percentage_execution" label="@lang('% de Ejecución')">
            <template slot-scope="row">
                <p>@{{ row.percentage_execution ? currencyFormat(row.percentage_execution) + "%" : "0%" }}</p>

            </template>
        </table-column> --}}

        <table-column show="percentage_execution" label="@lang('% de Ejecución')">
            <template slot-scope="row">
                <div class="progress" style="width: 100px;" v-cloak>
                    <div class="progress-bar" role="progressbar" :style="'width: ' + row.percentage_execution + '%;'" :aria-valuenow="row.percentage_execution" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p v-cloak>@{{ row.percentage_execution ? currencyFormat(row.percentage_execution) + "%" : "0%" }}</p>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.can_edit" @click="edit(row)" data-target="#modal-form-nonConformingCriterias" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('1° Paso Información general')">
                    <strong>1</strong>
                    <i class="fas fa-flag"></i>
                </button>

                <a :href="'goals?improvement-opportunity=' + row.encrypted_id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('Metas')">
                        <i class="fa fa-chart-line"></i>
                    </button>
                </a>

                <button @click="show(row)" data-target="#modal-view-nonConformingCriterias" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
