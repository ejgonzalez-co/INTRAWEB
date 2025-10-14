@extends('layouts.default')

@section('title', 'Listado de correos validados')

@section('section_img', '')

@section('menu')
    @include('notificacionesintraweb::layouts.menu')
@endsection

@section('content')

<crud
    name="listado-correos-checkeos"
    :resource="{default: 'listado-correos-checkeos', get: 'get-listado-correos-checkeos'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('listado-correos-checkeos')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage')'}} el listado de correos validados</h1>
        <!-- end page-header -->
        <p style="font-size: 17px;" class="text-center  m-b-35">Los correos dirigidos a una dirección de correo electrónico en estado <strong>Válido</strong> serán enviados automáticamente, mientras que los correos dirigidos a una dirección en estado <strong>No válido</strong> no serán enviados.</p>

        <!-- begin main buttons -->
        {{-- <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-listado-correos-checkeos" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('listado-correos-checkeos')
            </button>
        </div> --}}
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') correos checkeados: ${dataPaginator.total}` | capitalize }}</h5>
                </div>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <!-- begin #accordion search-->
            <div id="accordion" class="accordion">
                <!-- begin card search -->
                <div @click="toggleAdvanceSearch()" class="cursor-pointer card-header bg-white pointer-cursor d-flex align-items-center" data-toggle="collapse" data-target="#collapseOne">
                    <i class="fa fa-search fa-fw mr-2 f-s-12"></i> <b>@{{ (showSearchOptions)? 'trans.hide_search_options' : 'trans.show_search_options' | trans }}</b>
                </div>
                <div id="collapseOne" class="collapse border-bottom p-l-40 p-r-40" data-parent="#accordion">
                    <div class="card-body">
                        <label class="col-form-label"><b>@lang('quick_search')</b></label>
                        <!-- Campos de busqueda -->
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input v-model="searchFields.email" class="form-control" placeholder="Filtrar por email" name="name" type="text">
                            </div>
                            <div class="col-md-4">
                                <input v-model="searchFields.user_name" class="form-control" placeholder="Filtrar por nombre de usuario" name="name" type="text">
                            </div>
                            <div class="col-md-4">
                                <input type="date" v-model="searchFields.fecha_verificacion"  id="fecha_verificacion" name="fecha_verificacion" class="vc-appearance-none vc-text-base vc-text-gray-800 vc-bg-white vc-border vc-border-gray-400 vc-rounded vc-w-full vc-py-2 vc-px-3 vc-leading-tight focus:vc-outline-none focus:vc-shadow">
                                <small>filtro por fecha de verificación</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.estado" name="estado">
                                    <option value="Valida">Válido</option>
                                    <option value="Invalido">No válido</option>
                                </select>
                                <small>filtro por estado</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->
                
                <!-- end buttons action table -->
                @include('notificacionesintraweb::listado_correos_checkeos.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    <label for="show_qty" class="col-form-label col-md-7">Cantidad a mostrar:</label>
                    <div class="col-md-5">
                    {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], 20, ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEvent(1)']) !!}
                    </div>
                </div>
                <!-- Paginador de tabla -->
                <div class="col-md-12">
                    <paginate
                        v-model="dataPaginator.currentPage"
                        :page-count="dataPaginator.numPages"
                        :click-handler="pageEvent"
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

        <!-- begin #modal-view-listado-correos-checkeos -->
        <div class="modal fade" id="modal-view-listado-correos-checkeos">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') correo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('notificacionesintraweb::listado_correos_checkeos.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-listado-correos-checkeos -->

        <!-- begin #modal-form-listado-correos-checkeos -->
        <div class="modal fade" id="modal-form-listado-correos-checkeos">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-listado-correos-checkeos">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of')l listado de correos</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('notificacionesintraweb::listado_correos_checkeos.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-listado-correos-checkeos -->
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
    $('#modal-form-listado-correos-checkeos').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
