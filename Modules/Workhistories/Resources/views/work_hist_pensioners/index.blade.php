@extends('layouts.default')

@section('title', trans('Work histories').' '.trans('pensioners'))
@section('section_img', 'assets/img/components/doc_pensionado.png')

@section('menu')
    @include('workhistories::layouts.menu_pensioner')
@endsection

@section('content')

<crud name="work-hist-pensioners" :resource="{default: 'work-hist-pensioners', get: 'get-work-hist-pensioners', show: 'work-hist-pensioners'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('work histories') @lang('pensioners')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        @if(Auth::user()->hasRole('Administrador historias laborales')) 
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('work histories') @lang('pensioners')'}}</h1>
        @else
        <h1 class="page-header text-center m-b-35">Vista principal para consultar hojas de vida de funcionarios pensionados, sustitutos y cuotas partes.</h1>
        @endif 

        <!-- end page-header -->
        @if(Auth::user()->hasRole('Administrador historias laborales'))  
        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-work-hist-pensioners" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('Work historie') @lang('pensioners')
            </button>
        </div>
        <!-- end main buttons -->
        @endif 
        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('work histories') @lang('pensioners'): ${advancedSearchFilterPaginate().length}` | capitalize }}</h5>
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
                  
                                <div class="col-md-4 m-b-10">
                                 {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Name')]) ]) !!}
                                </div>

                                <div class="col-md-4 m-b-10">
                                    {!! Form::text('surname', null, ['v-model' => 'searchFields.surname', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Surname')]) ]) !!}
                                </div>

                                <div class="col-md-4 m-b-10">
                                    {!! Form::text('number_document', null, ['v-model' => 'searchFields.number_document', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Document')]) ]) !!}
                                </div>

                                <div class="col-md-4 m-b-10">
                                    {!! Form::text('email', null, ['v-model' => 'searchFields.email', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Email')]) ]) !!}
                                </div>

                                <div class="col-md-4 row  m-b-10">
                                {!! Form::label('deceased', trans('Deceaseds').':', ['class' => 'col-form-label col-md-3']) !!}

                                    <select v-model="searchFields.deceased" class="form-control col-md-9">
                                        <option value="">No</option>
                                        <option value="Si">Si</option>
                                    </select>
                                </div>

                            
                        </div>
                        <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>

                    </div>
                </div>
                <!-- end card search -->
            </div>
            <!-- end #accordion search -->
            <div class="panel-body">
                <!-- begin buttons action table -->
                <div class="float-xl-right m-b-15">
                    <!-- Acciones para exportar datos de tabla
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a>
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>-->
                </div>
                <!-- end buttons action table -->
                @include('workhistories::work_hist_pensioners.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], '5', ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems']) !!}
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



        <!-- begin #modal-view-work-histories-actives history-->
        <div class="modal fade" id="modal-view-history-work-histories">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('Tracing') @lang('Work historie')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @include('workhistories::work_hist_pensioners.show_history')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-work-histories-actives -->

        <!-- begin #modal-view-work-hist-pensioners -->
        <div class="modal fade" id="modal-view-work-hist-pensioners">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('Work historie') @lang('pensioner')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="view-work-histories-histories">
                        <div class="row">
                            @include('workhistories::work_hist_pensioners.show_fields')
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if(Auth::user()->hasRole('Administrador historias laborales'))
                        <button class="btn btn-warning" type="button" onclick="printContent('view-work-histories-histories');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        @endif 
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-work-hist-pensioners -->

        <!-- begin #modal-form-work-hist-pensioners -->
        <div class="modal fade" id="modal-form-work-hist-pensioners">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-work-hist-pensioners">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('Work historie') @lang('pensioner')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('workhistories::work_hist_pensioners.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-work-hist-pensioners -->


        <!-- En este modal se ve el historial del registro -->
        <div class="modal fade" id="modal-history-request">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Seguimiento y control</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="history-request">
                        <div class="row justify-content-center">
                            <div v-if="dataShow?.history_request?.length>0">
                            <table class="text-center default" border="1">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario que aprobo</th>
                                    <th>Estado</th>
                                    <th>Observación</th>
                                    
                                </tr>
                                <tr v-for="history in dataShow.history_request">
                                    <td style="padding: 15px">@{{ history.created_at }}</td>
                                    <td style="padding: 15px">@{{ history.user_aprobed }}</td>
                                    <td style="padding: 15px">@{{ history.condition }}</td>
                                    <td style="padding: 15px">@{{ history.observation }}</td>
                                </tr>
                            </table>
                            </div>
                            <div v-else>
                                <h3>No hay registros</h3>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('history-request');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-titleRegistrations -->
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
    $('#modal-form-work-hist-pensioners').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );

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
            // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
    }
</script>
@endpush
