<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta id="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte todas las oportunidades de mejora</title>
</head>

<body>
    <thead>
        <tr>
            <td>Fecha de creación</td>
            <td>Fuente de información</td>
            <td>Criterio de evaluación</td>
            <td>Tipo de oportunidad de mejora</td>
            <td>Nombre de la oportunidad de mejora</td>
            <td>Descripción de la oportunidad de mejora o no conformidad</td>
            <td>Dependencia o proceso responsable de la oportunidad de mejora</td>
            <td>Funcionario responsable de la dependencia</td>
            <td>Fecha límite de presentación del plan de mejoramiento</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $improvement_opportunity)
            <tr>
                <td> {!! $improvement_opportunity['created_at'] ?? '' !!}</td>
                <td> {!! $improvement_opportunity['source_information']['name'] ?? 'N/E' !!}</td>
                <td> {!! $improvement_opportunity['evaluation_criteria'] ?? 'N/E' !!}</td>
                <td> {!! $improvement_opportunity['type_oportunity_improvements']['name'] ?? 'N/E' !!}</td>
                <td> {!! $improvement_opportunity['name_opportunity_improvement'] ?? '' !!}</td>
                <td> {!! $improvement_opportunity['description_opportunity_improvement'] ?? 'N/E' !!}</td>
                <td> {!! $improvement_opportunity['unit_responsible_improvement_opportunity'] ?? '' !!}</td>

                <td> {!! $improvement_opportunity['official_responsible'] ?? '' !!}</td>
                <td> {!! $improvement_opportunity['deadline_submission'] ?? '' !!}</td>
            </tr>
        @endforeach
    </tbody>
</body>

</html>
