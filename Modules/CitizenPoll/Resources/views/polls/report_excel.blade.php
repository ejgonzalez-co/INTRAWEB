<table border="1">
   <thead>
      <tr>
         <td>Fecha de creación</td>
         <td>Número de cuenta Para Consultas y pagos(Número de Matrícula de Su factura)</td>
         <td>Nombres y Apellidos</td>
         <td>Género</td>
         <td>Dirección de correo electrónico</td>
         <td>Dirección del predio</td>
         <td>Teléfono</td>
         <td>¿Que calidad de Suscriptor cumple?</td>
         <td>¿Empresas públicas de Armenia ESP, le presta el servicio de Acueducto?</td>
         <td>¿Cómo califica la prestación del servicio de acueducto y alcantarillado?</td>
         <td>¿Cómo considera usted la continuidad  en la prestación del servicio de acueducto, medido este en la cantidad de horas?</td>
         <td>¿Cómo califica usted la calidad del servicio público de aseo domiciliario (vías,
            separadores, parques, calles, otros) en su lugar de residencia?</td>
         <td>¿Cómo califica usted el servicio de recolección de residuos
            sólidos de acuerdo con los horarios y los días establecidos?</td>
         <td>En caso de haber reportado daños</td>
         <td>¿La oportunidad fue? (llegaron a tiempo)</td>
         <td>¿La efectividad fue? (Arreglaron bien el daño)</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['created_at'] !!}</td>
            <td>{!! $item['number_account'] !!}</td>
            <td>{!! $item['name'] !!}</td>
            <td>{!! $item['gender'] !!}</td>
            <td>{!! $item['email'] !!}</td> 
            <td>{!! $item['direction_state'] !!}</td>
            <td>{!! $item['phone'] !!}</td>
            <td>{!! $item['suscriber_quality'] !!}</td>
            <td>{!! $item['aqueduct'] !!}</td>
            <td>{!! $item['aqueduct_benefit_service_name'] !!}</td>
            <td>{!! $item['aqueduct_continuity_service_name'] !!}</td>
            <td>{!! $item['chance_name'] !!}</td>
            <td>{!! $item['reports_effectiveness_name'] !!}</td>
            <td>{!! $item['damage_report'] !!}</td>
            <td>{!! $item['arrived_on_time_name'] !!}</td>
            <td>{!! $item['damage_well_fixed_name'] !!}</td>
         </tr> 
      @endforeach
   </tbody>
</table>