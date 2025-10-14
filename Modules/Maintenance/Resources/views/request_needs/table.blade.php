<div class="table-responsive">
    <table-component
        id="requestNeeds-table"
        :data="dataList"
        sort-by="requestNeeds"
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
        <table-column show="consecutivo" label="Consecutivo">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.consecutivo">
                    <span v-html="row.consecutivo"></span>
                </div>
                <div v-else>
                    <span>Aún no tiene consecutivo</span>
                </div>
            </template>

        </table-column>
        <table-column show="created_at" label="@lang('Fecha de solicitud')"></table-column>
        {{-- <table-column label="Proceso solicitante">
            <template slot-scope="row" :sortable="false" :filterable="false">
                    <label>@{{ row.dependencia ? row.dependencia.nombre : null }}</label>
            </template>

        </table-column> --}}
        <table-column show="dependencia.nombre" label="@lang('Proceso')"></table-column>
        <table-column show="users.name" label="Funcionario"></table-column>

        <table-column show="tipo_solicitud" label="@lang('Tipo Solicitud')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <p v-if="row.tipo_solicitud === 'Activo'">Activo</p>
                <p v-else-if="row.tipo_solicitud === 'Inventario'">Compra/Almacén</p>
                <p v-else>Stock/Salida</p>
            </template>
        </table-column>

        <table-column show="activo_nombre" label="Activo">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.activo_nombre">
                    <span v-html="row.activo_nombre"></span>
                </div>
                <div v-else>
                    <span>No aplica</span>
                </div>
            </template>

        </table-column>
        <table-column show="contrato_datos" label="Proveedor">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <p>@{{ row.contrato_datos ? row.contrato_datos.providers.name + " - " + row.contrato_datos.contract_number : 'No aplica' }}</p>
            </template>

        </table-column>

        <!-- <table-column show="rubro_datos.name_heading" label="Rubro">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.rubro_datos">
                    <span v-html="row.rubro_datos.name_heading"></span> - <span v-html="row.rubro_datos.code_heading"></span>
                </div>
                <div v-else>
                    <span>Rubro no asociado</span>
                </div>
            </template>

        </table-column> -->


        <table-column show="estado" label="@lang('State')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado" v-if="row.estado=='En elaboración'"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.estado" v-if="row.estado=='En revisión'"></div>
                <div class="text-white text-center p-4 states_style" style="background-color:rgb(156, 158, 157)" v-html="row.estado" v-if="row.estado=='Cancelada'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado" v-if="row.estado=='En trámite'"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.estado" v-if="row.estado=='Devuelto para modificaciones'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado" v-if="row.estado=='Finalizada'"></div>
            </template>
        </table-column>
       
       
            <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

              
                @role('mant Líder de proceso')
                <button v-if="row.estado === 'En elaboración'" @click="edit(row)" data-backdrop="static" data-target="#modal-form-request-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                {{-- <span class="" v-if="row.tipo_solicitud=='Activo' && row.rubro_id != null">
                    <a v-if="(row.estado === 'En revisión' || row.estado == 'Finalizada' || row.estado == 'En trámite') || (row.estado === 'En elaboración' && row.users_id === {{ auth()->user()->id }}) "  :href="'{!! url('maintenance/request-need-orders') !!}?rn=' + row.id_encript" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Gestionar órdenes de servicio"><i class="fas fa-cogs"></i></a>
                </span> --}}
                @endrole

                @role('Administrador de mantenimientos')
            
                {{-- <button v-if="(row.estado === 'En revisión' || row.estado === 'En trámite' || row.estado === 'En elaboración' ) && row.is_editable" @click="edit(row)" data-backdrop="static" data-target="#modal-form-request-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button> --}}

                <button 
                    v-if="( (row.estado === 'En revisión' || row.estado === 'En trámite' || row.estado === 'En elaboración') 
                            && row.is_editable 
                            && (!row.ordenes || row.ordenes.length === 0) )"
                    @click="edit(row)" 
                    data-backdrop="static" 
                    data-target="#modal-form-request-needs" 
                    data-toggle="modal" 
                    class="btn btn-white btn-icon btn-md" 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="@lang('crud.edit')"
                >
                    <i class="fas fa-pencil-alt"></i>
                </button>


                <span class="" v-if="row.tipo_solicitud=='Activo' && row.rubro_id != null">
                    <a v-if="(row.estado === 'En revisión' || row.estado == 'Finalizada' || row.estado == 'En trámite' || row.estado === 'En elaboración') && row.is_visible_management_orders "  :href="'{!! url('maintenance/request-need-orders') !!}?rn=' + row.id_encript" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Gestionar órdenes de servicio"><i class="fas fa-cogs"></i></a>
                </span>

                <span class="" v-if="row.tipo_solicitud=='Inventario' && row.rubro_id != null && row.is_visible_management_orders && (row.dependencias_id == 19 || row.dependencias_id == 23)">
                    <a v-if="(row.estado === 'En revisión' || row.estado == 'Finalizada' || row.estado == 'En trámite' || row.estado === 'En elaboración') && row.is_visible_management_orders "  :href="'{!! url('maintenance/request-need-orders') !!}?rn=' + row.id_encript" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Gestionar órdenes de servicio"><i class="fas fa-cogs"></i></a>
                </span>
{{-- 
                <a
                    v-if="row.estado === 'En trámite' && row.tipo_solicitud === 'Activo'"
                    :href="'addition-spare-part-activities?s=' + row.id_encript" target="_blank" class="btn btn-white btn-icon btn-md"
                    data-placement="top" title="Adicionar repuestos o actividades">
                    <i class="fa fa-plus"></i>
                </a> --}}

                
                <a
                    v-if="row.estado === 'En trámite' && row.tipo_solicitud === 'Activo'"
                    :href="'addition-spare-part-activities?s=' + row.id_encript"
                    target="_blank"
                    class="btn btn-white btn-icon btn-md position-relative"
                    data-placement="top"
                    
                    :title="row.pending_additions?.length > 0 ? 'Gestionar adiciones en trámite (' + row.pending_additions.length + ')' : 'Adicionar repuestos o actividades'"
                >
                    <i class="fa fa-plus"></i>

                    <span
                        v-if="row.pending_additions?.length > 0"
                        class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle"
                    >
                        @{{ row.pending_additions.length }}
                    </span>
                </a>

                {{-- ordenes --}}

                {{-- <a v-if="row.estado === 'En revisión'"  :href="'{!! url('maintenance/request-need-orders') !!}?rn=' + row.id_encript" target="_blank" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Gestionar ordenes de servicio"><i class="fas fa-cogs"></i></a> --}}
                {{-- <span class="" v-if="row.tipo_solicitud=='Activo' && row.rubro_id != null">
                    <a v-if="(row.estado === 'En revisión' || row.estado == 'Finalizada' || row.estado == 'En trámite') || (row.estado === 'En elaboración' && row.users_id === {{ auth()->user()->id }}) "  :href="'{!! url('maintenance/request-need-orders') !!}?rn=' + row.id_encript" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Gestionar órdenes de servicio"><i class="fas fa-cogs"></i></a>
                </span> --}}
                <span class="" v-if="(row.tipo_solicitud=='Inventario' || row.tipo_solicitud=='Activo') && row.rubro_id == null">
                    <button v-if="(row.estado === 'En revisión' || row.estado == 'Finalizada' || row.estado == 'En trámite') || (row.estado === 'En elaboración' && row.users_id === {{ auth()->user()->id }}) "  :href="'{!! url('maintenance/request-need-orders') !!}?rn=' + row.id_encript" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Aún no ha asignado un rubro"><i class="fas fa-exclamation-circle" style="color: #d81e1e;"></i></button>
                </span>

                <button v-if="row.tipo_solicitud == 'Stock' && row.estado == 'En trámite'"
                    class="btn btn-white btn-icon btn-md" title="Estado del almacen">
                    <i class="fas fa-store" :style="{color: row.estado_stock_almacen == 'Salida Pendiente' || row.estado_stock_almacen == 'Entrada Pendiente' ? '#ff9800' : '#0BC568'}"></i>
                </button>

                <button v-if="(row.tipo_solicitud == 'Activo' || row.tipo_solicitud == 'Inventario') && row.estado == 'En trámite'"
                    class="btn btn-white btn-icon btn-md" title="Estado del proveedor externo">
                    <i class="fas fa-user-tie" :style="{color: row.estado_proveedor_externo_almacen == 'Pendiente' || row.estado_proveedor_externo_almacen == 'Pendiente por finalizar' ? '#ff9800' : '#0BC568'}"></i>
                </button>

                <button @click="edit(row)" 
                v-if="row.estado != 'Finalizada' && row.estado != 'Cancelada' && row.is_editable"  
                 data-backdrop="static" data-target="#change-state-request" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Cambiar estado de Solicitud"><i class="fas fa-toggle-on"></i></button>

                 
                <button v-if="(row.estado === 'En elaboración' || row.estado == 'En revisión') && row.tipo_solicitud === 'Stock'"
                @click="callFunctionComponent('enviar-revision-stock','openConfirmationModal',row.id)"
                class="btn btn-white btn-icon btn-md" title="Enviar solicitud a revisión">
                <i class="fas fa-paper-plane"></i>
            </button>

            <button v-if="(row.estado === 'En elaboración' || row.estado === 'En revisión') && (row.tipo_solicitud === 'Inventario' || row.tipo_solicitud === 'Activo') && row.rubro_id != null"
                @click="callFunctionComponent('enviar-revision-proveedor','openConfirmationModal',row.id)"
                class="btn btn-white btn-icon btn-md" title="Enviar solicitud al proveedor externo">
                <i class="fas fa-paper-plane"></i>
            </button>

                @endrole

                @unless (auth()->user()->hasRole('Administrador de mantenimientos'))

                    {{-- Boton para enviar la solicitud directamente al almacen aseo cuando proviene de gestion de aseo o subgerencia de aseo y es de tipo stock --}}
                    <button v-if="row.estado === 'En elaboración' && row.tipo_solicitud === 'Stock' && (row.dependencias_id == 19 || row.dependencias_id == 23)"
                        @click="callFunctionComponent('enviar-revision-stock-aseo','openConfirmationModal',row.id)"
                        class="btn btn-white btn-icon btn-md" title="Enviar solicitud a almacen aseo">
                        <i class="fas fa-paper-plane"></i>
                    </button>

                    <button v-if="row.estado === 'En elaboración' && row.tipo_solicitud != 'Stock' && (row.dependencias_id != 19 || row.dependencias_id != 23)"
                        @click="callFunctionComponent('enviar-revision','openConfirmationModal',row.id)"
                        class="btn btn-white btn-icon btn-md" title="Enviar solicitud a revisión">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                @endunless

                <button @click="show(row)" data-target="#modal-view-request-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>


                <button v-if="row.estado != 'En elaboración1' && row.tipo_solicitud != 'Stock'" @click="callFunctionComponent('request-need','exportFormatoNecesidadesGoogle',row)" class="btn btn-white btn-icon btn-md" title="Formato Identificación de Necesidades">
                    <i class="fa fa-file-excel"></i>
                </button>

                <a :href="'{!! url('maintenance/request-annotations') !!}?ci=' + row.encrypted_id" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Observaciones"><i class="fas fa-comment"></i></a>

                <button @click="show(row)" data-target="#modal-view-request-needs-history" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Seguimiento y control">
                    <i class="fa fa-history"></i>
                </button>

                @if(['mant Líder de proceso','Administrador de mantenimientos'])
                    <button v-if="row.estado === 'En elaboración'" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
                
            </template>
        </table-column>
    </table-component>
</div>