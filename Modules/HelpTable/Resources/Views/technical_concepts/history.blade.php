<table class="table table-hover m-b-0">
    <thead>
        <tr>
            <td class="text-center border"><strong>Fecha</strong></td>
            <td class="text-center border"><strong>Usuario</strong></td>
            <td class="text-center border"><strong>Estado</strong></td>
            <td class="text-center border"><strong>Observaciones</strong></td>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(history,key) in dataShow.technical_concepts_history" :key="key">
            <td class="border">@{{ history.created_at ? history.created_at : "Sin fecha de creación" }}</td>
            <td class="border">@{{ history.user_name ? history.user_name : "Sin nombre de usuario" }}</td>
            <td class="border">
                <p v-if="history.status === 'Pendiente'" class="button__status-pending">
                    @{{ history.status ? history.status : "N/A" }}</p>
                <p v-if="history.status === 'Asignado'" class="button__status-assigned">
                    @{{ history.status ? history.status : "N/A" }}</p>
                <p v-if="history.status === 'En revisión'" class="button__status-in_review">
                    @{{ history.status ? history.status : "N/A" }}</p>
                <p v-if="history.status === 'Aprobación pendiente'" class="button__status-pending_approval">
                    @{{ history.status ? history.status : "N/A" }}</p>
                <p v-if="history.status === 'Devolver al técnico'" class="button__status-cancelled">
                    @{{ history.status ? "Devuelto" : "N/A" }}</p>
                <p v-if="history.status === 'Devolver al revisor'" class="button__status-cancelled">
                    @{{ history.status ? "Devuelto" : "N/A" }}</p>
                <p v-if="history.status === 'Aprobado'" class="button__status-approved">
                    @{{ history.status ? history.status : "N/A" }}</p>
            </td>
            <td class="border" width="450" style="text-align:justify;">@{{ history.observations ? history.observations : "Sin observaciones" }}</td>
        </tr>
    </tbody>
</table>