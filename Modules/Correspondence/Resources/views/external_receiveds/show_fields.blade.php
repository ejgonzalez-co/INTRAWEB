{{-- 
* Este archivo muestra los campos de un formulario de correspondencia.
* Incluye detalles generales de la correspondencia, datos de origen y destino,
* archivos adjuntos, seguimiento al trámite y historial de cambios.
--}}

<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row">
        <!-- Columna izquierda -->
        <div class="d-flex flex-column col-12 col-lg-7">
            <!-- Panel Izquierdo (Datos de origen) -->
            <div class="card mb-3">
                <div class="card-body">

                    <h5 class="mb-3"><strong>Información del ciudadano</strong></h5>

                    <div class="d-flex gap-3">
                        <div class="bg-gray bor rounded-circle"
                            style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">
                            <img v-if="dataShow.citizens && dataShow.citizens.url_img_profile"
                                class="w-100 rounded-circle"
                                :src="'{{ asset('storage') }}/' + dataShow.citizens.url_img_profile" alt="" />
                            <div v-else>@{{ dataShow.citizen_name ? dataShow.citizen_name.charAt(0) : 'N' }}</div>

                        </div>



                        <div class="col">
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="far fa-id-card-o" aria-hidden="true"></i>
                                    <p class="mb-0"><strong>Nombre</strong></p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-user"></i>
                                    <p class="mb-0 fw-medium ml-1">@{{ dataShow.citizen_name ?? 'No Aplica' }}</p>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="far fa-id-card-o" aria-hidden="true"></i>
                                    <p class="mb-0"><strong>Documento</strong></p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-id-card"></i>
                                    <p class="mb-0 ml-1">@{{ dataShow.citizen_document ?? 'No Aplica' }}</p>
                                </div>

                            </div>

                            <div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    <p class="mb-0"><strong>Correo</strong></p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-envelope"></i>
                                    <p class="mb-0 ml-1">@{{ dataShow.citizen_email ?? 'No Aplica' }}</p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Panel Datos generales de la correspondencia -->
            <div class="card mb-3 flex-grow-1">
                <div class="card-body h-100">
                    <h5 class="mb-3"><strong>Datos generales de la correspondencia</strong></h5>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Consecutivo</strong></p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-list-ol"></i> <!-- Lista ordenada -->
                                <p class="mb-0 ml-2">@{{ dataShow.consecutive }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Tipo de documento</strong></p>
                            <p class="mb-3">@{{ dataShow.type_documentary_name }}</p>
                        </div>

                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Folios</strong></p>
                            <p class="mb-3">@{{ dataShow.folio }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Canal</strong></p>
                            <p class="mb-3">@{{ dataShow.channel_name }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Fecha recibida</strong></p>
                            <div class="d-flex align-items-center">
                                <i class="far fa-calendar"></i>
                                <p class="mb-0 ml-2">@{{ formatDate(dataShow.created_at) }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1"><strong>Estado</strong></p>
                            <p class="mb-3">@{{ dataShow.state_name }}</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-1"><strong>Asunto</strong></p>
                            <p class="mb-3" style="text-align: justify;">@{{ dataShow.issue }}</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-1"><strong>Observación</strong></p>
                            <p class="mb-3" style="text-align: justify;">@{{ dataShow.observation ? dataShow.observation : "No aplica" }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Columna derecha -->
        <div class="d-flex flex-column col-12 col-lg-5 mb-3">
            <!-- Panel Derecho (Datos de destino) -->
            <div class="card mb-3" style="height:12.5rem">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos de destino</strong></h5>
                    <div class="d-flex gap-3">
                        <div class="bg-gray bor rounded-circle"
                            style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">
                            <img v-if="dataShow.functionaries && dataShow.functionaries.url_img_profile"
                                class="w-100 rounded-circle"
                                :src="'{{ asset('storage') }}/' + dataShow.functionaries.url_img_profile"
                                alt="" />
                            <div v-else>@{{ dataShow.functionary_name ? dataShow.functionary_name.charAt(0) : 'N' }}</div>

                        </div>

                        <div class="col">
                            <div>
                                <p class="mb-1"><strong>Funcionario</strong></p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-tie"></i>
                                    <p class="mb-0 ml-2">@{{ dataShow.functionary_name ?? 'No Aplica' }}</p>

                                </div>
                            </div>
                            <div class="mb-3">
                                <p class="mb-0"><strong>Dependencia destino</strong></p>
                                <p>@{{ dataShow.dependency_name ?? 'No Aplica' }}</p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="mb-3"><strong>Datos del PQRS asociado a esta correspondencia</strong></h5>
                        <div v-if="dataShow.pqr">
                            <div class="row g-3">
                                <div class="col-12">
                                    <p class="mb-1"><strong>No. radicado del PQRS</strong></p>
                                    <p class="mb-3">
                                        <a style="color: rgb(72, 142, 241); font-weight: bold; text-decoration: underline;"
                                            :href="'{{ url('/') }}/pqrs/p-q-r-s?qder=' + dataShow
                                                .recibida_pqr_encrypted_id.pqr_id">
                                            Ver detalles de PQRS: @{{ dataShow.pqr }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Nombre eje temático</strong> </p>
                                    <p class="mb-3">@{{ dataShow.pqr_datos.nombre_ejetematico || 'No aplica' }}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <p class="mb-1"><strong>Fecha de vencimiento</strong></p>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar"></i>
                                        <p class="mb-0 ml-2">@{{ dataShow.pqr_datos.fecha_vence || 'No aplica' }}</p>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <p class="mb-1"><strong>Funcionario destinatario</strong></p>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-tie"></i>
                                        <p class="mb-0 ml-2">@{{ dataShow.pqr_datos.funcionario_destinatario || 'No aplica' }}</p>
                                    </div>

                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Estado</strong></p>
                                    <p class="mb-3 text-center estado-badge"
                                        :class="{
                                            'estado_abierto': dataShow.pqr_datos.estado == 'Abierto',
                                            'estado_cancelado': dataShow.pqr_datos.estado == 'Cancelado',
                                            'estado_finalizado_a_tiempo': dataShow.pqr_datos.estado == 'Finalizado' &&
                                                dataShow.pqr_datos.linea_tiempo == 'A tiempo',
                                            'estado_finalizado_vencido_justificado': dataShow.pqr_datos.estado ==
                                                'Finalizado vencido justificado',
                                            'estado_a_tiempo': dataShow.pqr_datos.linea_tiempo == 'A tiempo' && dataShow
                                                .pqr_datos.estado != 'Finalizado',
                                            'estado_proximo_vencer': dataShow.pqr_datos.linea_tiempo ==
                                                'Próximo a vencer',
                                            'estado_vencido': dataShow.pqr_datos.linea_tiempo == 'Vencido'
                                        }">
                                        @{{ dataShow.pqr_datos.estado }}
                                        <span v-if="dataShow.pqr_datos.linea_tiempo" class="font-weight-bold">
                                            (@{{ dataShow.pqr_datos.linea_tiempo }})
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mt-3" v-if="dataShow.pqr_datos.estado == 'Finalizado'">
                                <div class="col-12">
                                    <p class=""><strong>Documento de respuesta</strong></p>
                                    <p
                                        v-if="dataShow.pqr_datos.adjunto_finalizado && dataShow.pqr_datos.adjunto_finalizado?.length > 0">
                                        <viewer-attachement
                                            :list="dataShow.pqr_datos.adjunto_finalizado"></viewer-attachement>
                                    </p>
                                    <p v-else>
                                        <span>No tiene adjunto</span>
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75" v-else>
                            <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                            <h6 class="text-secondary mt-3">No tiene PQR asociado</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!dataShow.pqr && !dataShow?.correo_integrado_datos" class="flex-grow-1 mt-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="mb-3"><strong>Datos de la comunicación por correo asociada</strong></h5>

                        <div v-if="dataShow?.correo_integrado_datos">
                            <div class="row g-3">
                                <div class="col-12">
                                    <p class=""><strong>Consecutivo</strong></p>
                                    <p class="mb-3">
                                        <a style="color: rgb(72, 142, 241); font-weight: bold; text-decoration: underline;"
                                            :href="'{{ url('/') }}/correspondence/correo-integrados?qderMailShra=' +
                                            dataShow.correo_integrado_encrypted_id.correo_id">
                                            @{{ dataShow.correo_integrado_datos.consecutivo }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Correo remitente</strong></p>
                                    <p class="mb-3">@{{ dataShow.correo_integrado_datos.correo_remitente || 'No aplica' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Estado</strong></p>
                                    <p class="mb-3">@{{ dataShow.correo_integrado_datos.estado || 'No aplica' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Nombre remitente</strong></p>
                                    <p class="mb-3">@{{ dataShow.correo_integrado_datos.nombre_remitente || 'No aplica' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Asunto</strong></p>
                                    <p class="mb-3">@{{ dataShow.correo_integrado_datos.asunto || 'No aplica' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Vigencia</strong></p>
                                    <p class="mb-3">@{{ dataShow.correo_integrado_datos.vigencia || 'No aplica' }}</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5 class="mb-2"><strong>Adjuntos de la comunicación por correo</strong></h5>
                                <div v-if="dataShow.correo_integrado_datos.adjuntos_correo?.length > 0">
                                    <ul class="list-unstyled">
                                        <li v-for="(adjunto, key) in dataShow.correo_integrado_datos.adjuntos_correo"
                                            class="mb-2">
                                            <viewer-attachement :link-file-name="true" v-if="adjunto.adjunto"
                                                :list="adjunto.adjunto"></viewer-attachement>
                                        </li>
                                    </ul>
                                </div>
                                <p v-else>
                                    No tiene adjuntos
                                </p>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center h-75" v-else>
                            <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                            <h6 class="mt-3 text-secondary">No tiene comunicación asociada</h6>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    @include('correspondence::internals.show_clasificacion_documental')

    {{-- Seccion de expedientes  --}}
    @include('correspondence::external_receiveds.show_expedientes_fields') 
    
    <div v-if="dataShow?.correo_integrado_datos || dataShow.pqr" class="row mb-3 mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos de la comunicación por correo asociada</strong></h5>
                    <div v-if="dataShow?.correo_integrado_datos">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <p class=""><strong>Consecutivo</strong></p>
                                <p class="mb-3">
                                    <a style="color: rgb(72, 142, 241); font-weight: bold; text-decoration: underline;"
                                        :href="'{{ url('/') }}/correspondence/correo-integrados?qderMailShra=' + dataShow
                                            .correo_integrado_encrypted_id.correo_id">
                                        @{{ dataShow.correo_integrado_datos.consecutivo }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="mb-1"><strong>Correo remitente</strong></p>
                                <p class="mb-3">@{{ dataShow.correo_integrado_datos.correo_remitente || 'No aplica' }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="mb-1"><strong>Estado</strong></p>
                                <p class="mb-3">@{{ dataShow.correo_integrado_datos.estado || 'No aplica' }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="mb-1"><strong>Nombre remitente</strong></p>
                                <p class="mb-3">@{{ dataShow.correo_integrado_datos.nombre_remitente || 'No aplica' }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="mb-1"><strong>Asunto</strong></p>
                                <p class="mb-3">@{{ dataShow.correo_integrado_datos.asunto || 'No aplica' }}</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="mb-1"><strong>Vigencia</strong></p>
                                <p class="mb-3">@{{ dataShow.correo_integrado_datos.vigencia || 'No aplica' }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-2"><strong>Adjuntos de la comunicación por correo</strong></h5>
                            <div v-if="dataShow.correo_integrado_datos.adjuntos_correo?.length > 0" class="row">
                                <p v-for="(adjunto, key) in dataShow.correo_integrado_datos.adjuntos_correo"
                                    class="mb-2">
                                    <viewer-attachement :display-Flex="true" :link-file-name="true"
                                        v-if="adjunto.adjunto" :list="adjunto.adjunto"></viewer-attachement>
                                </p>
                            </div>
                            <p v-else>
                                No tiene adjuntos
                            </p>
                        </div>
                    </div>
                    <div v-else>
                        <p class="col-12">No tiene comunicación asociada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div v-if="dataShow.document_pdf" class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Documento principal de la correspondencia</strong></h5>
                    <dd v-if="dataShow.document_pdf">
                        <viewer-attachement :link-file-name="true" :display-flex="true"
                            :list="dataShow.document_pdf" :key="dataShow.document_pdf"
                            :name="dataShow.consecutive"></viewer-attachement>
                    </dd>
                    <dd v-else>
                        No tiene adjuntos
                    </dd>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Sección de archivos anexos --}}
    <div v-if="dataShow.attached_document" class="row mb-3 mt-3">
        <div class="col-12">
            <!-- Sección de archivos anexos -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Archivos anexos</strong></h5>

                    <div class="row">
                        <p class="col-12 mb-1"><strong>Descripción de los anexos</strong></p>
                        <p class="col-12 mb-3">@{{ dataShow.annexed }}</p>
                    </div>
                    <div class="row">
                        <p class="col-12 mb-3" v-if="dataShow.attached_document">
                            <viewer-attachement :display-Flex="true" :link-file-name="true"
                                :list="dataShow.attached_document"
                                :key="dataShow.attached_document"></viewer-attachement>
                        </p>
                        <p class="col-12" v-else>No tiene adjuntos</p>
                    </div>
                  
                </div>
            </div>
        </div>

    </div>


    
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body" v-if='dataShow.external_copy_shares?.length > 0'>
                    <div class="mb-3">
                        <h5 class=""><strong>Listado de funcionarios con copia o compartidos</strong></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="">Usuario</th>
                                        <th class="">Copia/Compartido</th>
                                        <th class="">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="copy_share in dataShow.external_copy_shares">
                                        <td>@{{ copy_share.name }}</td>
                                        <td>@{{ copy_share.type }}</td>
                                        <td>@{{ copy_share.created_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-else>
                    <h5><strong>Listado de funcionarios con copia o compartidos: </strong></h5>
    
                    <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75">
                        <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                        <h6 class="text-secondary mt-3">No hay funcionarios con copia o compartidos</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-2"><strong>Seguimiento</strong></h5>
                    <p class="mb-3"><strong>Anotaciones</strong></p>
                    <div class="table-responsive mb-2" v-if="dataShow.external_annotations?.length > 0">
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
                                <tr v-for="anotacion in dataShow.external_annotations">
                                    <td>@{{ anotacion.created_at }}</td>
                                    <td>@{{ anotacion.users_name }}</td>
                                    <td><span class="contenidotext" v-html="anotacion.annotation"></span></td>

                                    <td v-if="anotacion.attached">
                                        <viewer-attachement :link-file-name="true" :list="anotacion.attached"
                                            :key="anotacion.attached"></viewer-attachement>
                                    </td>
                                    <td v-else>No tiene adjuntos</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75">
                            <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                            <h6 class="text-secondary mt-3">No hay anotaciones</h6>
                        </div>
                        <hr>
                    </div>
                    <br />


                    <div class="d-flex mb-2" v-if="dataShow.reason_return">
                        <p class="mb-0"><strong>Razón de la devolución:</strong></p>
                        <p class="mb-0 ml-2">@{{ dataShow.reason_return ?? 'No hay devolución' }}</p>
                    </div>

                    <p class="mb-2"><strong>Quiénes han leido la correspondencia </strong></p>

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
                                <tr v-for="leido in dataShow.external_read">
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
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" id="show_cards" data-sortable-id="ui-general">
                <div class="card-body">
                    <!-- begin panel-heading -->
                    <div class="panel-heading ui-sortable-handle">
                        <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample"
                            role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                        <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_cards')"
                            title="Ver en tabla" style="margin-left: auto;"><i id="btnTable" class="fa fa-th"
                                style="color: #5f6368;"></i></button>
                        <button type="button" class="btn btn-white btn-icon btn-md"
                            @click="exportarHistorial('xlsx', dataShow.id, 'external-received')"
                            title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download"
                                style="color: #5f6368;"></i> </button>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="collapse mt-2" id="collapseExample">
                        {{-- <div class="col-md-12">
                            <button type="button" class="btn bg-success-lighter" @click="exportarHistorial('xlsx', dataShow.id, 'external-received')"><i class="fas fa-file-download"></i> Exportar Historial</button>
                        </div> --}}
                        <div class="panel-body" id="show_card">
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
                                                v-for="(history, key) in dataShow.external_history">

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
                                                                <img v-if="history.users && history.users.url_img_profile !== '' && history.users.url_img_profile !=null  && history.users.url_img_profile !== 'users/avatar/default.png'"
                                                                    :src="'{{ asset('storage') }}/' + history.users
                                                                        .url_img_profile"
                                                                    alt="" class="profile-image">
                                                                <img v-else
                                                                    src="{{ asset('assets/img/user/profile.png') }}"
                                                                    alt="" class="profile-image">
                                                            </div>

                                                            <span class="timeline-author">
                                                                @{{ history.user_name }}</span>
                                                        </div>
                                                        <hr>

                                                        <p>
                                                            <strong style="color:#00B0BD ">@{{ key + 1 }}.
                                                                @{{ history.observation_history }} </strong> <br>
                                                            <strong>Asunto:</strong> @{{ history.issue }}<br>

                                                            <strong>Fecha y hora:</strong> @{{ history.date_format.day }} de
                                                            @{{ history.date_format.monthcompleto }} de @{{ history.date_format.year }}
                                                            @{{ history.date_format.hour }}<br>
                                                            <strong>Ciudadano:</strong> <span
                                                                v-html="history.citizen_name ? history.citizen_name : 'N/A'"></span>
                                                            - @{{ history.citizen_document ? history.citizen_document : 'N/A' }}<br>

                                                            <strong>Para:</strong> @{{ history.functionary_name }} -
                                                            @{{ history.dependency_name }}<br>
                                                            <strong>Consecutivo:</strong> @{{ history.consecutive ? history.consecutive : 'N/A' }}<br>

                                                            <strong>Documento:</strong>

                                                            <viewer-attachement :link-file-name="true"
                                                                :list="history.document_pdf"
                                                                :key="history.document_pdf"></viewer-attachement>
                                                            <strong>Razón
                                                                devolución:</strong>@{{ history.reason_return != null ? history.reason_return : 'No aplica' }}<br>


                                                            {{-- <span class="article-number">
                                    <strong>@{{ key + 1 }}</strong>
                                                            </span> --}}
                                                        </p>

                                                    </div>

                                                </div>


                                                <div class="meta-date">
                                                    <span class="date">@{{ history.date_format.day }}</span>
                                                    <span class="month">@{{ history.date_format.month }}</span>
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
            </div>
            <div class="card" id="show_table" title="Ver en lista" data-sortable-id="ui-general-1">
                <div class="card-body">
                    <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample"
                        role="button" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
                    <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_table')"
                        style="margin-left: auto;"><i id="btnCard" class="fas fa-square"
                            style="color: #5f6368;"></i></button>
                    <button type="button" class="btn btn-white btn-icon btn-md"
                        @click="exportarHistorial('xlsx', dataShow.id, 'external-received')"
                        title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download"
                            style="color: #5f6368;"></i> </button>

                    <div class="collapse mt-2" id="collapseExample">
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="table-responsive">
                                        <table class="table table-hover fix-vertical-table">
                                            <thead>
                                                <tr
                                                    style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;">
                                                    <th>Creado por</th>
                                                    <th>@lang('Created_at')</th>
                                                    <th>@lang('Consecutive')</th>
                                                    <th>Tipo de documento</th>
                                                    <th>@lang('State')</th>
                                                    <th>@lang('Title')</th>
                                                    <th>Ciudadano</th>
                                                    <th>Documento del Ciudadano</th>
                                                    <th>Destinatario</th>
                                                    <th>Archivos adjuntos</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(history, key) in dataShow.external_history">
                                                    <td>@{{ history.user_name }}</td>
                                                    <td>@{{ history.created_at }}</td>
                                                    <td>@{{ history.consecutive }}</td>
                                                    <td>@{{ history.type_documentary_name }}</td>
                                                    <td>@{{ history.state_name }}</td>
                                                    <td>@{{ history.issue }}</td>
                                                    <td v-html="history.citizen_name"></td>
                                                    <td>@{{ history.citizen_document }}</td>
                                                    <td>@{{ history.functionary_name }}</td>
                                                    <td>
                                                        {{-- <ul v-if="history.attached_document">
                                       <li v-for="attached_document in history.attached_document.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+attached_document" target="_blank">Ver adjunto</a><br /></li>
                                                        </ul> --}}
                                                        <viewer-attachement :link-file-name="true"
                                                            v-if="history.attached_document"
                                                            :list="history.attached_document"
                                                            :key="history.attached_document"></viewer-attachement>

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
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
