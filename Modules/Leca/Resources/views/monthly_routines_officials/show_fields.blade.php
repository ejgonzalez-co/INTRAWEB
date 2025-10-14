<div class="row">
   <!-- User Name Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Lc Officials Id'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.user_name }}.</dd>
</div>


<div class="col-md-6">
   <div class="panel">
         <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Ensayos</strong></h4>
         </div>
         <div class="alert hljs-wrapper fade show">
            <!--<span class="close" data-dismiss="alert">×</span>-->
            <i class="fa fa-info fa-2x pull-left m-r-10 m-t-5"></i>
            <p class="m-b-0">Seleccione Los tipos de ensayo que ejercerá el analista</p>
         </div>
         <div class="panel-body">
            <ul>
                  <li v-for="(lt, key) in dataShow.lc_list_trials" :key="key">
                     @{{ lt.name }}
                  </li>
            </ul>
         </div>
   </div>
</div>