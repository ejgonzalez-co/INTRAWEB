@extends('layouts.default')

@section('title', trans('mapa-procesos'))

@section('section_img', '')

@section('menu')
    @include('calidad::layouts.menu')
@endsection

@section('content')

<crud
    name="mapa-procesos"
    :resource="{default: 'mapa-procesos', get: 'get-mapa-procesos-publico'}"
    inline-template
    :crud-avanzado="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('mapa-procesos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center">@{{ '@lang('mapa-procesos')'}}</h1>
        <!-- end page-header -->

        <mapa-procesos-calidad-publico inline-template ref="mapaProcesos">
            <div>
                <!-- Tabs de navegación -->
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link" :class="{ active: activeTab === 'general' }"
                                    href="#" @click.prevent="activeTab = 'general'">
                                    Mapa de procesos general
                                </a>
                            </li>
                            <li class="nav-item" v-if="$parent.dataExtra.mapa_procesos_links?.length > 0">
                                <a class="nav-link" :class="{ active: activeTab === 'generado' }"
                                    href="#" @click.prevent="activeTab = 'generado'">
                                    Mapa de procesos personalizado
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <!-- Contenido del tab: Mapa de procesos general -->
                        <div v-if="activeTab == 'general'" style="font-weight: bold;">
                            <div v-for="(macro, indexMacro) in $parent.dataExtra.macroprocesos_mapa" :key="'macro-' + indexMacro" class="tproceso" :id="'macro-' + indexMacro">
                                <div class="macro-nombre">@{{ macro.nombre }}</div>
                                <br>
                                <div class="procesos">
                                    <div v-for="(proceso, indexProc) in macro.procesos_mapa" :key="'proceso-' + indexProc" class="proceso">
                                        <div class="proceso-nombre">
                                            <a :href="proceso.url" target="_top"><strong>@{{ proceso.prefijo }}</strong></a><br>
                                            <a :href="proceso.url" target="_top"><strong>@{{ proceso.nombre }}</strong></a>
                                        </div>
                                        <div class="titulo-sub" v-if="proceso.subprocesos_mapa?.length > 0">Subprocesos</div>
                                        <div v-for="(sub, indexSub) in proceso.subprocesos_mapa" :key="'sub-' + indexSub" class="subprocesos">
                                            <a :href="sub.url" target="_top"><strong>@{{ sub.nombre }}</strong></a>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <i v-if="indexMacro < $parent.dataExtra.macroprocesos_mapa.length - 1" class="fas fa-arrows-alt-v fa-2x" style="position: absolute; margin-top: 11px; left: 35%;"></i>
                                <i v-if="indexMacro < $parent.dataExtra.macroprocesos_mapa.length - 1" class="fas fa-arrows-alt-v fa-2x" style="position: absolute; margin-top: 11px; right: 36%;"></i>
                            </div>
                        </div>
                        <!-- Contenido del tab: Mapa de procesos generado -->
                        <div v-else-if="activeTab == 'generado' && $parent.dataExtra.mapa_procesos_links?.length > 0" class="image-container col-md-12">
                            <img :src="'/storage/' + $parent.dataExtra.mapa_procesos_links[0].adjunto" ref="image" style="max-width: 90%; margin: auto; display: block;" @load="$set($parent.dataExtra, 'loadedImg', true); onImageLoad()">
                            <a
                                v-for="(link, index) in zonasCalculadas"
                                :key="index"
                                class="hotspot"
                                :href="link.url"
                                target="_blank"
                                style="position: absolute;"
                                :style="{
                                    left: link.left + 'px',
                                    top: link.top + 'px',
                                    width: link.width + 'px',
                                    height: link.height + 'px'
                                }"
                            ></a>
                            <div v-if="!$parent.dataExtra.loadedImg" style="height: 20rem;">
                                <div class="spinner"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
            </div>
        </mapa-procesos-calidad-publico>

    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
.tproceso {
    padding: 10px;
    background-color: #2b2f3a;
    border-radius: 8px;
    text-align: center;
    width: 90%;
    margin: auto;
    margin-bottom: 28px;
}

/* Macroproceso */
.macro-nombre {
    color: white;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}

/* Procesos */
.procesos {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.proceso {
    background: #f5f5f5;
    padding: 12px;
    border-radius: 6px;
    width: 250px;
    text-align: center;
}

.proceso-nombre {
    font-size: 16px;
    font-weight: bold;
    color: black;
}

.proceso-nombre a {
    color: black;
}

/* Subprocesos */
.subprocesos {
    padding: 10px;
    background: #c2c4c7;
    border-radius: 5px;
    text-align: center;
    width: 200px;
    margin: auto;
    margin-bottom: 10px;
    /* font-size: 14px; */
}

.subprocesos a {
    color: #444;
}

.titulo-sub {
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
    margin-bottom: 5px;
    color: #333;
}

.clear {
    clear: both;
}

</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-mapa-procesos').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
