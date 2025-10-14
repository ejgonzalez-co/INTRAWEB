@extends('layouts.default')

@section('title', trans('mapa-procesos'))

@section('section_img', '')

@section('menu')
    @include('calidad::layouts.menu')
@endsection

@section('content')

<crud
    name="mapa-procesos"
    :resource="{default: 'mapa-procesos', get: 'get-mapa-procesos'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('mapa-procesos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('mapa-procesos')'}}</h1>
        <!-- end page-header -->

        <div class="mb-2" style="color: inherit; background-color: #eee; border-radius: 0.5rem; !important; padding: 1rem;">
            <p>¿Cómo hacerlo?</p>
            <ol>
                <li>Agregue un nuevo mapa de procesos usando el botón "Nuevo mapa de procesos".</li>
                <li>Haga clic sobre la imagen para insertar zonas y asocie cada una con el enlace correspondiente a un proceso.</li>
                <li>Copie los enlaces de los procesos desde el botón flotante "Procesos de la entidad".</li>
                <li>Guarde las zonas insertadas en el mapa utilizando el botón "Guardar zonas en el mapa de procesos".</li>
            </ol>
        </div>

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-mapa-procesos" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Nuevo mapa de procesos
            </button>
        </div>
        <!-- end main buttons -->

        <mapa-procesos-calidad v-if="dataList.length > 0" :image-src="dataList.length > 0 ? '/storage/'+dataList[0].adjunto : ''" :links="dataList[0].mapa_procesos_links" :image-id="dataList[0].id" :procesos="dataList[0].procesos"></mapa-procesos-calidad>
        <div v-else style="font-size: 14px; margin-top: 30px; margin-bottom: 40px;">
            No se ha seleccionado ninguna imagen
        </div>
        <!-- begin #modal-view-mapa-procesos -->
        <div class="modal fade" id="modal-view-mapa-procesos">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('mapa-procesos')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('calidad::mapa_procesos.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-mapa-procesos -->

        <!-- begin #modal-form-mapa-procesos -->
        <div class="modal fade" id="modal-form-mapa-procesos">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-mapa-procesos">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('mapa-procesos')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('calidad::mapa_procesos.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-mapa-procesos -->
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
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
