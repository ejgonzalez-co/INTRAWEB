<table border="1">
    @php
        $count = 1;
    @endphp
    <thead>
        <tr>
            <td colspan="4" style="text-align: center">Tipos de planes de mejoramiento</td>
        </tr>
        <tr>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">N°</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Codigo</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Nombre</td>
            <td rowspan="2"style="background-color:#d8d8d8;text-align: center;">Estado</td>
        </tr>
        <tr></tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td style="text-align: center;border:1px solid black">{!! $count !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $item['code'] !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $item['name'] !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $item['status'] !!}</td>
            </tr>
            @php
                $count = $count + 1;
            @endphp
        @endforeach
    </tbody>
</table>
