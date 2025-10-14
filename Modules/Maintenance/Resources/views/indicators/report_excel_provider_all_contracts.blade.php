<table border="1">
    @php

    $suma = 0;
    @endphp

    @foreach ($data as $key => $item)


    

    <tr style="margin: 5px">
        <td style="font-size: 18px"><strong>Ejecución presupuestal</strong></td>
    </tr>


    <tr>
        <td style="font-size: 14px; background: #0b802c;"><strong> Fecha de creación</strong></td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Número de contrato</strong></td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Objeto</strong></td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Valor CDP</strong></td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Valor contrato</strong></td>
    </tr>
    <tr>
        <td>{!! $item['created_at'] ?? null !!}</td>
        <td>{!! $item['contract_number'] ?? null !!}</td>
        <td>{!! $item['object'] ?? null !!}</td>
        <td>${!! $item['mant_budget_assignation'][0]['value_cdp'] ?? null!!}</td>
        <td>${!! $item['mant_budget_assignation'][0]['value_contract'] ?? null !!}</td>
    </tr>
    <tr></tr>
    
    <tr>
        <td style="font-size: 14px; background: #0d9b35;"><strong>Nombre del rubro</strong> </td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Código del rubro</strong> </td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Nombre del centro de costos</strong> </td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Código del centro de costos</strong> </td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Valor del rubro</strong> </td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Valor total ejecutado</strong> </td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Valor disponible</strong> </td>
        <td style="font-size: 14px; background: #0b802c;"><strong>Porcentaje de ejecución</strong> </td>
    </tr>
@if (!empty($item['mant_budget_assignation']))
    @foreach ($item['mant_budget_assignation'] as $value)
    
    @if (!empty($value['mant_administration_cost_items']))
        @foreach ($value['mant_administration_cost_items'] as $rubros)
        <tr>
            <td>{!! $rubros['name'] ?? null !!}</td>
            <td>{!! $rubros['code_cost'] ?? null !!}</td>
            <td>{!! $rubros['cost_center_name'] ?? null !!}</td>
            <td>{!! $rubros['cost_center'] ?? null !!}</td>
            <td>${!! $rubros['value_item'] ?? null !!}</td>
            <td>${!! $rubros['total_value_executed'] ?? null !!}</td>
            <td>${!! $rubros['value_avaible'] ?? null !!}</td>
            <td>{!! $rubros['total_percentage_executed'] ?? null !!}%</td>
        </tr>

        
        @endforeach
    @endif
    @endforeach
@endif
<tr></tr>
<tr>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Fecha de creación</strong> </td>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Acta</strong></td>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Fecha del acta</strong></td>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Valor ejecutado</strong></td>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Nuevo valor disponible</strong></td>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Porcentaje de ejecución</strong></td>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Observación</strong></td>
    <td style="font-size: 14px; background: #0d9b35;"><strong>Nombre de usuario</strong></td>


</tr>

    @if (!empty($item['mant_budget_assignation']))
        @foreach ($item['mant_budget_assignation'] as $value)
        
        @if (!empty($value['mant_administration_cost_items']))
            @foreach ($value['mant_administration_cost_items'] as $rubros)

            @if (!empty($rubros['mant_budget_executions']))
                @foreach ($rubros['mant_budget_executions'] as $execute)
                <tr>
                    <td>{!! $execute['created_at'] !!}</td>
                    <td>{!! $execute['minutes'] !!}</td>
                    <td>{!! $execute['date'] !!}</td>
                    <td>${!! $execute['executed_value'] !!}</td>
                    <td>${!! $execute['new_value_available'] !!}</td>
                    <td>{!! $execute['percentage_execution_item'] !!}%</td>
                    <td>{!! $execute['observation'] !!}</td>
                    <td>{!! $execute['name_user'] !!}</td>
    
                    
                </tr>

                
                @endforeach
                
            @endif
            @php
            $suma += $rubros['total_value_executed'];
            @endphp
            @endforeach
        @endif
        @endforeach
    @endif  
    <tr></tr>
    <tr>
        <td style="font-size: 14px; background: #0d9b35;"><strong>Porcentaje de ejecución</strong> </td>
    </tr>
    <tr>
        <td>{!! $item['total_percentage'] ?? null !!} %</td>
    </tr>
        

 
    <tr></tr>
    <tr></tr>
    <tr></tr> 
    <tr></tr>       
    @endforeach
    <tbody>
        <tr>
            <td style="font-size: 14px; background: #0d9b35;"><strong>Ejecución presupuestal general</strong> </td>
        </tr>
        <tr>
            <td>{!! $suma !!}</td>
        </tr>
    </tbody>

</table>
