@extends('layouts.default')

@section('title', trans('polls'))

@section('section_img', '../assets/img/components/solicitudes.png')

@section('menu')
    @include('citizen_poll::layouts.menu')
@endsection

@section('content')


<crud name="polls" :resource="{default: 'polls', get: 'get-polls'}" inline-template>
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
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-polls" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('polls')
            </button>
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
                                {!! Form::text('number_account', null, ['v-model' => 'searchFields.number_account', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Number Account')]) ]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('suscriber_quality',['Propietario'=>'Propietario','Arrendatario'=>'Arrendatario'],null,['class'=>'form-control','v-model' => 'searchFields.suscriber_quality']) !!}
                                <small>Filtro por calidad de suscriptor</small>
                            </div>
                            <div class="col-md-4">
                                {!! Form::select('gender',['Masculino'=>'Masculino','Femenino'=>'Femenino','Otro'=>'Otro'],null,['class'=>'form-control','v-model' => 'searchFields.gender']) !!}
                                <small>Filtro por género</small>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4">
                                {!! Form::date('created_at', null, ['v-model' => 'searchFields.created_at', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['created_at' => trans('created_at')]) ]) !!}
                                <small>Filtro por fecha de creación</small>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 ">          
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
                    <!-- Accion de exportacion de datos de la tabla en hoja de calculo-->
                    <a href="javascript:;" @click="exportDataTable('xlsx')" class="btn btn-blue"><i class="fa fa-download mr-2"></i>Generar Reporte Excel</a>
                </div>
                <!-- end buttons action table -->
                @include('citizen_poll::polls.table')
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

        <!-- begin #modal-view-polls -->
        <div class="modal fade" id="modal-view-polls">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información de la encuesta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body" id="showFields">
                        <div class="row">
                            @include('citizen_poll::polls.show_fields')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
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
                            <h4 class="modal-title text-white">Formulario de la encuesta</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('citizen_poll::polls.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-polls -->
        
    </div>
</crud>

@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
<style>
    .vue-feedback-reaction div:last-child{
        display: none;
    }
</style>

@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-polls').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );

    function printContent(divName) {
        
        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no perder estilos
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
        }, 30); 
   
    }

    //valida que solo ingresen numero en un campo especifico. 
    //Se tuvo que crear esta funcion porque las funciones on o keypress no funcionaban alguna extraña razon. 
    function noLetters() {
        let number_account = $("#number_account").val();
        //expresión regular 
        const regex = /^[0-9]*$/;
        const onlyNumbers = regex.test(number_account); // true
        if(!onlyNumbers){
            $("#number_account").val("")
        }
    }
   
</script>
@endpush

