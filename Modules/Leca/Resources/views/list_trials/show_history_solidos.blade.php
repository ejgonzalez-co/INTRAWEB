<div class="table-responsive">
    <table class="table" style="width:100%;" border="1">
    <tr>
        <th>Nombre funcionario</th>
        <th>Ensayo (1)</th>
        <th>Ensayo (2)</th>
        <th>Proceso</th>
        <th>Documento de referencia (1)</th>
        <th>Documento de referencia (2)</th>
        <th>Observación</th>
        <th>Justificación de la edición del registro del dato primario</th>
        <th>Nombre</th>
        <th>Cargo</th>
        <th>Firma</th>
    </tr>
    <tr v-for="history in dataShow.lc_solidos">
        <td style="padding: 15px">@{{ history.user_name }}</td>
        <td style="padding: 15px">@{{ history.ensayo_uno }}</td>
        <td style="padding: 15px">@{{ history.ensayo_dos }}</td>
        <td style="padding: 15px">@{{ history.processo }}</td>
        <td style="padding: 15px">@{{ history.documento_referencia_uno }}</td>
        <td style="padding: 15px">@{{ history.documento_referencia_dos }}</td>
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
