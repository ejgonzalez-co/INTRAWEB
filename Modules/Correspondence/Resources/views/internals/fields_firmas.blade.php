<div class="panel-body">
    <div class="row">

        <!-- firmado-->
        <div class="col-md-12">
            <div class="form-group row m-b-15">
                {!! Form::label('dibujar', '¿Usar firma actual?', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    @if (!empty(Auth::user()->url_digital_signature))
                        <div>
                            <p>Actualmente tiene esta firma registrada:</p>
                            <img src="{{ asset('storage/' . Auth::user()->url_digital_signature) }}" alt="Firma Digital" style="max-width: 100%; height: auto;" />
                            <br>
                            <a href="/profile" target="_blank" class="btn btn-link p-0">Cambiar firma desde el perfil <i class="fas fa-external-link-alt" style="color: #2196f3;"></i></a>
                            <p class="mt-3">Seleccione cómo desea proceder:</p>
                            <ul class="list-unstyled">
                                <li>
                                    <label>
                                        <input type="radio" value="Si" v-model="dataForm.usar_firma_cargada" />
                                        Usar la firma actual
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" value="No" v-model="dataForm.usar_firma_cargada" />
                                        Dibujar o subir una firma diferente
                                    </label>
                                </li>
                            </ul>
                            <sign-to-image ref="signToImage" v-if="dataForm.usar_firma_cargada === 'No'"></sign-to-image>
                        </div>
                    @else
                        <div>
                            <p>No tiene una firma registrada.</p>
                            <a href="/profile" target="_blank" class="btn btn-link p-0">Cambiar firma desde el perfil <i class="fas fa-external-link-alt" style="color: #2196f3;"></i></a>
                            <p class="mt-3">Opciones disponibles:</p>
                            <ul class="list-unstyled">
                                <li>
                                    <label>
                                        <input type="radio" value="Si" v-model="dataForm.usar_firma_cargada" />
                                        Usar la firma actual
                                    </label>
                                </li>
                                <li>
                                    <label for="">
                                        <input type="radio" value="No" v-model="dataForm.usar_firma_cargada" />
                                        Dibujar o subir una firma diferente
                                    </label>
                                </li>
                            </ul>
                            <sign-to-image ref="signToImage" v-if="dataForm.usar_firma_cargada === 'No'"></sign-to-image>
                        </div>
                    @endif
        
                    <div class="invalid-feedback" v-if="dataErrors.require_answer">
                        <p class="m-b-0" v-for="error in dataErrors.require_answer">
                            @{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>