@extends('layouts.default')

@section('title', trans('adiciones'))

@section('section_img', '')

@section('menu')
    @include('maintenance::addition_spare_part_activities.layouts.menu')
@endsection

@section('content')

<crud
    name="addition-spare-part-activities"
    :swal-confirmacion="true"
    texto-swal="¿Está a punto de enviar la orden al supervisor del contrato para su revisión y aprobación, Recuerda que una vez enviada, no podrás realizar modificaciones"
    :init-values="{order : '{{ $order ?? null }}',mant_administration_cost_items_id : '{{ $mant_administration_cost_items_id ?? null }}',rubro_objeto_contrato_id : '{{ $rubro_objeto_contrato_id ?? null }}', request_id : '{{ $request_id ?? null }}', needs:[]}"
    :resource="{default: 'addition-spare-part-activities', get: '{{ $endpoint ?? 'get-addition-spare-part-activities' }}'}"
    inline-template>
    <div>
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
            <li class="breadcrumb-item active">@lang('adiciones')</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">{{ $title }}</h1>
        <!-- end page-header -->

        <!-- begin main buttons -->
        <div class="m-t-20">
            @if(Auth::check() && Auth::user()->hasRole('Administrador de mantenimientos'))
                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-addition-spare-part-activities-admin" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('solicitud')
                </button>
            @else
                <button @click="add()" type="button" class="btn btn-primary m-b-10" data-backdrop="static" data-target="#modal-form-addition-spare-part-activities" data-toggle="modal">
                    <i class="fa fa-plus mr-2"></i>@lang('crud.create') @lang('solicitud')
                </button>
            @endif
        </div>
        <!-- end main buttons -->

        <!-- begin panel -->
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <div class="panel-title">
                    <h5 class="text-center"> @{{ `@lang('total_registers') @lang('adiciones'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                    mode="range">
                                </date-picker>
                                <small>Filtrar por un rango de fechas</small>                           
                            </div>
                            <div class="col-md-5">
                                <button @click="clearDataSearch()" class="btn btn-md btn-primary"><i class="fas fa-broom mr-2"></i>@lang('clear_search_fields')</button>
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
                @include('maintenance::addition_spare_part_activities.table')
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

        <!-- begin #modal-view-addition-spare-part-activities -->
        <div class="modal fade" id="modal-view-addition-spare-part-activities">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">@lang('info_of') @lang(' la solicitud')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                       @include('maintenance::addition_spare_part_activities.show_fields')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-view-addition-spare-part-activities -->

        <!-- begin #modal-form-addition-spare-part-activities-admin-add -->
        <div class="modal fade" id="modal-form-addition-spare-part-activities">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="save();" id="form-config-doc-pensioners">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formulario para solicitar a adición de repuestos y/o servicios</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body">
                        @include('maintenance::addition_spare_part_activities.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-addition-spare-part-activities-admin-add -->    

        <!-- begin #modal-form-addition-spare-part-activities-admin -->
        <dynamic-modal-form
        modal-id="modal-form-addition-spare-part-activities-admin"
        size-modal="xl"
        :data-form="dataForm"
        :is-update="isUpdate"
        title="Formulario de la solicitud"
        endpoint="request-addition-process"
        @saved="
            if($event.isUpdate) {
                assignElementList(dataForm.id, $event.data);
            } else {
                addElementToList($event.data);
            }        
            {{-- _getDataList(); --}}
        ">
            <template #fields="scope">
                <div>
                    @include('maintenance::addition_spare_part_activities.fields_admin')
                </div>
            </template>
        </dynamic-modal-form>
        <!-- end #modal-form-addition-spare-part-activities-admin -->
        
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}

<style>
.custom-tooltip {
    position: relative;
    display: inline-block;
}

.custom-tooltip-content {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #f8f9fa;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    display: none;
    width: 300px; /* Ajusta el ancho según tus necesidades */

}

.custom-tooltip:hover .custom-tooltip-content {
    display: block;
}

.custom-tooltip-trigger {
    cursor: pointer;
    color: #007bff;
}

</style>
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-addition-spare-part-activities').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
