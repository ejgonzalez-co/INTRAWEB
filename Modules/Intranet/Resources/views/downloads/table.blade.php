<div class="table-responsive">
    <table-component
        id="downloads-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="downloads"
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
            <table-column show="title" label="@lang('Title')">
                <template slot-scope="row">
                    <a :href="'/storage/'+row.filename" target="_blank">@{{row.title}}</a>
                </template>
            </table-column>
            <table-column show="image_filename" label="Ícono">
                <template slot-scope="row">
                    <a v-if="row.image_filename" :href="'/storage/'+row.image_filename" target="_blank"><img :src="'/storage/'+row.image_filename" alt="Ícono" style="width: 200px;"></a>
                    <img v-else src="{{ asset('assets/img/components/sin_imagen.jpg')}}" alt="Ícono" style="width: 200px;">
                </template>
            </table-column>
            <table-column show="hits" label="@lang('Descargas')"></table-column>
            <table-column show="owner.name" label="@lang('Propietario')"></table-column>
            <table-column show="users.name" label="@lang('Subido por')"></table-column>
            <table-column show="published" label="@lang('Publicado')"></table-column>
            <table-column show="categorie.title" label="@lang('Categoría')"></table-column>
            <table-column show="ordering" label="@lang('Orden')"></table-column>      
            <table-column label="@lang('Calificación')">
                <template slot-scope="row">
                    <star-rating v-model="row.file_vote ? (row.file_vote.rating ?? row.file_vote) : row.file_vote" :read-only="row.file_vote != null" @rating-selected="$refs.loadAssets.setRating($event, row[customId])" v-bind:max-rating="5" rounded-corners :star-size=20 :show-rating=false></star-rating>
                </template>
            </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @if(!Request::is('intranet/downloads-public*'))
                    @role('Administrador intranet de descargas')
                        <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-downloads" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    @endrole
                @endif
                
                <button @click="show(row)" data-target="#modal-view-downloads" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                @if(!Request::is('intranet/downloads-public*'))
                    @role('Administrador intranet de descargas')
                        <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                            <i class="fa fa-trash"></i>
                        </button>
                    @endrole
                @endif

                <a  @click="callFunctionComponent('loadAssets', 'incrementHits', row[customId])" class="btn btn-white btn-icon btn-md" :href="'/storage/'+row.filename" :download="(row.filename).split('/').pop()">
                    <i class="fa fa-download"></i>
                </a>

            </template>
        </table-column>
    </table-component>
    {{-- Componente para incrementar el número de descargar de un archivo --}}
    <downloads ref="loadAssets"></downloads>
</div>