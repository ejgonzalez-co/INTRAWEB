<table class="table table-hover m-b-0">
    <thead>
        <tr>
            <td class="text-center border"><strong>Fecha</strong></td>
            <td class="text-center border"><strong>Usuario</strong></td>
            <td class="text-center border"><strong>Acción</strong></td>
            <td class="text-center border"><strong>Cantidad</strong></td>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(history,key) in dataShow.stock_histories" :key="key">
            <td class="text-center border">@{{ history.created_at ? history.created_at : "Sin fecha de creación" }}</td>
            <td class="text-center border">@{{ history.usuario_nombre ? history.usuario_nombre : "Sin nombre de usuario" }}</td>
            <td class="text-center border">
                <p v-if="history.accion === 'Entrada'"><i class="fas fa-arrow-down"></i> Entrada</p>
                <p v-else><i class="fas fa-arrow-up"></i> Salida</p>
            </td>
            <td class="text-center border">@{{ history.cantidad ? history.cantidad : "N/E" }}</td>
        </tr>
    </tbody>
</table>
