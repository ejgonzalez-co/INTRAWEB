
    <table>
    <tbody>
      <tr>
        <td style="text-align: center"  > <strong> FECHA</strong></td>
        <td style="text-align: center"  > <strong> TIPO</strong></td>
        <td style="text-align: center"  > <strong> ESTADO</strong></td>
        <td style="text-align: center"  > <strong> NECESIDAD</strong></td>
        <td style="text-align: center"  > <strong> DESCRIPCIÓN DE LA NECESIDA</strong></td>
        <td style="text-align: center"  > <strong> PLANTEAMIENTO TÉCNICO DE SOLUCIÓN</strong></td>
        <td style="text-align: center"  > <strong> MODALIDAD DEL CONTRATO A CELEBRAR</strong></td>
      </tr>
      @foreach ($data as $key => $item)
      <tr> 
        <td>{!! htmlentities( $item['created_at'] ? $item['created_at'] : '' ) !!}</td>
        <td>{!! htmlentities( $item['type'] ? $item['type'] : '' ) !!}</td>
        <td>{!! $item['state'] === 1 ? "En elaboración" :
          ($item['state'] === 2 ? 'En revisión por parte de Asistente de gerencia' :
          ($item['state'] === 3 ? 'Verificación de la ficha en Planeación corporativa' :
          ($item['state'] === 4 ? 'Gestionando CDP por parte de presupuesto' :
          ($item['state'] === 5 ? 'Gestionando Plan Anual de Adquisiciones por parte de Gestión de Recursos' :
          ($item['state'] === 6 ? 'Asignación de abogado' :
          ($item['state'] === 7 ? 'Gestionando Reglas por parte de Jurídica' :
          ($item['state'] === 8 ? 'Gestionando invitación por parte de Asistente de Gerencia' :
          ($item['state'] === 9 ? 'Invitación generada' :
          ($item['state'] === 10 ? 'Revisión de jurídica con expediente' :
          ($item['state'] === 11 ? 'Evaluando propuestas' :
          ($item['state'] === 12 ? 'Elaborando minuta del contrato' :
          ($item['state'] === 13 ? 'Revisando minuta del contrato' :
          ($item['state'] === 14 ? 'Revisión y firma del contrato' :
          ($item['state'] === 15 ? 'Contrato legalizado' :
          ($item['state'] === 17 ? 'Devuelto al líder del proceso para mejoras' :
          ($item['state'] === 18 ? 'Mejorando observaciones hechas a la minuta' :
          ($item['state'] === 19 ? 'Estudio previo desierto' :
          ($item['state'] === 20 ? 'Revisando plan anual de adquisiciones' :
          ($item['state'] === 21 ? 'Evaluando las propuestas de todos' :
          ($item['state'] === 22 ? 'Pendiente de visto bueno' :
          ($item['state'] === 23 ? 'Finalizado' :
          ($item['state'] === 24 ? 'Devolución de propuesta' :
          ($item['state'] === 25 ? 'Solicitando CRP' :
          ($item['state'] === 26 ? 'Generando CRP' : ''
          )))))))))))))))))))))))) !!}</td>
        <td>{!! htmlentities( $item['project_or_needs'] ? $item['project_or_needs'] : '' ) !!}</td>
        <td>{!! htmlentities( $item['justification_tecnic_description'] ? $item['justification_tecnic_description'] : '' ) !!}</td>
        <td>{!! htmlentities( $item['justification_tecnic_approach'] ? $item['justification_tecnic_approach'] : '' ) !!}</td>
        <td>{!! htmlentities( $item['justification_tecnic_modality'] ? $item['justification_tecnic_modality'] : '' ) !!}</td>
      </tr>
      @endforeach
    </tbody>
    </table>