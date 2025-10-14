<table class="table table-hover m-b-0">
    <thead>
       <tr>
          <td class="text-center border"><strong>Fecha</strong></td>
          <td class="text-center border"><strong>Usuario</strong></td>
          <td class="text-center border"><strong>Acción</strong></td>
       </tr>
    </thead>
    <tbody>
       <tr v-for="(history,key) in dataShow.keyboard_configurations_histories" :key="key">
          <td class="text-center border">@{{ history.created_at ? history.created_at : "Sin fecha de creación" }}</td>
          <td class="text-center border">@{{ history.user_name ? history.user_name : "Sin nombre de usuario" }}</td>
          <td class="text-center border">@{{ history.action ? history.action : "Sin acción" }}</td>
       </tr>
    </tbody>
 </table>
 