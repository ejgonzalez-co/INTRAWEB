@extends('layouts.default')

@section('title', trans('typeDocumentaries'))

@section('section_img', '/assets/img/components/inventario_documents.png')

@section('menu')
    @include('documentaryclassification::layouts.menu')
@endsection

@section('content')

<crud
    name="typeDocumentaries"
    :resource="{default: 'type-documentaries', get: 'get-documentos-inventario-documental?type={{ $type ?? null }}'}"
    :init-values="{ type: '{!! $type ?? null !!}' }"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Inventario documental digital')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        @if ($type == 'Correspondencia externa enviada' || $type == '')
            <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('Inventario documental digital')'}}: Correspondencia externa envidada</h1>

        @elseif($type == 'Correspondencia externa recibida')
            <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('Inventario documental digital')'}}: Correspondencia externa recibida</h1>

        @elseif($type == 'Correspondencia interna')
            <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('Inventario documental digital')'}}: Correspondencia interna</h1>
        
        @elseif($type == 'PQRSD')
            <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('Inventario documental digital')'}}: PQRSD</h1>

        @elseif($type == 'Documentos electrónicos')
            <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('Inventario documental digital')'}}: Documentos electrónicos</h1>

        @elseif($type == 'Todos')
            <h1 class="page-header text-center m-b-35">@{{ '@lang('text_title') @lang('Inventario documental digital')'}}: Todos</h1>

        @endif
        <!-- end page-header -->
        <div class="row">
            <div class="col-md-4">

                {{-- {!! Form::text('documento', null, ['v-model' => 'searchFields.documento', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['documento' => trans('ejemplo')]) ]) !!} --}}
                <select id="type" v-model="dataForm.type" value="2023" class="custom-select" name="type" required>
                    <option value="" selected>Seleccione</option>
                    <option value="Correspondencia externa recibida">Correspondencia externa recibida</option>
                    <option value="Correspondencia interna">Correspondencia interna</option>
                    <option value="Correspondencia externa enviada">Correspondencia externa enviada</option>
                    <option value="PQRSD">PQRSD</option>
                    @if(config('app.mod_documentos'))<option value="Documentos electrónicos">Documentos electrónicos</option>@endif
                    <option value="Todos">Todos</option>



                </select>
                <small>Seleccione el tipo de documento que desea consultar</small>

            </div>
            <div class="col-md-4">
                <a :href="'documentos-serie-subseries?type='+dataForm.type" style="button">
                <button class="btn btn-primary text-white">Consultar información</button>
                </a>
            </div>
        </div>
        <br>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Inventario documental digital'): ${dataPaginator.total}` | capitalize }}</h5>
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


                            @if ($type == 'Correspondencia externa enviada' || $type == 'Documentos electrónicos' || $type == 'Todos' || $type == '')
                                <div class="col-md-4">
                                    {!! Form::text('consecutivo', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('consecutivo')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    <select-check css-class="form-control" name-field="tipo_documento" reduce-label="name"
                                        reduce-key="name" name-resource="/correspondence/get-types-external" :value="searchFields" :key="keyRefresh"
                                        :is-required="true">
                                    </select-check>
                
                                <small>Filtro por tipo de documento</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('folios', null, ['v-model' => 'searchFields.folios', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('folios')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select('origen', 
                                    ["FISICO" => "FISICO", 
                                    "DIGITAL" => "DIGITAL"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.origen }", 'v-model' => 'searchFields.origen', 'required' => true]) !!}
                                <small>Filtro por origen</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('nombre', null, ['v-model' => 'searchFields.nombre', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre dependencia')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_serie', null, ['v-model' => 'searchFields.name_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre serie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_serie', null, ['v-model' => 'searchFields.no_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código serie')]) ]) !!}
                                    <small style="color: #FFFFFF;">serie</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_subserie', null, ['v-model' => 'searchFields.name_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre subserie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_subserie', null, ['v-model' => 'searchFields.no_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código subserie')]) ]) !!}
                                </div>

                                {{-- este filtro se deja documentado, ya que en un futuro se puede utilizar --}}
                                {{-- @if($type == 'Todos')
                                <div class="col-md-4">
                                    {!! Form::select('tipo', 
                                    ["Externa recibida" => "Externa recibida", 
                                    "Interna" => "Interna",
                                    "Externa enviada" => "Externa enviada",
                                    "PQRS" => "PQRS",
                                    "Documento electrónico" => "Documento electrónico"
                                    ], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo }", 'v-model' => 'searchFields.tipo', 'required' => true]) !!}
                                    <small>Filtro por tipo</small>
                                </div>
                                @endif --}}

                            @elseif($type == 'Correspondencia externa recibida')
                                <div class="col-md-4">
                                    {!! Form::text('consecutivo', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('consecutivo')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                <select-check
                                    css-class="form-control"
                                    name-field="tipo_documento"
                                    reduce-label="name"
                                    reduce-key="name"
                                    name-resource="/correspondence/get-types-documentaries"
                                    :value="searchFields"
                                    :key="keyRefresh"
                                    :is-required="true">
                                </select-check>
                
                                <small>Filtro por tipo de documento</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('folios', null, ['v-model' => 'searchFields.folios', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('folios')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select('origen', 
                                    ["FISICO" => "FISICO", 
                                    "DIGITAL" => "DIGITAL"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.origen }", 'v-model' => 'searchFields.origen', 'required' => true]) !!}
                                <small>Filtro por origen</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('nombre', null, ['v-model' => 'searchFields.nombre', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre dependencia')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_serie', null, ['v-model' => 'searchFields.name_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre serie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_serie', null, ['v-model' => 'searchFields.no_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código serie')]) ]) !!}
                                    <small style="color: #FFFFFF;">serie</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_subserie', null, ['v-model' => 'searchFields.name_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre subserie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_subserie', null, ['v-model' => 'searchFields.no_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código subserie')]) ]) !!}
                                </div>
                            @elseif($type == 'Correspondencia interna')
                                <div class="col-md-4">
                                    {!! Form::text('consecutivo', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('consecutivo')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    <select-check css-class="form-control" name-field="tipo_documento" reduce-label="name"
                                    reduce-key="name" name-resource="/correspondence/get-types-internal" :value="searchFields"
                                    :is-required="true">
                                </select-check>
                
                                <small>Filtro por tipo de documento</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('folios', null, ['v-model' => 'searchFields.folios', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('folios')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select('origen', 
                                    ["FISICO" => "FISICO", 
                                    "DIGITAL" => "DIGITAL"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.origen }", 'v-model' => 'searchFields.origen', 'required' => true]) !!}
                                <small>Filtro por origen</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('nombre', null, ['v-model' => 'searchFields.nombre', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre dependencia')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_serie', null, ['v-model' => 'searchFields.name_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre serie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_serie', null, ['v-model' => 'searchFields.no_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código serie')]) ]) !!}
                                    <small style="color: #FFFFFF;">serie</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_subserie', null, ['v-model' => 'searchFields.name_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre subserie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_subserie', null, ['v-model' => 'searchFields.no_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código subserie')]) ]) !!}
                                </div>
                            @elseif($type == 'PQRSD')
                                <div class="col-md-4">
                                    {!! Form::text('consecutivo', null, ['v-model' => 'searchFields.consecutivo', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('consecutivo')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select('canal', 
                                    ["Buzon de sugerencia" => "Buzon de sugerencia", 
                                    "Correo certificado" => "Correo certificado", 
                                    "Correo electrónico" => "Correo electrónico", 
                                    "FAX" => "FAX", 
                                    "Personal" => "Personal", 
                                    "Telefónico" => "Telefónico", 
                                    "Web" => "Web"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.canal }", 'v-model' => 'searchFields.canal', 'required' => true]) !!}
                
                                <small>Filtro por canal de recepción</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('folios', null, ['v-model' => 'searchFields.folios', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('folios')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::select('origen', 
                                    ["FISICO" => "FISICO", 
                                    "DIGITAL" => "DIGITAL"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.origen }", 'v-model' => 'searchFields.origen', 'required' => true]) !!}
                                <small>Filtro por origen</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('nombre', null, ['v-model' => 'searchFields.nombre', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre dependencia')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_serie', null, ['v-model' => 'searchFields.name_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre serie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_serie', null, ['v-model' => 'searchFields.no_serie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código serie')]) ]) !!}
                                    <small style="color: #FFFFFF;">serie</small>
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('name_subserie', null, ['v-model' => 'searchFields.name_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('nombre subserie')]) ]) !!}
                                </div>

                                <div class="col-md-4">
                                    {!! Form::text('no_subserie', null, ['v-model' => 'searchFields.no_subserie', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Código subserie')]) ]) !!}
                                </div>
                            @endif


                            
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
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTableSecondView('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> FUID</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @if ($type == 'Correspondencia externa enviada' || $type == '')
                    @include('documentaryclassification::documentos_serie_subseries.table_externa')
                @elseif($type == 'Correspondencia externa recibida')
                    @include('documentaryclassification::documentos_serie_subseries.table_recibida')
                @elseif($type == 'Correspondencia interna')
                    @include('documentaryclassification::documentos_serie_subseries.table_interna')
                @elseif($type == 'PQRSD')
                    @include('documentaryclassification::documentos_serie_subseries.table_pqr')
                @elseif($type == 'Documentos electrónicos')
                    @include('documentaryclassification::documentos_serie_subseries.table_documentos_electronicos')
                @elseif($type == 'Todos')
                    @include('documentaryclassification::documentos_serie_subseries.table_documentos_electronicos')
                @endif
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

        <!-- end #modal-view-typeDocumentaries -->

        <!-- end #modal-form-typeDocumentaries -->
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
    $('#modal-form-typeDocumentaries').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
