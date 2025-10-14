<div class="table-responsive" style="overflow-y: clip; !important">
    <table-component
        id="internals-table"
        :data="dataList"
        sort-by="internals"
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
        <table-column show="created_at" label="@lang('Created_at')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.created_at }}</span>
            </template>
        </table-column>

        <table-column show="consecutive" label="@lang('Consecutive')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.consecutive }}</span>
            </template>
        </table-column>

        <table-column show="internal_type.name" label="@lang('Type')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.internal_type.name }}</span>
            </template>
        </table-column>

        <table-column show="title" label="@lang('Title')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.title }}</span>
            </template>
        </table-column>

        <table-column show="state" label="@lang('State')" cell-class="column-100">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.state" v-if="row.state=='Elaboración'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.state" v-if="row.state=='Revisión'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-4 bg-cyan states_style" v-html="row.state" v-if="row.state=='Aprobación'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.state" v-if="row.state=='Pendiente de firma'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.state" v-if="row.state=='Devuelto para modificaciones'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.state" v-if="row.state=='Público'" :class="{'registro-no-leido': !row.leido}"></div>

                {{-- <div class="button__status-in_review states_style" v-html="row.state" v-if="row.state=='Elaboración'"></div>
                <div class="button__status-assigned states_style" v-html="row.state" v-if="row.state=='Revisión'"></div>
                <div class="button__status-pending_approval states_style" v-html="row.state" v-if="row.state=='Aprobación'"></div>
                <div class="button__status-pending states_style" v-html="row.state" v-if="row.state=='Pendiente de firma'"></div>
                <div class="button__status-cancelled states_style" v-html="row.state" v-if="row.state=='Devuelto para modificaciones'"></div>
                <div class="button__status-approved states_style" v-html="row.state" v-if="row.state=='Público'"></div> --}}
            </template>
        </table-column>


        <table-column show="dependency_from" label="Dependencia">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.dependency_from }}</span>
            </template>
        </table-column>

        <table-column show="from" label="Remitente">
            <template slot-scope="row">
                {{-- <span :class="{'registro-no-leido': !row.leido}">@{{ row.from }}</span> --}}
                <div v-if="row.from" :class="{'registro-no-leido': !row.leido}">
                    <span v-html="formatTextForHtml(shortText(row.from).text)"></span>
                    <br>
                    <strong style="font-size:18px">
                        <span class="tooltip-trigger">
                            ...
                            <!-- Tooltip Layer -->
                            <div class="tooltip-layer">
                                <div class="tooltip-content">
                                    <span v-html="formatTextForTooltip(row.from)"></span>
                                </div>
                            </div>
                        </span>
                    </strong>
                </div>
            </template>
        </table-column>

        <table-column show="require_answer" label="Estado de respuesta">
            <template slot-scope="row">
                <div v-if="row.require_answer === 'Se requiere que esta correspondencia reciba una respuesta'" :class="{'registro-no-leido': !row.leido}">
                    <!-- Estado de respuesta con tooltip -->
                    <span :class="[
                        'text-white text-center p-4 states_style',
                        row.estado_respuesta === 'Pendiente de tramitar' && row.is_overdue ? 'bg-danger' :
                        row.estado_respuesta === 'Pendiente de tramitar' ? 'bg-warning' :
                        row.estado_respuesta === 'Finalizado' ? 'bg-success' :
                        row.estado_respuesta === 'No aplica' ? 'bg-secondary' : ''
                        ]" class="tooltip-trigger">
                        @{{ row.estado_respuesta }}<br>(@{{ row.fecha_limite_respuesta }})

                        <!-- Tooltip Layer -->
                        <div class="tooltip-layer">
                            <div class="tooltip-content">
                                <span>Estado de la respuesta: @{{ row.estado_respuesta }}<br>Fecha límite: @{{ row.fecha_limite_respuesta }}<br>Responsable: @{{ row.responsable_respuesta_nombre }}</span>
                            </div>
                        </div>
                    </span>
                </div>
                <div v-else-if="row.require_answer === 'Responder a otra correspondencia'">
                    <span>Responde la correspondencia: @{{ row.answer_consecutive_name }}</span>
                </div>
                <div v-else>
                    No aplica
                </div>
            </template>
        </table-column>

        <table-column show="recipients" label="Destinatarios">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.recipients" :class="{'registro-no-leido': !row.leido}">
                    {{-- <span v-if="row.list_recipients.length > 1"> @{{ row.list_recipients[0] }} <br> <strong style="font-size:18px"><span class="cursor-pointer" data-toggle="tooltip" :title="row.list_recipients" data-placement="rigth" data-trigger="click">...</span></strong></span>
                    <span v-else v-html="row.recipients"></span> --}}

                    <span v-html="formatTextForHtml(shortText(row.recipients).text)"></span>
                    <br>
                    <strong style="font-size:18px">
                        <span class="tooltip-trigger">
                            ...
                            <!-- Tooltip Layer -->
                            <div class="tooltip-layer">
                                <div class="tooltip-content">
                                    <span v-html="formatTextForTooltip(row.recipients)"></span>
                                </div>
                            </div>
                        </span>
                    </strong>

                </div>

                <div v-else-if="row.internal_all" :class="{'registro-no-leido': !row.leido}">
                    <span>Todos</span>
                </div>
                <div v-else :class="{'registro-no-leido': !row.leido}">
                    <span>Aún no tiene destinatarios</span>
                </div>




            </template>

        </table-column>

        <table-column show="document_pdf" label="Documento principal" cell-class="col-sm-2" style="width: 12%">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.document_pdf" :class="{'registro-no-leido': !row.leido}">

                      <viewer-attachement :link-file-name="true"
                      :ref="row.consecutive"
                      :component-reference="row.consecutive"
                      type="only-link"
                      read-document="true"
                      ref-module="internal_ref"
                      :read-id="row[customId]"
                      :list="row.document_pdf"
                      :key="row.document_pdf"
                      :name="row.consecutive"></viewer-attachement>

                </div>

                <div v-else :class="{'registro-no-leido': !row.leido}">
                    <span>Sin documento</span>
                </div>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- Accion para crear interna de respuesta --}}
                <button v-if="row.require_answer == 'Se requiere que esta correspondencia reciba una respuesta'
                 && row.responsable_respuesta=={{ Auth::user()->id }} && row.estado_respuesta!='Finalizado' && row.state=='Público'" @click="callFunctionComponent('internal_ref', 'createInternaRespuesta', row)" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Responder correspondencia"><i class="fas fa-check"></i></button>

                {{-- Accion de aprobar firma --}}
                <button v-if="row.origen != 'FISICO' && row.permission_edit && row.state == 'Pendiente de firma'" @click="edit(row); getPathDocument(row.internal_versions[0]?.document_pdf_temp)" data-backdrop="static" data-target="#modal-approve-cancel-sign" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Aprobar o devolver para modificaciones"><i class="fas fa-signature"></i></button>


                <button v-if="row.origen != 'FISICO' && row.permission_edit && row.state != 'Pendiente de firma' " @click="callFunctionComponent('internal_ref', 'loadInterna', row)" data-backdrop="static" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                @if (!Auth::user()->hasRole('Correspondencia Interna Admin'))

                
                <button v-if="row.permission_check == 'Si' && row.state == 'Público'" @click="$refs.internal_ref.readCheck(row[customId],row.status_permission_check == 'Si' ? 'No' : 'Si');" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" :title="row.status_permission_check == 'Si' ? 'Correspondencia leída' : 'Correspondencia no leída'">
                    <i v-if="row.status_permission_check == 'Si'"  class="fas fa-check-square" style="color: #10e032;"></i>
                    <i v-else class="far fa-check-square"></i>
                </button>

                @endif
                
<!-- 
                <button v-if="row.permission_check == 'Si'" @click="$refs.internal_ref.readCheck(row[customId],'No');" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Correspondencia leída">
                    <i class="fas fa-check-square" style="color: #10e032;"></i>
                </button> -->


                @role('Correspondencia Interna Admin')

                <button v-if="row.origen != 'FISICO' && row.state == 'Público' && (row.document_pdf == null || row.document_pdf == '')" @click="callFunctionComponent('internal_ref', 'loadInternaRecuperar', row)" data-backdrop="static" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Recuperar documento">
                    <i class="fas fa-sync"></i>
                </button>


                <button v-if="row.origen == 'FISICO'"  @click="callFunctionComponent('internal_ref','edit',row)" data-backdrop="static" data-target="#modal-form-internals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button v-if="row.origen == 'FISICO'" @click="show(row)" data-target="#modal-view-rotule" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Rótulo">
                    <em class="fa fa-stamp"></em>
                </button>
                @endrole



                <!-- Botón para anotaciones pendientes -->
                {{-- <a :href="'{!! url('correspondence/internal-annotations') !!}?ci=' + row.id"
                    target="_blank"
                    class="btn btn-green btn-icon btn-md"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Anotaciones Pendiente"
                    v-if="row.anotaciones_pendientes?.length > 0"
                    :key="row.anotaciones_pendientes?.length"
                    @click="$refs.internal_ref.read(row[customId]);"> <!-- Maneja el evento click -->
                <i class="fas fa-comment  text-white"></i> <!-- Icono -->
                </a> --}}


                <button @click="edit(row)"  v-if="row.state=='Público'" data-backdrop="static" data-target="#share-internal" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Compartir correspondencia"><i class="fas fa-share-square"></i></button>





                <!-- Botón para cuando no hay anotaciones pendientes -->
                {{-- <a
                    @click="$refs.annotations.abrirModal(row);"
                    target="_blank"
                    class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Anotaciones">
                    <i class="fa fa-question-circle fa-lg"></i> <!-- Icono -->
                </a> --}}

                @if (!Auth::user()->hasRole('Correspondencia Interna Admin'))
                    <!-- Botón para anotaciones pendientes -->
                    <a
                        target="_blank"
                        class="btn btn-white btn-icon btn-md position-relative"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Anotaciones Pendiente"
                        v-if="row.anotaciones_pendientes?.length > 0"
                        :key="row.anotaciones_pendientes?.length"
                        @click="$refs.annotations.abrirModal(row);$refs.internal_ref.read(row[customId]);"> <!-- Maneja el evento click -->
                        <i class="fas fa-comment"></i> <!-- Icono -->
                        <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                            @{{ row.anotaciones_pendientes.length }}
                        </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                    </a>

                    <!-- Botón para cuando no hay anotaciones pendientes -->
                    <a v-else
                         @click="$refs.annotations.abrirModal(row);$refs.internal_ref.read(row[customId]);"
                        target="_blank"
                        class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Anotaciones">
                        <i class="fas fa-comment"></i> <!-- Icono -->
                    </a>

                @else <!-- Si tiene el rol -->

                    <!-- Botón para cuando no hay anotaciones pendientes -->
                    <a  @click="$refs.annotations.abrirModal(row);$refs.internal_ref.read(row[customId]);"
                         target="_blank"
                        class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Anotaciones">
                        <i class="fas fa-comment"></i> <!-- Icono -->
                    </a>

                @endif

                <button @click="show(row); $refs.internal_ref.read(row[customId]);" data-target="#modal-view-internals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <a
                    target="_blank"
                    class="btn btn-white btn-icon btn-md position-relative"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Esta correspondencia contiene notificaciones no entregadas. Por favor, vaya a la sección de notificaciones para revisarlas."
                    v-if="row.count_rebounds > 0"
                    href="/notificacionesintraweb"> <!-- Maneja el evento click -->
                    <i class="fas fa-envelope"></i> <!-- Icono -->
                    <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                        @{{ row.count_rebounds }}
                    </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                </a>

                <button @click="drop(row[customId])" v-if="row.state!='Público' && row.users_id == {!!  Auth::user()->id !!}" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

                @if (config('app.mod_expedientes'))
                <a  v-if="row.state == 'Público'"
                    @click="$refs.expedientes.abrirModal(row);"
                    target="_blank"
                    class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Expedientes">
                    <i class="fas fa-sitemap"></i>
                </a>
                @endif


            </template>
        </table-column>

    </table-component>
</div>
