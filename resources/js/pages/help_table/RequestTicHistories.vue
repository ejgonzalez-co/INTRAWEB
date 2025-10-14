<template>
   <div>
      <crud name="request-tic-histories" :resource="{default: 'request-tic-histories', get: 'get-request-tic-histories/'+requestId}" inline-template>
         <div>
            <!-- begin panel -->
            <div class="panel panel-default">
                  <div class="panel-heading border-bottom">
                     <div class="panel-title">
                        <h5 class="text-center"> @{{ `@lang('total_registers') @lang('Request Tic Histories'): ${dataPaginator.total}` | capitalize }}</h5>
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
                                    {!! Form::text('name', null, ['v-model' => 'searchFields.name', 'class' => 'form-control', 'placeholder' => trans('crud.filter_by', ['name' => trans('ejemplo')]) ]) !!}
                                 </div>
                                 <button @click="clearDataSearch()" class="btn btn-md btn-primary">@lang('clear_search_fields')</button>
                              </div>
                        </div>
                     </div>
                     <!-- end card search -->
                  </div>
                  <!-- end #accordion search -->
                  <div class="panel-body">
                     @include('help_table::request_tic_histories.table')
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

            <!-- begin #modal-view-request-tic-histories -->
            <div class="modal fade" id="modal-view-request-tic-histories">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                              <h4 class="modal-title text-white">@lang('info_of') @lang('Request Tic Histories')</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body">
                              <div class="row">
                                 @include('help_table::request_tic_histories.show_fields')
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                        </div>
                     </div>
                  </div>
            </div>
            <!-- end #modal-view-request-tic-histories -->
         </div>
      </crud>
   </div>
</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import * as bootstrap from 'bootstrap';

   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();

   /**
   * Componente del historial de solicitudes tic
   *
   * @author Carlos Moises Garcia T. - Nov. 19 - 2020
   * @version 1.0.0
   */
   @Component
   export default class RequestTicHistories extends Vue {

      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: Number, required: true})
      public requestId: number;

   }
</script>