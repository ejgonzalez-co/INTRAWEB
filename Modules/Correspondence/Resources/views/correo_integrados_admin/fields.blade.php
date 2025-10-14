
<div>
    <div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Mensajes de respuesta</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div>
                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('notificacion_correspondencia', 'Clasificado como correspondencia:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('notificacion_correspondencia', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.notificacion_correspondencia }", 'v-model' => 'dataForm.notificacion_correspondencia', 'required' => true, 'rows' => '3']) !!}
                        <small>Ingrese el mensaje para la notificación, cuando la comunicación es clasificada como correspondencia.</small>
                        <div class="invalid-feedback" v-if="dataErrors.notificacion_correspondencia">
                            <p class="m-b-0" v-for="error in dataErrors.notificacion_correspondencia">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('notificacion_pqr', 'Clasificado como PQRS:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('notificacion_pqr', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.notificacion_pqr }", 'v-model' => 'dataForm.notificacion_pqr', 'required' => true, 'rows' => '3']) !!}
                        <small>Ingrese el mensaje para la notificación, cuando la comunicación es clasificada como PQRS.</small>
                        <div class="invalid-feedback" v-if="dataErrors.notificacion_pqr">
                            <p class="m-b-0" v-for="error in dataErrors.notificacion_pqr">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('notificacion_no_oficial', 'No era una comunicación oficial:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('notificacion_no_oficial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.notificacion_no_oficial }", 'v-model' => 'dataForm.notificacion_no_oficial', 'required' => true, 'rows' => '3']) !!}
                        <small>Ingrese el mensaje para la notificación, cuando la comunicación es clasificada como no oficial.</small>
                        <div class="invalid-feedback" v-if="dataErrors.notificacion_no_oficial">
                            <p class="m-b-0" v-for="error in dataErrors.notificacion_no_oficial">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Correo oficial de comunicaciones</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div>
                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('correo_comunicaciones', 'Correo electrónico:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('correo_comunicaciones', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.correo_comunicaciones }", 'v-model' => 'dataForm.correo_comunicaciones', 'required' => true]) !!}
                        <small>Ingrese el correo oficial de la entidad para obtener las comunicaciones. Ej: admin@dominio.com</small>
                        <div class="invalid-feedback" v-if="dataErrors.correo_comunicaciones">
                            <p class="m-b-0" v-for="error in dataErrors.correo_comunicaciones">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('correo_communicaciones_clave', 'Contraseña:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::password('correo_communicaciones_clave', [':class' => "{'form-control':true, 'is-invalid':dataErrors.correo_communicaciones_clave }", 'v-model' => 'dataForm.correo_communicaciones_clave', 'required' => true]) !!}
                        <small>Ingrese la contraseña del correo.</small>
                        <div class="invalid-feedback" v-if="dataErrors.correo_communicaciones_clave">
                            <p class="m-b-0" v-for="error in dataErrors.correo_communicaciones_clave">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Configuración IMAP</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div>
                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('servidor', 'Servidor:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('servidor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.servidor }", 'v-model' => 'dataForm.servidor', 'placeholder' => 'imap.gmail.com']) !!}
                        <small>Ingrese el servidor del correo. Ej: imap.gmail.com. Si no ingresa nada, este será el valor por defecto.</small>
                        <div class="invalid-feedback" v-if="dataErrors.servidor">
                            <p class="m-b-0" v-for="error in dataErrors.servidor">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('seguridad', 'Seguridad:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('seguridad', ["SSL" => "SSL", "TLS" => "TLS"], "SSL", [':class' => "{'form-control':true, 'is-invalid':dataErrors.seguridad }", 'v-model' => 'dataForm.seguridad']) !!}
                        <small>Seleccione el tipo de seguridad para obtener los correos, si no selecciona nada, tomará el valor SSL por defecto.</small>
                        <div class="invalid-feedback" v-if="dataErrors.seguridad">
                            <p class="m-b-0" v-for="error in dataErrors.seguridad">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('puerto', 'Puerto:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('puerto', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.puerto }", 'v-model' => 'dataForm.puerto', 'placeholder' => '993']) !!}
                        <small>Ingrese el puerto de comunicación con el servidor de correo. Ej: 993. Si no ingresa nada, este será el valor por defecto.</small>
                        <div class="invalid-feedback" v-if="dataErrors.puerto">
                            <p class="m-b-0" v-for="error in dataErrors.puerto">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group row m-b-15 col-md-12">
                    {!! Form::label('obtener_desde', 'Obtener desde:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::date('obtener_desde', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.obtener_desde }", 'v-model' => 'dataForm.obtener_desde']) !!}
                        <small>Seleccione la fecha desde la que se va a empezar a obtener los correos. Si selecciona ninguna fecha, por defecto será la fecha actual.</small>
                        <div class="invalid-feedback" v-if="dataErrors.obtener_desde">
                            <p class="m-b-0" v-for="error in dataErrors.obtener_desde">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

