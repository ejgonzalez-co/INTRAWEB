<div class="table-responsive">
    <table-component
        id="listTrials-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="listTrials"
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
        <table-column show="type" label="@lang('Type')"></table-column>
            <table-column show="code" label="@lang('Code')"></table-column>
            <table-column show="name" label="@lang('Name')"></table-column>
            <table-column show="description" label="@lang('Description')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-listTrials" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button> --}}

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}

                <button v-if="row.name=='Ensayo Nitritos'" @click="edit(row)" data-target="#modal-generalities-nitritos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Nitritos">
                <i class="fas fa-cog"></i>
                </button>
                
                <button v-if="row.name=='Ensayo Plomo'" @click="edit(row)" data-target="#modal-generalities-plomo" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Plomo">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Cadmio'" @click="edit(row)" data-target="#modal-generalities-cadmio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Cadmio">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Mercurio'" @click="edit(row)" data-target="#modal-generalities-mercurio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Mercurio">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Cloruro'" @click="edit(row)" data-target="#modal-generalities-cloruro" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Cloruro">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Hidrocarburos'" @click="edit(row)" data-target="#modal-generalities-hidrocarburos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Hidrocarburos ">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Plaguicidas'" @click="edit(row)" data-target="#modal-generalities-plaguicidas" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Plaguicidas ">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Nitratos'" @click="edit(row)" data-target="#modal-generalities-nitratos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Nitratos">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Hierro'" @click="edit(row)" data-target="#modal-generalities-hierro" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Hierro">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Fosfatos'" @click="edit(row)" data-target="#modal-generalities-fosfatos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Fosfatos">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Aluminio'" @click="edit(row)" data-target="#modal-generalities-aluminio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Aluminio">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Trialometanos'" @click="edit(row)" data-target="#modal-generalities-trialometanos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Trialometanos">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Cloro Residual'" @click="edit(row)" data-target="#modal-generalities-cloro-residual" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Cloro Residual">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Calcio'" @click="edit(row)" data-target="#modal-generalities-calcio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Calcio">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Dureza Total'" @click="edit(row)" data-target="#modal-generalities-dureza-total" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Dureza Total">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Acidez'" @click="edit(row)" data-target="#modal-generalities-acidez" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Acidez">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Fluoruros'" @click="edit(row)" data-target="#modal-generalities-fluoruros" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Fluoruros">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Sulfatos'" @click="edit(row)" data-target="#modal-generalities-sulfatos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Sulfatos">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Alcalinidad Total'" @click="edit(row)" data-target="#modal-generalities-alcalinidad" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Alcalinidad Total">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Turbiedad'" @click="edit(row)" data-target="#modal-generalities-turbidez" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Turbiedad">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo pH'" @click="edit(row)" data-target="#modal-generalities-ph" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Ph">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Coliformes totales'" @click="edit(row)" data-target="#modal-generalities-coliformes-totales" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Coliformes totales">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo de Escherichia coli'" @click="edit(row)" data-target="#modal-generalities-escherichia-coli" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo de Escherichia coli">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo Bacterias heterotróficas (Mesófilos)'" @click="edit(row)" data-target="#modal-generalities-bacterias-heterotroficas" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Bacterias heterotróficas (Mesófilos)">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo de Olor'" @click="edit(row)" data-target="#modal-generalities-olor" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo de Olor">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo de Conductividad'" @click="edit(row)" data-target="#modal-generalities-conductividad" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo de Conductividad">
                <i class="fas fa-cog"></i>
                </button>

                {{-- <button v-if="row.name=='Ensayo de Sustancias Flotantes'" @click="edit(row)" data-target="#modal-generalities-sustancias-flotantes" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo de Sustancias Flotantes">
                <i class="fas fa-cog"></i>
                </button> --}}

                <button v-if="row.name=='Ensayo de Color'" @click="edit(row)" data-target="#modal-generalities-color" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo de Color">
                <i class="fas fa-cog"></i>
                </button>
                

                <button v-if="row.name=='Ensayo Carbono Orgánico Total'" @click="edit(row)" data-target="#modal-generalities-carbono-organico" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo Carbono Orgánico Total">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo de Sólidos (Sólidos totales disueltos)'" @click="edit(row)" data-target="#modal-generalities-solidos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo de Sólidos (Sólidos totales disueltos)">
                <i class="fas fa-cog"></i>
                </button>

                <button v-if="row.name=='Ensayo de Sólidos (Sólidos totales suspendidos secos)'" @click="edit(row)" data-target="#modal-generalities-solidos-secos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Ensayo de Sólidos (Sólidos totales suspendidos secos)">
                <i class="fas fa-cog"></i>
                </button>

 <button v-if="row.name !='Ensayo de Sustancias Flotantes'" @click="edit(row,'/blanco-'+row.name)" data-target="#modal-blancos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Blanco">
                {{-- <i class="fa-solid fa-circle-b"></i> --}}
                <i class="fas fa-bold"></i>
                </button>

                <button v-if="row.name != 'Ensayo Alcalinidad Total' 
                && row.name != 'Ensayo de Sustancias Flotantes'
                && row.name != 'Ensayo Bacterias heterotróficas (Mesófilos)'
                && row.name != 'Ensayo de Escherichia coli'
                && row.name != 'Ensayo Coliformes totales'
                "  @click="edit(row,'/patron-'+row.name)" data-target="#modal-patron" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Patrón">
                <i class="fas fa-parking"></i></button>
                {{-- <i class="fa-solid fa-circle-p"></i> --}}
                </button>

                <button v-else-if="row.name != 'Ensayo de Sustancias Flotantes'
                && row.name != 'Ensayo Bacterias heterotróficas (Mesófilos)'
                && row.name != 'Ensayo de Escherichia coli'
                && row.name != 'Ensayo Coliformes totales'" @click="edit(row,'/patron-'+row.name)" data-target="#modal-patron-alcalinidad" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Patrón">
                <i class="fas fa-parking"></i></button>
                {{-- <i class="fa-solid fa-circle-p"></i> --}}
                </button>

                <button @click="show(row)" data-target="#modal-view-listTrials" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                
                <button v-if="row.name=='Ensayo Nitritos'" @click="show(row)" data-target="#modal-history-nitritos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Nitratos'" @click="show(row)" data-target="#modal-history-nitratos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>



                <button v-if="row.name=='Ensayo Cadmio'" @click="show(row)" data-target="#modal-history-cadmio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Mercurio'" @click="show(row)" data-target="#modal-history-mercurio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>


                <button v-if="row.name=='Ensayo Plaguicidas'" @click="show(row)" data-target="#modal-history-plaguicidas" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Trialometanos'" @click="show(row)" data-target="#modal-history-trialometanos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Hierro'" @click="show(row)" data-target="#modal-history-hierro" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Fosfatos'" @click="show(row)" data-target="#modal-history-fosfatos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Aluminio'" @click="show(row)" data-target="#modal-history-aluminio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>


                <button v-if="row.name=='Ensayo Plomo'" @click="show(row)" data-target="#modal-history-plomo" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Hidrocarburos'" @click="show(row)" data-target="#modal-history-hidrocarburos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>
                
                <button v-if="row.name=='Ensayo Cloruro'" @click="show(row)" data-target="#modal-history-cloruro" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Cloro Residual'" @click="show(row)" data-target="#modal-history-cloro-residual" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Calcio'" @click="show(row)" data-target="#modal-history-calcio" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Dureza Total'" @click="show(row)" data-target="#modal-history-dureza-total" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Acidez'" @click="show(row)" data-target="#modal-history-acidez" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Fluoruros'" @click="show(row)" data-target="#modal-history-fluoruros" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Sulfatos'" @click="show(row)" data-target="#modal-history-sulfatos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Alcalinidad Total'" @click="show(row)" data-target="#modal-history-alcalinidad" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Turbiedad'" @click="show(row)" data-target="#modal-history-turbidez" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo pH'" @click="show(row)" data-target="#modal-history-ph" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Coliformes totales'" @click="show(row)" data-target="#modal-history-coliformes-totales" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo de Escherichia coli'" @click="show(row)" data-target="#modal-history-escherichia-coli" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>
                
                <button v-if="row.name=='Ensayo Bacterias heterotróficas (Mesófilos)'" @click="show(row)" data-target="#modal-history-bacterias-heterotroficas" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo de Color'" @click="show(row)" data-target="#modal-history-color" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo de Olor'" @click="show(row)" data-target="#modal-history-olor" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo de Conductividad'" @click="show(row)" data-target="#modal-history-conductividad" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo de Sustancias Flotantes'" @click="show(row)" data-target="#modal-history-sustancias-flotantes" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo Carbono Orgánico Total'" @click="show(row)" data-target="#modal-history-carbono-organico" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo de Sólidos (Sólidos totales disueltos)'" @click="show(row)" data-target="#modal-history-solidos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                <button v-if="row.name=='Ensayo de Sólidos (Sólidos totales suspendidos secos)'" @click="show(row)" data-target="#modal-history-solidos-secos" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>