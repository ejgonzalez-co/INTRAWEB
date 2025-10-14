<div class="table-responsive"  style="overflow-y: clip; !important">
    <table-component
        id="externals-table"
        :data="dataList"
        sort-by="externals"
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

        <table-column show="external_type.name" label="@lang('Type')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.external_type ? row.external_type.name : 'N/A' }}</span>
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

        <table-column show="citizen_name" label="Ciudadano">
            <template slot-scope="row" :sortable="false" :filterable="false">
                {{-- <span v-html="shortText(row.citizen_name)"></span>
                <span id="tooltip" v-html="row.citizen_name"></span> --}}
                {{-- <span> @{{ shortText(row.citizen_name) }} <br> <strong style="font-size:18px"><span class="cursor-pointer" data-toggle="tooltip" :title="row.citizen_name" data-placement="rigth" data-trigger="click">...</span></strong></span> --}}
                <span :class="{'registro-no-leido': !row.leido}">
                    <template v-if="shortText(row.citizen_name).isLong">
                        <!-- Contenido principal con <br> -->
                        <span v-html="formatTextForHtml(shortText(row.citizen_name).text)"></span>
                        <!-- Tooltip Trigger -->
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

                            {{-- <span class="cursor-pointer" data-toggle="tooltip" :title="formatTextForTooltip(row.citizen_name)" data-placement="right" data-trigger="click">...</span> --}}
                        </strong>
                    </template>

                    <template v-else>
                        <span v-html="row.citizen_name"></span>
                    </template>
                </span>
            </template>
        </table-column>

        {{-- <table-column show="document_pdf" label="Adjuntos 2"></table-column> --}}

        <table-column show="document_pdf" label="Documento principal" cell-class="col-sm-2">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.document_pdf" :class="{'registro-no-leido': !row.leido}">

                    <viewer-attachement type="only-link"
                    :ref="row.consecutive" :component-reference="row.consecutive"
                    :list="row.document_pdf"
                    read-document="true"
                    ref-module="external_ref"
                    :read-id="row[customId]"
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

                @if (!Auth::user()->hasRole('Consulta correspondencias'))

                    <button v-if="row.origen != 'FISICO' && row.permission_edit && row.state == 'Pendiente de firma'" @click="edit(row);getPathDocument(row.external_versions[0]?.document_pdf_temp)" data-backdrop="static" data-target="#modal-approve-cancel-sign-external" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Aprobar o devolver para modificaciones"><i class="fas fa-signature"></i></button>


                    <button v-if="row.origen != 'FISICO' && row.permission_edit && row.state != 'Pendiente de firma'" @click="callFunctionComponent('external_ref', 'loadExterna', row)" data-backdrop="static" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    @if(Auth::user()->hasRole('Correspondencia Enviada Admin'))

                    <button v-if="row.origen == 'FISICO'" @click="callFunctionComponent('external_ref','edit',row)" data-backdrop="static" data-target="#modal-form-externals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    {{-- <button v-if="row.origen == 'FISICO'" @click="show(row)" data-target="#modal-view-rotule" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Rótulo">
                        <em class="fa fa-stamp"></em>
                    </button> --}}

                    <button v-if="row.origen == 'FISICO'"  @click="$refs.rotulo_enviada.asginarData(row);" data-target="#modal-view-rotule" data-toggle="modal"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Rótulo">
                            <i class="fa fa-stamp"></i>
                    </button>
                    @elseif(config('app.todos_radican_enviadas'))

                        <button v-if="row.origen == 'FISICO' && row.users_id == {!!  Auth::user()->id !!}" @click="callFunctionComponent('external_ref','edit',row)" data-backdrop="static" data-target="#modal-form-externals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.sdsdsds')">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        
                        <button v-if="row.origen == 'FISICO' && row.users_id == {!!  Auth::user()->id !!}"  @click="$refs.rotulo_enviada.asginarData(row);" data-target="#modal-view-rotule" data-toggle="modal"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Rótulo">
                            <i class="fa fa-stamp"></i>
                        </button>
                    @endif





                    <button @click="edit(row)" v-if="row.state=='Público'" data-backdrop="static" data-target="#share-external" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Compartir correspondencia"><i class="fas fa-share-square"></i></button>


                    <button @click="drop(row[customId])" v-if="row.state!='Público' && row.users_id == {!!  Auth::user()->id !!}" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                        <i class="fa fa-trash"></i>
                    </button>


                    @if (!Auth::user()->hasRole('Correspondencia Enviada Admin'))
                        <!-- Botón para anotaciones pendientes -->
                        <a
                            target="_blank"
                            class="btn btn-white btn-icon btn-md position-relative"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Anotaciones Pendiente"
                            v-if="row.anotaciones_pendientes?.length > 0"
                            :key="row.anotaciones_pendientes?.length"
                            @click="$refs.annotations.abrirModal(row);$refs.external_ref.read(row[customId]);"> <!-- Maneja el evento click -->
                            <i class="fas fa-comment"></i> <!-- Icono -->
                            <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                                @{{ row.anotaciones_pendientes.length }}
                            </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                        </a>

                        <!-- Botón para cuando no hay anotaciones pendientes -->
                        <a v-else
                            @click="$refs.annotations.abrirModal(row);$refs.external_ref.read(row[customId]);"
                            target="_blank"
                            class="btn btn-white btn-icon btn-md"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Anotaciones">
                            <i class="fas fa-comment"></i> <!-- Icono -->
                        </a>

                    @else <!-- Si tiene el rol -->

                        <!-- Botón para cuando no hay anotaciones pendientes -->
                        <a @click="$refs.annotations.abrirModal(row);$refs.external_ref.read(row[customId]);"
                            target="_blank"
                            class="btn btn-white btn-icon btn-md"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Anotaciones">
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
                    
                @endif

                <button v-if="row.pqr_consecutive !== null && row.pqr_consecutive !== '' && row.pqr_consecutive !== undefined" @click="show(row); $refs.external_ref.read(row[customId]);"
                data-target="#modal-view-externals"  data-toggle="modal"
                class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip"
                data-placement="top" title="@lang('see_details')">
               <i class="fas fa-search"></i>
                   <span class="badge badge-info position-absolute top-0 start-100"
                       style="font-size: 8px !important;">
                       PQR
                   </span>
               </button>
               <button  v-else @click="show(row); $refs.external_ref.read(row[customId]);"
                data-target="#modal-view-externals"  data-toggle="modal"
                class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip"
                data-placement="top" title="@lang('see_details')">
                <i class="fas fa-search"></i>

               </button>


            </template>
        </table-column>

    </table-component>
</div>
