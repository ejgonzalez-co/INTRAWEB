<table border="1">
    @php
        $count = 1;
    @endphp
    <thead>
        <tr>
            <td colspan="8" style="text-align: center">Usuarios</td>
        </tr>
        <tr>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">N°</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Nombre</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Cargo</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Dependencia</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Correo</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Roles</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Estado</td>
        </tr>
        <tr></tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $user)
            <tr>
                <td style="text-align: center;border:1px solid black">{!! $count !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $user['name'] !!}</td>
                <td style="text-align: center;border:1px solid black">{!! !empty($user['positions']) ? $user['positions']['nombre'] : '' !!}</td>
                <td style="text-align: center;border:1px solid black">{!! !empty($user['dependencies']) ? implode(', ', array_column($user["dependencies"],'nombre')) : '' !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $user['email'] !!}</td>
                <td style="text-align: center;border:1px solid black">{!! count($user['roles']) > 0 ? implode(', ', array_column($user["roles"],'name')) : " " !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $user['status'] !!}</td>
            </tr>
            @php
                $count = $count + 1;
            @endphp
        @endforeach
    </tbody>
</table>
