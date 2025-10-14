<table border="1">
    <tr style="margin: 5px">
        <td>Valor contratado por contrato</td>
    </tr>

    <thead>
        <tr>
            <td>Fecha de creación</td>
            <td>Proceso</td>
            <td>Número de contrato</td>
            <td>Objeto</td>
            <td>Valor CDP</td>
            <td>Valor contrato</td>
        </tr>
    </thead>
    <tbody>
        @php
            $dataRequest = array_values($data);
            unset($dataRequest[count($dataRequest)-1]);
            $suma = 0;
        @endphp
        @foreach ($dataRequest as $key => $item)
            <tr>
                @php
                $dateCreatecreport = date("Y-m-d");
                @endphp
            <td>{{ $dateCreatecreport }}</td>
                <td>{!! $item['dependencias']['nombre'] !!}</td>
                <td>{!! $item['contract_number'] !!}</td>
                <td>{!! $item['object'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['value_cdp'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['value_contract'] !!}</td>
            </tr>
            @php
                $suma += $item['mant_budget_assignation'][0]['value_contract'];
            @endphp
        @endforeach
        <tr>
            <td><strong><h6>Valor contratado en general</h6></strong></td>
            <td colspan="5">{{ $suma }}</td>
        </tr>
    </tbody>
</table>
