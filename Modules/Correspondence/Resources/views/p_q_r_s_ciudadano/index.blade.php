@extends('layouts.default')

@section('title', trans('Consulta de PQRS'))

@section('menu')
    @include('correspondence::layouts.menu_ciudadano')
@endsection

@section('content')

<crud
    name="p-q-r-s"
    :resource="{default: 'p-q-r-s-ciudadano', get: 'get-recibida-pqrs-ciudadano'}"
    inline-template
    :init-values="{showCounters : false}"
    :crud-avanzado="true">
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">PQR</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Consulta de Correspondencia Recibida/PQRS</h1>
        <!-- end page-header -->
        <hr>
        <div class="border-bottom p-3">
            <div class="card-body">
                <label class="col-form-label"><b>Para conocer el estado de su solicitud, utilice el número de radicado de la correspondencia recibida o el consecutivo del PQRS junto con el código de validación.</b></label>
                <!-- Campos de busqueda -->
                <div class="row form-group">
                    <div class="col-md-4 col-sm-12 mb-2 mb-md-0">
                        {{-- Consecutivo de la corrspondencia recibida --}}
                        {!! Form::text('consecutive', null, [
                            'v-model' => 'searchFields.consecutive_igual_',
                            'class' => 'form-control',
                            'placeholder' => trans('crud.filter_by', ['name' => trans('número de radicado')]),
                            '@keyup.enter' => 'pageEventActualizado(1)',
                        ]) !!}
                    </div>
                    {{-- Código de validación de la correspondencia recibida --}}
                    <div class="col-md-4 col-sm-12 mb-2 mb-md-0">
                        {!! Form::text('pqr', null, [
                            'v-model' => 'searchFields.validation_code_igual_',
                            'class' => 'form-control',
                            'placeholder' => trans('crud.filter_by', ['name' => trans('código de validación')]),
                            '@keyup.enter' => 'pageEventActualizado(1)',
                        ]) !!}
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <button @click="!searchFields.consecutive_igual_ ? searchFields.consecutive_igual_ = '' : ''; pageEventActualizado(1)" class="btn btn-md btn-primary mr-2 mt-2"><i class="fa fa-search mr-2"></i>Buscar</button>
                        <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-secondary mt-2">@lang('clear_search_fields')</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') PQRS: ${dataPaginator.total}` | capitalize }}</h5>
                </div>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>

            <div class="panel-body">
                <!-- begin buttons action table -->
                <viewer-attachement :link-file-name="true" type="table" ref="viewerDocuments"></viewer-attachement>
                <!-- end buttons action table -->
                @include('correspondence::p_q_r_s_ciudadano.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    <label for="show_qty" class="col-form-label col-md-7">Cantidad a mostrar:</label>
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], 20, ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}
                    </div>
                </div>
                <!-- Paginador de tabla -->
                <div class="col-md-12">
                    <paginate
                        v-model="dataPaginator.currentPage"
                        :page-count="dataPaginator.numPages"
                        :click-handler="pageEventActualizado"
                        :prev-text="'Anterior'"
                        :next-text="'Siguiente'"
                        :container-class="'pagination m-10'"
                        :page-class="'page-item'"
                        :page-link-class="'page-link'"
                        :prev-class="'page-item'"
                        :next-class="'page-item'"
                        :prev-link-class="'page-link'"
                        :next-link-class="'page-link'"
                        :disabled-class="'ignore disabled'">
                    </paginate>
                </div>
            </div>
        </div>
        <!-- end panel -->

        <!-- begin #modal-view-p-q-r-s -->
        <div class="modal fade" id="modal-view-p-q-r-s">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of')l PQR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('correspondence::p_q_r_s_ciudadano.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-p-q-r-s -->
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
    $('#modal-form-p-q-r-s').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
