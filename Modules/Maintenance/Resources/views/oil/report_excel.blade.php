<table border="1">
  <!-- <tr>
     <td></td>
     <td colspan="8">REPORTE DE GESTION DE COCMBUSTIBLES</td>
  </tr> -->
  <tr><td style="font-size: 15px; background: #12E54F;">REPORTE DE GESTIÓN DE ACEITES</td></tr>
 

 @foreach ($data as $key => $item)
 <tr>
  <td style="font-size: 14px; background: #12E54F;">Consecutivo</td>
  <td style="font-size: 14px; background: #12E54F;">Fecha de registro </td>
  <td style="font-size: 14px; background: #12E54F;">Placa </td>
  <td style="font-size: 14px; background: #12E54F;">Nombre del activo </td>
  <td style="font-size: 14px; background: #12E54F;">Tipo de activo</td>
  <td style="font-size: 14px; background: #12E54F;">Proceso</td>
  <td style="font-size: 14px; background: #12E54F;">Tipo de muestra</td>
  <td style="font-size: 14px; background: #12E54F;">Componente</td>
  <td style="font-size: 14px; background: #12E54F;">Número de serie</td>
  <td style="font-size: 14px; background: #12E54F;">Marca</td>
  <td style="font-size: 14px; background: #12E54F;">Modelo</td>
  <td style="font-size: 14px; background: #12E54F;">Lugar de trabajo</td>
  <td style="font-size: 14px; background: #12E54F;">Número de garantía extendida</td>
  <td style="font-size: 14px; background: #12E54F;">Orden de trabajo</td>
  <td style="font-size: 14px; background: #12E54F;">Serie componente</td>
  <td style="font-size: 14px; background: #12E54F;">Modelo del componente</td>
  <td style="font-size: 14px; background: #12E54F;">Fabricante del componente</td>
  <td style="font-size: 14px; background: #12E54F;">Marca/grado de aceite</td>
  <td style="font-size: 14px; background: #12E54F;">Tipo de fluido</td>
  <td style="font-size: 14px; background: #12E54F;">Fecha de término número garantía ext</td>
</tr>
<tbody>

 <tr>
   <td>{!! $item['component'] !!}</td>
   <td>{!! $item['register_date'] !!}</td>
   <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']->plaque : '' !!}</td>
   <td>{!! $item['asset_name'] !!}</td>
   <td>{!! $item['asset_type'] ? $item['asset_type']->name: '' !!}</td>
   <td>{!! $item['dependencias'] ? htmlentities($item['dependencias']->nombre): '' !!}</td>
   <td>{!! $item['show_type'] !!}</td>
   <td>{!! $item['component_name'] !!}</td>
   <td>{!! $item['serial_number'] !!}</td>
   <td>{!! $item['brand'] !!}</td>
   <td>{!! $item['model'] !!}</td>
   <td>{!! $item['job_place'] !!}</td>
   <td>{!! $item['number_warranty_extended'] !!}</td>
   <td>{!! $item['work_order'] !!}</td>
   <td>{!! $item['serial_component'] !!}</td>
   <td>{!! $item['model_component'] !!}</td>
   <td>{!! $item['maker_component'] !!}</td>
   <td>{!! $item['grade_oil'] !!}</td>
   <td>{!! $item['type_fluid'] !!}</td>
   <td>{!! $item['date_finished_warranty_extended'] !!}</td>
 </tr>  
 <tr><td style="font-size: 16px; background: #BDBDBD;">Control de laboratorios</td>
    <td style="background: #BDBDBD;"></td>
    <td style="background: #BDBDBD;"></td>
    <td style="background: #BDBDBD;"></td>
    <td style="background: #BDBDBD;"></td>
    <td style="background: #BDBDBD;"></td>
    <td style="background: #BDBDBD;"></td>
    <td style="background: #BDBDBD;"></td>
    <td style="background: #BDBDBD;"></td>

</tr>
 <tr>
  <td style="background: #BDBDBD; font-size:14px;">Número de control lab</td>
  <td style="background: #BDBDBD; font-size:14px;">Fecha de muestreo</td>
  <td style="background: #BDBDBD; font-size:14px;">Fecha proceso</td>
  <td style="background: #BDBDBD; font-size:14px;">Horómetro</td>
  <td style="background: #BDBDBD; font-size:14px;">¿Cambio de filtro? </td>
  <td style="background: #BDBDBD; font-size:14px;">Relleno</td>
  <td style="background: #BDBDBD; font-size:14px;">Unidades de relleno</td>
  <td style="background: #BDBDBD; font-size:14px;">Tipo de resultado</td>
  <td style="background: #BDBDBD; font-size:14px;">Resultado</td>
</tr>
  @foreach ($item['oil_control_laboratories'] as $key => $info)
    
    <tr>
      <td>{!! $info->number_control_laboratory !!}</td>
      <td>{!! $info->date_sampling !!}</td>
      <td>{!! $info->date_process !!}</td>
      <td>{!! $info->hourmeter !!}</td>
      <td>{!! $info->change_filter !!}</td>
      <td>{!! $info->filling !!}</td>
      <td>{!! $info->filling_units !!}</td>
      <td>{!! $info->result !!}</td>
      <td>{!! $info->type_result !!}</td>
    </tr>  
    
  @endforeach

  <tr><td style="font-size: 16px; background: #409394;">Elementos de desgaste</td>
      <td style="background: #409394;"></td>
      <td style="background: #409394;"></td>
      <td style="background: #409394;"></td>
      

      
  </tr>
  <tr>
    <td style="background:#409394; font-size: 14px;">Número de control de laboratorio</td>
    <td style="background:#409394; font-size: 14px;">Nombre del elemento de desgaste</td>
    <td style="background:#409394; font-size: 14px;">Valor detectado</td>
    <td style="background:#409394; font-size: 14px;">Rango</td>
  </tr>
    @foreach ($item['oil_element_wears'] as $key => $element)
      
      <tr>
        <td>{!! $element->number_control_laboratory !!}</td>
        <td>{!! $element->oil_element_wear_configurations? $element->oil_element_wear_configurations->element_name: '' !!}</td>
        <td>{!! $element->detected_value !!}</td>
        <td>{!! $element->range !!}</td>
      </tr>  
      
    @endforeach
     
  @endforeach
</tbody>

  

</table>

