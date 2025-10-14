<table v-if="dataShow.novelty_type == 'Adición al contrato' " class="text-center default" style="width:100%; table-layout: fixed;" border="1">
    <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        {{-- <th>CDP adicionado</th> --}}
        <th>Observación</th>
    
    </tr>
    <tr v-for="history in dataShow.history_novelty">
        <td style="padding: 15px">@{{ history.created_at }}</td>
        <td style="padding: 15px">@{{ history.user_name}}</td>
        {{-- <td style="padding: 15px">@{{ history.value_cdp}}</td> --}}
        <td style="padding: 15px">@{{ history.description}}</td>
    </tr>
</table>


<table v-if="dataShow.novelty_type == 'Prórroga' " class="text-center default" style="width:100%; table-layout: fixed;" border="1">
    <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Fecha de terminación anterior</th>
        <th>Fecha terminación de la prórroga</th>
        <th>Observación</th>
    
    </tr>
    <tr v-for="history in dataShow.history_novelty">
        <td style="padding: 15px">@{{ history.created_at }}</td>
        <td style="padding: 15px">@{{ history.user_name}}</td>
        <td style="padding: 15px">@{{ history.date_previus_contract_term}}</td>
        <td style="padding: 15px">@{{ history.date_contract_term}}</td>
        <td style="padding: 15px">@{{ history.description}}</td>
    </tr>
</table>

<table v-if="dataShow.novelty_type == 'Suspensión' || dataShow.novelty_type == 'Reinicio' " class="text-center default" style="width:100%; table-layout: fixed;" border="1">
    <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Observación</th>
    
    </tr>
    <tr v-for="history in dataShow.history_novelty">
        <td style="padding: 15px">@{{ history.created_at }}</td>
        <td style="padding: 15px">@{{ history.user_name}}</td>
        <td style="padding: 15px">@{{ history.description}}</td>
    </tr>
</table>

<table v-if="dataShow.novelty_type == 'Asignación presupuestal'" class="text-center default" style="width:100%; table-layout: fixed;" border="1">
    <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Observación</th>
    
    </tr>
    <tr v-for="history in dataShow.history_novelty">
        <td style="padding: 15px">@{{ history.created_at }}</td>
        <td style="padding: 15px">@{{ history.user_name}}</td>
        <td style="padding: 15px">@{{ history.description}}</td>
    </tr>
</table>