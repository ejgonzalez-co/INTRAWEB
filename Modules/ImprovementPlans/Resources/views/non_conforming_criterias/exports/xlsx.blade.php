<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta id="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de las oportunidades de mejora</title>
</head>

<body>
    <thead>
        <tr>
            <td>Nombre de la oportunidad de mejora</td>
            <td>Observación</td>
            <td>Peso del criterio en el plan</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $criteria)
            <tr>
                <td> {!! $criteria['name_opportunity_improvement'] !!}</td>
                <td> {!! $criteria['description_cause_analysis'] !!}</td>
                <td> {!! $criteria['weight'] ?? 'N/E' !!}</td>
            </tr>
        @endforeach
    </tbody>
</body>

</html>
