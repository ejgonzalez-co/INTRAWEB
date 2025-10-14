<table border="1">
    <tr style="margin: 5px">
        <td>Información de actividades importadas</td>
    </tr>
    <thead>
        <!-- <tr>
        <td></td>
        <td colspan="8">REPORTE DE LOS CONTRATOS DE LOS PROVEEDORES</td>
        </tr> -->
        <tr>
            <td>Item</td>
            <td>Descripción</td>
            <td>Tipo</td>
            <td>Sistema</td>
            <td>Unidad de medida</td>
            <td>Cantidad</td>
            <td>Valor unitario</td>
            <td>Iva</td>
            <td>Valor total</td>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['item'] ?? 'N/A' !!}</td>
                <td>{!! $item['description'] ?? 'N/A' !!}</td>
                <td>{!! $item['type'] ?? 'N/A' !!}</td>
                <td>{!! $item['system'] ?? 'N/A' !!}</td>
                <td>{!! $item['unit_measurement'] ?? 'N/A' !!}</td>
                <td>{!! $item['quantity'] ?? 'N/A' !!}</td>
                <td>${!! $item['unit_value'] ?? 'N/A' !!}</td>
                <td>${!! $item['iva'] ?? 'N/A' !!}</td>                
                <td>${!! $item['total_value'] ?? 'N/A' !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
