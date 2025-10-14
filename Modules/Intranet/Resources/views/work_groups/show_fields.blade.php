<!-- Name Field -->
<dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Name'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.name }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Description'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.description }}.</dd>


<!-- State Field -->
<dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('State'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ (dataShow.state) ?  '@lang('active')' : '@lang('inactive')' }}.</dd>


<!-- Url Img Profile Field -->
<dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('Url Img Profile'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">
   <img width="200" v-if="dataShow.url_img_profile" :src="'{{ asset('storage') }}/'+dataShow.url_img_profile" alt="">
</dd>


<!-- End Date Field -->
{{-- <dt class="text-inverse text-justify col-sm-3 col-md-3 col-lg-3 ">@lang('End Date'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.end_date }}.</dd>
 --}}

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.users?.length > 0">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Funcionarios que pertenecen al grupo</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">

       <div class="table-responsive">
           <table class="table table-hover table-bordered">
               <thead>
                   <tr>
                       <th>@lang('Name')</th>
                       <th>Email</th>

                   </tr>
               </thead>
               <tbody>
                   <tr v-for="(users, key) in dataShow.users">
                       <td>@{{ users.name }}</td>
                       <td>@{{ users.email }}</td>

                   </tr>
               </tbody>
           </table>
       </div>
   </div>
</div>


