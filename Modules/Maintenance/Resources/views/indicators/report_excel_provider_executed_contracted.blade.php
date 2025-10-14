<table border="1">
    <tr style="margin: 5px">
        <td>Valor ejecutado del contrato </td>
    </tr>

    <thead>
        <tr>
            <td>Fecha de creación</td>
            <td>Proceso</td>
            <td>Número de contrato</td>
            <td>Objeto</td>
            <td>Valor CDP</td>
            <td>Valor contrato</td>
            <td>Valor ejecutado</td>
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
                <td>{!! $item['total_executed'] !!}</td>
            </tr>
            @php
                $suma += $item['total_executed'];
            @endphp
        @endforeach
        <tr>
            <td><strong><h6>Valor ejecutado del contrato</h6></strong></td>
            <td colspan="6">{{ $suma }}</td>
        </tr>
    </tbody>
</table>
