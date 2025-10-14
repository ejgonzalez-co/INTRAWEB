<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="panel-title"><strong>Datos de Origen</strong></h5>
                    <div class="d-flex gap-3">
                        <div class="bg-gray bor rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">
                            
                            <div v-if="(dataShow.from ?? '').split(' - ').length > 1">
                                <i class="fas fa-users text-white" style="font-size: 1.5rem; margin-top: 5px"></i>
                            </div>
                            <div v-else>
                                <div>@{{ dataShow.from ? dataShow.from.charAt(0) : 'N' }}</div>
                            </div>

                        </div>
                        <div class="col">
                            <div class="mb-2">
                                <dt>Remitente:</dt>

                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-tie"></i>
                                    <dd class="mb-0 ml-2">@{{ dataShow.from }}</dd>
                                </div>
                            </div>
                            <div class="mb-2">
                                <dt>Dependencia origen:</dt>

                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building"></i>
                                    <dd class="mb-0 ml-2">@{{ dataShow.dependency_from }}</dd>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
      
    </div>

        <!-- Panel datos destino-->

    <div class="row mb-3">
        <div class="col-12 " data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="card">

                <div class="card-body">
                    <h5><strong><i class="fas fa-user"></i> Datos de destino</strong></h5>
                    
                
                    <div class="table-responsive" v-if="dataShow.citizens?.length > 0">
                        <table class="table table-striped table-bordered">
                            <thead class="custom-thead">
                                <tr>
                                    <th>Trato</th>
                                    <th>Cargo</th>
                                    <th>Entidad</th>
                                    <th>Dirección</th>
                                    <th>Ciudadano</th>
                                    <th>Documento</th>
                                    <th>Correo</th>
                                    <th>Departamento</th>
                                    <th>Municipio</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(citizen, key) in dataShow.citizens" :key="key">
                                    <td>@{{ citizen.trato && citizen.trato.trim() !== '' ? citizen.trato : '-' }}</td>
                                    <td>@{{ citizen.cargo && citizen.cargo.trim() !== '' ? citizen.cargo : '-' }}</td>
                                    <td>@{{ citizen.entidad && citizen.entidad.trim() !== '' ? citizen.entidad : '-' }}</td>
                                    <td>@{{ citizen.direccion && citizen.direccion.trim() !== '' ? citizen.direccion : '-' }}</td>
                                    <td>@{{ citizen.citizen_name && citizen.citizen_name.trim() !== '' ? citizen.citizen_name : '-' }}</td>
                                    <td>@{{ citizen.citizen_document && citizen.citizen_document.trim() !== '' ? citizen.citizen_document : '-' }}</td>
                                    <td>@{{ citizen.citizen_email && citizen.citizen_email.trim() !== '' ? citizen.citizen_email : '-' }}</td>
                                    <td>@{{ citizen.departamento_informacion?.name && citizen.departamento_informacion.name.trim() !== '' ? citizen.departamento_informacion.name : '-' }}</td>
                                    <td>@{{ citizen.ciudad_informacion?.name && citizen.ciudad_informacion.name.trim() !== '' ? citizen.ciudad_informacion.name : '-' }}</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                        <p class="text-muted mt-2">
                            <small>Nota: "-" indica que la información no ha sido especificada.</small>
                        </p>
                    </div>
                    <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75"
                        v-else>
                        <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                        <h6 class="text-secondary mt-3">No hay información relacionada</h6>
                    </div>
                </div>
            </div>
        <!-- end panel-body -->
        </div>
    </div>



    <div class="card mb-3" data-sortable-id="ui-general-1">
        <div class="card-body">
            <h5 class="mb-3"><strong>Datos generales de la correspondencia: @{{ dataShow.consecutive }}</strong></h5>

            <div class="row">
                <div class="col-12 col-md-6">
                    <p class="mb-1"><strong>Consecutivo</strong></p>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-list-ol"></i> <!-- Lista ordenada -->
                        <dd class="mb-0 ml-2">@{{ dataShow.consecutive }}</dd>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <!-- Ciudadano Users Id Field -->
                    <dt class="">Tipo de documento:</dt>
                    <dd class="">@{{ dataShow.type_document }}</dd>
                </div>

                <div class="col-12 col-md-6">
                    <!-- Ciudadano Users Id Field -->
                    <dt class="text-inverse text-left  ">@lang('State'):</dt>
                    <dd class="">@{{ dataShow.state }}</dd>
                </div>
                <div class="col-12 col-md-6" v-if="dataShow.origen=='FISICO'">

                    <!-- Email Ciudadano Field -->
                    <dt class="text-inverse text-left  ">@lang('Title'):</dt>
                    <dd class="">@{{ dataShow.title }}</dd>
                </div>

                <div class="col-12 col-md-6" v-if="dataShow.origen=='FISICO'">
                    <!-- Ciudadano Users Id Field -->
                    <dt class="text-inverse text-left  ">Folios:</dt>
                    <dd class="">@{{ dataShow.folios ? dataShow.folios : 'No aplica' }}</dd>
                </div>
                <div class="col-12 col-md-6" v-if="dataShow.origen=='FISICO'">
                    <!-- Email Ciudadano Field -->
                    <dt class="text-inverse text-left  ">Anexos:</dt>
                    <dd class="">@{{ dataShow.annexes ? dataShow.annexes : 'No aplica' }}</dd>
                </div>

                <div class="col-12 col-md-6">
                    <!-- Ciudadano Users Id Field -->
                    <dt class="text-inverse text-left  ">Relacionar correspondencia recibida:</dt>
                    <dd class="">@{{ dataShow.have_assigned_correspondence_received ? dataShow.have_assigned_correspondence_received : 'No aplica' }}</dd>

                    <!-- Email Ciudadano Field -->
                    <dt v-if="dataShow.have_assigned_correspondence_received == 'Si' && dataShow.external_received_id" class="text-inverse text-left  ">Correspondencia recibida a relacionada:</dt>

                    <a v-if="dataShow.have_assigned_correspondence_received == 'Si' && dataShow.external_received_id" class="col-sm-2 col-md-2 col-lg-2"
                        :href="'{{ url('/') }}/correspondence/external-receiveds?qder=' + (dataShow?.external_receiveds?.recibida_pqr_encrypted_id?.recibida_id ?? '')">
                        @{{ dataShow.external_received_consecutive ?? '' }}
                    </a>
                </div>

                <div class="col-12 col-md-6">
                    <!-- Ciudadano Users Id Field -->
                    <dt class="text-inverse text-left  ">Guía:</dt>
                    <dd class="">@{{ dataShow.guia ? dataShow.guia : 'No aplica' }}</dd>
                </div>
                <div class="col-12 col-md-6">

                    <!-- Email Ciudadano Field -->
                    <dt class="text-inverse text-left  ">Finaliza PQR:</dt>
                    <dd class="">@{{ dataShow.require_answer ? dataShow.require_answer : 'No aplica' }}</dd>
                </div>

                <div class="col-12 col-md-6">
                    <!-- Ciudadano Users Id Field -->
                    <dt class="text-inverse text-left  ">Respuesta a PQR:</dt>
                    <dd class="" v-if="dataShow.require_answer=='Si'">@{{ dataShow.pqr_consecutive }}</dd>
                    <dd class="" v-else>No aplica</dd>

                </div>
            </div>
        </div>
    </div>

    {{-- Detalles de clasificacion documental --}}
    @include('correspondence::internals.show_clasificacion_documental')


    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Ciudadano Users Id Field -->
                    <h5 class="mb-3"><strong>Documento principal de la correspondencia:</strong></h5>
                    <dd class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.document_pdf">
                        <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.document_pdf" :key="dataShow.document_pdf" :name="dataShow.consecutive"></viewer-attachement>
                    </dd>
                    <dd class="" v-else>No tiene adjuntos</dd>
                    </strong>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">

        <!-- Panel anexos-->
        <div class="col-12" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="card">

                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="card-body">
                    <h5><strong>Archivos anexos</strong></h5>

                    <div class="">
                        <!-- Ciudadano Users Id Field -->
                        <dt class="">Descripción de los anexos:</dt>
                        <dd class="">@{{ dataShow.annexes_description ? dataShow.annexes_description : 'No aplica' }}</dd>


                    </div>

                    <div class="">


                        <!-- Email Ciudadano Field -->
                        <dt class="mb-2">Listado de anexos:</dt>
                        <dd class="col-12" v-if="dataShow.annexes_digital">
                            <viewer-attachement :display-flex="true" :link-file-name="true" :list="dataShow.annexes_digital" :key="dataShow.annexes_digital"></viewer-attachement>
                        </dd>
                        <dd class="" v-else="dataShow.annexes_digital">No tiene adjuntos</dd>
                    </div>
                </div>
            </div>
            <!-- end panel-body -->
        </div>
    </div>

    {{-- Seccion de expedientes  --}}
    @include('correspondence::external_receiveds.show_expedientes_fields') 



<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body" v-if='dataShow.external_copy_shares?.length > 0'>
                <h5><strong>Listado de funcionarios con copia o compartidos: </strong></h5>

                <div class="table-responsive" v-if="dataShow.external_copy_shares?.length > 0">
                    <table id="anotaciones" class="table table-bordered">
                        <thead>
                            <tr class="custom-thead">
                                <td>Usuario</td>
                                <td>Copia/Compartido</td>
                                <td>Fecha</td>
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
<!-- Panel Seguimiento al trámite -->
<div class="row mb-3" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="col-12">
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="card">
            <div class="card-body">
                <h5><strong>Seguimiento</strong></h5>

                <div><strong>Anotaciones: </strong></div>
                <div class="table-responsive" v-if="dataShow.external_annotations?.length > 0">
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
                                <td><span class="contenidotext" v-html="anotacion.content"></span></td>
                                <td v-if="anotacion.attached">

                                    <!-- <span v-for="attached in anotacion.attached.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+attached" target="_blank">Ver adjunto</a><br/></span> -->

                                    <viewer-attachement :link-file-name="true" :list="anotacion.attached" :key="anotacion.attached"></viewer-attachement>

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
                <div><strong>Quiénes han leido la correspondencia: </strong></div>
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
                                <i class="fa fa-arrow-circle-down" aria-hidden="true" data-toggle="collapse" :data-target="'#learnMore'+leido.id"></i>
                                <div :id="'learnMore'+leido.id" class="collapse" v-html="leido.access"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>





<!-- Panel versiones-->
<div class="card mb-3" data-sortable-id="ui-general-1" v-if="dataShow.external_versions?.length > 0">
    <div class="card-body">


        <!-- begin panel-heading -->
        <div class="card-heading ui-sortable-handle">
            <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseVersiones" role="button" aria-expanded="false" aria-controls="collapseVersiones">
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
                                    <tr v-for="(version, key) in dataShow.external_versions">
                                        <td>@{{ version.number_version }}</td>
                                        <td>@{{ version.users_name }}</td>
                                        <td><viewer-attachement :link-file-name="true" :list="version.document_pdf_temp" :key="version.document_pdf_temp"></viewer-attachement>
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
                                                    </tr>
                                                </thead>

                                                <body>
                                                    <tr v-for="(sign, key) in version.external_signs">
                                                        <td>@{{ sign.created_at }}</td>
                                                        <td>@{{ sign.users_name }}</td>
                                                        <td>@{{ sign.state }}</td>
                                                        <td>@{{ sign.observation }}</td>
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


<!-- Panel Flujo de producción documental -->
<div id="show_cards" class="card" data-sortable-id="ui-general-1">
    <div class="card-body">


        <!-- begin panel-heading -->
        <div class="card-heading ui-sortable-handle">
            <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
            <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_cards')" title="Ver en tabla" style="margin-left: auto;"><i id="btnTable" class="fa fa-th" style="color: #5f6368;"></i></button>
            <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'external')" title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i> </button>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="collapse" id="collapseExample">
            {{-- <div class="col-md-12">
            <button type="button" class="btn bg-success-lighter" @click="exportarHistorial('xlsx', dataShow.id, 'external')"><i class="fas fa-file-download"></i> Exportar Historial</button>
        </div> --}}
            <div class="card-body">
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
                                <div class="timeline-article" v-for="(history, key) in dataShow.external_history">

                                    <div style="cursor: pointer;" data-toggle="collapse" data-target="#historial_completo" v-bind:class="{
                                'content-left-container': key % 2 === 0,
                                'content-right-container': key % 2 !== 0
                            }">

                                        <div v-bind:class="{
                                    'content-left': key % 2 === 0,
                                    'content-right': key % 2 !== 0
                                }">

                                            <div style="display: flex; align-items: center;">
                                                <div class="profile-image-container">
                                                    <img v-if="history.users && history.users.url_img_profile !== '' && history.users.url_img_profile !== 'users/avatar/default.png'"
                                                        :src="'{{ asset('storage') }}/' + history.users.url_img_profile"
                                                        alt=""
                                                        class="profile-image">
                                                    <img v-else
                                                        src="{{ asset('assets/img/user/profile.png') }}"
                                                        alt=""
                                                        class="profile-image">
                                                </div>
                                                <span class="timeline-author"> @{{ history.users_name }}</span>
                                            </div>
                                            <hr>
                                            <p>
                                                <strong style="color:#00B0BD ">@{{ key + 1 }}. @{{ history.observation_history }} </strong> <br>
                                                <strong>Observación:</strong> @{{ history.observation ? history.observation : 'N/A' }}<br>
                                                <strong>Fecha y hora:</strong> @{{ history.date_format_day }} de @{{ history.date_format_month_completo }} de @{{ history.date_format_year }} @{{ history.date_format_hour }}<br>
                                                <strong>Para:</strong> @{{ history.user_for_last_update ? history.user_for_last_update : 'N/A' }}<br>
                                                <strong>Ciudadano:</strong> <span v-html="history.citizen_name ? history.citizen_name : 'N/A'"></span> - @{{ history.citizen_document ? history.citizen_document : 'N/A' }}<br>
                                                <strong>Consecutivo:</strong> @{{ history.consecutive ? history.consecutive : 'N/A' }}<br>
                                                <strong>Documento:</strong>
                                                <span v-if="history.document_pdf">
                                                    <viewer-attachement :link-file-name="true" :list="history.document_pdf" :key="history.document_pdf"></viewer-attachement>

                                                </span>
                                                <span v-else>
                                                    <span>No tiene adjuntos</span>
                                                </span>
                                            <div class="row">
                                                <strong class="col-md-2">Estado:</strong>
                                                <div class="text-center  col-md-6"
                                                    :class="{
                                                'estado_elaboracion': history.state == 'Elaboración',
                                                'estado_publico': history.state == 'Público',
                                                'estado_revision': history.state == 'Revisión',
                                                'estado_pendiente_firma': history.state == 'Pendiente de firma',
                                                'estado_aprobacion': history.state == 'Aprobación',
                                                'estado_devuelto_modificar': history.state == 'Devuelto para modificaciones'
                                            }"> @{{ history.state }} </div>
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
    </div>
    <!-- end panel-body -->
</div>


<!-- Panel Flujo de producción documental -->
<div id="show_table" class="card" title="Ver en lista" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="card-body">


        <div class="card-heading ui-sortable-handle">
            <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <i class="fa fa-hand-pointer m-r-2"></i>Ver flujo de producción documental</button>
            <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_table')" style="margin-left: auto;"><i id="btnCard" class="fas fa-square" style="color: #5f6368;"></i></button>
            <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'external')" title="Descargar flujo documental"><i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i> </button>

        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="collapse" id="collapseExample">
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover fix-vertical-table">
                                <thead>
                                    <tr style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;">
                                        <th>Creado por</th>
                                        <th>@lang('Created_at')</th>
                                        <th>@lang('Consecutive')</th>
                                        <th>Tipo de documento</th>
                                        <th>@lang('State')</th>
                                        <th>@lang('Title')</th>
                                        <th>Remitente</th>
                                        <th>Ciudadano</th>
                                        <th>Documento del Ciudadano</th>
                                        <th>Correo del ciudadano</th>
                                        <th>Documento principal</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(history, key) in dataShow.external_history">
                                        <td>@{{ history.users_name }}</td>
                                        <td>@{{ history.created_at }}</td>
                                        <td>@{{ history.consecutive }}</td>
                                        <td>@{{ history.type_document }}</td>
                                        <td>@{{ history.state }}</td>
                                        <td>@{{ history.title }}</td>
                                        <td>@{{ history.from }}</td>
                                        <td v-html="history.citizen_name"></td>
                                        <td>@{{ history.citizen_document }}</td>
                                        <td>@{{ history.citizen_email }}</td>

                                        <td>
                                            <viewer-attachement :link-file-name="true" v-if="history.document_pdf" :list="history.document_pdf" :key="history.document_pdf"></viewer-attachement>
                                            {{-- <ul v-if="history.document_pdf">
                                            <li v-for="document_pdf in history.document_pdf.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+document_pdf" target="_blank">Ver adjunto</a><br /></li>
                                            </ul> --}}
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
    <!-- end panel-body -->
</div>
</div>