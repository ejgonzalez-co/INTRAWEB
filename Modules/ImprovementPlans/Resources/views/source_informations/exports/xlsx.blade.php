<table border="1">
    @php
        $count = 1;
   @endphp
    <thead>
        <tr>
            <td colspan="3" style="text-align: center">Fuentes de información</td>
        </tr>
        <tr>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">N°</td>
            <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">Nombre</td>
            <td rowspan="2"style="background-color:#d8d8d8;text-align: center;">Estado</td>
        </tr>
        <tr></tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td style="text-align: center;border:1px solid black">{!! $count !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $item['name'] !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $item['status'] !!}</td>
            </tr>
            @php
                $count = $count+1;
            @endphp
        @endforeach
    </tbody>
</table>