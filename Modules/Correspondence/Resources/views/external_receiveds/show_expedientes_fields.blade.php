<!-- Panel expedientes relacionados -->

@if (config('app.mod_expedientes')) 
<div v-if="" class="row mb-3">

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h5 class=""><strong>Expedientes relacionados</strong></h5>
            <div v-if="dataShow.tiene_expediente?.length > 0">
                <div class="row g-3">
                    <div class="col-12 col-md-4" v-for="expediente in dataShow.tiene_expediente" :key="expediente.id">
                        <p class="mb-2">
                            @php
                                $isLoggedIn = Auth::check();
                                $userId = Auth::id(); // devuelve null si no hay usuario
                            @endphp

                            <a v-if="@json($isLoggedIn) && (expediente.permiso_usar_expediente || expediente.permiso_consultar_expediente || expediente.id_responsable == @json($userId))" 
                               target="_blank" 
                               :href="'/expedientes-electronicos/documentos-expedientes?c='+expediente.id_encoded" 
                               style="color: rgb(72, 142, 241); font-weight: bold; text-decoration: underline;" 
                               :title="expediente.nombre_expediente">
                                @{{ expediente.consecutivo }}
                            </a>
                            <span v-else>@{{ expediente.consecutivo }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body" v-else>
                <div class="d-flex flex-column align-items-center justify-content-center gap-3 h-75">
                    <i style="color:#49474750;" class="fas fa-exclamation-circle fa-4x"></i>
                    <h6 class="text-secondary mt-3">No hay expedientes asociados.</h6>
                </div>
            </div>
            
        </div>
    </div>
</div>

</div>
@endif
