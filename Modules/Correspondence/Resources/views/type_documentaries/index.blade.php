@extends('layouts.default')

@section('title', trans('Types Documentaries'))

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
    @include('correspondence::layouts.menu')
@endsection

@section('content')

<crud
    name="types-documentaries"
    :resource="{default: 'types-documentaries', get: 'get-types-documentaries'}"
    :crud-avanzado="true"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Types Documentaries')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Types Documentaries')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-add m-b-10" data-backdrop="static" data-target="#modal-form-types-documentaries" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('Type Documentary')
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
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Types Documentaries'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <input type="text" v-model="searchFields.name" class="form-control" placeholder="Filtrar por nombre" @keyup.enter="pageEventActualizado(1)" @keyup="!searchFields.name ? $delete(searchFields, name) : null">
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
                {{-- <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div> --}}
                <!-- end buttons action table -->
                @include('correspondence::type_documentaries.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 75 => 75], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
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

        <!-- begin #modal-view-types-documentaries -->
        <div class="modal fade" id="modal-view-types-documentaries">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('Type Documentary')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('correspondence::type_documentaries.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-types-documentaries -->

        <!-- begin #modal-form-types-documentaries -->
        <div class="modal fade" id="modal-form-types-documentaries">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-types-documentaries">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('Type Documentary')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('correspondence::type_documentaries.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-add"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-types-documentaries -->

        <dynamic-modal-form modal-id="props-rotule-edit" size-modal="lg" :title="'Ajustar rotulo ' "
        :data-form="dataExtra" endpoint="create-rotule-props/Recibida"
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
                                {!! Form::label('variable', trans('Variable') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                                <div class="col-md-9">
                                    <select name="nombre_label" v-model="scope.dataForm.nombre_label" class="form-control" ref="rotule" required>
                                        <option value="Fecha">Fecha</option> 
                                        <option value="Funcionario Radicador">Funcionario Radicador</option>                              
                                        <option value="Ciudadano">Ciudadano</option> 
                                        <option value="Asunto">Asunto</option> 
                                        <option value="Anexo">Anexo</option> 
                                        <option value="Destinatario">Destinatario</option> 
                                        <option value="PQRS Asociado">PQRS Asociado</option> 
                                        <option value="Novedad">Novedad</option>
                                        <option value="Codigo Validacion">Codigo Validacion</option>
                                        <option value="Folios">Folios</option> 
                                        <option value="Canal">Canal</option>  
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
    $('#modal-form-types-documentaries').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
