<style type="text/css">
    .tg {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .tg td {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg .tg-34fe {
        background-color: #c0c0c0;
        border-color: inherit;
        text-align: center;
        vertical-align: top
    }

    .tg .tg-fpwh {
        background-color: #32cb00;
        border-color: inherit;
        text-align: center;
        vertical-align: top
    }

    .tg .tg-c3ow {
        border-color: inherit;
        text-align: center;
        vertical-align: top
    }

    .tg .tg-0pky {
        border: 1px solid #000000;
        text-align: left;
        vertical-align: top
    }

    .tg .tg-y698 {
        background-color: #efefef;
        border-color: inherit;
        text-align: left;
        vertical-align: top
    }
</style>
<table class="tg">
    <thead>
        <tr>
            <th class="tg-c3ow" colspan="7"><span style="font-weight:bold; text-align: center;">EVALUACIÓN</span></th>
        </tr>
    </thead>
    <tbody>
        <tr style="background-color: green;">
            <td class="tg-fpwh">Tipo de evaluación</td>
            <td class="tg-fpwh">Nombre de evaluación</td>
            <td class="tg-fpwh">Funcionario evaluador</td>
            <td class="tg-fpwh">Dependencia o proceso evaluado</td>
            <td class="tg-fpwh">Funcionario evaluado</td>
            <td class="tg-fpwh">Fecha inicial</td>
            <td class="tg-fpwh">Fecha final</td>
        </tr>
        @foreach ($data[0]["evaluacion"] as $key => $evaluation)
            <tr>
                <td class="tg-0pky">{!! $evaluation['type_evaluation'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['evaluation_name'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['evaluator']['name'] !!}</td>
                <td class="tg-0pky">{!! implode(', ', array_column($evaluation['evaluation_dependences'], 'dependence_name')) !!}</td>
                <td class="tg-0pky">{!! $evaluation['evaluated']['name'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['evaluation_start_date'].' '.$evaluation['evaluation_start_time'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['evaluation_end_date'].' '.$evaluation['evaluation_end_time'] !!}</td>
            </tr>
        @endforeach
    </tbody>

    <tr><td></td></tr>

    <thead>
        <tr>
            <th class="tg-c3ow" colspan="5"><span style="font-weight:bold; text-align: center;">PLAN DE MEJORAMIENTO</span></th>
        </tr>
    </thead>
    <tbody>
        <tr style="background-color: green;">
            <td class="tg-fpwh">Nombre de evaluación</td>
            <td class="tg-fpwh">Funcionario evaluador</td>
            <td class="tg-fpwh">Funcionario evaluado</td>
            <td class="tg-fpwh">Estado</td>
            <td class="tg-fpwh">Porcentaje</td>
        </tr>
        @foreach ($data[0]["plan_mejoramiento"] as $key => $evaluation)
            <tr>
                <td class="tg-0pky">{!! $evaluation['evaluation_name'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['evaluator']['name'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['evaluated']['name'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['status_improvement_plan'] !!}</td>
                <td class="tg-0pky">{!! $evaluation['percentage_execution'] !!}</td>
            </tr>
        @endforeach
    </tbody>

    <tr><td></td></tr>

    <thead>
        <tr>
            <th class="tg-c3ow" colspan="12"><span style="font-weight:bold; text-align: center;">CIERRE FINAL DEL MISMO</span></th>
        </tr>
    </thead>
    <tbody>
        <tr style="background-color: green;">
            <td class="tg-fpwh">Nombre de la meta</td>
            <td class="tg-fpwh">Nombre del funcionario evaluado</td>
            <td class="tg-fpwh">Nombre de dependencias del funcionario evaluado</td>
            <td class="tg-fpwh" colspan="7">Avances y evidencias del plan de mejoramiento</td>
            <td class="tg-fpwh">Porcentaje de aprobación del avance</td>
        </tr>
        @foreach ($data[0]["cierre_final"] as $key => $goal)
            <tr>
                <td class="tg-0pky">{!! $goal['evaluation_name'] !!}</td>
                <td class="tg-0pky">{!! $goal['evaluator']['name'] !!}</td>
                <td class="tg-0pky">{!! $goal['evaluated']['name'] !!}</td>
                <td class="tg-0pky" colspan="7">
                    <table>
                        <tbody>
                            <tr>
                                <td class="tg-y698">Nombre de la meta</td>
                                <td class="tg-y698">Actividad</td>
                                <td class="tg-y698">Peso de la actividad</td>
                                <td class="tg-y698">Peso del avance</td>
                                <td class="tg-y698">Descripción de la evidencia</td>
                                <td class="tg-y698">Estado del avance</td>
                                <td class="tg-y698">Observación del estado del avance</td>
                            </tr>
                            @foreach ($data[0]["goals_progress"] as $key => $goal)
                                <tr>
                                    <td class="tg-0pky">{!! $goal['goals']["goal_weight"] !!}</td>
                                    <td class="tg-0pky">{!! $goal["goal_activities"]['activity_name'] !!}</td>
                                    <td class="tg-0pky">{!! $goal['activity_weigth'] !!}</td>
                                    <td class="tg-0pky">{!! $goal['progress_weigth'] !!}</td>
                                    <td class="tg-0pky">{!! $goal['evidence_description'] !!}</td>
                                    <td class="tg-0pky">{!! $goal['status'] !!}</td>
                                    <td class="tg-0pky"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td class="tg-0pky"></td>
            </tr>
        @endforeach
    </tbody>
</table>