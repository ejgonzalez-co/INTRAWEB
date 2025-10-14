<div><strong>Anotaciones: </strong></div>

<section id="timeline">
    <article v-for="(anotacion,key) in dataList">
        <div class="inner">
            <span class="date">
                <span class="day"> @{{ anotacion.date_format['day'] }}</span>
                <span class="month"> @{{ anotacion.date_format['month'] }}</span>
                <span class="year"> @{{ anotacion.date_format['year'] }}</span>
            </span>
            <h2 style="display: flex; align-items: center;">
                {{-- <strong style="background: rgba(0, 0, 0, 0.17); padding: 10px;">@{{ key + 1 }}</strong> --}}
                <div style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%; margin-right: 10px;">
                    <img v-if="anotacion.users.url_img_profile !== '' && anotacion.users.url_img_profile !== 'users/avatar/default.png'"
                        :src="'{{ asset('storage') }}/' + anotacion.users.url_img_profile" alt=""
                        style="width: 100%; height: auto;">
                    <img v-else src="{{ asset('assets/img/user/profile.png') }}" alt=""
                        style="width: 100%; height: auto;">
                </div>
                <span style="width: 80%;">@{{ anotacion.nombre_usuario }}</span>
            </h2>

            <p>
                <strong>Fecha y hora:</strong> @{{ anotacion.date_format['fecha_completo'] }}<br>
                <strong>Anotación:</strong> @{{ anotacion.anotacion }}<br>
            </p>
        </div>
    </article>
</section>

{{-- <div class="table-responsive">
    <table-component
        id="documento-anotacions-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="documento-anotacions"
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
        <table-column show="nombre_usuario" label="@lang('nombre_usuario')"></table-column>

                    <table-column show="anotacion" label="@lang('anotacion')"></table-column>

                    <table-column show="vigencia" label="@lang('vigencia')"></table-column>

                    <table-column show="leido_por" label="@lang('leido_por')"></table-column>

                    <table-column show="de_documento_id" label="@lang('de_documento_id')"></table-column>

                    <table-column show="users_id" label="@lang('users_id')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-documento-anotacions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-documento-anotacions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div> --}}
