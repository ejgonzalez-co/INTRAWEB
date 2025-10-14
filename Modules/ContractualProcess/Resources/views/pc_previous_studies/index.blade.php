@extends('layouts.default')

@section('title', trans('Studies')." ".trans('Previous'))

@section('section_img', '/assets/img/components/estudio_precontractual.png')

@section('menu')
    @include('contractual_process::layouts.menus.menu_studie_previous')
@endsection

@section('content')

<crud name="pc-previous-studies" 
:init-values="{ obligation_principal_documentation: false,
                situation_estates_public: false,
                situation_estates_private: false,
                solution_servitude: false,
                solution_owner: false,
                process_licenses_environment: false,
                process_licenses_beach: false,
                process_licenses_forestal: false,
                process_licenses_guadua: false,
                process_licenses_tree: false,
                process_licenses_road: false,
                process_licenses_demolition: false,
                process_licenses_tree_urban: false,
                filter: '{!! $filter ?? null !!}',
                pc_previous_studies_tipifications: []
                }"
                :resource="{default: 'pc-previous-studies', get: 'get-pc-previous-studies?f={!! $filter ?? null !!}'}" inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('Studies') @lang('Previous') </li>
        </ol>
        <!-- end breadcrumb -->

        <!-- Condiciones por Roles -->
        <!-- begin page-header -->
        @if(Auth::user()->hasRole('PC Asistente de gerencia'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Asistente de gerencia</h1>
        @endif

        @if(Auth::user()->hasRole('PC Jefe de jurídica'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Jurídica</h1>
        @endif

        @if(Auth::user()->hasRole('PC Revisor de jurídico'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Revisor Jurídico</h1>
        @endif

        @if(Auth::user()->hasRole('PC Gestor planeación'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Planeación</h1>
        @endif

        @if(Auth::user()->hasRole('PC Gestor presupuesto'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Presupuesto</h1>
        @endif

        @if(Auth::user()->hasRole('PC Gestor de recursos'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Gestión recursos</h1>
        @endif

        @if(Auth::user()->hasRole('PC Gerente'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Gerente</h1>
        @endif
        
        @if(Auth::user()->hasRole('PC Líder de proceso'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Líder de proceso</h1>
        @endif

        @if(Auth::user()->hasRole('PC tesorero'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Tesorero</h1>
        @endif

        @if(Auth::user()->hasRole('PC jurídica especializado 3'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Jurídica especializado 3</h1>
        @endif

        
        @if(Auth::user()->hasRole('PC director financiero'))
            <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Director financiero</h1>
        @endif

        @if(Auth::user()->hasRole('PC director jurídico'))
        <h1 class="page-header text-center m-b-35">@{{ '@lang('main_view_to_manage') @lang('Studies') @lang('Previous') '}} - Director jurídico</h1>
        @endif
        <!-- end page-header -->

        @if(Auth::user()->hasRole('PC Líder de proceso'))
        <!-- begin main buttons -->
        <div class="m-t-20">
            <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-pc-previous-studies" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('Previous study')
            </button>
        </div>
        <!-- end main buttons -->
        @endif

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Studies') @lang('Previous') : ${dataList.length}` | capitalize }}</h5>
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
                                {!! Form::text('organizational_unit', null, ['v-model' => 'searchFields.organizational_unit', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Organizational Unit')]) ]) !!}
                            </div>
                       
                            <div class="col-md-4">
                                {!! Form::text('project', null, ['v-model' => 'searchFields.project', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('Project')]) ]) !!}
                            </div>


                            <div class="col-md-4 row  m-b-10">
                                {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}

                                <select v-model="searchFields.state" class="form-control col-md-9">
                                    <option value="">Todos</option>
                                    <option value="1">En elaboración</option>
                                </select>
                            </div>

                            <div class="col-md-4 row  m-b-10">
                                {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-3']) !!}

                                <select v-model="searchFields.type" class="form-control col-md-9">
                                    <option value="">Todos</option>
                                    <option value="Funcionamiento">Funcionamiento</option>
                                    <option value="Proyecto de inversión">Proyecto de inversión</option>
                                    <option value="Funcionamiento e inversión">Funcionamiento e inversión</option>


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
                    <!-- Acciones para exportar datos de tabla-->
                    <div class="btn-group">
                        <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary"><i class="fa fa-download mr-2"></i> @lang('export_data_table')</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right bg-blue">
                            {{-- <a href="javascript:;" @click="exportDataTable('pdf')" class="dropdown-item text-white no-hover"><i class="fa fa-file-pdf mr-2"></i> PDF</a> --}}
                            <a href="javascript:;" @click="exportDataTable('xlsx')" class="dropdown-item text-white no-hover"><i class="fa fa-file-excel mr-2"></i> EXCEL</a>
                        </div>
                    </div>
                </div>
                <!-- end buttons action table -->
                @include('contractual_process::pc_previous_studies.table')
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

        <!-- begin #modal-view-pc-previous-studies -->
        <div class="modal fade" id="modal-view-pc-previous-studies">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Información en detalle del @lang('Previous study') </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @include('contractual_process::pc_previous_studies.show_fields')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-pc-previous-studies -->

        <!-- begin #modal-form-pc-previous-studies -->
        <div class="modal fade" id="modal-form-pc-previous-studies">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-pc-previous-studies">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of')l @lang('Previous study') </h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('contractual_process::pc_previous_studies.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-pc-previous-studies -->

        <!-- begin #modal-form-pc-previous-studies send asistente de gerencia-->
        @if(Auth::user()->hasRole('PC Asistente de gerencia'))
        <dynamic-modal-form
                modal-id="send-studies-previous"
                size-modal="lg"
                title="Enviar estudios previos"
                :data-form="dataForm"
                endpoint="pc-previous-studies-send"
                :is-update="true"
                confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso. <br> <br> ¿Estás seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                {{-- ojo aqui --}}
                <template #fields="scope">
                    {{-- Validar si el estado es En revisión por parte de Asistente de gerencia--}}
                    <div class="row" v-if="dataForm.state == 2 && dataForm.type !== 'Funcionamiento'">    
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <h1 for="danger" class="col-form-label col-md-12">Enviar a Verificación de la ficha por parte de Planeación corporativa o devolver al líder del proceso para mejoras</h1>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                <div class="col-md-8">
                                    <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                        <option value="">Seleccione</option>
                                        <option value="Verificación de la ficha en Planeación corporativa">Enviar a Verificación de la ficha por parte de Planeación corporativa</option>
                                        <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                    </select>
                                </div>
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

                    <div class="row" v-if="dataForm.state == 2 && dataForm.type == 'Funcionamiento'">    
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <h1 for="danger" class="col-form-label col-md-12">Enviar a gestionar CDP por parte de presupuesto o devolver al líder del proceso para mejoras</h1>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                <div class="col-md-8">
                                    <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                        <option value="">Seleccione</option>
                                        <option value="Gestionando CDP por parte de presupuesto">Enviar a gestionar CDP por parte de presupuesto</option>
                                        <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                    </select>
                                </div>
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

                    <div class="row" v-if="dataForm.state == 8">    
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <h1 for="danger" class="col-form-label col-md-12">Cambiar estado del estudio previo a Invitación generada</h1>
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

                    <div class="row" v-if="dataForm.state == 9">    
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <h1 for="danger" class="col-form-label col-md-12">Enviar a Revisión de jurídica con expediente para evaluar propuestas o devolver al líder de proceso para mejoras</h1>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                <div class="col-md-8">
                                    <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                        <option value="">Seleccione</option>
                                        <option value="Evaluando propuestas">Enviar a Revisión de jurídica con expediente para evaluar propuestas</option>
                                        <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                    </select>
                                </div>
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

                    
                    <div class="row" v-if="dataForm.state == 20">    
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <h1 for="danger" class="col-form-label col-md-12">Enviar a Revisión de jurídica para asignar abogado</h1>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                <div class="col-md-8">
                                    <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                        <option value="">Seleccione</option>
                                        <option value="Asignación de abogado">Enviar a Revisión de jurídica para asignación de abogado</option>
                                        <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                    </select>
                                </div>
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
        <!-- end #modal-form-pc-previous-studies send -->

        <!-- begin #modal-form-pc-previous-studies send jurídica -->
        @elseif (Auth::user()->hasRole('PC Jefe de jurídica'))    
        <dynamic-modal-form
                modal-id="send-studies-previous-juridic"
                size-modal="lg"
                title="Enviar estudios previos"
                :data-form="dataForm"
                endpoint="pc-previous-studies-send"
                :is-update="true"
                confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso.<br> <br> ¿Estás seguro de aceptar?"
                @saved="
                    if($event.isUpdate) {
                        assignElementList(dataForm.id, $event.data);
                    } else {
                        addElementToList($event.data);
                    }">
                
                <template #fields="scope">

                        <div class="row" v-if="dataForm.state == 6">    

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <h1 for="danger" class="col-form-label col-md-12">Enviar a revisión del abogado de jurídica o devolver al líder del proceso para mejoras</h1>
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                            <option value="">Seleccione</option>
                                            <option value="Gestionando Reglas por parte de Jurídica">Asignar un abogado para Gestionar Reglas por parte de Jurídica</option>
                                            <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="scope.dataForm.type_send=='Gestionando Reglas por parte de Jurídica'">
                                <div class="form-group row m-b-15">
                                    <label for="" class="col-form-label col-md-4">Abogado: </label>
                                    <div class="col-md-8">

                                        <autocomplete
                                            asignar-al-data = "1"
                                            name-prop="name"
                                            name-field="lawyer"
                                            :value='dataForm'
                                            name-resource='get-user-by-rol/PC Revisor de jurídico' 
                                            css-class="form-control"
                                            :name-labels-display="['name', 'email']"
                                            reduce-key="id"
                                            :key="keyRefresh"
                                        >
                                        </autocomplete>

                                    </div>
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

                        <div class="row" v-if="dataForm.state == 11">    

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <h1 for="danger" class="col-form-label col-md-12">Asignar abogado</h1>
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                            <option value="">Seleccione</option>
                                            <option value="Evaluando las propuestas de todos">Asignar abogado para evaluar propuestas</option>
                                            <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="scope.dataForm.type_send=='Evaluando las propuestas de todos'">
                                <div class="form-group row m-b-15">
                                    <label for="" class="col-form-label col-md-4">Abogado: </label>
                                    <div class="col-md-8">

                                        <autocomplete
                                            asignar-al-data = ""
                                            name-prop="name"
                                            name-field="lawyer"
                                            :value='dataForm'
                                            name-resource='get-user-by-rol/PC Revisor de jurídico' 
                                            css-class="form-control"
                                            :name-labels-display="['name', 'email']"
                                            reduce-key="id"
                                            :key="keyRefresh"
                                        >
                                        </autocomplete>

                                    </div>
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

                        {{-- Revisando minuta del contrato --}}
                        <div class="row" v-if="dataForm.state == 13">    

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <h1 for="danger" class="col-form-label col-md-12">Enviar a Revisión y firma del contrato por parte del gerente o devolver al abogado de jurídica para mejoras</h1>
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                            <option value="">Seleccione</option>
                                            <option value="Revisión y firma del contrato">Revisión y firma del contrato por parte del gerente</option>
                                            <option value="Mejorando observaciones hechas a la minuta">Devolver al abogado de jurídica para mejoras a la minuta</option>
                                        </select>
                                    </div>
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

                        {{-- Revisando minuta del contrato --}}
                        <div class="row" v-if="dataForm.state == 25">    

                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <h1 for="danger" class="col-form-label col-md-12">Enviar a presupuesto para la elaboración del registro presupuestal</h1>
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <div class="form-group row m-b-15">
                                    <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                            <option value="">Seleccione</option>
                                            <option value="Generando CRP">Enviar a presupuesto</option>
                                        </select>
                                    </div>
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
        <!-- end #modal-form-pc-previous-studies send -->

        <!-- begin #modal-form-pc-previous-studies send revisor jurídico -->
        @elseif (Auth::user()->hasRole('PC Revisor de jurídico'))
                
            <dynamic-modal-form
                    modal-id="send-studies-previous-juridic"
                    size-modal="lg"
                    title="Enviar estudios previos"
                    :data-form="dataForm"
                    endpoint="pc-previous-studies-send"
                    :is-update="true"
                    confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso.<br> <br> ¿Estás seguro de aceptar?"
                    @saved="
                        if($event.isUpdate) {
                            assignElementList(dataForm.id, $event.data);
                        } else {
                            addElementToList($event.data);
                        }">
                    
                    <template #fields="scope">
    
                            <div class="row" v-if="dataForm.state == 7">    
    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar a gestionar invitación por parte de Asistente de Gerencia o devolver al líder de proceso para mejoras</h1>
                                    </div>
                                </div>
    
                            
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Gestionando invitación por parte de Asistente de Gerencia">Enviar a Gestionar invitación por parte de Asistente de Gerencia</option>
                                                <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                            </select>
                                        </div>
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

                            <div class="row" v-if="dataForm.state == 12 || dataForm.state == 18">    
    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar a revisión de minuta del contrato</h1>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Revisando minuta del contrato">Enviar a revisión de minuta del contrato</option>
                                                {{-- <option value="Declarar estudio previo desierto">Declarar estudio previo desierto</option> --}}
                                            </select>
                                        </div>
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

                            <div class="row" v-if="dataForm.state == 21 || dataForm.state == 24">    
    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar a visto bueno o devolver al líder de proceso para mejoras</h1>
                                    </div>
                                </div>
    
                            
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">Estado de la propuesta:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Pendiente de visto bueno">Adjudicada</option>
                                                <option value="Finalizado">Desierta</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>  

                                <div class="col-md-12" v-if="dataForm.type_send=='Pendiente de visto bueno'">
                                    <div class="form-group row m-b-15">
                                        <label for="type_welcome" class="col-form-label col-md-4 required">Tipo de invitación:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_welcome" name="type_welcome" id="type_welcome" required>
                                                <option value="">Seleccione</option>
                                                <option value="Invitación simplificada o contratación directa">Invitación simplificada o contratación directa</option>
                                                <option value="Invitación abreviada, detallada o pública">Invitación abreviada, detallada o pública</option>
                                            </select>
                                        </div>
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
            <!-- end #modal-form-pc-previous-studies send -->

            <!-- begin #modal-form-pc-previous-studies send planeación-->
            @elseif (Auth::user()->hasRole('PC Gestor planeación'))
        
            <dynamic-modal-form
                    modal-id="send-studies-previous-juridic"
                    size-modal="lg"
                    title="Enviar estudios previos"
                    :data-form="dataForm"
                    endpoint="pc-previous-studies-send"
                    :is-update="true"
                    confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso.<br> <br> ¿Estás seguro de aceptar?"
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
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar estudios previos</h1>
                                    </div>
                                </div>
    
                            
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Gestionando CDP por parte de presupuesto">Enviar a gestionar CDP por parte de presupuesto</option>
                                                <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                            </select>
                                        </div>
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
            <!-- end #modal-form-pc-previous-studies send -->

        <!-- begin #modal-form-pc-previous-studies send presupuesto-->
        @elseif (Auth::user()->hasRole('PC Gestor presupuesto'))
            <dynamic-modal-form
                    modal-id="send-studies-previous-juridic"
                    size-modal="lg"
                    title="Enviar estudios previos"
                    :data-form="dataForm"
                    endpoint="pc-previous-studies-send"
                    :is-update="true"
                    confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso.<br> <br> ¿Estás seguro de aceptar?"
                    @saved="
                        if($event.isUpdate) {
                            assignElementList(dataForm.id, $event.data);
                        } else {
                            addElementToList($event.data);
                        }">
                    
                    <template #fields="scope">
    
                            <div class="row"  v-if="dataForm.state != 26">    
    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar a gestionar Plan Anual de Adquisiciones por parte de Gestión de Recursos o devolver al líder de proceso para mejoras</h1>
                                    </div>
                                </div>
    
                            
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Gestionando Plan Anual de Adquisiciones por parte de Gestión de Recursos">Enviar a gestionar Plan Anual de Adquisiciones por parte de Gestión de Recursos</option>
                                                <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                            </select>
                                        </div>
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


                            <div class="row"  v-if="dataForm.state == 26">    
    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <h1 for="danger" class="col-form-label col-md-12">Envíar a Jurídica para legalización de contrato</h1>
                                    </div>
                                </div>
    
                            
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Contrato legalizado">Enviar a Jurídica para legalización de contrato</option>
                                            </select>
                                        </div>
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
            <!-- end #modal-form-pc-previous-studies send -->

        <!-- begin #modal-form-pc-previous-studies send gestión recursos-->
        @elseif (Auth::user()->hasRole('PC Gestor de recursos'))
            <dynamic-modal-form
                    modal-id="send-studies-previous-juridic"
                    size-modal="lg"
                    title="Enviar estudios previos"
                    :data-form="dataForm"
                    endpoint="pc-previous-studies-send"
                    :is-update="true"
                    confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso.<br> <br> ¿Estás seguro de aceptar?"
                    @saved="
                        if($event.isUpdate) {
                            assignElementList(dataForm.id, $event.data);
                        } else {
                            addElementToList($event.data);
                        }">
                    
                    <template #fields="scope">
    
                            <div class="row">    
                                <div v-if="dataForm.state == 5">
                                    <div class="col-md-12">
                                        <div class="form-group row m-b-15">
                                            <h1 for="danger" class="col-form-label col-md-12">Enviar a revisión de asistente de gerencia</h1>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="col-md-12">
                                        <div class="form-group row m-b-15">
                                            <h1 for="danger" class="col-form-label col-md-12">Enviar al Jefe oficina jurídica para Asignación de abogado o devolver al líder de proceso para mejoras</h1>
                                        </div>
                                    </div>
                                </div>
                                
                            
                                <div class="col-md-12"  v-if="dataForm.state == 5"> 
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Revisando plan anual de adquisiciones">Enviar a revisión de asistente de gerencia para revisar el plan anual de adquisiciones </option>
                                                <option value="Devuelto al líder del proceso para mejoras">Devolver al líder de proceso para mejoras</option>
                                            </select>
                                        </div>
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
            <!-- end #modal-form-pc-previous-studies send -->

        <!-- begin #modal-form-pc-previous-studies send gerente-->
        @elseif (Auth::user()->hasRole('PC Gerente'))
            <dynamic-modal-form
                    modal-id="send-studies-previous-juridic"
                    size-modal="lg"
                    title="Enviar estudios previos"
                    :data-form="dataForm"
                    endpoint="pc-previous-studies-send"
                    :is-update="true"
                    confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso.<br> <br> ¿Estás seguro de aceptar?"
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
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar a jurídica para solicitud de CRP</h1>
                                    </div>
                                </div>
    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Solicitando CRP">Enviar a jurídica para solicitud de CRP</option>
                                            </select>
                                        </div>
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
            <!-- end #modal-form-pc-previous-studies send -->

        <!-- begin #modal-form-pc-previous-studies send gerente-->
        @elseif (Auth::user()->hasRole('PC Líder de proceso'))
            <dynamic-modal-form
                    modal-id="send-studies-previous"
                    size-modal="lg"
                    title="Enviar estudios previos"
                    :data-form.sync="dataForm"
                    endpoint="pc-previous-studies-send"
                    :is-update="true"
                    confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso. <br> <br> ¿Estás seguro de aceptar?"
                    @saved="
                        if($event.isUpdate) {
                            assignElementList(dataForm.id, $event.data);
                        } else {
                            addElementToList($event.data);
                        }">
                    <template #fields="scope">

                            <div class="row" v-if="dataForm.state == 1 || dataForm.state == 17">    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar a revisión a asistente de gerencia</h1>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="boss_process_id" class="col-form-label col-md-4">Jefe de Oficina:</label>
                                        <div class="col-md-8">

                                            <autocomplete
                                                asignar-al-data = ""
                                                name-prop="name"
                                                name-field="boss_process_id"
                                                :value='dataForm'
                                                name-resource="/intranet/get-users"
                                                css-class="form-control"
                                                :name-labels-display="['name', 'email']"
                                                reduce-key="id"
                                                :key="keyRefresh"
                                            >
                                            </autocomplete>
                                            <small>Ingrese el nombre del jefe de oficina sólo si el estudio previo requiere la firma del funcionario</small>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="leader_process_id" class="col-form-label col-md-4 required">Líder del Proceso:</label>
                                        <div class="col-md-8">

                                            <autocomplete
                                                asignar-al-data = ""
                                                name-prop="name"
                                                name-field="leader_process_id"
                                                :value='dataForm'
                                                name-resource='get-user-by-rol/PC Líder de proceso' 
                                                css-class="form-control"
                                                :name-labels-display="['name', 'email']"
                                                reduce-key="id"
                                                :key="keyRefresh"
                                                :is-required="true"

                                            >
                                            </autocomplete>
                                            <small>Ingrese el nombre del líder de proceso que firmará el estudio previo</small>

                                        </div>
                                    </div>
                                </div>
                                                                
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="subgerente_process_id" class="col-form-label col-md-4 required">Subgerente del proceso:</label>
                                        <div class="col-md-8">

                                            <autocomplete
                                                asignar-al-data = ""
                                                name-prop="name"
                                                name-field="subgerente_process_id"
                                                :value='dataForm'
                                                name-resource="/intranet/get-users"
                                                css-class="form-control"
                                                :name-labels-display="['name', 'email']"
                                                reduce-key="id"
                                                :key="keyRefresh"
                                                :is-required="true"

                                            >
                                            </autocomplete>
                                            <small>Ingrese el nombre del Subgerente o director del proceso que firmará el estudio previo</small>
                                        </div>
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

                            <div class="row" v-if="dataForm.state == 22">    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <h1 for="danger" class="col-form-label col-md-12">Enviar al abogado con visto bueno</h1>
                                    </div>
                                </div>
    
                                <div class="col-md-12">
                                    <div class="form-group row m-b-15">
                                        <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                                <option value="">Seleccione</option>
                                                <option value="Visto bueno">Enviar al abogado con visto bueno</option>
                                                <option value="Devolución de propuesta">Devolver al abogado para mejoras</option>
                                            </select>
                                        </div>
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
            <!-- end #modal-form-pc-previous-studies send -->
        @endif

        <dynamic-modal-form
        modal-id="send-studies-previous-juridic-approbed"
        size-modal="lg"
        title="Enviar estudios previos"
        :data-form="dataForm"
        endpoint="pc-previous-studies-send"
        :is-update="true"
        confirmation-message-saved="Estás a punto de enviar el estudio previo al siguiente paso en el proceso.<br> <br> ¿Estás seguro de aceptar?"
        @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }">
        
        <template #fields="scope">

                    <div class="row" v-if="dataForm.state == 22">    
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <h1 for="danger" class="col-form-label col-md-12">Enviar al abogado con visto bueno</h1>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                <label for="type_send" class="col-form-label col-md-4 required">¿Qué desea hacer?:</label>
                                <div class="col-md-8">
                                    <select class="form-control" v-model="scope.dataForm.type_send" name="type_send" id="type_send" required>
                                        <option value="">Seleccione</option>
                                        <option value="Visto bueno">Enviar al abogado con visto bueno</option>
                                        <option value="Devolución de propuesta">Devolver al abogado para mejoras</option>
                                    </select>
                                </div>
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


        <!-- begin #modal-history-->
        <div class="modal fade" id="modal-view-history-studie">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Historial de cambios</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @include('contractual_process::pc_previous_studies.show_history')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-history-->

        
        <!-- begin #modal-history-->
        <div class="modal fade" id="modal-view-news-studie">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Seguimiento y Control</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @include('contractual_process::pc_previous_studies.show_news')
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-history-->
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
  
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-pc-previous-studies').on('keypress', ':input:not(textarea):not([type=submit])', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
</script>
@endpush
