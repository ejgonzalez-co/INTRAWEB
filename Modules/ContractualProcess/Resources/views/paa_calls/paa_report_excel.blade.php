
<table border="1">
   <thead>
      <tr>
         <td colspan="12"></td>
         <td colspan="3" align="center"><b>Documento Controlado</b></td>
      </tr>
      <tr>
         <td rowspan="4">
         </td>
         <td colspan="11" align="center" rowspan="4">
            <b>Plan Anual de Adquisiciones "PAA" de Empresas Publicas de Armenia ESP Vigencia {!! $data[0]['validity'] !!}</b>
         </td>
         <td colspan="3">Código: GR-PP-003</td>
      </tr>
      <tr>
         <td colspan="3">Versión: 11</td>
      </tr>
      <tr>
         <td colspan="3">Fecha de Emisión:  21-05-14</td>
      </tr>
      <tr>
         <td colspan="3">Página:</td>
      </tr>
      <tr>
         <td>
            <b>Objetivo:</b>
         </td>
         <td colspan="14" align="center">
            Permitir que Empresas Públicas de Armenia ESP, aumente la probabilidad de lograr mejores condiciones de competencia a través de la participación de un mayor número de operadores económicos interesados en los procesos de selección que se van a adelantar durante el año fiscal, y que el Estado cuente con información suficiente para realizar compras coordinadas.
         </td>
      </tr>
      <tr>
         <td colspan="15" align="center"><b>1. Información general de la Empresa:</b></td>
      </tr>
      <tr>
         <td>
            <b>Nombre</b>
         </td>
         <td colspan="2" align="center">Empresas Públicas de Armenia ESP.</td>
         <td align="center">
            <b>Dirección</b>
         </td>
         <td colspan="11" align="center">Carrera 17 # 16-00 Edificio CAM</td>
      </tr>
      <tr>
         <td>
            <b>Página web</b>
         </td>
         <td colspan="2" align="center">www.epa.gov.co</td>
         <td align="center">
            <b>Teléfono</b>
         </td>
         <td colspan="11" align="center">7411780</td>
      </tr>
      <tr>
         <td><b>Misión</b></td>
         <td colspan="14">Empresas Públicas de Armenia ESP., está comprometida con el desarrollo regional, trabaja bajo el concepto de sostenibilidad en servicios públicos y negocios estratégicos; para la satisfacción de las demandas ciudadanas.</td>
      </tr>
      <tr>
         <td><b>Visión</b></td>
         <td colspan="14">Empresas Públicas de Armenia ESP., se consolida y reconoce como un modelo de gestión a nivel nacional, basada en prácticas transparentes y de equilibrio ambiental, que superan los estándares del sector y participa en el desarrollo integral de la región.</td>
      </tr>
      <tr>
         <td><b>Política de compras y suministros</b></td>
         <td colspan="14">Empresas Públicas de Armenia ESP., Asegura el cumplimiento de requisitos y especificaciones en la adquisición de productos o prestación de los servicios críticos requeridos que afectan la calidad del producto, servicio, trabajo de Ensayo y/o Calibración, mediante la aplicación de mecanismos de selección,  evaluación de proveedores, compra, recepción, inspección, verificación, aprobación y almacenamiento de los productos o servicios comprados.</td>
      </tr>
      <tr>
         <td>
            <b>Información de contacto</b>
         </td>
         <td colspan="2" align="center">Camilo Andrés Duque Orozco , Tel. 7411780 Ext. 1514</td>
         <td colspan="3" align="center">
            <b>Límite de contratación menor cuantía</b>
         </td>
         <td colspan="9" align="center">NA</td>
      </tr>
      <tr>
         <td>
            <b>Valor total del PAA</b>
         </td>
         <td colspan="2" align="center" data-format="$ #,##0_-">{!! $data[1]['total_value'] !!}</td>
         <td colspan="3" align="center">
            <b>Límite de contratación mínima cuantía</b>
         </td>
         <td colspan="9" align="center">NA</td>
      </tr>
      <tr>
         <td colspan="3"></td>
         <td colspan="3" align="center"><b>Fecha de última actualización del PAA</b></td>
         <td colspan="9" align="center">NA</td>
      </tr>

      <tr>
         <td align="center" colspan="11"><b>2. Adquisiciones Planeadas</b></td>
         <td align="center" colspan="4"><b>Seguimiento</b></td>
      </tr>
      <tr>
         <td align="center">Descripción</td>
         <td align="center">Modalidad de selección </td>
         <td align="center">Fuente de los recursos</td>
         <td align="center">Valor total estimado</td>
         <td align="center">Valor estimado en la vigencia actual</td>
         <td align="center">Adiciones</td>
         <td align="center">Valor total</td>
         <td align="center">¿Se requieren vigencias futuras?</td>
         <td align="center">Estado de solicitud de vigencias futuras</td>
         <td align="center">Datos de contacto  responsable</td>
         <td align="center">Observaciones</td>
         <td align="center">No. Contrato</td>
         <td align="center">Nombre Contratista</td>
         <td align="center">Fecha estimada de inicio de proceso de selección</td>
         <td align="center">Duración estimada del contrato</td>
      </tr>
   </thead>
   <tbody>
      @php
      unset($data[0]);
      unset($data[1]);
      @endphp
      @endphp
      @foreach ($data as $key => $item)
         <tr>
            <td>{!! $item['description'] !!}</td>
            <td align="center">Régimen Especial / Manual de Contratación</td>
            <td>{!! $item['type_need'] !!}</td>
            {{-- <td>{!! '$ '.number_format($item['total_value'], 0, ".", ".") !!}</td> --}}
            <td data-format="$ #,##0_-">{!! $item['total_value'] !!}</td>
            <td></td>
            <td></td>
            <td></td>
            <td align="center">No</td>
            <td align="center">N/A</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
         </tr>
      @endforeach
   </tbody>
</table>