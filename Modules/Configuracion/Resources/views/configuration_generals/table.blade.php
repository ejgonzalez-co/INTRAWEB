<div class="table-responsive">
    <table-component
        id="configuration-generals-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="configuration-generals"
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

        <table-column show="nombre_entidad" label="Nombre de la entidad"></table-column>

        <table-column show="logo" label="Logo">
            <template slot-scope="row">
                <a v-if="row.logo" :href="'/storage/'+row.logo" target="_blank"><img :src="'/storage/'+row.logo" alt="Ícono" style="width: 200px;"></a>
                <img v-else src="{{ asset('assets/img/components/sin_imagen.jpg')}}" alt="Ícono" style="width: 200px;">
            </template>
        </table-column>

        <table-column show="color_barra" label="Color de la barra superior">
            <template slot-scope="row" >
                <div :style="'width:100px; height: 100px;background-color:'+row.color_barra"></div>
                <div v-html="row.color_barra"></div>

            </template>
        </table-column>

        <table-column show="imagen_fondo" label="Imagen de fondo Login">
            <template slot-scope="row">
                <a v-if="row.imagen_fondo" :href="'/storage/'+row.imagen_fondo" target="_blank"><img :src="'/storage/'+row.imagen_fondo" alt="Ícono" style="width: 200px;"></a>
                <img v-else src="{{ asset('assets/img/components/sin_imagen.jpg')}}" alt="Ícono" style="width: 200px;">
            </template>
        </table-column>

        <table-column show="imagen_fondo" label="Imagen de fondo Login respaldo">
            <template slot-scope="row">
                <a v-if="row.imagen_fondo_responsive" :href="'/storage/'+row.imagen_fondo_responsive" target="_blank"><img :src="'/storage/'+row.imagen_fondo_responsive" alt="Ícono" style="width: 200px;"></a>
                <img v-else src="{{ asset('assets/img/components/sin_imagen.jpg')}}" alt="Ícono" style="width: 200px;">
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-configuration-generals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                {{-- <button @click="show(row)" data-target="#modal-view-configuration-generals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button> --}}

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}
                
            </template>
        </table-column>
    </table-component>
</div>