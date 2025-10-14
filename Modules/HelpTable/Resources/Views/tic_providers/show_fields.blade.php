<div class="row">
   <!-- Type Person Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Type Person'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.type_person_name }}.</dd>


   <!-- Document Type Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Document Type'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.document_type_name }}.</dd>


   <!-- Identification Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Identification'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.identification }}.</dd>


   <!-- Name Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Name'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.users? dataShow.users.name : '' }}.</dd>


   <!-- Profession Field -->
   <!-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Profession'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.profession }}.</dd> -->


   <!-- Professional Card Number Field -->
   <!-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Professional Card Number'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.professional_card_number }}.</dd> -->


   <!-- Regime Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Regime'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.regime_name }}</dd>


   <!-- Address Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Address'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.address }}.</dd>


   <!-- Phone Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Phone'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.phone }}.</dd>


   <!-- Cellular Field -->
   <!-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Cellular'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.cellular }}.</dd> -->


   <!-- Email Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Email'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.users? dataShow.users.email : '' }}</dd>


   <!-- State Field -->
   <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('State'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.state_name }}.</dd>


   <!-- Ciiu Activities Field -->
   <!-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Ciiu Activities'):</dt>
   <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.ciiu_activities }}.</dd> -->
</div>