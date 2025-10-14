<table class="text-center default" style="width:100%; table-layout: fixed;" border="1">
    <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Estado</th>
    
    </tr>
    <tr v-for="history in dataShow.history_attachments">
        <td style="padding: 15px">@{{ history.created_at }}</td>
        <td style="padding: 15px">@{{ history.user_name}}</td>
        <td style="padding: 15px">@{{ history.status}}</td>
    
    </tr>
</table>
