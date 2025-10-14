<table border="1">
    <tr style="margin: 5px">
        <td> Rubros</td>
    </tr>
    <tr>
        <td>Fecha de creación</td>
        <td>Número de contrato</td>
        <td>Objeto</td>
        <td>Valor CDP</td>
        <td>Valor contrato</td>
    </tr>
    <tr>
        <td>{!! $data[0]->mant_budget_assignation->mant_provider_contract->created_at !!}</td>
        <td>{!! $data[0]->mant_budget_assignation->mant_provider_contract->contract_number !!}</td>
        <td>{!! $data[0]->mant_budget_assignation->mant_provider_contract->object !!}</td>
        <td>${!! $data[0]->mant_budget_assignation->value_cdp !!}</td>
        <td>${!! $data[0]->mant_budget_assignation->value_contract !!}</td>
    </tr>
    <tr></tr>
    <thead>
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
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item->name !!}</td>
                <td>{!! $item->code_cost !!}</td>
                <td>{!! $item->cost_center_name !!}</td>
                <td>{!! $item->cost_center !!}</td>
                <td>${!! $item->value_item !!}</td>
                <td>${!! $item->total_value_executed !!}</td>
                <td>${!! $item->value_avaible !!}</td>
                <td>{!! $item->total_percentage_executed !!}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
