<div class="table-responsive">
    <table-component
        id="request-need-orders-table"
        :data="dataList"
        sort-by="request-need-orders"
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

        <table-column show="consecutivo" label="Consecutivo de la orden">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.consecutivo">
                    <span v-html="row.consecutivo"></span>
                </div>
                <div v-else>
                    <span>Aún no tiene consecutivo</span>
                </div>
            </template>
        </table-column>

        <table-column show="solicitud_consecutivo" label="Consecutivo de la solicitud de necesidad">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div>
                    <span>@{{ row.solicitud_principal ? row.solicitud_principal.consecutivo : "N/E" }}</span>
                </div>
            </template>
        </table-column>


        <table-column show="updated_at" label="Fecha"></table-column>

        <table-column show="ordenes_item" label="@lang('Descripción')">
            <template slot-scope="row">
                <ul v-for="(order_item,key) in row.ordenes_item" :key="order_item.id || key">
                    <li>@{{ order_item.descripcion_nombre }} - @{{ order_item.cantidad }} - @{{ order_item.unidad }}</li>
                </ul>
            </template>
        </table-column>

        {{-- <table-column show="tipo_solicitud" label="@lang('Tipo Solicitud')"></table-column> --}}

        <table-column show="estado" label="@lang('State')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado" v-if="row.estado=='Orden en elaboración'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado" v-if="row.estado=='Orden en trámite'"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.estado" v-if="row.estado=='Orden Cancelada'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado" v-if="row.estado=='Orden Finalizada'"></div>
            </template>
        </table-column>

        @role('mant Proveedor interno')

        <table-column show="estado_proveedor" label="@lang('State') proveedor" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado_proveedor" v-if="row.estado_proveedor=='Pendiente'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado_proveedor" v-if="row.estado_proveedor=='Finalizado'"></div>
            </template>
        </table-column>

        @endrole

        @if (Auth::check() && (Auth::user()->hasRole('mant Almacén Aseo') || Auth::user()->hasRole('mant Almacén CAM')))

        <table-column show="tramite_almacen" label="Trámite en almacén" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.tramite_almacen" v-if="row.tramite_almacen=='Entrada Pendiente'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.tramite_almacen" v-if="row.tramite_almacen=='Salida Pendiente'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.tramite_almacen" v-if="row.tramite_almacen=='Salida Confirmada'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.tramite_almacen" v-if="row.tramite_almacen=='Entrada Confirmada'"></div>
            </template>
        </table-column>
        @endif

        @role('Administrador de mantenimientos')

        <table-column show="rol_asignado_nombre" label="Rol asignado">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.rol_asignado_nombre">
                    <p>@{{ row.rol_asignado_nombre.includes(" - ") ? row.rol_asignado_nombre.split("-")[2] : row.rol_asignado_nombre }}</p>
                </div>
                <div v-else>
                    <span>Aún no tiene rol asignado</span>
                </div>
            </template>
        </table-column>

        <table-column show="estado_proveedor_almacen" label="Estado proveedor / Almacén" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado_proveedor"
                    v-if="row.estado_proveedor=='Pendiente'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado_proveedor"
                    v-else-if="row.estado_proveedor=='Salida Pendiente'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado_proveedor"
                    v-else-if="row.estado_proveedor=='Finalizado'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado_proveedor"
                    v-else-if="row.estado_proveedor=='Entrada Confirmada'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado_proveedor"
                    v-else-if="row.estado_proveedor=='Salida Confirmada'"></div>
                <div class="text-white text-center p-4 bg-orange states_style"
                    v-else-if="row.estado_proveedor=='Pendiente por finalizar'">Pendiente</div>
                <div class="text-white text-center p-4 bg-gray states_style"
                    v-else>N/A</div>
            </template>
        </table-column>

        @endrole

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
             @role('Administrador de mantenimientos')
             <button @click="edit(row)" v-if="row.estado === 'Orden en elaboración'" data-backdrop="static" data-target="#send-request-need-order" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Enviar solicitud a revisión"><i class="fas fa-paper-plane"></i></button>

             <button @click="edit(row)" v-if="(row.estado_proveedor === 'Salida Confirmada' || row.estado_proveedor === 'Entrada Confirmada' || row.estado_proveedor == 'Finalizado') && row.estado !== 'Orden Finalizada'" data-backdrop="static" data-target="#modal-form-request-order" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Finalizar orden"><i class="fas fa-paper-plane"></i></button>
                <button @click="edit(row)" v-if="row.estado === 'Orden en elaboración'" data-backdrop="static" data-target="#modal-form-request-need-orders" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="drop(row[customId])" v-if="row.estado === 'Orden en elaboración'" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
            @endrole

                @role('mant Proveedor interno')

                    <button @click="edit(row)" v-if="row.estado_proveedor === 'Pendiente'" data-backdrop="static" data-target="#finish-state-provider" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Finalizar Orden"><i class="fas fa-check"></i></button>

                @endrole

                @if (Auth::check() && (Auth::user()->hasRole('mant Almacén Aseo') || Auth::user()->hasRole('mant Almacén CAM')))


                    {{-- <button @click="edit(row)" v-if="row.tramite_almacen === 'Entrada Pendiente'"  data-backdrop="static" data-target="#modal-form-entrada" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Entrada">
                        <i class="fas fa-arrow-down"></i> 
                    </button> --}}


                    <button
                        @click="edit(row)"
                        v-if="row.tramite_almacen === 'Entrada Pendiente'"
                        data-backdrop="static"
                        data-target="#modal-form-entrada"
                        data-toggle="modal"
                        class="btn btn-white btn-icon btn-md position-relative"
                        data-placement="top"
                        
                        :title="row.processing_additions?.length > 0 ? 'Entrada (Tiene adiciones en trámite)' : 'Entrada'"
                    >
                        <i class="fas fa-arrow-down"></i>

                        <span
                            v-if="row.processing_additions?.length > 0"
                            class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle"
                        >
                            @{{ row.processing_additions.length }}
                        </span>
                    </button>

                    
                    <button @click="edit(row)" v-if="row.tramite_almacen === 'Salida Pendiente'"  data-backdrop="static" data-target="#modal-form-salida" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Salida">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    
                    <button @click="edit(row)" v-if="row.no_factura == 1 || ((!row.numero_factura || !row.numero_entrada_almacen) && row.tramite_almacen === 'Entrada Confirmada')"  data-backdrop="static" data-target="#modal-form-entrada-factura" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Número de factura">
                        <i class="fas fa-file-invoice"></i>
                    </button>

        
                @endif


               <button @click="show(row)" data-target="#modal-view-request-need-orders" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                <i class="fa fa-search"></i>
                </button>


                <a v-if="row.tramite_almacen == 'Entrada Pendiente' || row.tramite_almacen == 'Entrada Confirmada'"
                :href="'generate-gr-r/' + row.id" target="_blank" class="btn btn-white btn-icon btn-md"
                data-placement="top" title="Formato de solicitud de productos">
                    <i class="fa fa-file-excel"> </i>
                </a>

            <a :href="'{!! url('maintenance/document-orders') !!}?od=' + row.encrypted_id" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Documentos"><i class="fas fa-folder-plus"></i></a>

            <button @click="show(row)" data-target="#modal-view-order-history" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Seguimiento y control">
                <i class="fa fa-history"></i>
            </button>
            
                
            </template>
        </table-column>
    </table-component>
</div>