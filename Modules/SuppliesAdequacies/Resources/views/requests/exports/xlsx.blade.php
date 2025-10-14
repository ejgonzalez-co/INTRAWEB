<table border="1">
    <thead>
        <tr>
            <td colspan="7" style="font-size: 20px; background: #12E54F;"><h1>Información de las solicitudes</h1></td>

        </tr>
        <tr>
            <td style="background: #BDBDBD; font-size:14px;">Número</td>
            <td style="background: #BDBDBD; font-size:14px;">Fecha de creación</td>
            <td style="background: #BDBDBD; font-size:14px;">Funcionario solicitante</td>
            <td style="background: #BDBDBD; font-size:14px;">Dependencia del funcionario solicitante</td>
            <td style="background: #BDBDBD; font-size:14px;">Tipo de necesidad</td>
            <td style="background: #BDBDBD; font-size:14px;">Asunto</td>
            <td style="background: #BDBDBD; font-size:14px;">Tiempo asignado</td>
            <td style="background: #BDBDBD; font-size:14px;">Usuario asignado</td>
            <td style="background: #BDBDBD; font-size:14px;">Fecha de vencimiento</td>
            <td style="background: #BDBDBD; font-size:14px;">Fecha de atención</td>
            <td style="background: #BDBDBD; font-size:14px;">Estado</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $request)
          <tr>            
             <td>{!! $request["consecutive"] ?? "N/E" !!}</td>
             <td>{!! $request["created_at"] ?? "N/E" !!}</td>
             <td>{!! $request["user_creator"] ? $request["user_creator"]->name : "N/E" !!}</td>
             <td>{!! $request["user_creator"] ? $request["user_creator"]->dependencies->nombre : "N/E" !!}</td>
             <td>{!! $request["need_type"] !!}</td>
             <td>{!! $request["subject"] !!}</td>
             <td>{!! $request["quantity_term"] !!}</td>
             <td>{!! $request["assigned_officer"] ? $request["assigned_officer"]->name : "N/E" !!}</td>
             <td>{!! $request["expiration_date"] ?? "N/E" !!}</td>
             <td>{!! $request["date_attention"] ?? "N/E" !!}</td>
             <td>{!! $request["status"] !!}</td>
          </tr> 
        @endforeach
    </tbody>
</table></table>
 </table>