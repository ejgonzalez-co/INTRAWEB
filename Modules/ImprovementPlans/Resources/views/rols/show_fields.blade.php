<div class="row">
   <!-- Name Field -->
   <strong class="text-inverse text-let col-3 text-break">@lang('Name'):</strong>
   <p class="col-9 text-break">@{{ dataShow.name }}.</p>
</div>


<div class="row">
   <!-- Description Field -->
   <strong class="text-inverse text-let col-3 text-break">@lang('Descripción'):</strong>
   <p class="col-9 text-break">@{{ dataShow.description }}.</p>
</div>

<div class="row mb-2">
   <!-- Process Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Permisos'):</strong>
   <table border="1" class="text-center col-3">
       <thead>
           <tr>
               <td><strong>Módulo</strong></td>
               <td><strong>Gestion</strong></td>
               <td><strong>Reporte</strong></td>
               <td><strong>Solo consulta</strong></td>
           </tr>
       </thead>
       <tbody>
           <tr v-for="(permission,key) in dataShow.rol_permissions" :key="key">
               <td>@{{ permission.module }}</td>
               <td>@{{ permission.can_manage == true ? 'X' : '' }}</td>
               <td>@{{ permission.can_generate_reports == true ? 'X' : '' }}</td>
               <td>@{{ permission.only_consultation == true ? 'X' : '' }}</td>
           </tr>
       </tbody>
   </table>
</div>
