<div class="table-responsive">
    <table-component id="request-need-orders-table" :data="dataList" sort-by="request-need-orders"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">

        <table-column label="Fecha">
            <template slot-scope="row">
                @{{ formatDate(row.created_at) }}
            </template>

        </table-column>

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

        <table-column show="consecutivo" label="Consecutivo de la solicitud de necesidad">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div>
                    <span>@{{ row.solicitud_principal ? row.solicitud_principal.consecutivo : "N/E" }}</span>
                </div>
            </template>
        </table-column>

        <table-column show="numero_contrato" label="Número de contrato">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div>
                    <span>@{{ row.numero_contrato ? row.numero_contrato : "N/A" }}</span>
                </div>
            </template>
        </table-column>


        {{-- <table-column show="tipo_solicitud" label="@lang('Tipo Solicitud')"></table-column> --}}

        <table-column show="estado" label="@lang('State')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado"
                    v-if="row.estado=='Orden en elaboración'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado"
                    v-if="row.estado=='Orden en trámite'"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.estado"
                    v-if="row.estado=='Orden Cancelada'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado"
                    v-if="row.estado=='Orden Finalizada'"></div>
            </template>
        </table-column>



        <table-column show="estado_proveedor" label="@lang('State') proveedor" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado_proveedor"
                    v-if="row.estado_proveedor=='Pendiente'"></div>
                <div class="text-white text-center p-4 bg-orange states_style"
                    v-if="row.estado_proveedor=='Entrada Pendiente'">
                    Pendiente
                </div>
                <div class="text-white text-center p-4 bg-orange states_style"
                    v-if="row.estado_proveedor=='Salida Pendiente'">
                    Pendiente
                </div>
                <div class="text-white text-center p-4 bg-green states_style"
                    v-if="row.estado_proveedor=='Entrada Confirmada'">
                    Finalizado
                </div>
                <div class="text-white text-center p-4 bg-green states_style"
                    v-if="row.estado_proveedor=='Salida Confirmada'">
                    Finalizado
                </div>
                <div class="text-white text-center p-4 bg-orange states_style"
                    v-if="row.estado_proveedor=='Pendiente por finalizar'">Pendiente</div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado_proveedor"
                    v-if="row.estado_proveedor=='Finalizado'"></div>
            </template>
        </table-column>



        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- <button @click="edit(row)" data-backdrop="static"
                data-target="#finish-state-provider" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                data-toggle="tooltip" data-placement="top" title="Finalizar Orden"><i
                    class="fas fa-check"></i></button>

                <button @click="edit(row)" data-backdrop="static"
                data-target="#modal-form-entrada" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                data-toggle="tooltip" data-placement="top" title="Entrada">
                <i class="fas fa-arrow-down"></i>
            </button>


            <button @click="edit(row)" data-backdrop="static"
                data-target="#modal-form-salida" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                data-toggle="tooltip" data-placement="top" title="Salida">
                <i class="fas fa-arrow-up"></i>
            </button> --}}



                @role('mant Proveedor interno')
                    <button @click="edit(row)" v-if="row.estado_proveedor === 'Pendiente'" data-backdrop="static"
                        data-target="#finish-state-provider" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip" data-placement="top" title="Finalizar Orden"><i
                            class="fas fa-check"></i></button>
                @endrole

                @if (Auth::check() && (Auth::user()->hasRole('mant Almacén Aseo') || Auth::user()->hasRole('mant Almacén CAM')))
                    <button @click="edit(row)" v-if="row.tramite_almacen === 'Entrada Pendiente'" data-backdrop="static"
                        data-target="#modal-form-entrada" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip" data-placement="top" title="Entrada">
                        <i class="fas fa-arrow-down"></i>
                    </button>


                    <button @click="edit(row)" v-if="row.tramite_almacen === 'Salida Pendiente'" data-backdrop="static"
                        data-target="#modal-form-salida" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip" data-placement="top" title="Salida">
                        <i class="fas fa-arrow-up"></i>
                    </button>
    
                @endif

                {{-- Acciones para un proveedor externo --}}
                @if (session('outside') )
                    {{-- TODO: Implementar el nuevo formato  --}}
                    {{-- <a
                        :href="'vig-gr-r-026/' + row.id" target="_blank" class="btn btn-white btn-icon btn-md"
                        data-placement="top" title="Formato de solicitud de productos">
                        <i class="fas fa-file-pdf"></i>
                    </a> --}}

                    {{-- <button @click="callFunctionComponent('request-need','exportFormatoNecesidadesGoogle',row)" class="btn btn-white btn-icon btn-md" title="Formato Identificación de Necesidades">
                        <i class="fa fa-file-pdf"></i>
                    </button> --}}

                    <a
                        :href="'generate-gr-r/' + row.id" target="_blank" class="btn btn-white btn-icon btn-md"
                        data-placement="top" title="Formato de solicitud de productos">
                            <i class="fa fa-file-pdf"> </i>
                        </a>

                    <button v-if="row.estado_proveedor == 'Pendiente' || row.estado_proveedor == 'Pendiente por finalizar' || row.estado_proveedor == 'Pendiente' || row.estado_proveedor == 'Salida Pendiente' || row.estado_proveedor == 'Entrada Pendiente'" @click="callFunctionComponent('externalProviderForm', 'openForm', row.id)" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Editar')"><i class="fas fa-pencil-alt"></i></button>

                    <button @click="show(row)" data-target="#modal-view-request-need-orders-by-external-provider" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                        <i class="fa fa-search"></i>
                    </button>

                    {{-- <a
                        v-if="row.solicitud_principal.estado === 'En trámite' && row.solicitud_principal.tipo_solicitud === 'Activo'"
                        :href="'addition-spare-part-activities?o=' + row.encrypted_id" target="_blank" class="btn btn-white btn-icon btn-md"
                        data-placement="top" title="Adicionar repuestos o actividades">
                        <i class="fa fa-plus"></i>
                    </a> --}}




                    <a
                    v-if="row.solicitud_principal.estado === 'En trámite' && row.solicitud_principal.tipo_solicitud === 'Activo'"
                    :href="'addition-spare-part-activities?o=' + row.encrypted_id"
                    target="_blank"
                    class="btn btn-white btn-icon btn-md position-relative" data-placement="top"
                    
                    :title="row.processing_additions?.length > 0 ? 'Gestionar adiciones en trámite (' + row.processing_additions.length + ')' : 'Adicionar repuestos o actividades'"
                >
                    <i class="fa fa-plus"></i>

                    <span
                        v-if="row.processing_additions?.length > 0"
                        class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle"
                    >
                        @{{ row.processing_additions.length }}
                    </span>
                </a>
                @endif





            </template>
        </table-column>
    </table-component>
</div>