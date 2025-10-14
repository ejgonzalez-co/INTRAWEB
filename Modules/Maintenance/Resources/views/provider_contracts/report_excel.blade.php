<table border="1">
    <tr style="margin: 5px">
        <td>Información general del contrato</td>
    </tr>
    <thead>
        <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE LOS CONTRATOS DE LOS PROVEEDORES</td>
       </tr> -->
        <tr>
            <td>Proveedor</td>
            <td>Identificación(proveedor)</td>
            <td>Municipio(proveedor)</td>
            <td>Departamento(proveedor)</td>
            <td>Teléfono(proveedor)</td>
            <td>Tipo de contrato</td>
            <td>Número de contrato</td>
            <td>Fecha de acta de inicio</td>
            <td>Tiempo de ejecución</td>
            <td>Régimen</td>
            <td>Valor del CDP</td>
            <td>Valor del contrato</td>
            <td>Valor disponible CDP</td>
            <td>Porcentaje ejecutado</td>
            <td>Total ejecutado</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['providers'] ? $item['providers']->name : '' !!}</td>
                <td>{!! $item['providers'] ? $item['providers']->identification : '' !!}</td>
                <td>{!! $item['providers'] ? $item['providers']->municipality : '' !!}</td>
                <td>{!! $item['providers'] ? $item['providers']->department : '' !!}</td>
                <td>{!! $item['providers'] ? $item['providers']->phone : '' !!}</td>
                <td>{!! $item['type_contract'] !!}</td>
                <td>{!! $item['contract_number'] !!}</td>
                <td>{!! $item['start_date'] !!}</td>
                <td>{!! $item['execution_time'] !!}</td>
                <td>{!! $item['providers'] ? $item['providers']->regime : '' !!}</td>
                <td>${!! $item['total_value_cdp'] !!}</td>
                <td>${!! $item['total_value_contract'] !!}</td>
                <td>${!! $item['total_value_avaible_cdp'] !!}</td>
                <td>{!! $item['total_percentage'] !!}%</td>
                <td>${!! $item['total_executed'] !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
