<table border="1">
    <tr style="margin: 5px">
        <td>Información de repuestos importados</td>
    </tr>
    <thead>
        <!-- <tr>
        <td></td>
        <td colspan="8">REPORTE DE LOS CONTRATOS DE LOS PROVEEDORES</td>
        </tr> -->
        <tr>
            <td>Item</td>
            <td>Descripción</td>
            <td>Unidad de medida</td>
            <td>Valor unitario</td>
            <td>Iva</td>
            <td>Valor total</td>>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['item'] ?? 'N/A' !!}</td>
                <td>{!! $item['description'] ?? 'N/A' !!}</td>                
                <td>{!! $item['unit_measure'] ?? 'N/A' !!}</td>
                <td>${!! $item['unit_value'] ?? 'N/A' !!}</td>
                <td>{!! $item['iva'] ?? 'N/A' !!}</td>                
                <td>${!! $item['total_value'] ?? 'N/A' !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
