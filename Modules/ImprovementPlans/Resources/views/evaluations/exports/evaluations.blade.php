<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta id="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte todas las evaluaciones</title>
</head>

<body>
    <thead>
        <tr>
            <td>Fecha de creación</td>
            <td>Funcionario evaluador</td>
            <td>Funcionario evaluado</td>
            <td>Tipo de evaluación</td>
            <td>Nombre de la evaluación</td>
            <td>Lugar de la evaluación</td>
            <td>Criterios de evaluación</td>
            <td>Fecha inicial de la evaluación</td>
            <td>Hora inicial de la evaluación</td>
            <td>Fecha final de la evaluación</td>
            <td>Hora final de la evaluación</td>
            <td>Dependencias</td>
            <td>Proceso</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $evaluation)
            <tr>
                <td> {!! $evaluation['created_at'] !!}</td>
                <td> {!! $evaluation['evaluator']['name'] ?? 'N/E' !!}</td>
                <td> {!! $evaluation['evaluated']['name'] ?? 'N/E' !!}</td>
                <td> {!! $evaluation['type_evaluation'] !!}</td>
                <td> {!! $evaluation['evaluation_name'] ?? 'N/E' !!}</td>
                <td> {!! $evaluation['evaluation_site'] !!}</td>
                <td>
                    @if (isset($evaluation['EvaluationCriteria']))
                        @foreach ($evaluation['EvaluationCriteria'] as $evaluationCriteria)
                            {!! $evaluationCriteria['criteria_name'] . ',' !!}
                        @endforeach
                    @endif
                </td>
                <td> {!! $evaluation['evaluation_start_date'] !!}</td>
                <td> {!! $evaluation['evaluation_start_time'] !!}</td>
                <td> {!! $evaluation['evaluation_end_date'] !!}</td>
                <td> {!! $evaluation['evaluation_end_time'] !!}</td>
                <td>
                    @if (isset($evaluation['EvaluationDependences']))
                        @foreach ($evaluation['EvaluationDependences'] as $evaluationDependence)
                            {!! $evaluationDependence['dependence_name'] . ',' !!}
                        @endforeach
                    @endif
                </td>
                <td> {!! $evaluation['process'] !!}</td>
            </tr>
        @endforeach
    </tbody>
</body>

</html>
