<table border="1">
    <tr style="margin: 5px">
        <td> Asignación presupuestal y novedades</td>
    </tr>
    <tr>
        <td>Fecha de creación</td>
        <td>Número de contrato</td>
        <td>Objeto</td>
        <td>Valor CDP</td>
        <td>Valor contrato</td>
    </tr>
    <tr>

        <td>{!! $data[0]->mant_provider_contract->created_at !!}</td>
        <td>{!! $data[0]->mant_provider_contract->contract_number !!}</td>
        <td>{!! $data[0]->mant_provider_contract->object !!}</td>
        <td>${!! $data[0]->value_cdp !!}</td>
        <td>${!! $data[0]->value_contract !!}</td>
    </tr>
    <tr></tr>
   
        <thead>
            <tr>
                <td>Fecha de creación</td>
                <td>Tipo de novedad</td>
                <td>Fecha de novedad</td>
                <td>Observación</td>
                <td>CDP modificado</td>
                <td>Contrato modificado</td>
                <td>Nombre de usuario</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data[0]->mant_provider_contract->mant_contract_new as $key => $item)
                <tr>
                    <td>{!! $item->created_at !!}</td>
                    <td>{!! $item->type_new !!}</td>
                    <td>{!! $item->date_new !!}</td>
                    <td>{!! $item->observation !!}</td>
                    <td>${!! $item->cdp_modify !!}</td>
                    <td>${!! $item->contract_modify !!}</td>
                    <td>{!! $item->name_user !!}</td>

                </tr>
            @endforeach
        </tbody>
</table>
