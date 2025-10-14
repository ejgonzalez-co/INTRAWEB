@if(config('app.mod_expedientes'))
    <span v-if="dataShow.tiene_expediente" class="ml-auto">
        <span class="badge badge-light">
            <i class="fas fa-sitemap"></i> Expediente:
            <!-- Si el usuario tiene el rol 'Operador', muestra el link -->
            @if(Auth::user()->hasAnyRole(['Operador Expedientes Electrónicos']))
                <a target="_blank" :href="'/expedientes-electronicos/documentos-expedientes?c='+dataShow.tiene_expediente.id" class="text-primary">
                    @{{ dataShow.tiene_expediente.consecutivo }}
                </a>
            @else
                <!-- Si el usuario no tiene el rol, muestra solo el texto sin el link -->
                <span>@{{ dataShow.tiene_expediente.consecutivo }}</span>
            @endif
        </span>
    </span>
@endif
