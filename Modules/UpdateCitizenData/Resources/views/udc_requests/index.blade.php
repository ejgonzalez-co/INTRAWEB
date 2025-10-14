@extends('layouts.default')

@section('title', trans('Encuesta Actualización de Datos Personales'))

@section('section_img', '../assets/img/components/solicitudes.png')

@section('menu')
    @include('update_citizen_data::layouts.menu')
@endsection

@section('content')

<crud name="udc-requests" :resource="{default: 'udc-requests', get: 'get-udc-requests'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('polls')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('polls')'}}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-udc-requests" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('poll')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('poll'): ${dataPaginator.total}` | capitalize }}</h5>
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
                            {!! Form::text('citizen_name', null, ['v-model' => 'searchFields.citizen_name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Citizen')]) ]) !!}
                            </div>

                            <div class="col-md-4 m-b-10">
                                {!! Form::text('identification', null, ['v-model' => 'searchFields.identification', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Document')]) ]) !!}
                            </div>

                            <div class="col-md-4 m-b-10">
                                {!! Form::text('payment_account_number', null, ['v-model' => 'searchFields.payment_account_number', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Payment Account Number')]) ]) !!}
                            </div>

                            <div class="col-md-4 row  m-b-10">
                                {!! Form::label('subscriber_quality', trans('Subscriber Quality').':', ['class' => 'col-form-label col-md-3']) !!}

                                <select v-model="searchFields.subscriber_quality" name="subscriber_quality" class="form-control col-md-9">  
                                    <option value="">Todos</option>
                                    <option value="Propietario">Propietario</option>
                                    <option value="Arrendatario">Arrendatario</option>
                                </select>
                            </div>

                            <div class="col-md-4 row  m-b-10">
                                {!! Form::label('gender', trans('Gender').':', ['class' => 'col-form-label col-md-3']) !!}

                                <select v-model="searchFields.gender" name="gender" class="form-control col-md-9">  
                                    <option value="">Seleccione</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="No determinado">No determinado</option>
                                </select>
                            </div>

                            
                            <div class="col-md-4 row  m-b-10">
                                {!! Form::label('created_at', trans('Created_at').':', ['class' => 'col-form-label col-md-3']) !!}
                                    <input  v-model="searchFields.created_at" id="created_at" placeholder="Select Date" name="created_at" type="date" class="form-control col-md-9">
                            </div>

                        </div>

                        <!-- Campos de busqueda -->
                        <div class="row form-group">
                           
                            <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
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
                @include('update_citizen_data::udc_requests.table')
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

        <!-- begin #modal-view-udc-requests -->
        <div class="modal fade" id="modal-view-udc-requests">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('poll')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="content-show-fields">
                        <div class="row">
                            @include('update_citizen_data::udc_requests.show_fields')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" type="button" onclick="printContent('content-show-fields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>

                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-udc-requests -->

        <!-- begin #modal-form-udc-requests -->
        <div class="modal fade" id="modal-form-udc-requests">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-udc-requests">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('poll')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('update_citizen_data::udc_requests.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('Send') datos</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-udc-requests -->
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
    $('#modal-form-udc-requests').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );

    //valida que solo ingresen numero en un campo especifico. 
    //Se tuvo que crear esta funcion porque las funciones on o keypress no funcionaban alguna extraña razon. 
    function noLetters() {
        let value_payment_account_number = $("#payment_account_number").val();
        //expresión regular 
        const regex = /^[0-9]*$/;
        const onlyNumbers = regex.test(value_payment_account_number); // true
        if(!onlyNumbers){
            $("#payment_account_number").val("")
        }
    }

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
            }, 10);
    }


</script>
@endpush


