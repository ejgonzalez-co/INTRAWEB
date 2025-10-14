<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestión de mantenimientos</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>FECHA DEL REGISTRO</td>
                <td>NOMBRE ACTIVO</td>
                <td>PLACA</td>
                <td>INVENTARIO</td>
                <td>UNIDAD DE MEDIDA</td>
                <td>TIPO DE MANTENIMIENTO</td>
                <td>KILOMETRAJE ACTUAL</td>
                <td>KILOMETRAJE RECIBIDO POR EL PROVEEDOR</td>
                <td>NOMBRE DEL PROVEEDOR</td>
                <td>NÚMERO DE SALIDA DEL ALMACEN</td>
                <td>NÚMERO DE FACTURA</td>
                <td>NÚMERO DE SOLICITUD</td>
                <td>ACTIVIDAD</td>
                <td>REPUESTO</td>
                <td>CANTIDAD</td>
                <td>VALOR TOTAL</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $asset_management)
                <tr>
                    <td>{!! $asset_management['created_at'] ? $asset_management['created_at'] : "No aplica" !!}</td>
                    <td>{!! $asset_management['nombre_activo'] != "No aplica" ? explode(" ",$asset_management['nombre_activo'])[0] : "No aplica" !!}</td>
                    <td>{!! $asset_management['nombre_activo'] != "No aplica" ? explode(" ",$asset_management['nombre_activo'])[1] : "No aplica" !!}</td>
                    <td>{!! $asset_management['inventory_number'] ? $asset_management['inventory_number'] : "No aplica" !!}</td>
                    <td>{!! $asset_management['unidad_medida'] ? $asset_management['unidad_medida'] : "No aplica" !!}</td>
                    <td>{!! $asset_management['tipo_mantenimiento'] ? $asset_management['tipo_mantenimiento'] : "No aplica" !!}</td>
                    <td>{!! $asset_management['kilometraje_actual'] ? $asset_management['kilometraje_actual'] : "No aplica" !!}</td>
                    <td>{!! $asset_management['kilometraje_recibido_proveedor'] ? $asset_management['kilometraje_recibido_proveedor'] : "No aplica" !!}</td>
                    <td>
                        {!! 
                            !is_null($asset_management['nombre_proveedor']) && 
                            $asset_management['nombre_proveedor'] != 'No aplica' && 
                            count(explode(" - ", $asset_management['nombre_proveedor'])) > 2 
                                ? explode(" - ", $asset_management['nombre_proveedor'])[2] 
                                : "No aplica" 
                        !!}
                    </td>
                    <td>{!! $asset_management['request_need'] ? ($asset_management['request_need']->tipo_solicitud == 'Activo' ? "Salida automatica" : $asset_management['no_salida_almacen']) : $asset_management['no_salida_almacen'] !!}</td>
                    <td>{!! $asset_management['request_need'] ? $asset_management['request_need']->invoice_no : "No aplica" !!}</td>
                    <td>{!! $asset_management['no_solicitud'] ? $asset_management['no_solicitud'] : "No aplica" !!}</td>
                    {{-- <td>{!! $asset_management['actividad'] ? !empty($asset_management['actividades']) || !empty($asset_management['repuestos']) ? implode(",",array_column($asset_management['actividades'],"descripcion_nombre")) : implode(",",array_column($asset_management['repuestos'],"descripcion_nombre")) : "" !!}</td>
                    <td>{!! $asset_management['repuesto'] ? implode(",",array_column($asset_management['repuestos'],"cantidad_solicitada")) : "No aplica" !!}</td>
                    <td>{!! $asset_management['repuesto'] ? implode(",",array_column($asset_management['repuestos'],"valor_total")) : "No aplica" !!}</td> --}}
                    @if (!is_null($asset_management['repuesto']))
                        <td>{!! "No aplica" !!}</td>
                        <td>{!! !empty($asset_management['repuestos']) ? $asset_management['repuestos']->descripcion_nombre : "No aplica" !!}</td>
                        <td>{!! $asset_management['repuesto'] ? $asset_management['repuestos']->cantidad_solicitada : "No aplica" !!}</td>
                        <td>{!! $asset_management['repuesto'] ? $asset_management['repuestos']->valor_total : "No aplica" !!}</td>
                    @else
                        <td>{!! !empty($asset_management['actividades']) ? $asset_management['actividades']->descripcion_nombre : "No aplica" !!}</td>
                        <td>{!! "No aplica" !!}</td>
                        <td>{!! $asset_management['actividad'] ? $asset_management['actividades']->cantidad_solicitada : "No aplica" !!}</td>
                        <td>{!! $asset_management['actividad'] ? $asset_management['actividades']->valor_total : "No aplica" !!}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
