<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de la meta</strong></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Goal Type Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Tipo de meta'):</strong>
            <p class="col-9 text-break">@{{ dataShow.goal_type }}.</p>
        </div>

        <div class="row">
            <!-- Goal Name Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Nombre de la meta'):</strong>
            <p class="col-9 text-break">@{{ dataShow.goal_name }}.</p>
        </div>

        <div class="row">
            <!-- Goal Weight Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Peso de la meta en el plan'):</strong>
            <p class="col-9 text-break">@{{ dataShow.goal_weight + "%" }}.</p>
        </div>

        <div class="row">
            <!-- Goal Commitment Date Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Fecha del compromiso'):</strong>
            <p class="col-9 text-break">@{{ dataShow.commitment_date }}.</p>
        </div>

        <div class="row">
            <!-- Indicator Description Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Descripción del indicador'):</strong>
            <p class="col-9 text-break">@{{ dataShow.indicator_description }}.</p>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Actividades que componen la meta del plan de mejoramiento</strong></div>
    </div>
    <div class="panel-body">
      <table border="1" class="text-center table">
         <thead>
             <tr>
                 <td><strong>Nombre actividad</strong></td>
                 <td><strong>Cantidad</strong></td>
                 <td><strong>Peso(porcentaje) actividad</strong></td>
                 <td><strong>Linea base para la meta</strong></td>
                 <td><strong>Brecha para cumplimiento de la meta</strong></td>
             </tr>
         </thead>
         <tbody>
             <tr v-for="(activity,key) in dataShow.goal_activities" :key="key">
                 <td>@{{ activity.activity_name }}</td>
                 <td>@{{ activity.activity_quantity }}</td>
                 <td>@{{ activity.activity_weigth + "%" }}</td>
                 <td>@{{ activity.baseline_for_goal }}</td>
                 <td>@{{ activity.gap_meet_goal }}</td>
             </tr>
         </tbody>
     </table>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Dependencias</strong></div>
    </div>
    <div class="panel-body">
      <table border="1" class="text-center col-3">
         <thead>
             <tr>
                 <td><strong>Dependencias</strong></td>
             </tr>
         </thead>
         <tbody>
             <tr v-for="(dependence,key) in dataShow.goal_dependencies" :key="key">
                 <td>@{{ dependence.dependence_name }}</td>
             </tr>
         </tbody>
     </table>
    </div>
</div>
