<table border="1">
    <thead>
        <tr>
            <td colspan="7" style="font-size: 20px; background: #12E54F;"><h1>Información de las necesidades</h1></td>
 
        </tr>
        <tr>
            <td style="background: #BDBDBD; font-size:14px;">Consecutivo</td>
            <td style="background: #BDBDBD; font-size:14px;">Fecha de creación</td>
            <td style="background: #BDBDBD; font-size:14px;">Tipo solicitud</td>
            {{-- <td style="background: #BDBDBD; font-size:14px;">Tipo de necesidad</td> --}}
            <td style="background: #BDBDBD; font-size:14px;">Nombre del proveedor</td>
            <td style="background: #BDBDBD; font-size:14px;">Número del contrato</td>
            <td style="background: #BDBDBD; font-size:14px;">Objeto de contrato</td>
            <td style="background: #BDBDBD; font-size:14px;">Nombre del rubro</td>
            <td style="background: #BDBDBD; font-size:14px;">Valor de la solicitud</td>
            <td style="background: #BDBDBD; font-size:14px;">Estado</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
          <tr>            
             <td>{!! $item["consecutivo"] ?? "" !!}</td>
             <td>{!! $item["created_at"] !!}</td>
             <td>{!! $item["tipo_solicitud"] == "Inventario" ? "Compra/Almacén" : $item["tipo_solicitud"] !!}</td>
             {{-- <td>{!! $item["tipo_necesidad"] !!}</td> --}}
             <td>{!! !empty($item["contrato_datos"]) ? $item["contrato_datos"]->providers->name : "No aplica" !!}</td>
             <td>{!! !empty($item["contrato_datos"]) ? $item["contrato_datos"]->contract_number : "No aplica" !!}</td>
             <td>{!! !empty($item["contrato_datos"]) ? $item["contrato_datos"]->object : "No aplica" !!}</td>
             <td>{!! !empty($item["contrato_datos"]) ? $item["contrato_datos"]->mant_budget_assignation[0]->mant_administration_cost_items[0]->name : "No aplica" !!}</td>
             <td>{!! $item["valor_disponible"] !!}</td>
             <td>{!! $item["estado"] !!}</td>
          </tr> 
        @endforeach
    </tbody>
 </table>