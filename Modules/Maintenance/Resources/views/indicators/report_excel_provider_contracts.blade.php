<table border="1">
    <tr style="margin: 5px">
        <td> Ejecución general presupuestal de los contratos </td>
    </tr>

    <thead>
        <tr>
            <td>Fecha de creación</td>
            <td>Proceso</td>
            <td>Número de contrato</td>
            <td>Objeto</td>
            <td>Valor CDP</td>
            <td>Valor contrato</td>
            <td>Nombre del rubro</td>
            <td>Código del rubro</td>
            <td>Nombre del centro de costos</td>
            <td>Código del centro de costos</td>
            <td>Valor del rubro</td>
            <td>Valor total ejecutado</td>
            <td>Valor disponible</td>
            <td>Porcentaje de ejecución</td>
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
                <td>{!! $item['start_date'] !!}</td>
                <td>{!! $item['dependencias']['nombre'] !!}</td>
                <td>{!! $item['contract_number'] !!}</td>
                <td>{!! $item['object'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['value_cdp'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['value_contract'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['name'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['code_cost'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['cost_center_name'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['cost_center'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['value_item'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['total_value_executed'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['value_avaible'] !!}</td>
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['total_percentage_executed'] !!}%</td>
                @if ($item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'] == null)
                    <td>No aplica</td>
                @else
                    <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['minutes'] ? $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['minutes']: 'No aplica' !!}</td>
                @endif
                @if ($item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'] == null)
                    <td>No aplica</td>
                @else
                    <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['date'] ? $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['date']: 'No aplica' !!}</td>
                @endif
                @if ($item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'] == null)
                    <td>No aplica</td>
                @else
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['executed_value'] ? $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['executed_value']: 'No aplica' !!}</td>
                @endif
                @if ($item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'] == null)
                    <td>No aplica</td>
                @else
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['new_value_available'] ? $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['new_value_available']: 'No aplica' !!}</td>
                @endif
                @if ($item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'] == null)
                    <td>No aplica</td>
                @else
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['percentage_execution_item'] ? $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['percentage_execution_item']: 'No aplica' !!}%</td>
                @endif
                @if ($item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'] == null)
                    <td>No aplica</td>
                @else
                    <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['observation'] ? $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['observation']: 'No aplica' !!}</td>
                @endif
                @if ($item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'] == null)
                    <td>No aplica</td>
                @else
                <td>{!! $item['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['mant_budget_executions'][0]['name_user'] !!}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
