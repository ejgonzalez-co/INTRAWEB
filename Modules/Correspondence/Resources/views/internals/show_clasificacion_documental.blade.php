<!-- Panel clasificación documental -->
@if (isset($clasificacion) && $clasificacion === 'si')
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <!-- Panel Comunicación por correo -->
            {{-- Detalles de clasificación documental --}}
            <div class="mb-4" data-sortable-id="ui-general-1" id="clasificacion">

            <div class="card-body" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <h5 class="mb-3"><strong>Clasificación documental</strong></h5>
                <!-- end panel-heading -->

                <!-- begin panel-body -->
                <div class="">
                    <div class="d-flex mb-2">
                        <p class="mb-0"><strong>Oficina productora:</strong></p>
                        <p class="mb-0 ml-2">@{{ dataShow.oficina_productora_clasificacion_documental?.nombre ?? 'No asignada' }}</p>
                    </div>

                    <div class="d-flex mb-2">
                        <p class="mb-0"><strong>Serie:</strong></p>
                        <p class="mb-0 ml-2">@{{ dataShow.serie_clasificacion_documental?.name_serie ?? 'No asignada' }}</p>
                    </div>

                    <div class="d-flex mb-2">
                        <p class="mb-0"><strong>Subserie:</strong></p>
                        <p class="mb-0 ml-2">@{{ dataShow.subserie_clasificacion_documental?.name_subserie ?? 'No asignada' }}</p>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>

            </div>
        </div>
    </div>
</div>
@endif
<!-- end panel -->
