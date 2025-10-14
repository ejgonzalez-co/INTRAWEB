<div class="table-responsive"  style="overflow-y: clip; !important">
    <table-component 
        id="external-receiveds-table" 
        :data="dataList" 
        sort-by="external-receiveds" 
        sort-order="asc"
        table-class="table table-hover m-b-0" 
        :show-filter="false" 
        :pagination="dataPaginator" 
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')" 
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" 
        :cache-lifetime="0">


        <table-column show="recibido_fisico" label="Recibido Físico" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @if (!Auth::user()->hasRole('Correspondencia Recibida Admin'))
                    <execution-from-action
                        v-if="row.is_functionary && row.recibido_fisico == null && row.channel != 2"
                        :value="row"
                        route="change-recibido-fisico"
                        field-update="recibido_fisico"
                        value-update="Si"
                        css-class="fa fa-check"
                        title="Haz clic aquí para poner fecha de que recibió el documento físico."
                    >
                    </execution-from-action>
                    <span v-else-if="row.channel == 2" title="Documento Digital" :class="{'registro-no-leido': !row.leido}">
                         Documento digital
                    </span>
                    <span v-else-if="row.recibido_fisico != null" :title="'Documento recibido físico el ' + row.recibido_fisico" :class="{'registro-no-leido': !row.leido}">
                        <i class="fas fa-check"> </i> @{{ row.recibido_fisico }}
                    </span>
                @else
                    <span v-if="row.channel == 2" title="Documento Digital" :class="{'registro-no-leido': !row.leido}">
                         Documento digital
                    </span>
                    <span v-else-if="row.recibido_fisico == null" title="Pendiente de recibido físico." :class="{'registro-no-leido': !row.leido}">
                        Pendiente de recibido físico
                    </span>
                    <span v-else :title="'Documento recibido físico el ' + row.recibido_fisico" :class="{'registro-no-leido': !row.leido}">
                        <i class="fas fa-check"> </i> @{{ row.recibido_fisico }}
                    </span>
                @endif
            </template>
        </table-column>

        <table-column show="created_at" label="Fecha">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.created_at }}</span>
            </template>
        </table-column>
        <table-column show="consecutive" label="@lang('Consecutive')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.consecutive }}</span>
            </template>
        </table-column>
    
        <table-column show="issue" label="@lang('Title')">
            <template slot-scope="row">
                {{-- <span :class="{'registro-no-leido ': !row.leido}">@{{ row.issue }}</span> --}}
                <div v-if="row.issue" :class="{'registro-no-leido': !row.leido}">
                    <span v-html="formatTextForHtml(shortText(row.issue,20).text)"></span>
                    <!-- <span>@{{ row.issue}}</span> -->
                    <strong style="font-size:18px">
                        <span class="tooltip-trigger">
                            ...
                            <!-- Tooltip Layer -->
                            <div class="tooltip-layer">
                                <div class="tooltip-content">
                                    <span v-html="formatTextForTooltip(row.issue)"></span>
                                </div>
                            </div>
                        </span>
                    </strong>
                </div>
            </template>
        </table-column>
        <table-column show="state_name" label="@lang('State')" cell-class="column-100">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-2 bg-blue states_style" v-html="row.state_name" v-if="row.state=='2'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-2 bg-grey states_style" v-html="row.state_name" v-if="row.state=='4'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-2 bg-orange states_style" v-html="row.state_name" v-if="row.state=='1'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-2 bg-green states_style" v-html="row.state_name" v-if="row.state=='3'" :class="{'registro-no-leido': !row.leido}"></div>
            </template>
        </table-column>

<!-- //a -->
        <table-column show="citizen_name" label="@lang('Citizen')">
            <template slot-scope="row">
                <div v-if="row.citizen_name" :class="{'registro-no-leido': !row.leido}">
                    <span v-html="formatTextForHtml(shortText(row.citizen_name).text)"></span>
                    <strong style="font-size:18px">
                        <span class="tooltip-trigger">
                            ...
                            <!-- Tooltip Layer -->
                            <div class="tooltip-layer">
                                <div class="tooltip-content">
                                    <span v-html="formatTextForTooltip(row.citizen_name)"></span>
                                </div>
                            </div>
                        </span>
                    </strong>
                </div>
            </template>
        </table-column>

        <table-column show="dependency_name" label="@lang('Dependency')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.dependency_name }}</span>
            </template>
        </table-column>
        <table-column show="functionary_name" label="@lang('Functionary')" >
            <template slot-scope="row">
                {{-- <span :class="{'registro-no-leido': !row.leido}">@{{ row.functionary_name }}</span> --}}

                <div v-if="row.functionary_name" :class="{'registro-no-leido': !row.leido}">
                    <span v-html="formatTextForHtml(shortText(row.functionary_name).text)"></span>
                    <strong style="font-size:18px">
                        <span class="tooltip-trigger">
                            ...
                            <!-- Tooltip Layer -->
                            <div class="tooltip-layer">
                                <div class="tooltip-content">
                                    <span v-html="formatTextForTooltip(row.functionary_name)"></span>
                                </div>
                            </div>
                        </span>
                    </strong>
                </div>
            </template>
        </table-column>
        <table-column show="user_name" label="Radicó">
            <template slot-scope="row">
                <div v-if="row.user_name" :class="{'registro-no-leido': !row.leido}">
                    <span v-html="formatTextForHtml(shortText(row.user_name).text)"></span>
                    <strong style="font-size:18px">
                        <span class="tooltip-trigger">
                            ...
                            <!-- Tooltip Layer -->
                            <div class="tooltip-layer">
                                <div class="tooltip-content">
                                    <span v-html="formatTextForTooltip(row.user_name)"></span>
                                </div>
                            </div>
                        </span>
                    </strong>
                </div>
            </template>
        </table-column>


        <table-column show="document_pdf" label="Documento principal" cell-class="column-adjunto">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.document_pdf" :class="{'registro-no-leido': !row.leido}">

                    <viewer-attachement :link-file-name="true" type="only-link"
                        :ref="row.consecutive"
                        :component-reference="row.consecutive"
                        :list="row.document_pdf" :key="row.document_pdf"
                        read-document="true"
                        ref-module="received_ref"
                        :read-id="row[customId]"
                        :name="row.consecutive"></viewer-attachement>

                </div>
                <div v-else-if="row.correo_integrado_datos" :class="{'registro-no-leido': !row.leido}">
                    <span>Documento digital</span>
                </div>
                <div v-else :class="{'registro-no-leido': !row.leido}">
                    <span>Sin documento</span>
                </div>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false"  cell-class="column-100">
            <template slot-scope="row">

                @if (!Auth::user()->hasRole('Consulta correspondencias'))
                    @role('Correspondencia Recibida Admin')
                        <button @click="callFunctionComponent('received_ref','edit',row)" data-backdrop="static"
                            data-target="#modal-form-external-receiveds" data-toggle="modal"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="@lang('crud.edit')">
                            <i class="fas fa-pencil-alt"></i>
                        </button>

                        {{-- <button @click="$set(dataForm,'document_type','Carta');show(row);openForm = true" data-target="#modal-view-rotule" data-toggle="modal"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Rótulo">
                            <i class="fa fa-stamp"></i>
                        </button> --}}

                        <button @click="$refs.rotulo_recibida.asginarData(row);" data-target="#modal-view-rotule" data-toggle="modal"
                                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Rótulo">
                                <i class="fa fa-stamp"></i>
                        </button>
                    @endrole

                    @if (!Auth::user()->hasRole('Correspondencia Recibida Admin'))
                        <button v-if="row.is_functionary && (row.state == 2 || row.state == 3)"
                            @click="callFunctionComponent('alert-confirmation','openConfirmationModal',row.id,row)"
                            class="btn btn-white btn-icon btn-md" title="Devolver correspondencia">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    @endif




                    {{--
                    <button v-if="row.pqr !== null" @click="show(row); $refs.received_ref.read(row[customId]);" data-target="#modal-view-external-receiveds" data-toggle="modal" style="background: #90bde1;" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                        <i class="fa fa-search"></i>
                    </button>

                    <button v-else @click="show(row); $refs.received_ref.read(row[customId]);" data-target="#modal-view-external-receiveds" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                        <i class="fa fa-search"></i>
                    </button> --}}

                    {{-- <button @click="callFunctionComponent('share-correspondence-user','edit',row)" class="btn btn-white btn-icon btn-md" title="Compartir">
                        <i class="fas fa-share-alt"></i>
                    </button> --}}



                    <button @click="edit(row)" data-backdrop="static" data-target="#share-external" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="Compartir correspondencia"><i class="fas fa-share-square"></i></button>

                    {{-- <b v-if='row.pqr != NULL' data-backdrop="static"    style="color: red" title="Contiene PQR"><i class="fas fa-exclamation-circle"></i></b> --}}

                    {{-- <a v-if="row.pqr !== null"
                        :href="'{{ url('/') }}/pqrs/p-q-r-s?qder='+ row.recibida_pqr_encrypted_id.pqr_id"
                        target="_blank"
                        class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip"
                        data-placement="top"
                        style="background: #90bde1;"
                        :title="'Contiene PQR: ' + row.pqr">
                        <i class="fas fa-bullhorn"></i>
                    </a> --}}


                    @if (!Auth::user()->hasRole('Correspondencia Recibida Admin'))
                        <!-- Botón para anotaciones pendientes -->
                        <a target="_blank" class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip"
                            data-placement="top" title="Anotaciones Pendiente" v-if="row.anotaciones_pendientes?.length > 0"
                            :key="row.anotaciones_pendientes?.length"
                            @click="$refs.annotations.abrirModal(row);$refs.received_ref.read(row[customId]);">
                            <!-- Maneja el evento click -->
                            <i class="fas fa-comment"></i> <!-- Icono -->
                            <span
                                class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                                @{{ row.anotaciones_pendientes.length }}
                            </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                        </a>

                        <!-- Botón para cuando no hay anotaciones pendientes -->
                        <a v-else target="_blank" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                            data-placement="top" title="Anotaciones"
                            @click="$refs.annotations.abrirModal(row);$refs.received_ref.read(row[customId]);">
                            <i class="fas fa-comment"></i> <!-- Icono -->
                        </a>
                    @else
                        <!-- Si tiene el rol 'Correspondencia Recibida Admin' -->

                        <!-- Botón para cuando no hay anotaciones pendientes -->
                        <a target="_blank" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="Anotaciones"
                            @click="$refs.annotations.abrirModal(row);$refs.received_ref.read(row[customId]);">
                            <i class="fas fa-comment"></i> <!-- Icono -->
                        </a>
                    @endif

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

                    @if (config('app.mod_expedientes'))
                    <a  v-if="row.state_name == 'Público'"
                        @click="$refs.expedientes.abrirModal(row);" 
                        target="_blank" 
                        class="btn btn-white btn-icon btn-md" 
                        data-toggle="tooltip" 
                        data-placement="top" 
                        title="Expedientes">
                        <i class="fas fa-sitemap"></i>
                    </a>
                    @endif
                @endif
                
                

                <button v-if="row.pqr !== null" @click="show(row); $refs.received_ref.read(row[customId]);"
                data-target="#modal-view-external-receiveds" data-toggle="modal"
                class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip"
                data-placement="top" title="@lang('see_details')">
                <i class="fas fa-search"></i>
                <span class="badge badge-info position-absolute top-0 start-100"
                    style="font-size: 8px !important;">
                    PQR
                </span>
                </button>

                <button v-else @click="show(row); $refs.received_ref.read(row[customId]);"
                    data-target="#modal-view-external-receiveds" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip"
                    data-placement="top" title="@lang('see_details')">
                    <i class="fas fa-search"></i>
                </button>


            </template>
        </table-column>
    </table-component>
</div>
