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
            <th class="tg-c3ow" colspan="8"><span style="font-weight:bold">PLAN DE MEJORAMIENTO DE GESTIÓN</span></th>
        </tr>
    </thead>
    <tbody>
        <tr style="background-color: green;">
            <td class="tg-fpwh" colspan="8" rowspan="2"><span
                    style="background-color:#32cb00;border-color:inherit;text-align:center;vertical-align:top;font-weight:bold ">1.
                    Información de la evaluación</span></td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td class="tg-0pky" colspan="8"></td>
        </tr>
        @foreach ($data as $key => $evaluation)
            <tr>
                <td class="tg-y698" colspan="2">Tipo de evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['type_evaluation'] !!}</td>
                <td class="tg-y698" colspan="2">Nombre de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluation_name'] !!}</td>
            </tr>
            <tr>
                <td class="tg-y698" colspan="2">Objetivo de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['objective_evaluation'] !!}</td>
                <td class="tg-y698" colspan="2">Alcance de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluation_scope'] !!}</td>
            </tr>
            <tr>
                <td class="tg-y698" colspan="2">Lugar de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluation_site'] !!}</td>
                <td class="tg-y698" colspan="2">Criterios de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! implode(', ', array_column($evaluation['evaluation_criteria'], 'criteria_name')) !!}</td>
            </tr>
            <tr>
                <td class="tg-y698" colspan="2">Fecha de inicio de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluation_start_date'] !!}</td>
                <td class="tg-y698" colspan="2">Hora de inicio de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluation_start_time'] !!}</td>
            </tr>
            <tr>
                <td class="tg-y698" colspan="2">Fecha de finalización de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluation_end_date'] !!}</td>
                <td class="tg-y698" colspan="2">Hora de finalización de la evaluación</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluation_end_time'] !!}</td>
            </tr>
            <tr>
                <td class="tg-y698" colspan="2">Dependencia interna o entidad externa responsable de la evaluación
                </td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['unit_responsible_for_evaluation'] !!}</td>
                <td class="tg-y698" colspan="2">Funcionario designado de la dependencia o entidad evaluadora</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluator']['name'] !!}</td>
            </tr>
            <tr>
                <td class="tg-y698" colspan="2">Dependencia/s a evaluar</td>
                <td class="tg-0pky" colspan="2">{!! implode(', ', array_column($evaluation['evaluation_dependences'], 'dependence_name')) !!}</td>
                <td class="tg-y698" colspan="2">Proceso</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['process'] !!}</td>
            </tr>
            <tr>
                <td class="tg-y698" colspan="2">Responsable de la dependencia evaluado</td>
                <td class="tg-0pky" colspan="2">{!! $evaluation['evaluated']['name'] !!}</td>
                <td class="tg-y698" colspan="2"></td>
                <td class="tg-0pky" colspan="2"></td>
            </tr>
            <tr>
                <td class="tg-0pky" colspan="8"></td>
            </tr>
            <tr>
                <td class="tg-fpwh" colspan="8" rowspan="2"><span style="font-weight:bold">2. Resultados de la
                        evaluación</span></td>
            </tr>
            <tr>
            </tr>
            @foreach ($evaluation['evaluation_improvement_opportunities'] as $evaluationImprovementOpportunity)
                <tr>
                    <td class="tg-0pky" colspan="8"></td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Fuente de información</td>
                    <td class="tg-0pky" colspan="2">{!! $evaluationImprovementOpportunity['source_information']['name'] !!}</td>
                    <td class="tg-y698" colspan="2">Tipo de oportunidad de mejora o no conformidad</td>
                    <td class="tg-0pky" colspan="2">{!! $evaluationImprovementOpportunity['type_oportunity_improvements']['name'] !!}</td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Nombre de la oportunidad de mejorao no conformidad</td>
                    <td class="tg-0pky" colspan="2">{!! $evaluationImprovementOpportunity['name_opportunity_improvement'] !!}</td>
                    <td class="tg-y698" colspan="2">Descripción de la oportunidad de mejora o no conformidad</td>
                    <td class="tg-0pky" colspan="2">{!! $evaluationImprovementOpportunity['description_opportunity_improvement'] !!}</td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Fecha límite de presentación del plan de mejoramiento</td>
                    <td class="tg-0pky" colspan="2">{!! $evaluationImprovementOpportunity['deadline_submission'] !!}</td>
                    <td class="tg-y698" colspan="2"></td>
                    <td class="tg-0pky" colspan="2"></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="8"></td>
                </tr>
            @endforeach
            <tr>
                <td class="tg-fpwh" colspan="8" rowspan="2"><span style="font-weight:bold">3. Diseño Plan de
                        mejoramiento</span></td>
            </tr>
            @foreach ($evaluation['goals'] as $goal)
                <tr>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Tipo de meta</td>
                    <td class="tg-0pky" colspan="2">{!! $goal['goal_type'] !!}</td>
                    <td class="tg-y698" colspan="2">Nombre de la meta</td>
                    <td class="tg-0pky" colspan="2">{!! $goal['goal_name'] !!}</td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Peso de la meta</td>
                    <td class="tg-0pky" colspan="2">{!! $goal['goal_weight'] . '%' !!}</td>
                    <td class="tg-y698" colspan="2">Descripción del indicador</td>
                    <td class="tg-0pky" colspan="2">{!! $goal['indicator_description'] !!}</td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Fecha de cumplimiento de la meta</td>
                    <td class="tg-0pky" colspan="2">{!! $goal['commitment_date'] !!}</td>
                    <td class="tg-y698" colspan="2"></td>
                    <td class="tg-0pky" colspan="2"></td>
                </tr>
                @foreach ($goal['goal_activities'] as $goalActivity)
                    <tr>
                        <td class="tg-34fe" colspan="8"><span style="font-weight:bold">Actividades que componen la
                                meta
                                del Plan de mejoramiento</span></td>
                    </tr>
                    <tr>
                        <td class="tg-y698" colspan="2">Nombre de la actividad</td>
                        <td class="tg-0pky" colspan="2">{!! $goalActivity['activity_name'] !!}</td>
                        <td class="tg-y698" colspan="2">Cantidad</td>
                        <td class="tg-0pky" colspan="2">{!! $goalActivity['activity_quantity'] !!}</td>
                    </tr>
                    <tr>
                        <td class="tg-y698" colspan="2">Peso de la actividad</td>
                        <td class="tg-0pky" colspan="2">{!! $goalActivity['activity_weigth'] . '%' !!}</td>
                        <td class="tg-y698" colspan="2">Línea base para la meta</td>
                        <td class="tg-0pky" colspan="2">{!! $goalActivity['baseline_for_goal'] !!}</td>
                    </tr>
                    <tr>
                        <td class="tg-y698" colspan="2">Brecha para el cumplimiento de la meta</td>
                        <td class="tg-0pky" colspan="2">{!! $goalActivity['gap_meet_goal'] !!}</td>
                        <td class="tg-y698" colspan="2">Dependencias que participaran en el cumplimiento de la meta
                        </td>
                        <td class="tg-0pky" colspan="2">{!! implode(', ', array_column($goal['goal_dependencies'], 'dependence_name')) !!}</td>
                    </tr>
                    <tr>
                        <td class="tg-0pky" colspan="8"></td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td class="tg-fpwh" colspan="8" rowspan="2"><span style="font-weight:bold">4. Avances y
                        evidencias</span></td>
            </tr>
            @foreach ($evaluation['goals_progress'] as $goalProgress)
                <tr>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="8"></td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Nombre de la meta</td>
                    <td class="tg-0pky" colspan="2">{!! $goalProgress['goals']["goal_weight"] !!}</td>
                    <td class="tg-y698" colspan="2">Actividad</td>
                    <td class="tg-0pky" colspan="2">{!! $goalProgress["goal_activities"]['activity_name'] !!}</td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Peso de la actividad</td>
                    <td class="tg-0pky" colspan="2">{!! $goalProgress['activity_weigth'] !!}</td>
                    <td class="tg-y698" colspan="2">Peso del avance</td>
                    <td class="tg-0pky" colspan="2">{!! $goalProgress['progress_weigth'] !!}</td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Descripción de la evidencia</td>
                    <td class="tg-0pky" colspan="2">{!! $goalProgress['evidence_description'] !!}</td>
                    <td class="tg-y698" colspan="2"></td>
                    <td class="tg-0pky" colspan="2"></td>
                </tr>
                <tr>
                    <td class="tg-y698" colspan="2">Estado del avance</td>
                    <td class="tg-0pky" colspan="2">{!! $goalProgress['status'] !!}</td>
                    <td class="tg-y698" colspan="2">Observación del estado del avance</td>
                    <td class="tg-0pky" colspan="2"></td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
