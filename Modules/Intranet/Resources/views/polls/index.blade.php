@extends('layouts.default')

@section('title', trans('polls'))

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
    @include('intranet::layouts.menus.menu_poll')
@endsection

@section('content')

<crud name="polls" :resource="{default: 'polls', get: 'get-polls', show: 'polls'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('polls')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('polls')'}}</h1>
        <!-- end page-header -->

        @if(Auth::user()->hasRole('Administrador intranet de encuestas'))     

        <!-- begin widget -->
        <div class="row">
         
            <widget-counter
                icon="fa fa-edit"
                class-css-color="bg-orange"
                :qty="dataList.length"
                title="Total"
            ></widget-counter>

            <widget-counter
                icon="fa fa-edit"
                class-css-color="bg-blue"
                :qty="dataList.filter((data) =>  data.state == 1).length"
                title="Encuestas En elaboración"
            ></widget-counter>

            <widget-counter
                icon="fa fa-edit"
                class-css-color="bg-green"
                :qty="dataList.filter((data) =>  data.state == 2).length"
                title="Encuestas Publicadas"
            ></widget-counter>
        
            <widget-counter
                icon="fa fa-edit"
                class-css-color="bg-grey"
                :qty="dataList.filter((data) =>  data.state == 3).length"
                title="Encuestas cerradas"
            ></widget-counter>
        </div>
        <!-- end widget -->
        @endif

        <!-- begin main buttons -->
        
        <div class="m-t-20">
            @if(Auth::user()->hasRole('Administrador intranet de encuestas'))     
                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-polls" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('polls')
                </button>
            @endif
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('polls'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                {!! Form::text('id', null, ['v-model' => 'searchFields.id', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => 'Número de encuesta']) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('title', null, ['v-model' => 'searchFields.title', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Title')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::text('description', null, ['v-model' => 'searchFields.description', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Description')]) ]) !!}
                            </div>

                            <br>
                            <br>
                            <br>

                            <div class="col-md-4">
                                {!! Form::text('users_name', null, ['v-model' => 'searchFields.users_name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Funcionario')]) ]) !!}
                            </div>

                            <div class="col-md-4">

                                <select-check
                                    css-class="form-control"
                                    name-field="state"
                                    reduce-label="name"
                                    reduce-key="id"
                                    name-resource="get-constants/state"
                                    :value="searchFields">
                                </select-check>

                                <small>Filtro por estado</small>
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
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('intranet::polls.table')
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

        <!-- begin #modal-view-polls -->
        <div class="modal fade" id="modal-view-polls">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('polls')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="view-poll">
                        <div class="row">
                            @include('intranet::polls.show_fields')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('view-poll');"><i class="fa fa-print mr-2"></i>@lang('print')</button>

                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-polls -->

        <!-- begin #modal-form-polls -->
        <div class="modal fade" id="modal-form-polls">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-polls">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('polls')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('intranet::polls.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-polls -->

         @if (Auth::user()->hasRole('Administrador intranet de encuestas'))    
            <dynamic-modal-form
                modal-id="form-publish-poll"
                size-modal="lg"
                title="Enviar encuesta"
                :data-form="dataForm"
                endpoint="publish-polls"
                :is-update="true"
                confirmation-message-saved="Estás a punto de enviar la Encuesta de la entidad a los funcionarios que ingresó en la información general de la encuesta. <br> <br> ¿Está seguro de enviar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">
                    <div class="row">    
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <h1 for="danger" class="col-form-label col-md-12">Enviar encuesta</h1>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <label for="danger" class="col-form-label col-md-4 required">Observaciones:</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" required type="text" v-model="scope.dataForm.observations" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-modal-form>
         @endif

        {{-- Componente PollAnswer que es llamado desde el tableblade y contiene la logica para responder una encuesta --}}
        <intranet-poll-answer ref="poll-answer" name="poll-answers"></intranet-poll-answer>

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
    $('#modal-form-polls').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    
    // Función para imprimir el contenido de un identificador pasado por parámetro
    function printContent(divName) {
        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no peder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se abre la ventana para imprimir el contenido de la pestaña nueva
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };   
    }
</script>
@endpush
