<div class="row">
   <!-- Type Person Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Type Person'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.type_person_name }}.</dd>

   <!-- Type Document Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Type Document'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.type_document_name }}.</dd>
</div>


<div class="row">
   <!-- First Name Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('First Name'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.first_name }}.</dd>

   <!-- Second Name Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Second Name'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.second_name }}.</dd>
</div>


<div class="row">
   <!-- First Surname Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('First Surname'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.first_surname }}.</dd>

   <!-- Second Surname Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Second Surname'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.second_surname }}.</dd>
</div>

<div class="row">
   <!-- State Id Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Department'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.states?.name }}.</dd>

   <!-- City Id Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('City'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.cities?.name }}.</dd>
</div>

<div class="row">
   <!-- Address Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Address'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.address }}.</dd>

   <!-- Phone Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Phone'):</dt>
   <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.phone }}.</dd>
</div>

<div class="row">
   <!-- Email Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Email'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.email }}.</dd>
</div>


