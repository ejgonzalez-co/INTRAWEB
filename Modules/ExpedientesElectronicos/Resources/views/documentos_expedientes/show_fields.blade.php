<div class="container-fluid bg-light py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5><strong>Información general</strong></h5>
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <p class="mb-0"><strong><i class="fas fa-user"></i> Usuario Creador</strong></p>
                            <dd>@{{ dataShow?.documento_expediente?.user_name ?? 'N/A' }}.</dd>
                        </div>
                        <div class="col">
                            <p class="mb-0"><strong><i class="fas fa-cogs"></i> Origen de creación:</strong></p>
                            <dd>@{{ dataShow?.documento_expediente?.origen_creacion ?? 'N/A' }}.</dd>
                        </div>
                        <div class="col">
                            <p class="mb-0"><strong><i class="fas fa-hashtag"></i> Consecutivo:</strong></p>
                            <dd>@{{ dataShow?.documento_expediente?.consecutivo ?? 'Documento nuevo' }}.</dd>
                        </div>
                        <div class="col">
                            <p class="mb-0"><strong><i class="fas fa-file-alt"></i> Nombre expediente:</strong></p>
                            <dd>@{{ dataShow?.documento_expediente?.ee_expediente?.nombre_expediente ?? 'N/A' }}.</dd>
                        </div>
                        <div class="col">
                            <p class="mb-0"><strong><i class="fas fa-calendar-check"></i> Vigencia:</strong></p>
                            <dd>@{{ dataShow?.documento_expediente?.vigencia ?? 'N/A' }}.</dd>
                        </div>
                        <div class="col">
                            <p class="mb-0"><strong><i class="fas fa-file"></i> Nombre del documento:</strong></p>
                            <dd>@{{ dataShow?.documento_expediente?.nombre_documento ?? 'N/A' }}.</dd>
                        </div>
                        <div class="col">
                            <p class="mb-0"><strong><i class="fas fa-pen"></i> Descripción:</strong></p>
                            <dd>@{{ dataShow?.documento_expediente?.descripcion ?? 'N/A' }}.</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5><strong>Documento</strong></h5>
                    <viewer-attachement v-if="dataShow?.documento_expediente?.adjunto && dataShow?.documento_expediente?.adjunto.length > 0" :link-file-name="true" v-if="dataShow?.documento_expediente?.adjunto" :list="dataShow?.documento_expediente?.adjunto "></viewer-attachement>
                    <span v-else>No tiene adjunto</span>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div><strong>Anotaciones: </strong></div>
                    <table id="anotaciones" class="table table-bordered text-center mt-1">
                        <thead>
                            <tr class="custom-thead">
                                <td>Fecha</td>
                                <td>Usuario</td>
                                <td>Anotación</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="anotacion in dataShow.documento_expediente?.documento_expediente_anotaciones">
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
</div>



{{-- Detalles de los metadatos y sus respuestas --}}
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow?.documento_expediente?.documento_has_metadatos?.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Metadatos</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 form-group row m-b-15" v-for="metadato in dataShow?.documento_expediente.documento_has_metadatos">
                <dt class="text-inverse col-sm-4 col-md-3 col-lg-4"><strong>@{{ metadato.metadatos.nombre }}: </strong></dt>
                <dd class="col-sm-8 col-md-8 col-lg-8">
                    @{{
                        metadato.valor.includes("option")
                        ? JSON.parse(metadato.metadatos.opciones)[metadato.valor]
                        : metadato.valor
                    }}
                </dd>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- Panel Información inicial -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" style="background: #dddddd42;" v-if="dataShow?.documento_expediente?.modulo_intraweb != 'Expediente electrónico'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información del documento asociado</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div v-if="dataShow?.documento_expediente?.modulo_intraweb == 'Correspondencia interna'">
            @include('correspondence::internals.show_fields')
        </div>
        <div v-else-if="dataShow?.documento_expediente?.modulo_intraweb == 'Correspondencia recibida'">
            @include('correspondence::external_receiveds.show_fields')
        </div>
        <div v-else-if="dataShow?.documento_expediente?.modulo_intraweb == 'Correspondencia enviada'">
            @include('correspondence::externals.show_fields')
        </div>
        <div v-else-if="dataShow?.documento_expediente?.modulo_intraweb == 'PQRSD'">
            @include('pqrs::p_q_r_s.show_fields')
        </div>
        <div v-else-if="dataShow?.documento_expediente?.modulo_intraweb == 'Documentos electrónicos'">
            @include('documentoselectronicos::documentos.show_fields')
        </div>
    </div>
</div>
@push('scripts')

<script>
    // función encargada de mostrar y ocultar el flujo documental
    function toggleDiv(divId) {
        const elementoDiv = document.getElementById(divId);
        var otroDivId = '';
        if (elementoDiv.id === 'show_cards') {
            otroDivId = 'show_table';
        } else if(elementoDiv.id === 'show_table'){
            otroDivId = 'show_cards';
        }

        const otroElementoDiv = document.getElementById(otroDivId);

        if (elementoDiv && otroElementoDiv) {


            const estiloActual = elementoDiv.style.display;
            const estiloActual2 = otroElementoDiv.style.display;
            elementoDiv.style.display = estiloActual === 'none' ? 'block' : 'none';
            otroElementoDiv.style.display = otroElementoDiv === 'block' ? 'none' : 'block';

        } else {
            console.error(`El elemento Div con ID ${divId} o ${otroDivId} no se encontró.`);
        }
    }

</script>

@endpush
