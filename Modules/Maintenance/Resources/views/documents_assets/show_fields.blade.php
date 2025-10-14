<!-- Name Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate" style="white-space: break-spaces;">@{{ dataShow.description }}.</dd>


<!-- Url Document Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Url Document'):</dt>
<dd v-if="dataShow.url_document && dataShow.url_document.length > 0" class="col-9 text-truncate">
<viewer-attachement v-if="dataShow.url_document" :list="dataShow.url_document"></viewer-attachement>
</dd>


<!-- Mant Resume Asset Id Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Activo'):</dt>

<dd class="col-9 text-truncate" v-if="dataShow.mant_resume_machinery_vehicles_yellow">
    @{{ dataShow.mant_resume_machinery_vehicles_yellow ? dataShow.mant_resume_machinery_vehicles_yellow.name_vehicle_machinery : '' }}.
</dd>
<dd class="col-9 text-truncate" v-if="dataShow.mant_resume_equipment_machinery">
    @{{ dataShow.mant_resume_equipment_machinery ? dataShow.mant_resume_equipment_machinery.name_equipment : '' }}.
</dd>
<dd class="col-9 text-truncate" v-if="dataShow.mant_resume_equipment_machinery_leca">
    @{{ dataShow.mant_resume_equipment_machinery_leca ? dataShow.mant_resume_equipment_machinery_leca.name_equipment_machinery : '' }}.
</dd>
<dd class="col-9 text-truncate" v-if="dataShow.mant_resume_equipment_leca">
    @{{ dataShow.mant_resume_equipment_leca ? dataShow.mant_resume_equipment_leca.name_equipment : '' }}.
</dd>
<dd class="col-9 text-truncate" v-if="dataShow.mant_resume_inventory_leca">
    @{{ dataShow.mant_resume_inventory_leca ? dataShow.mant_resume_inventory_leca.description_equipment_name : '' }}.
</dd>
