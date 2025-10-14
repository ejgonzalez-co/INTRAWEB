<div class="table-responsive">
    <table-component
        id="notificaciones-mail-intrawebs-table"
        :data="dataList"
        sort-by="notificaciones-mail-intrawebs"
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
                    <table-column show="created_at" label="Fecha"></table-column>

                    <table-column show="modulo" label="Módulo de origen"></table-column>

                    <table-column show="consecutivo" label="@lang('Consecutivo')"></table-column>

                    <table-column show="correo_destinatario" label="Correo del destinatario"></table-column>

                    <table-column label="Apertura del documento"> 
                        <template slot-scope="row">
                            <div v-if="row.leido == 'Si'" class="bg-green"
                                style="text-align: center; width: 200px; color: white;">
                                <strong>@{{ row . leido  }}</strong>
                            </div>
                            
                            <div v-else class="bg-gray"
                                style="text-align: center; width: 200px; color: white;">
                                <strong>No aplica</strong>
                            </div>
                        </template>
                    </table-column>

                    <table-column label="Estado de la notificación"> 
                    <template slot-scope="row">
                        <div v-if="row.estado_notificacion == 'Enviado' || row.estado_notificacion == 'Enviado por el correo institucional'" class="bg-green" 
                            style="text-align: center; width: 200px; color: white;">
                            <strong>@{{ row . estado_notificacion  }}</strong>
                        </div>

                        <div v-else class="bg-red"
                            style="text-align: center; width: 200px; color: white;">
                            <strong>@{{ row . estado_notificacion }}</strong>
                        </div>
                    </template>
                    </table-column>


        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.estado_notificacion == 'Rebote' || row.estado_notificacion == 'No enviado' || row.estado_notificacion == 'Cancelado'" @click="edit(row)" data-backdrop="static" data-target="#modal-reenviar-notificacion" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Reenviar notificación"><i class="fas fa-paper-plane"></i></button>


                <button @click="show(row)" data-target="#modal-view-notificaciones-mail-intrawebs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>