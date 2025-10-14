<div class="table-responsive">
    <table-component id="administrationCostItems-table" :data="advancedSearchFilterPaginate()"
        sort-by="administrationCostItems" sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false"
        :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="type" label="@lang('Type')"></table-column>
        <table-column show="code" label="@lang('Code')"></table-column>
        <table-column show="name" label="@lang('Name')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">


                <button
                    v-if="row.name=='Ensayo Aluminio' || row.name=='Ensayo Nitratos' || row.name=='Ensayo Nitritos' ||row.name=='Ensayo Fosfatos' || row.name=='Ensayo Hierro' ||row.name=='Ensayo Carbono Orgánico Total' || row.name=='Ensayo Plomo' || row.name=='Ensayo Cadmio' || row.name=='Ensayo Mercurio' || row.name=='Ensayo Hidrocarburos' || row.name=='Ensayo Plaguicidas' || row.name=='Ensayo Trialometanos'"
                    @click="show(row)" data-target="#modal-view-espectro" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo de Color' || row.name=='Ensayo pH' || row.name=='Ensayo de Olor' || row.name=='Ensayo de Conductividad' || row.name=='Ensayo de Sustancias Flotantes'"
                    @click="show(row)" data-target="#modal-view-lectura" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Turbiedad'" @click="show(row)" data-target="#modal-view-turbiedad"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Alcalinidad Total'" @click="show(row)"
                    data-target="#modal-view-alcalinidad" data-toggle="modal" class="btn btn-white btn-icon btn-md m-3"
                    data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Cloruro'" @click="show(row)" data-target="#modal-view-cloruro"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Sulfatos'" @click="show(row)" data-target="#modal-view-sulfato"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Calcio'" @click="show(row)" data-target="#modal-view-calcio"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Dureza Total'" @click="show(row)" data-target="#modal-view-dureza"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Cloro Residual'" @click="show(row)" data-target="#modal-view-cloro"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Acidez'" @click="show(row)" data-target="#modal-view-acidez"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.name=='Ensayo Fluoruros'" @click="show(row)" data-target="#modal-view-fluoruro"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button
                    v-if="row.name=='Ensayo Coliformes totales' || row.name=='Ensayo de Escherichia coli' || row.name=='Ensayo Bacterias heterotróficas (Mesófilos)'"
                    @click="show(row)" data-target="#modal-view-micro" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button
                    v-if="row.name=='Ensayo de Sólidos (Sólidos totales disueltos)'"
                    @click="show(row)" data-target="#modal-view-solido" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button
                v-if="row.name=='Ensayo de Sólidos (Sólidos totales suspendidos secos)'"
                @click="show(row)" data-target="#modal-view-solido-secos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title="@lang('see_details')">
                <i class="fa fa-search"></i>
                </button>

                <a v-if="row.name=='Ensayo Alcalinidad Total'" :href="'{!! url('leca/ensayo-alcalinidad') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Aluminio'" :href="'{!! url('leca/ensayo-aluminio') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Hierro'" :href="'{!! url('leca/ensayo-hierro') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Fosfatos'" :href="'{!! url('leca/ensayo-fosfato') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Nitritos'" :href="'{!! url('leca/ensayo-nitrito') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Nitratos'" :href="'{!! url('leca/ensayo-nitrato') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Fluoruros'" :href="'{!! url('leca/ensayo-fluoruro') !!}'"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Sulfatos'" :href="'{!! url('leca/ensayo-sulfatos') !!}'"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo de Sólidos (Sólidos totales disueltos)'" :href="'{!! url('leca/ensayo-disueltos') !!}'"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo de Sólidos (Sólidos totales suspendidos secos)'" :href="'{!! url('leca/ensayo-secos') !!}'"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Coliformes totales'" :href="'{!! url('leca/ensayo-coliformes') !!}'"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo de Escherichia coli'" :href="'{!! url('leca/ensayo-escherichia') !!}'"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Bacterias heterotróficas (Mesófilos)'" :href="'{!! url('leca/ensayo-heterotroficas') !!}'"
                class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Carbono Orgánico Total'" :href="'{!! url('leca/ensayo-carbono') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Acidez'" :href="'{!! url('leca/ensayo-acidez') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Cloruro'" :href="'{!! url('leca/ensayo-cloruro') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Calcio'" :href="'{!! url('leca/ensayo-calcio') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Cloro Residual'" :href="'{!! url('leca/ensayo-cloro') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Dureza Total'" :href="'{!! url('leca/ensayo-dureza') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Turbiedad'" :href="'{!! url('leca/ensayo-turbidez') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Plomo'" :href="'{!! url('leca/ensayo-plomo') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>


                <a v-if="row.name=='Ensayo Cadmio'" :href="'{!! url('leca/ensayo-cadmio') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>


                <a v-if="row.name=='Ensayo Mercurio'" :href="'{!! url('leca/ensayo-mercurio') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Hidrocarburos'" :href="'{!! url('leca/ensayo-hidrocarburos') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo Plaguicidas'" :href="'{!! url('leca/ensayo-plaguicidas') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>


                <a v-if="row.name=='Ensayo Trialometanos'" :href="'{!! url('leca/ensayo-trialometanos') !!}'"
                    class="btn btn-white btn-icon btn-md m-3" data-toggle="tooltip" data-placement="top"
                    title=" Empezar rutina de ensayo"><i class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo de Color'"
                    :href="'{!! url('leca/ensayo-color') !!}'" class="btn btn-white btn-icon btn-md m-3"
                    data-toggle="tooltip" data-placement="top" title=" Empezar rutina de ensayo"><i
                        class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo de Olor'"
                :href="'{!! url('leca/ensayo-olor') !!}'" class="btn btn-white btn-icon btn-md m-3"
                data-toggle="tooltip" data-placement="top" title=" Empezar rutina de ensayo"><i
                    class="fas fa-cog"></i></a>


                <a v-if="row.name=='Ensayo Turbidez'"
                :href="'{!! url('leca/ensayo-turbidez') !!}'" class="btn btn-white btn-icon btn-md m-3"
                data-toggle="tooltip" data-placement="top" title=" Empezar rutina de ensayo"><i
                    class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo de Conductividad'"
                :href="'{!! url('leca/ensayo-conductividad') !!}'" class="btn btn-white btn-icon btn-md m-3"
                data-toggle="tooltip" data-placement="top" title=" Empezar rutina de ensayo"><i
                    class="fas fa-cog"></i></a>


                <a v-if="row.name=='Ensayo pH'"
                :href="'{!! url('leca/ensayo-ph') !!}'" class="btn btn-white btn-icon btn-md m-3"
                data-toggle="tooltip" data-placement="top" title=" Empezar rutina de ensayo"><i
                    class="fas fa-cog"></i></a>

                <a v-if="row.name=='Ensayo de Sustancias Flotantes'"
                :href="'{!! url('leca/ensayo-sustancias') !!}'" class="btn btn-white btn-icon btn-md m-3"
                data-toggle="tooltip" data-placement="top" title=" Empezar rutina de ensayo"><i
                    class="fas fa-cog"></i></a>

            </template>
        </table-column>
        </table-column>
    </table-component>
</div>
