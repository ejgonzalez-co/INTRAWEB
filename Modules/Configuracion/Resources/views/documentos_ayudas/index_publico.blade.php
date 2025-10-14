@extends('layouts.default')

@section('title', trans('Documentos de ayuda'))

@section('section_img', 'assets/img/components/doc_consultar.png')

@section('menu')
    @include('configuracion::layouts.menu')
@endsection

@section('content')

<crud
    name="documentos-ayudas"
    :resource="{default: 'documentos-ayudas', get: 'get-documentos-ayudas'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('documentos de ayuda')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Documentos de ayuda de la entidad</h1>
        <!-- end page-header -->

        <div class="d-flex justify-content-start align-items-center flex-wrap">
            <div class="m-r-20 m-b-20" @click="show(ayuda)" v-for="ayuda in advancedSearchFilterPaginate()"  data-target="#modal-view-documentos-ayudas" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="@lang('see_details')" class="card shadow col-md-3" style="cursor: pointer;">
                <div v-if="ayuda.detalles_ayuda.categoria != 'Documento'" style="display: flex; justify-content: flex-end; align-items: flex-end;" :key="ayuda.id">
                    <img :src="ayuda.detalles_ayuda.miniatura" alt="Imagen previa" class="miniatura">
                    <span v-if="ayuda.categoria != 'Documento'" style="position: absolute; background-color: black; border-radius: 4px; color: white; margin: 4px; padding: 3px 4px; font-weight: 500;">@{{ayuda.detalles_ayuda.duracion_video}}</span>
                </div>

                <div v-else :key="ayuda.id">
                    <img v-if="ayuda.imagen_previa" :src="'/storage/'+ayuda.imagen_previa" :alt="ayuda.nombre" style="border: 1px solid rgb(0 0 0 / 10%); padding: 5px;" class="miniatura">

                    <img v-else src="{{ asset('assets/img/imagen_previa.png') }}" :alt="ayuda.nombre" style="border: 1px solid rgb(0 0 0 / 10%); padding: 5px;" class="miniatura">
                </div>
                <div class="">
                    <div class="text-break mt-2" style="font-size: 1rem; line-height: 1.5rem; font-weight: 500;max-width:300px;">@{{ ayuda.nombre }}</div>
                </div>
                <div style="font-size: .7rem; font-weight: 400;">Contenido: @{{ ayuda.detalles_ayuda.categoria }}</div>
            </div>
        </div>

        <!-- begin #modal-view-documentos-ayudas -->
        <div class="modal fade" id="modal-view-documentos-ayudas">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información de la ayuda</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('configuracion::documentos_ayudas.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>

    // $(document).ready(function(){
    //     $('.video-preview').click(function(){
    //         $('#videoModal').modal('show');
    //     });
    // });
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-documentos-ayudas').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
