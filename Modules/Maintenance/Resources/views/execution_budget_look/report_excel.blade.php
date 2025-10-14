<table border="1">
    <tr style="margin: 5px">
        <td> Ejecución presupuestal</td>
    </tr>

    <tr>
        <td>Fecha de creación</td>
        <td>Número de contrato</td>
        <td>Objeto</td>
        <td>Valor CDP</td>
        <td>Valor contrato</td>
    </tr>
    <tr>
        <td>{!! $data[0]['mant_administration_cost_items']['mant_budget_assignation']['mant_provider_contract']['created_at'] !!}</td>
        <td>{!! $data[0]['mant_administration_cost_items']['mant_budget_assignation']['mant_provider_contract']['contract_number'] !!}</td>
        <td>{!! $data[0]['mant_administration_cost_items']['mant_budget_assignation']['mant_provider_contract']['object'] !!}</td>
        <td>${!! $data[0]['mant_administration_cost_items']['mant_budget_assignation']['value_cdp'] !!}</td>
        <td>${!! $data[0]['mant_administration_cost_items']['mant_budget_assignation']['value_contract'] !!}</td>
    </tr>
    <tr></tr>
    
    <tr>
        <td>Nombre del rubro</td>
        <td>Código del rubro</td>
        <td>Nombre del centro de costos</td>
        <td>Código del centro de costos</td>
        <td>Valor del rubro</td>
        <td>Valor total ejecutado</td>
        <td>Valor disponible</td>
        <td>Porcentaje de ejecución</td>
    </tr>
    <tr>
        <td>{!! $data[0]['mant_administration_cost_items']['name'] !!}</td>
        <td>{!! $data[0]['mant_administration_cost_items']['code_cost'] !!}</td>
        <td>{!! $data[0]['mant_administration_cost_items']['cost_center_name'] !!}</td>
        <td>{!! $data[0]['mant_administration_cost_items']['cost_center'] !!}</td>
        <td>${!! $data[0]['mant_administration_cost_items']['value_item'] !!}</td>
        <td>${!! $data[0]['mant_administration_cost_items']['total_value_executed'] !!}</td>
        <td>${!! $data[0]['mant_administration_cost_items']['value_avaible'] !!}</td>
        <td>{!! $data[0]['mant_administration_cost_items']['total_percentage_executed'] !!}%</td>
    </tr>
    <tr></tr>
    <thead>
        <tr>
            <td>Fecha de creación</td>
            <td>Acta</td>
            <td>Fecha del acta</td>
            <td>Valor ejecutado</td>
            <td>Nuevo valor disponible</td>
            <td>Porcentaje de ejecución</td>
            <td>Observación</td>
            <td>Nombre de usuario</td>


        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['created_at'] !!}</td>
                <td>{!! $item['minutes'] !!}</td>
                <td>{!! $item['date'] !!}</td>
                <td>${!! $item['executed_value'] !!}</td>
                <td>${!! $item['new_value_available'] !!}</td>
                <td>{!! $item['percentage_execution_item'] !!}%</td>
                <td>{!! $item['observation'] !!}</td>
                <td>{!! $item['name_user'] !!}</td>

            </tr>
        @endforeach
    </tbody>
</table>
