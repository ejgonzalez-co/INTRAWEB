
<div class="table-responsive">
    <table class="table" style="width:100%;" border="1">
    <tr>
        <th>Nombre funcionario</th>
        <th>Ensayo</th>
        <th>Proceso</th>
        <th>Documento de referencia</th>
        <th>Año</th>
        <th>Mes</th>
        <th>k Pendiente</th>
        <th>b Intercepto</th>
        <th>Factor dilución</th>
        <th>Nombre patrón</th>
        <th>Valor esperado</th>
        <th>Recuperación adicionado</th>
        <th>Límite de cuantificación</th>
        <th>Curva N°</th>
        <th>Fecha de la curva</th>
        <th>Ensayo mg/L N°3</th>
        <th>Observación</th>
        <th>Justificación de la edición del registro del dato primario</th>
        <th>Nombre</th>
        <th>Cargo</th>
        <th>Firma</th>
    </tr>
    <tr v-for="history in dataShow.lc_nitratos">
        <td style="padding: 15px">@{{ history.user_name }}</td>
        <td style="padding: 15px">@{{ history.ensayo }}</td>
        <td style="padding: 15px">@{{ history.processo }}</td>
        <td style="padding: 15px">@{{ history.documento_referencia }}</td>
        <td style="padding: 15px">@{{ history.año }}</td>
        <td style="padding: 15px">@{{ history.mes }}</td>
        <td style="padding: 15px">@{{ history.k_pendiente }}</td>
        <td style="padding: 15px">@{{ history.b_intercepto }}</td>
        <td style="padding: 15px">@{{ history.fd_factor_dilucion }}</td>
        <td style="padding: 15px">@{{ history.nombre_patron }}</td>
        <td style="padding: 15px">@{{ history.valor_esperado }}</td>
        <td style="padding: 15px">@{{ history.recuperacion_adicionado }}</td>
        <td style="padding: 15px">@{{ history.limite_cuantificacion }}</td>
        <td style="padding: 15px">@{{ history.curva_numero }}</td>
        <td style="padding: 15px">@{{ history.fecha_curva }}</td>
        <td style="padding: 15px">@{{ history.ensayo_mgl_no3 }}</td>
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
