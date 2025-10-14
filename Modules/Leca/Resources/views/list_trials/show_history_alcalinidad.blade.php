
<div class="table-responsive">
    <table class="table" style="width:100%;" border="1">
    <tr>
        <th>Nombre funcionario</th>
        <th>Proceso</th>
        <th>Documento de referencia</th>
        <th>Año</th>
        <th>Mes</th>
        <th>Límite de cuantificación</th>
        <th>V muestra (mL)</th>
        <th>Alcalinidad alta</th>
        <th>Factor/Cálculo</th>
        <th>Alcalinidad baja</th>
        <th>Factor/Cálculo</th>
        <th>% Curva</th>
        <th>Fecha curva</th>
        <th>Alcalinidad total</th>
        <th>Observación</th>
        <th>Justificación de la edición del registro del dato primario</th>
        <th>Nombre</th>
        <th>Cargo</th>
        <th>Firma</th>
    </tr>
    <tr v-for="history in dataShow.lc_alcalinidad">
        <td style="padding: 15px">@{{ history.user_name }}</td>
        <td style="padding: 15px">@{{ history.proceso }}</td>
        <td style="padding: 15px">@{{ history.documento_referencia }}</td>
        <td style="padding: 15px">@{{ history.año }}</td>
        <td style="padding: 15px">@{{ history.mes }}</td>
        <td style="padding: 15px">@{{ history.limite_cuantificacion }}</td>
        <td style="padding: 15px">@{{ history.v_muestra }}</td>
        <td style="padding: 15px">@{{ history.alcalinidad_alta }}</td>
        <td style="padding: 15px">@{{ history.factor_alcal_alta }}</td>
        <td style="padding: 15px">@{{ history.alcalinidad_baja }}</td>
        <td style="padding: 15px">@{{ history.factor_alcal_baja }}</td>
        <td style="padding: 15px">@{{ history.curva }}</td>
        <td style="padding: 15px">@{{ history.fehca_curva }}</td>
        <td style="padding: 15px">@{{ history.alcalinidad_total }}</td>
        <td style="padding: 15px">@{{ history.obervacion }}</td>
        <td v-if="history.observations_edit == null" style="padding: 15px">Creacion de registro</td>
        <td v-else style="padding: 15px">@{{ history.observations_edit }}</td>
        <td v-if="history.user_remplace == 'Si'">@{{ history.user_remplace_admin }}</td>
        <td v-else style="padding: 15px">@{{ history.nombre }}</td>
        <td style="padding: 15px">@{{ history.cargo }}</td>
        <td v-if="history.user_remplace == 'Si'"><img :src="'{{ asset('storage') }}/'+history.firma" width="150"></td>
        <td v-else><img width="150" src="{{ asset('assets/img/default/firma_leca.png')}}" /></td>
    </tr>
</table>
</div>
