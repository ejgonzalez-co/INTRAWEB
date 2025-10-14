<div class="row">
   <!-- User Name Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('User Name'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.user_name }}.</dd>
</div>


<div class="row">
   <!-- Annotation Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Annotation'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.annotation }}.</dd>
</div>

<div class="row">
   <!-- Annotation Field -->
 
</div>

<div >
   <div class="form-group row m-b-15">
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">Adjunto:</dt>

       <ul v-if="dataShow.attached">
           <li v-for="attached in dataShow.attached.split(',')" style="margin-left: -15px;"><a class="col-sm-9 col-md-9 col-lg-9 " :href="'{{ asset('storage') }}/'+attached" target="_blank">Ver adjunto</a><br/></li>
       </ul>
       <ul v-else>
           <li>No tiene adjuntos</li>
       </ul>
   </div>
</div>



