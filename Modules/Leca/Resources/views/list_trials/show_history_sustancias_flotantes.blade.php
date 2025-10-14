<div class="table-responsive">
    <table class="table" style="width:100%;" border="1">
    <tr>
        <th>Nombre funcionario</th>
        <th>Proceso</th>
        <th>Año</th>
        <th>mes</th>
        <th>Fecha curva (1)</th>
        <th>Fecha curva (2)</th>
        <th>Fecha curva (3)</th>
        <th>Documento de referencia (1)</th>
        <th>Documento de referencia (2)</th>
        <th>Documento de referencia (3)</th>
        <th>Límite de cuantificación del método (LCM) (1)</th>
        <th>Límite de cuantificación del método (LCM) (2)</th>
        <th>Límite de cuantificación del método (LCM) (3)</th>
        <th>Nombre patrón (1)</th>
        <th>Valor esperado (1)</th>
        <th>Nombre patrón (2)</th>
        <th>Valor esperado (2)</th>
        <th>Nombre patrón (3)</th>
        <th>Valor esperado (3)</th>
        <th>Observación</th>
        <th>Justificación de la edición del registro del dato primario</th>
        <th>Nombre</th>
        <th>Cargo</th>
        <th>Firma</th>
    </tr>
    <tr v-for="history in dataShow.lc_sustancias_flotantes">
        <td style="padding: 15px">@{{ history.user_name }}</td>
        <td style="padding: 15px">@{{ history.proceso }}</td>
        <td style="padding: 15px">@{{ history.año }}</td>
        <td style="padding: 15px">@{{ history.mes }}</td>
        <td style="padding: 15px">@{{ history.fecha_ajuste_curva_uno }}</td>
        <td style="padding: 15px">@{{ history.fecha_ajuste_curva_dos }}</td>
        <td style="padding: 15px">@{{ history.fecha_ajuste_curva_tres }}</td>
        <td style="padding: 15px">@{{ history.documento_referencia_uno }}</td>
        <td style="padding: 15px">@{{ history.documento_referencia_dos }}</td>
        <td style="padding: 15px">@{{ history.documento_referencia_tres }}</td>
        <td style="padding: 15px">@{{ history.limite_cuantificacion_uno }}</td>
        <td style="padding: 15px">@{{ history.limite_cuantificacion_dos }}</td>
        <td style="padding: 15px">@{{ history.limite_cuantificacion_tres }}</td>
        <td style="padding: 15px">@{{ history.nombre_patron_uno }}</td>
        <td style="padding: 15px">@{{ history.valor_esperador_uno }}</td>
        <td style="padding: 15px">@{{ history.nombre_patron_dos }}</td>
        <td style="padding: 15px">@{{ history.valor_esperado_dos }}</td>
        <td style="padding: 15px">@{{ history.nombre_patron_tres }}</td>
        <td style="padding: 15px">@{{ history.valor_esperador_tres }}</td>
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