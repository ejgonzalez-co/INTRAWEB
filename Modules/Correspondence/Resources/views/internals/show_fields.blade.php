<!-- Panel -->
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row">
        <!-- Columna izquierda -->
        <div class="d-flex flex-column col-12 col-lg-7">
            <!-- Panel Izquierdo (Datos de origen) -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos de Origen</strong></h5>
                    <div class="d-flex gap-3">
                        <div class="bg-gray bor rounded-circle"
                            style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">
                            
                            <div v-if="(dataShow.from ?? '').split(' - ').length > 1">
                                <i class="fas fa-users text-white" style="font-size: 1.5rem; margin-top: 5px"></i>
                            </div>
                            <div v-else>
                                <div>@{{ dataShow.from ? dataShow.from.charAt(0) : 'N' }}</div>
                            </div>


                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <dt>Remitente:</dt>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-user"></i>
                                    <p class="mb-0 fw-medium ml-1">@{{ (dataShow.from ?? '').split(' , ')[0] + ')' }}</p>
                                </div>
                            </div>
                            <div>
                                <dt>Dependencia origen:</dt>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building"></i>
                                    <p class="mb-0 fw-medium ml-1">@{{ dataShow.dependency_from }}</p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="card mb-3" data-sortable-id="ui-general-1">

                <!-- begin panel-body -->
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos generales de la correspondencia: @{{ dataShow.consecutive }}</strong>
                    </h5>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Consecutivo</strong></p>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa fa-list-ol"></i> <!-- Lista ordenada -->
                                <p class="mb-0 ml-2">@{{ dataShow.consecutive }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Titulo</strong></p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-alt"></i> <!-- Documento de texto -->
                                <p class="mb-0 ml-2">@{{ dataShow.title }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <!-- Ciudadano Users Id Field -->
                            <dt class=" ">@lang('State'):</dt>
                            <dd class="">@{{ dataShow.state }}</dd>
                        </div>
                        <div class="col-12 col-md-6">
                            <!-- Ciudadano Users Id Field -->
                            <dt class=" ">Tipo de documento:</dt>
                            <dd class="">@{{ dataShow.type_document }}</dd>
                        </div>
                        <div class="col-12 col-md-6">
                            <!-- Ciudadano Users Id Field -->
                            <dt class=" ">Folios:</dt>
                            <dd class="">@{{ dataShow.folios ? dataShow.folios : 'No aplica' }}</dd>
                        </div>
                        <div class="col-12 col-md-6">
                            <!-- Email Ciudadano Field -->
                            <dt class=" ">Anexos:</dt>
                            <dd class="">@{{ dataShow.annexes ? dataShow.annexes : 'No aplica' }}</dd>
                        </div>
                    </div>


                </div>
            </div>

        </div>

        <!-- Panel datos destino (más pequeño) -->
        <div class="d-flex flex-column col-12 col-lg-5">
            <!-- Primera tarjeta: Datos de Destino -->
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos de Destino</strong></h5>

                    <div class="d-flex gap-3">
                        <div v-if="dataShow.recipients">
                            <div v-if="dataShow.recipients.split('<br>').length == 1" class="bg-gray bor rounded-circle"
                                style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">

                        
                                <div v-if="(dataShow.recipients ?? '').split(' <br> ').length > 1">
                                    <i class="fas fa-users text-white" style="font-size: 1.5rem; margin-top: 5px"></i>
                                </div>
                                <div v-else>
                                    <div>@{{ dataShow.recipients ? dataShow.recipients.charAt(0) : 'N' }}</div>
                                </div>


                            </div>
                        </div>


                        <div v-if="dataShow.recipients"
                            :class="dataShow.recipients.split('<br>').length == 1 ? 'col' : ''">
                            <div v-if="dataShow.recipients.split('<br>').length > 1">
                                <dt>Destinatarios:</dt>
                                <dd><label v-html="dataShow.recipients"></label></dd>
                            </div>
                            <div v-else>
                                <dt>Destinatario:</dt>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="far fa-user"></i>
                                    <p class="mb-0 fw-medium ml-1">@{{ dataShow.recipients.split(' , ')[0] }}</p>
                                </div>

                                <dt>Dependencia:</dt>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building"></i>
                                    <p class="mb-0 fw-medium ml-1">
                                        @{{ dataShow.recipients.replace(/^[^,]+, /, '').replace(/\s*\)$/, '') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75 w-100"
                            v-else>
                            <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                            <h6 class="text-secondary mt-3">No hay destinatarios</h6>
                        </div>
                    </div>




                </div>
            </div>

            <!-- Segunda tarjeta: Información relacionada -->
            <div class="flex-grow-1 mt-3 mb-3"> <!-- Agregamos mt-3 para separación -->
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="mb-3"><strong>Información relacionada a esta correspondencia</strong></h5>

                        <div v-if="dataShow.require_answer != 'No'">
                            <dt>Información relacionada:</dt>
                            <dd>@{{ dataShow.require_answer }}</dd>
                        </div>

                        <div>
                            <dt
                                v-if="dataShow.require_answer === 'Se requiere que esta correspondencia reciba una respuesta'">
                                Estado de la respuesta:</dt>
                            <dt v-else-if="dataShow.require_answer === 'Responder a otra correspondencia'">Responde la
                                correspondencia:</dt>

                        </div>

                        <div class=""
                            v-if="dataShow.require_answer === 'Se requiere que esta correspondencia reciba una respuesta'">
                            <div>
                                <!-- Estado con estilo moderno -->
                                <span
                                    :class="[
                                        'tooltip-trigger text-white text-center p-4 states_style',
                                        dataShow.estado_respuesta === 'Pendiente de tramitar' && dataShow.is_overdue ?
                                        'bg-danger' :
                                        dataShow.estado_respuesta === 'Pendiente de tramitar' ? 'bg-warning' :
                                        dataShow.estado_respuesta === 'Finalizado' ? 'bg-success' :
                                        dataShow.estado_respuesta === 'No aplica' ? 'bg-secondary' : ''
                                    ]">
                                    @{{ dataShow.estado_respuesta }}
                                </span>

                                <br>
                                <p class="mt-2 mb-1"><strong>Fecha límite para la respuesta:</strong>
                                    @{{ dataShow.fecha_limite_respuesta }}</p>
                                <p class="mb-0"><strong>Responsable:</strong> @{{ dataShow.responsable_respuesta_nombre }} <span
                                        v-if="dataShow.responsable_respuesta_cargo">(@{{ dataShow.responsable_respuesta_cargo }})</span></p>

                                <span v-if="dataShow.estado_respuesta=='Finalizado'">
                                    <p class="mb-0"><strong>Consecutivo de la respuesta:</strong>
                                        @{{ dataShow.answer_consecutive_name }}</p>
                                    <dd v-if="dataShow.answer_consecutive_name">
                                        <a style="color: rgb(72, 142, 241); font-weight: bold; text-decoration: underline;"
                                            target="_blank"
                                            :href="'{{ url('/') }}/correspondence/internals?qder=' + dataShow
                                                .data_interna_relacionada.id_encript">
                                            Ver detalles de la respuesta: @{{ dataShow.answer_consecutive_name }}
                                        </a>
                                    </dd>
                                    <dd class="col-12" v-if="dataShow.data_interna_relacionada">
                                        <viewer-attachement :link-file-name="true"
                                            :list="dataShow.data_interna_relacionada.document_pdf"></viewer-attachement>
                                    </dd>
                                </span>
                            </div>
                        </div>

                        <div class="" v-else-if="dataShow.require_answer === 'Responder a otra correspondencia'">
                            <dd v-if="dataShow.answer_consecutive_name">
                                <a style="color: rgb(72, 142, 241); font-weight: bold; text-decoration: underline;"
                                    target="_blank"
                                    :href="'{{ url('/') }}/correspondence/internals?qder=' + dataShow
                                        .data_interna_relacionada.id_encript">
                                    Ver detalles de la correspondencia: @{{ dataShow.answer_consecutive_name }}
                                </a>
                            </dd>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75" v-else>
                            <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                            <h6 class="text-secondary mt-3">No hay información relacionada</h6>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>

    {{-- Detalles de clasificacion documental --}}
    @include('correspondence::internals.show_clasificacion_documental')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Ciudadano Users Id Field -->
                    <h5 class="mb-3"><strong>Documento principal de la correspondencia:</strong></h5>

                    <template v-if="dataShow.document_pdf">
                        <dd>
                            <viewer-attachement
                                :display-flex="true"
                                :link-file-name="true"
                                :list="dataShow.document_pdf"
                                :key="dataShow.document_pdf"
                                :name="dataShow.consecutive">
                            </viewer-attachement>
                        </dd>
                    </template>

                    <template v-else>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75">
                            <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                            <h6 class="text-secondary mt-3">No hay información relacionada</h6>
                        </div>
                    </template>
                </div>

            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <!-- Sección de archivos anexos -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Archivos anexos</strong></h5>

                    <div class="row">
                        <p class="col-12 mb-1"><strong>Descripción de los anexos</strong></p>
                        <p class="col-12 mb-3">@{{ dataShow.annexes_description ? dataShow.annexes_description : 'No aplica' }}</p>
                    </div>

                    <div class="row">
                        <p class="col-12 mb-1"><strong>Listado de anexos</strong></p>

                        <template v-if="dataShow.annexes_digital && dataShow.annexes_digital.length > 0">
                            <p class="col-12 mb-3">
                                <viewer-attachement
                                    :display-flex="true"
                                    :link-file-name="true"
                                    :list="dataShow.annexes_digital"
                                    :key="dataShow.annexes_digital">
                                </viewer-attachement>
                            </p>
                        </template>

                        <template v-else>
                            <div class="d-flex flex-column align-items-center justify-content-center col-md-12">
                                <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                                <h6 class="text-secondary mt-3">No hay información relacionada</h6>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5><strong>Listado de funcionarios con copia o compartidos:</strong></h5>
                    <span v-if="dataShow.internal_copy_shares?.length === 0">
                        Esta correspondencia no ha sido compartida o enviada a copia.
                    </span>
                    <div class="table-responsive" v-if="dataShow.internal_copy_shares?.length > 0">
                        <table id="anotaciones" class="table table-bordered">
                            <thead>
                                <tr class="custom-thead">
                                    <td>Usuario</td>
                                    <td>Copia/Compartido</td>
                                    <td>Fecha</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="copy_share in dataShow.internal_copy_shares">
                                    <td>@{{ copy_share.name }}</td>
                                    <td>@{{ copy_share.type }}</td>
                                    <td>@{{ copy_share.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Panel Seguimiento al trámite -->
    <div class="card mb-3" data-sortable-id="ui-general-1">

        <!-- begin panel-body -->
        <div class="card-body">
            <h5 class=""><strong>Seguimiento</strong></h5>

            <div><strong>Anotaciones: </strong></div>
            <div class="table-responsive">
                <table id="anotaciones" class="table table-bordered">
                    <thead>
                        <tr class="custom-thead">
                            <td>Fecha</td>
                            <td>Usuario</td>
                            <td>Anotación</td>
                            <td>Adjuntos</td>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="anotacion in dataShow.internal_annotations">
                            <td>@{{ anotacion.created_at }}</td>
                            <td>@{{ anotacion.users_name }}</td>
                            <td><span class="contenidotext" v-html="anotacion.content"></span>
                            </td>
                            <td v-if="anotacion.attached">
                                <!-- <span v-for="attached in anotacion.attached.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/' + attached" target="_blank">Ver adjunto</a><br/></span> -->
                                <viewer-attachement :link-file-name="true" :list="anotacion.attached"
                                    :key="anotacion.attached"></viewer-attachement>
                            </td>
                            <td v-else>No tiene adjuntos</td>


                        </tr>
                    </tbody>
                </table>
            </div>
            <div><strong>Quiénes han leido la correspondencia: </strong></div>
            <div class="table-responsive">
                <table id="anotaciones" class="table table-bordered">
                    <thead>
                        <tr class="custom-thead">
                            <td>Usuario</td>
                            <td>Rol</td>
                            <td>Accesos</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="leido in dataShow.internal_read">
                            <td>@{{ leido.users_name }}</td>
                            <td>@{{ leido.users_type }}</td>
                            <td width="250">
                                <i class="fa fa-arrow-circle-down" aria-hidden="true" data-toggle="collapse"
                                    :data-target="'#learnMore' + leido.id"></i>
                                <div :id="'learnMore' + leido.id" class="collapse" v-html="leido.access"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="dataShow.state == 'Público'"><strong>Quiénes han chequeado la correspondencia: </strong></div>
            <div class="table-responsive" v-if="dataShow.state == 'Público'">
                <table id="anotaciones" class="table table-bordered">
                    <thead>
                        <tr class="custom-thead">
                            <td>Usuario</td>
                            <td>Rol</td>
                            <td>Accesos</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="leido in dataShow.internal_chequeos" v-if="leido.estado_check == 'Si'">
                            <td>@{{ leido.fullname }}</td>
                            <td>@{{ leido.users_type }}</td>
                            <td width="250">
                                <i class="fa fa-arrow-circle-down" aria-hidden="true" data-toggle="collapse"
                                    :data-target="'#learnMore' + leido.id"></i>
                                <div :id="'learnMore' + leido.id" class="collapse" v-html="leido.access"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>
        <br />

    </div>

    <!-- Panel versiones-->
    <div class="card mb-3" data-sortable-id="ui-general-1" v-if="dataShow.internal_versions?.length > 0">
        <!-- begin panel-body -->
        <div class="card-body">


            <!-- begin panel-heading -->
            <div class="card-heading ui-sortable-handle">
                <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseVersiones"
                    role="button" aria-expanded="false" aria-controls="collapseVersiones">
                    <i class="fa fa-hand-pointer m-r-2"></i> Flujo de publicación y firma del documento
                </button>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="collapse" id="collapseVersiones">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover fix-vertical-table">
                                    <thead>
                                        <tr>
                                            <th>Número de versión</th>
                                            <th>Creado por</th>
                                            <th>Documento</th>
                                            <th>@lang('Created_at')</th>
                                            <th>@lang('State')</th>
                                            <th>@lang('Observation')</th>
                                            <th>Funcionarios para firma</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(version, key) in dataShow.internal_versions">
                                            <td>@{{ version.number_version }}</td>
                                            <td>@{{ version.users_name }}</td>
                                            <td><viewer-attachement :link-file-name="true"
                                                    :list="version.document_pdf_temp"
                                                    :key="version.document_pdf_temp"></viewer-attachement>
                                            <td>@{{ version.created_at }}</td>
                                            <td>@{{ version.state }}</td>
                                            <td>@{{ version.observation }}</td>
                                            <td>

                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Funcionario</th>
                                                            <th>Estado</th>
                                                            <th>Observación</th>
                                                            <th>Hash (ID firma)</th>
                                                            <th>IP</th>
                                                        </tr>
                                                    </thead>

                                                    <body>
                                                        <tr v-for="(sign, key) in version.internal_signs">
                                                            <td>@{{ sign.updated_at }}</td>
                                                            <td>@{{ sign.users_name }}</td>
                                                            <td>@{{ sign.state }}</td>
                                                            <td>@{{ sign.observation }}</td>
                                                            <td>@{{ sign.hash ?? 'No aplica' }}</td>
                                                            <td>@{{ sign.ip ?? 'No aplica' }}</td>
                                                        </tr>
                                                    </body>
                                                </table>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end panel-body -->
    </div>




    <div class="row mt-3">
        <div class="col-12">

            <!-- Panel Flujo de producción documental -->
            <div id="show_cards" class="card" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="card-body">
                    <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample"
                        role="button" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                    <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_cards')"
                        title="Ver en tabla" style="margin-left: auto;"><i id="btnTable" class="fa fa-th"
                            style="color: #5f6368;"></i></button>
                    <button type="button" class="btn btn-white btn-icon btn-md"
                        @click="exportarHistorial('xlsx', dataShow.id, 'internal')"
                        title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download"
                            style="color: #5f6368;"> </i> </button>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="collapse" id="collapseExample">
                    {{-- <div class="col-md-12">
            <button type="button" class="btn bg-success-lighter" @click="exportarHistorial('xlsx', dataShow.id, 'internal')"><i class="fas fa-file-download"></i> Exportar Historial</button>
        </div> --}}
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <!-- Vertical Timeline -->
                                <section id="conference-timeline">
                                    <div class="timeline-start">
                                        <p>Inicio</p>
                                    </div>
                                    <div class="conference-center-line"></div>

                                    <div class="conference-timeline-content">
                                        <!-- Article -->
                                        <div class="timeline-article"
                                            v-for="(history, key) in dataShow.internal_history">
                                            <div style="cursor: pointer;" data-toggle="collapse"
                                                data-target="#historial_completo"
                                                v-bind:class="{
                                'content-left-container': key % 2 === 0,
                                'content-right-container': key % 2 !== 0
                            }">
                                                <div
                                                    v-bind:class="{
                                    'content-left': key % 2 === 0,
                                    'content-right': key % 2 !== 0
                                }">
                                                    <div style="display: flex; align-items: center;">
                                                        <div class="profile-image-container">
                                                            <img v-if="history.users && history.users.url_img_profile !== '' && history.users.url_img_profile !== 'users/avatar/default.png'"
                                                                :src="'{{ asset('storage') }}/' + history.users
                                                                    .url_img_profile"
                                                                alt="" class="profile-image">
                                                            <img v-else
                                                                src="{{ asset('assets/img/user/profile.png') }}"
                                                                alt="" class="profile-image">
                                                        </div>
                                                        <span class="timeline-author">@{{ history.users_name }}</span>
                                                    </div>
                                                    <hr>
                                                    <p>
                                                        <strong style="color:#00B0BD ">@{{ key + 1 }}.
                                                            @{{ history.observation_history }}</strong> <br>
                                                        <strong>Observación:</strong> @{{ history.observation ? history.observation : 'N/A' }}<br>
                                                        <strong>Fecha y hora:</strong> @{{ history.date_format_day }} de
                                                        @{{ history.date_format_month_completo }} de @{{ history.date_format_year }}
                                                        @{{ history.date_format_hour }}<br>
                                                        <strong>Para:</strong> @{{ history.user_for_last_update ? history.user_for_last_update : 'N/A' }}<br>
                                                        <strong>Título:</strong> @{{ history.title }}<br>
                                                        <strong>Destinatarios del documento:</strong> <span
                                                            v-html="history.recipients ? history.recipients : 'N/A'"></span><br>
                                                        <strong>Consecutivo:</strong> @{{ history.consecutive ? history.consecutive : 'N/A' }}<br>
                                                        <strong>Documento:</strong>
                                                        <viewer-attachement :link-file-name="true"
                                                            :list="history.document_pdf"
                                                            :key="history.document_pdf"></viewer-attachement>
                                                    <div class="row">
                                                        <strong class="col-md-2">Estado:</strong>
                                                        <div class="text-center  col-md-6"
                                                            :class="{
                                                                'estado_elaboracion': history.state == 'Elaboración',
                                                                'estado_publico': history.state == 'Público',
                                                                'estado_revision': history.state == 'Revisión',
                                                                'estado_pendiente_firma': history.state ==
                                                                    'Pendiente de firma',
                                                                'estado_aprobacion': history.state == 'Aprobación',
                                                                'estado_devuelto_modificar': history.state ==
                                                                    'Devuelto para modificaciones'
                                                            }">
                                                            @{{ history.state }} </div>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="meta-date">
                                                <span class="date">@{{ history.date_format_day }}</span>
                                                <span class="month">@{{ history.date_format_month }}</span>
                                            </div>
                                        </div>
                                        <!-- // Article -->
                                    </div>
                                    <div class="timeline-end">Última actualización</div>
                                </section>



                            </div>
                        </div>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>


            <!-- Panel Flujo de producción documental -->
            <div class="card" id="show_table" title="Ver en lista" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="card-body">
                    <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample"
                        role="button" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                    <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_table')"
                        style="margin-left: auto;"><i id="btnCard" class="fas fa-square"
                            style="color: #5f6368;"></i></button>
                    <button type="button" class="btn btn-white btn-icon btn-md"
                        @click="exportarHistorial('xlsx', dataShow.id, 'internal')"
                        title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download"
                            style="color: #5f6368;"> </i> </button>

                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="collapse" id="collapseExample">

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped fix-vertical-table">

                                        <thead>
                                            <tr class="font-weight-bold"
                                                style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;">
                                                <th>Creado por</th>
                                                <th>@lang('Created_at')</th>
                                                <th>@lang('Consecutive')</th>
                                                <th>Tipo de documento</th>
                                                <th>@lang('State')</th>
                                                <th>@lang('Title')</th>
                                                <th>Remitente</th>
                                                <th>Destinatarios</th>
                                                <th>Dependencia</th>
                                                <th>Usuario</th>
                                                <th>Historial</th>
                                                <th>Observación</th>
                                                <th>Archivos adjuntos</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(history, key) in dataShow.internal_history">
                                                <td>@{{ history.users_name }}</td>
                                                <td>@{{ history.created_at }}</td>
                                                <td>@{{ history.consecutive }}</td>
                                                <td>@{{ history.type_document }}</td>
                                                <td>@{{ history.state }}</td>
                                                <td>@{{ history.title }}</td>
                                                <td>@{{ history.from }}</td>
                                                <td>@{{ history.recipients }}</td>
                                                <td>@{{ history.dependency_from }}</td>
                                                <td>@{{ history.user_for_last_update }}</td>
                                                <td>@{{ history.observation_history }}</td>
                                                <td>@{{ history.observation }}</td>

                                                <td>
                                                    {{-- <ul v-if="history.document_pdf">
                                            <li v-for="document_pdf in history.document_pdf.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+document_pdf" target="_blank">Ver adjunto</a><br /></li>
                                        </ul> --}}
                                                    <viewer-attachement :link-file-name="true"
                                                        v-if="history.document_pdf" :list="history.document_pdf"
                                                        :key="history.document_pdf"></viewer-attachement>

                                                    <ul v-else>
                                                        <li>No tiene adjuntos</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end panel-body -->

                </div>

            </div>

        </div>
    </div>
</div>
