<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registros en el stock</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>Código</td>
                <td>Artículo</td>
                <td>Grupo</td>
                <td>Cantidad</td>
                <td>Costo Unitario</td>
                <td>Total</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $stock)
                <tr>
                    <td>{!! $stock['codigo'] !!}</td>
                    <td>{!! $stock['articulo'] !!}</td>
                    <td>{!! $stock['grupo'] !!}</td>
                    <td>{!! $stock['cantidad'] !!}</td>
                    <td>{!! $stock['unit_cost'] !!}</td>
                    <td>{!! $stock['cantidad'] * $stock['unit_cost'] !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
