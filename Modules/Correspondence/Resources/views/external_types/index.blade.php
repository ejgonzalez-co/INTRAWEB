@extends('layouts.default')

@section('title', trans('external-types'))

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
    @include('correspondence::layouts.menu')
@endsection

@section('content')

<crud
    name="external-types"
    :resource="{default: 'external-types', get: 'get-external-types'}"
    :init-values="{variables_documento : [],modulo:'Enviada'}"
    :crud-avanzado="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('external-types')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('external-types')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-external-types" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') tipo de correspondencia externa
            </button>
            <button @click="openForm = true;add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#props-rotule-edit" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Ajustar propiedades del rotulo
            </button>
        </div>

      
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('external-types'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <input v-model="searchFields.name" class="form-control" placeholder="Filtrar por nombre" name="name" type="text">
                            </div>
                            <div class="col-md-4">
                                <input v-model="searchFields.prefix" class="form-control" placeholder="Filtrar por prefijo" name="prefix" type="text">
                            </div>
                            <div class="col-md-4"><br>
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i
                                        class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()"
                                    class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                @include('correspondence::external_types.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    <label for="show_qty" class="col-form-label col-md-7">Cantidad a mostrar:</label>
                    <div class="col-md-5">
                        <select class="form-control" v-model="dataPaginator.pagesItems" name="Cantidad a mostrar"><option value="5" selected="selected">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="50">50</option><option value="75">75</option></select>
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

        <!-- begin #modal-view-external-types -->
        <div class="modal fade" id="modal-view-external-types">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('external-types')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('correspondence::external_types.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-external-types -->

        <!-- begin #modal-form-external-types -->
        <div class="modal fade" id="modal-form-external-types">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-external-types">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('external-types')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('correspondence::external_types.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-external-types -->
        <dynamic-modal-form modal-id="props-rotule-edit" size-modal="lg" :title="'Ajustar rotulo ' "
            :data-form="dataExtra" endpoint="create-rotule-props/Enviada"
            @saved="
            if($event.isUpdate) {
                assignElementList(dataExtra.id, $event.data);
            } ">
                <template #fields="scope">
            
                    <div>
                        <h6 class="col-form-label">Variables del rotulo</h6>
                        <dynamic-list label-button-add="Agregar campo" :data-list.sync="dataExtra.rotule_props" :is-remove="true"
                            :data-list-options="[
                                { label: 'Campo', name: 'nombre_label', isShow: true, refList: 'rotule' },
                            ]"
                            class-container="col-md-12" class-table="table table-bordered" >
                            <template #fields="scope">
                                
                                <!-- Variables Field -->
                                <div class="form-group row m-b-15">
                                    <input v-model="scope.dataForm.modulo" name="modulo" value="Enviada" type="hidden" ref="module">
                                    {!! Form::label('variable', trans('Variable') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                                    <div class="col-md-9">
                                        <select name="nombre_label" v-model="scope.dataForm.nombre_label" class="form-control" ref="rotule" required>
                                            <option value="Fecha">Fecha</option> 
                                            <option value="Funcionario Radicador">Funcionario Radicador</option>  
                                            <option value="Funcionario remitente">Funcionario remitente</option>                             
                                            <option value="Ciudadano">Ciudadano</option> 
                                            <option value="Asunto">Asunto</option> 
                                            <option value="Anexo">Anexo</option> 
                                            <option value="Canal">Canal</option> 
                                            <option value="Folios">Folios</option> 
                                            <option value="Codigo Validacion">Codigo Validacion</option> 
                                        </select>
                                    
                                        <div class="invalid-feedback" v-if="dataErrors.variable">
                                            <p class="m-b-0" v-for="error in dataErrors.variable">@{{ error }}</p>
                                        </div>
                                    </div>
                                </div>
                            
                            </template>
                        </dynamic-list>
                    </div>
                </template>
            </dynamic-modal-form>

        {{-- <div class="modal fade" id="modal-form-external-rotule">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('createProps') }}" id="form-internal-types">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('internal-types')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            <div class="form-group row m-b-15">
                                <h6 class="col-form-label">Variables de la plantilla</h6>
                                <dynamic-list label-button-add="Agregar campo" :data-list.sync="dataForm.variables_documento" :is-remove="true"
                                    :data-list-options="[
                                        { label: 'Variable - Descripción', name: 'variable', isShow: true, refList: 'variables' }
                                    ]"
                                    class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                                    <template #fields="scope">
                                        <!-- Variables Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('variable', trans('Variable') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                                            <div class="col-md-9">
                                                <select name="variable" v-model="scope.dataForm.variable" class="form-control" ref="variables" required>
                                                   
                                                    <option value="#fecha">Fecha</option> 
                                                    <option value="#funcionario_radicador">Funcionario Radicador</option>                              
                                                    <option value="#ciudadano">Ciudadano</option> 
                                                    <option value="#asunto">Asunto</option> 
                                                    <option value="#anexo">Anexo</option> 
                                                    <option value="#destinatario">Destinatario</option> 
                                                    <option value="#pqrs_asociado">PQRS Asociado</option> 
                                                    <option value="#novedad">Novedad</option>
                                                    <option value="#codigo_validacion">Codigo Validacion</option> 
                      
                                                </select>
                                                <div class="invalid-feedback" v-if="dataErrors.variable">
                                                    <p class="m-b-0" v-for="error in dataErrors.variable">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </dynamic-list>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
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
    $('#modal-form-external-types').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
