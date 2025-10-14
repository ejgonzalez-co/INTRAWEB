<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Documentos electronicos</title>
</head>
<body>
    <table border="1">
        <thead>
           <tr>
              <td>Fecha de creación</td>
              <td>Creado por</td>
              <td>Consecutivo</td>
              <td>Titulo</td>
              <td>Tipo de documento</td>
              <td>Estado</td>
           </tr>
        </thead>
        <tbody>
           @foreach ($data as $key => $item)
              <tr>
                 <td>{!! $item['created_at'] !!}</td>
                 <td>{!! $item['users_name'] !!}</td>
                 <td>{!! $item['consecutivo'] !!}</td>
                 <td>{!! $item['titulo_asunto'] !!}</td>
                 <td>{!! $item['de_tipos_documentos'] ? $item['de_tipos_documentos']->nombre : "Documento en blanco" !!}</td>
                 <td>{!! $item['estado'] !!}</td>
              </tr> 
           @endforeach
        </tbody>
     </table>
</body>
</html>