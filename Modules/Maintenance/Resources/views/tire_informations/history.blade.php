<div>
    <div style="text-align: center">
        <table class="table-bordered text-center default" style="width:100%; table-layout: fixed;">
            <thead class="thead-light ">
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Nombre del equipo o activo</th>
                    <th scope="col">Placa</th>
                    <th scope="col">Referencia de llanta</th>
                    <th scope="col">Posición de la llanta</th>
                    <th scope="col">Tipo de llanta</th>
                    <th scope="col">Marca de llanta</th>
                    <th scope="col">Código de llantas</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Observación</th>
                    <th scope="col">Info</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="data in dataShow.tire_histories">
                    <td>@{{ data.created_at }}</td>
                    <td>@{{ data.user_name }}</td>
                    <td>@{{ data.active_name }}</td>
                    <td>@{{ data.plaque }}</td>
                    <td>@{{ data.tire_reference }}</td>
                    <td>@{{ data.tire_position }}</td>
                    <td>@{{ data.tire_type }}</td>
                    <td>@{{ data.tire_brand }}</td>
                    <td>@{{ data.code_tire }}</td>
                    <td>@{{ data.status }}</td>
                    <td>@{{ data.observation }}</td>
                    <td>@{{ data.info }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
