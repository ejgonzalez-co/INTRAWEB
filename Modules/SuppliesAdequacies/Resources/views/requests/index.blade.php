@extends('layouts.default')

@section('title', trans('Requests'))

@section('section_img', '/assets/img/components/solicitudes.png')

@section('menu')
    @include('suppliesadequacies::layouts.menu')
@endsection

@section('content')

<crud
    name="requests"
    :resource="{default: 'requests', get: 'get-requests'}"
    :crud-avanzado="true"
    :init-values="{ requests_supplies_adjustements_needs:[] }"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/home') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('requests')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">@{{ '@lang('Solicitudes Requerimientos de Suministros y Adecuaciones')'}}</h1>
        <!-- end page-header -->

        <a href="javascript:hideShow('widget')"><u style="color: black"><strong>Ocultar/Mostrar Consolidado</strong></u></a><br><br>
        @if(Auth::user()->hasRole("Administrador requerimiento gestión recursos"))
            <!-- begin widget -->
            <div id="widget" class="row">
                <widget-counter
                    icon="fa fa-book-open"
                    bg-color="#17a2b8"
                    :qty="dataExtra.status.abierta ?? 0"
                    title="Abierta"
                    title-link-see-more="Filtrar"
                    status="Abierta"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('1')" --}}
                ></widget-counter>
                <widget-counter
                    icon="far fa-user"
                    bg-color="#ffc107"
                    :qty="dataExtra.status.asignada ?? 0"
                    title="Asignada"
                    title-link-see-more="Filtrar"
                    status="Asignada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('2')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fas fa-cogs"
                    bg-color="#fd7e14"
                    :qty="dataExtra.status.enproceso ?? 0"
                    title="En proceso"
                    title-link-see-more="Filtrar"
                    status="En proceso"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('3')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-minus"
                    bg-color="#e97878"
                    :qty="dataExtra.status.próximavigencia ?? 0" 
                    title="Próxima vigencia"
                    title-link-see-more="Filtrar"
                    status="Próxima vigencia"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('4')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-check"
                    bg-color="rgb(151, 227, 159)"
                    :qty="dataExtra.status.cerrada ?? 0" 
                    title="Cerrada"
                    title-link-see-more="Filtrar"
                    status="Cerrada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('5')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-check"
                    bg-color="rgb(31, 168, 46)"
                    :qty="dataExtra.status.finalizada ?? 0"
                    title="Finalizada"
                    title-link-see-more="Filtrar"
                    status="Finalizada"
                    :value="searchFields"
                    name-field="status"
                {{-- link-see-more="javascript:checkData('5')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-check"
                    bg-color="rgb(209, 20, 20)"
                    :qty="dataExtra.status.cancelada ?? 0" 
                    title="Cancelada"
                    title-link-see-more="Filtrar"
                    status="Cancelada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('5')" --}}
                ></widget-counter>



                <button  @click="clearDataSearchAvanzado()" style="height: 40px; margin-left:10px;" class="btn btn-md btn-primary">Limpiar datos filtrados</button>
            </div>

        @elseif (Auth::user()->hasRole(["Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación"]))
            <div id="widget" class="row">
                <widget-counter
                    icon="far fa-user"
                    bg-color="#ffc107"
                    :qty="dataExtra.status.asignada ?? 0"
                    title="Asignada"
                    title-link-see-more="Filtrar"
                    status="Asignada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('2')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fas fa-cogs"
                    bg-color="#fd7e14"
                    :qty="dataExtra.status.enproceso ?? 0"
                    title="En proceso"
                    title-link-see-more="Filtrar"
                    status="En proceso"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('3')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-minus"
                    bg-color="rgb(151, 227, 159)"
                    :qty="dataExtra.status.cerrada ?? 0" 
                    title="Cerrada"
                    title-link-see-more="Filtrar"
                    status="Cerrada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('4')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-check"
                    bg-color="rgb(11, 197, 104)"
                    :qty="dataExtra.status.finalizada ?? 0" 
                    title="Finalizada"
                    title-link-see-more="Filtrar"
                    status="Finalizada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('5')" --}}
                ></widget-counter>
                <button  @click="clearDataSearchAvanzado()" style="height: 40px; margin-left:10px;" class="btn btn-md btn-primary">Limpiar datos filtrados</button>
            </div>
        @else
            <!-- begin widget -->
            <div id="widget" class="row">
                <widget-counter
                    icon="fa fa-book-open"
                    bg-color="#17a2b8"
                    :qty="dataExtra.status.abierta ?? 0"
                    title="Abierta"
                    title-link-see-more="Filtrar"
                    status="Abierta"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('1')" --}}
                ></widget-counter>
                <widget-counter
                    icon="far fa-user"
                    bg-color="#ffc107"
                    :qty="dataExtra.status.asignada ?? 0"
                    title="Asignada"
                    title-link-see-more="Filtrar"
                    status="Asignada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('2')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fas fa-cogs"
                    bg-color="#fd7e14"
                    :qty="dataExtra.status.enproceso ?? 0"
                    title="En proceso"
                    title-link-see-more="Filtrar"
                    status="En proceso"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('3')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-minus"
                    bg-color="#e97878"
                    :qty="dataExtra.status.próximavigencia ?? 0" 
                    title="Próxima vigencia"
                    title-link-see-more="Filtrar"
                    status="Próxima vigencia"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('4')" --}}
                ></widget-counter>
                <widget-counter
                    icon="fa fa-user-check"
                    bg-color="rgb(11, 197, 104)"
                    :qty="dataExtra.status.finalizada ?? 0" 
                    title="Finalizada"
                    title-link-see-more="Filtrar"
                    status="Finalizada"
                    :value="searchFields"
                    name-field="status"
                    {{-- link-see-more="javascript:checkData('5')" --}}
                ></widget-counter>
                <widget-counter
                icon="fa fa-user-check"
                bg-color="rgb(209, 20, 20)"
                :qty="dataExtra.status.cancelada ?? 0" 
                title="Cancelada"
                title-link-see-more="Filtrar"
                status="Cancelada"
                :value="searchFields"
                name-field="status"
                {{-- link-see-more="javascript:checkData('5')" --}}
                ></widget-counter>


                <button  @click="clearDataSearchAvanzado()" style="height: 40px; margin-left:10px;" class="btn btn-md btn-primary">Limpiar datos filtrados</button>
            </div>
        @endif

        <!-- begin main buttons -->
        <div class="m-t-20">
            @role("Administrador requerimiento gestión recursos")
                <button @click="callFunctionComponent('holiday-calendars', 'loadCalendar')" type="button" class="btn btn-primary m-b-10">
                    <i class="fas fa-calendar-alt mr-2"></i>@lang('Calendar')
                </button>
            @endrole
            @if (Auth::user()->hasRole(["Administrador requerimiento gestión recursos","Funcionario requerimiento gestión recursos"]))                
                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-requests" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('request')
                </button>
            @endif
            
            <button onclick="window.location.href = window.location.href.split('?')[0];" class="btn btn-md btn-primary m-b-10"><i class="fa fa-redo-alt mr-2"></i>Cargar página de nuevo</button>
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('requests'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                <date-picker
                                :value="searchFields"
                                name-field="created_at"
                                mode="range"
                                >
                                </date-picker>
                                <small>Filtrar por un rango de fecha de creación.</small>
                            </div>
                            <div class="col-md-4">
                                <select-check css-class="form-control" name-field="assigned_officer_id" reduce-label="name"
                                reduce-key="id" name-resource="operators/requirements"
                                :enable-search="true" :value="searchFields">
                                </select-check>
                                <small>Filtrar por el usuario asignado.</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.requirement_type" required>
                                    <option value="Infraestuctura">Infraestuctura</option>
                                    <option value="Suministros de consumo">Suministros de consumo</option>
                                    <option value="Suministros devolutivo - Asignación">Suministros devolutivo / Asignación</option>
                                </select>
                                <small>Filtrar por el tipo de requerimiento.</small>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="searchFields.subject" @keyup.enter = "pageEventActualizado(1);">
                                <small>Filtrar por el asunto.</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                :value="searchFields"
                                name-field="date_attention"
                                mode="range"
                                >
                                </date-picker>
                                <small>Filtrar por un rango de fecha de recepción.</small>
                            </div>
                            <div class="col-md-4">
                                <date-picker
                                :value="searchFields"
                                name-field="expiration_date"
                                mode="range"
                                >
                                </date-picker>
                                <small>Filtrar por un rango de fecha de vencimiento.</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.cost_center" required>
                                    <option value="Aseo">Aseo</option>
                                    <option value="Acueducto">Acueducto</option>
                                    <option value="Alcantarillado">Alcantarillado</option>
                                    <option value="Procesos">Procesos</option>
                                </select>
                                <small>Filtrar por el centro de costos.</small>
                            </div>
                            <div class="col-md-4">
                                <select v-model="searchFields.need_type" class="form-control" >
                                    <option value="Producto">Producto</option>
                                    <option value="Servicio">Servicio</option>
                                    <option value="Mantenimiento">Mantenimiento</option>
                                </select>
                                <small>Filtrar por el tipo de necesidad.</small>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" v-model="searchFields.supplier_verification">
                                    <option value="Verificación realizada">Verificación realizada</option>
                                    <option value="Verificación pendiente">Verificación pendiente</option>
                                </select>
                                <small>Filtrar por la verficación con el proveedor.</small>
                            </div>
                            <div class="col-md-4">
                                <button @click="pageEventActualizado(1)" class="btn btn-md btn-primary"><i class="fa fa-search mr-2"></i>Buscar</button>
                                <button @click="clearDataSearchAvanzado()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>                            </div>
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
                @include('suppliesadequacies::requests.table')
            </div>
            <div class="p-b-15 text-center">
                <!-- Cantidad de elementos a mostrar -->
                <div class="form-group row m-5 col-md-3 text-center d-sm-inline-flex">
                    {!! Form::label('show_qty', trans('show_qty').':', ['class' => 'col-form-label col-md-7']) !!}
                    <div class="col-md-5">
                        {!! Form::select(trans('show_qty'), [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 50 => 50, 100 => 100, 200 => 200], 20, ['class' => 'form-control', 'v-model' => 'dataPaginator.pagesItems', '@change' => 'pageEventActualizado(1)']) !!}
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

        <!-- begin #modal-view-requests -->
        <div class="modal fade" id="modal-view-requests">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang('requests')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        @include('suppliesadequacies::requests.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-requests -->

        <!-- begin #modal-form-requests -->
        <div class="modal fade" id="modal-form-requests">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save()" id="form-requests">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('requests')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('suppliesadequacies::requests.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            {{-- <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button> --}}
                            @role("Funcionario requerimiento gestión recursos")
                                <button @click="saveProgress()" type="button" class="btn btn-secondary"><i class="fa fa-save mr-2"></i>Guardar avance</button>
                                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane mr-2"></i>@lang('Enviar solicitud')</button>
                            @endrole
                            @role("Administrador requerimiento gestión recursos")
                                <button v-show="!isUpdate" @click="saveProgress()" type="button" class="btn btn-secondary"><i class="fa fa-save mr-2"></i>Guardar avance</button>
                                <button v-show="!isUpdate" type="submit" class="btn btn-success"><i class="fas fa-paper-plane mr-2"></i>@lang('Enviar solicitud')</button>
                                <button v-show="isUpdate" type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            @endrole
                            @if(Auth::user()->hasRole(["Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación"]))
                                <button v-show="!isUpdate" @click="saveProgress()" type="button" class="btn btn-secondary"><i class="fa fa-save mr-2"></i>Guardar avance</button>
                                <button v-show="!isUpdate" type="submit" class="btn btn-success"><i class="fas fa-paper-plane mr-2"></i>@lang('Enviar solicitud')</button>
                                <button v-show="isUpdate" type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-requests -->

        <!-- begin #modal-form-goals-indicators send -->
        <dynamic-modal-form
        modal-id="modal-form-knowledge-base"
        size-modal="xl"
        title="Formulario de Base de conocimiento"
        :data-form.sync="dataForm"
        endpoint="knowledge-bases"
        >
            <template #fields="scope">
                @include("suppliesadequacies::knowledge_bases.fields")
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-goals-indicators send -->

        <holiday-calendar ref="holiday-calendars" name="holiday-calendars"></holiday-calendar>

        <annotations-general ref="annotations" route="/supplies-adequacies/request-annotations" name-list="annotations" file-path="public/container/supplies_adequacies/annotations_{{ date('Y') }}/anotaciones"  field-title="Anotaciones de la solicitud: " field-title-var="consecutive"></annotations-general>
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
    $('#modal-form-requests').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
