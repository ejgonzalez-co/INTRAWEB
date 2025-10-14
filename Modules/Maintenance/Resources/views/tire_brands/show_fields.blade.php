<div class="row">
    <!-- Brand Name Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Brand Name'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.brand_name }}.</dd>
</div>


<div class="row">
    <!-- Descripction Field -->
    <strong class="text-inverse text-left col-3 text-truncate">@lang('Referencia de la llanta'):</strong>
    <table border="1" class="text-center">
      <thead>
         <tr>
            <td><strong>Referencias de la llanta</strong></td>
         </tr>
      </thead>
      <tbody>
         <tr v-for="(tire_reference,key) in dataShow.tire_references" :key="key">
            <td>@{{ tire_reference.reference_name }}</td>
         </tr>
      </tbody>
    </table>
</div>
