<div class="container-fluid bg-light py-4">
    <div v-if="dataShow.existe_fisicamente == 'Si'" class="row">
        <div class="d-flex flex-column col-12 col-lg-7">
            <div class="card mb-3 h-100">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos generales del expediente</strong></h5>

                    <div class="d-flex gap-3">
                        <div class="bg-gray bor rounded-circle mr-2" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">
                            <img v-if="dataShow.image_responsable != null"
                                class="w-100 rounded-circle" :src="'{{ asset('storage') }}/' + dataShow.image_responsable" alt="" />
                            <div v-else>@{{ dataShow.name_responsable ? dataShow.name_responsable.charAt(0) : 'N' }}</div>
                        </div>
                        <div class="col">
                            <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2">
                                <div class="mb-2">
                                    <p class="mb-0"><strong><i class="fas fa-user"></i> Responsable</strong></p>
                                    <p>@{{ dataShow.name_responsable ? dataShow.name_responsable : 'N/A' }}.</p>
                                </div>

                                <div class="mb-2">
                                    <p class="mb-0"><strong>Nombre del expediente</strong></p>
                                    <p>@{{ dataShow.nombre_expediente ? dataShow.nombre_expediente : 'N/A' }}.</p>
                                </div>
                                <div class="mb-2">
                                    <p class="mb-0"><strong> Fecha inicio del expediente</strong></p>
                                    <p>@{{ dataShow.fecha_inicio_expediente ? dataShow.fecha_inicio_expediente : 'N/A' }}.</p>
                                </div>
                                <div class="mb-2">
                                    <p class="mb-0"><strong>Oficina productora</strong></p>
                                    <p>@{{ dataShow.oficina_productora_clasificacion_documental?.nombre ?? 'No asignada' }}.</p>
                                </div>

                                <div class="mb-2">
                                    <p class="mb-0"><strong>Estado</strong></p>
                                    <p>@{{ dataShow.estado }}.</p>
                                </div>
                                <div class="mb-2">
                                    <p class="mb-0"><strong>Serie</strong></p>
                                    <p>@{{ dataShow.serie_clasificacion_documental?.name_serie ?? 'No asignada' }}.</p>
                                </div>

                                <div class="mb-2">
                                    <p class="mb-0"><strong>Subserie</strong></p>
                                    <p>@{{ dataShow.subserie_clasificacion_documental?.name_subserie ?? 'No asignada' }}.</p>
                                </div>
                                   <div class="mb-2">
                                    <p class="mb-0"><strong>Observación de la aprobación y/o devolución</strong></p>
                                    <p>@{{ dataShow.observacion_accion ? dataShow.observacion_accion : 'N/A' }}.</p>
                                </div>
                                <div class="mb-2">
                                    <p class="mb-0"><strong><i class="fas fa-info-circle"></i> Observación</strong></p>
                                    <p>@{{ dataShow.observacion ? dataShow.observacion : 'N/A' }}.</p>
                                </div>

                                <div class="mb-2">
                                    <p class="mb-0"><strong><i class="fas fa-stream"></i> Descripción</strong></p>
                                    <p>@{{ dataShow.descripcion ? dataShow.descripcion : 'N/A' }}.</p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column col-12 col-lg-5">
            <!-- Card para "Datos Generales" -->
            <div class="h-100 card mb-3">
                <div class="card-body h-100 d-flex flex-column">
                    <h5 class="mb-3"><strong>Datos Generales</strong></h5>
                    <div class="row flex-grow-1">
                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong>Ubicación</strong></p>
                            <p>@{{ dataShow.ubicacion ? dataShow.ubicacion : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong>Sede</strong></p>
                            <p>@{{ dataShow.sede ? dataShow.sede : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong><i class="fas fa-sitemap"></i> Dependencia</strong></p>
                            <p>@{{ dataShow.nombre_dependencia ? dataShow.nombre_dependencia : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong><i class="fas fa-archive"></i> Área de archivo</strong></p>
                            <p>@{{ dataShow.area_archivo ? dataShow.area_archivo : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong>Estante</strong></p>
                            <p>@{{ dataShow.estante ? dataShow.estante : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong>Módulo</strong></p>
                            <p>@{{ dataShow.modulo ? dataShow.modulo : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong><i class="fas fa-bars"></i> Entrepaño</strong></p>
                            <p>@{{ dataShow.entrepano ? dataShow.entrepano : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong><i class="fas fa-box"></i> Caja</strong></p>
                            <p>@{{ dataShow.caja ? dataShow.caja : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong>Cuerpo</strong></p>
                            <p>@{{ dataShow.cuerpo ? dataShow.cuerpo : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong>Unidad de conservación</strong></p>
                            <p>@{{ dataShow.unidad_conservacion ? dataShow.unidad_conservacion : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong><i class="fas fa-calendar-alt"></i> Fecha de archivo</strong></p>
                            <p>@{{ dataShow.fecha_archivo ? dataShow.fecha_archivo : 'N/A' }}.</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <p class="mb-0"><strong><i class="fas fa-list-ol"></i> Número de inventario</strong></p>
                            <p>@{{ dataShow.numero_inventario ? dataShow.numero_inventario : 'N/A' }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3" v-else>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3"><strong>Datos generales del expediente</strong></h5>

                        <div class="d-flex gap-3">
                            <div class="bg-gray bor rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; color:white; font-size:1.2rem">
                                <img v-if="dataShow.image_responsable != null"
                                    class="w-100 rounded-circle" :src="'{{ asset('storage') }}/' + dataShow.image_responsable" alt="" />
                                <div v-else>@{{ dataShow.name_responsable ? dataShow.name_responsable.charAt(0) : 'N' }}</div>
                            </div>

                           <div class="col">
                            <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 g-3 d-flex flex-wrap">
                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-user"></i> Responsable</strong></p>
                                    <p>@{{ dataShow.name_responsable ? dataShow.name_responsable : 'N/A' }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-folder"></i> Nombre del expediente</strong></p>
                                    <p>@{{ dataShow.nombre_expediente ? dataShow.nombre_expediente : 'N/A' }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-folder"></i> Fecha inicio del expediente</strong></p>
                                    <p>@{{ dataShow.fecha_inicio_expediente ? dataShow.fecha_inicio_expediente : 'N/A' }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-building"></i> Oficina productora</strong></p>
                                    <p>@{{ dataShow.oficina_productora_clasificacion_documental?.nombre ?? 'No asignada' }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-info-circle"></i> Estado</strong></p>
                                    <p>@{{ dataShow.estado }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-layer-group"></i> Serie</strong></p>
                                    <p>@{{ dataShow.serie_clasificacion_documental?.name_serie ?? 'No asignada' }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-stream"></i> Subserie</strong></p>
                                    <p>@{{ dataShow.subserie_clasificacion_documental?.name_subserie ?? 'No asignada' }}.</p>
                                </div>


                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-stream"></i> Observación de la aprobación y/o devolución</strong></p>
                                    <p>@{{ dataShow.observacion_accion ? dataShow.observacion_accion : 'N/A' }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-info-circle"></i> Observación</strong></p>
                                    <p>@{{ dataShow.observacion ? dataShow.observacion : 'N/A' }}.</p>
                                </div>

                                <div class="col d-flex flex-column">
                                    <p class="mb-0"><strong><i class="fas fa-stream"></i> Descripción</strong></p>
                                    <p>@{{ dataShow.descripcion ? dataShow.descripcion : 'N/A' }}.</p>
                                </div>

                            </div>
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3" v-if="dataShow.expediente_has_metadatos?.length > 0">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Metadatos</strong></h5>

                    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 g-3 d-flex flex-wrap">
                        <div class="col d-flex flex-column" v-for="metadato in dataShow.expediente_has_metadatos">
                            <p class="mb-0"><strong>@{{ metadato.metadatos.nombre }}</strong></p>
                            <p> @{{
                                metadato.valor.includes("option")
                                ? JSON.parse(metadato.metadatos.opciones)[metadato.valor]
                                : metadato.valor
                            }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-3 flex-grow-1">
                <div class="card-body h-100 d-flex flex-column">
                    <h5 class="mb-3"><strong>Permisos del expediente</strong></h5>

                    <div class="">
                        <!-- Permiso Consultar Expedientes Todas Field -->
                        <dt>@lang('Permisos generales sobre el expediente y sus documentos')</dt>
                        <dd>@{{ dataShow.permiso_general_expediente ?? 'N/A' }}.</dd>
                    </div>

                    <div v-if="dataShow.ee_permiso_usuarios_expedientes" class="flex-grow-1">
                        <!-- Dependencias/Usuarios Field -->
                        <dt class="mb-1">@lang('Dependencias y usuarios con permisos sobre el expediente')</dt>
                        <dd>
                            <table id="permisos_usuarios" class="table table-bordered">
                                <thead>
                                    <tr class="custom-thead">
                                        <td>Tipo</td>
                                        <td>Nombre</td>
                                        <td>Permiso</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="dependencia_usuario in dataShow.ee_permiso_usuarios_expedientes">
                                        <td>@{{ dependencia_usuario.tipo_usuario }}</td>
                                        <td>@{{ (dependencia_usuario.tipo == 'Dependencia' ? dependencia_usuario.tipo : '') + dependencia_usuario.nombre }}</td>
                                        <td>@{{ dependencia_usuario.permiso }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Seguimiento del expediente</strong></h5>
                    <div><strong>¿Quiénes han leido el expediente electrónico?</strong></div>
                    <table id="expediente_leido" class="table table-bordered mt-1">
                        <thead>
                            <tr class="custom-thead">
                                <td>Usuario</td>
                                <td>Tipo de usuario</td>
                                <td>Accesos</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="leido in dataShow.expediente_leido">
                                <td>@{{ leido.nombre_usuario }}</td>
                                <td>@{{ leido.tipo_usuario }}</td>
                                <td width="250">
                                    <i class="fa fa-arrow-circle-down" aria-hidden="true" data-toggle="collapse"
                                        :data-target="'#learnMore' + leido.id"></i>
                                    <div :id="'learnMore' + leido.id" class="collapse" v-html="leido.accesos"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <br />

                    <div><strong>Anotaciones: </strong></div>
                    <table id="anotaciones" class="table table-bordered mt-1">
                        <thead>
                            <tr class="custom-thead">
                                <td>Fecha</td>
                                <td>Usuario</td>
                                <td>Anotación</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="anotacion in dataShow.expediente_anotaciones">
                                <td>@{{ anotacion.created_at }}</td>
                                <td>@{{ anotacion.nombre_usuario  ?? 'NA'}}</td>
                                <td><span class="contenidotext" v-html="anotacion.anotacion"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card" id="show_cards" data-sortable-id="ui-general-1">
                <div class="card-body">
                    <div class="card-heading ui-sortable-handle mb-2">
                        <button class="btn btn-outline-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-hand-pointer m-r-2"></i>Historial de cambios del expediente</button>
                        {{-- <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_cards')" title="Ver en tabla" style="margin-left: auto;"><i id="btnTable" class="fa fa-th" style="color: #5f6368;"></i></button> --}}
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="collapse" id="collapseExample">
                        {{-- <div class="col-md-12">
                <button type="button" class="btn bg-success-lighter" @click="exportarHistorial('xlsx', dataShow.id, 'external-received')"><i class="fas fa-file-download"></i> Exportar Historial</button>
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

                                            <div class="timeline-article" v-for="(history, key) in dataShow.ee_expediente_historials">

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

                                                            <span class="timeline-author"> @{{ history.user_name }}</span>
                                                        </div>
                                                        <hr>

                                                        <p>
                                                            <strong style="color:#00B0BD ">@{{ key + 1 }}. @{{ history.detalle_modificacion }} </strong> <br>
                                                            <strong>Consecutivo:</strong> @{{ history.consecutivo }}<br>

                                                            <strong>Fecha y hora:</strong> @{{ history.created_at }}<br>

                                                            <strong>Responsable:</strong> @{{ history.nombre_responsable }}<br>
                                                            <strong>Estado:</strong> @{{ history.estado ? history.estado : 'N/A' }}<br>
                                                            <strong>Descripción del cambio:</strong> @{{ history.detalle_modificacion ? history.detalle_modificacion : 'N/A' }}<br>

                                                        </p>

                                                    </div>

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
            </div>
        </div>

    </div>
</div>


{{-- <div id="show_table"  class="panel" title="Ver en lista" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <button class="btn btn-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fa fa-hand-pointer m-r-2"></i>Historial de cambios del expediente
        </button>
        <button class="btn btn-white btn-icon btn-md" onclick="toggleDiv('show_table')" style="margin-left: auto;">
            <i id="btnCard" class="fas fa-square" style="color: #5f6368;"></i>
        </button>
        <button type="button" class="btn btn-white btn-icon btn-md" @click="exportarHistorial('xlsx', dataShow.id, 'expediente')" title="Descargar flujo documental">
            <i id="btnDowLoad" class="fas fa-file-download" style="color: #5f6368;"></i>
        </button>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="collapse" id="collapseExample">
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
       <div class="row">
           <div class="col-md-12">
                <div class="table-responsive">
                   <table class="table table-hover fix-vertical-table">
                       <thead>
                           <tr style="background-color: #00B0BD; color: white; text-align: center;vertical-align: middle;" >
                               <th>Consecutivo</th>
                               <th>Fecha y hora</th>
                               <th>Responsable</th>
                               <th>Estado</th>
                               <th>Descripción del cambio</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr v-for="(history, key) in dataShow.ee_expediente_historials">
                               <td>@{{ history.consecutivo }}</td>
<td>@{{ history.created_at }}</td>
<td>@{{ history.nombre_responsable }}</td>
<td>@{{ history.estado }}</td>
<td>@{{ history.detalle_modificacion }}</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div> --}}
