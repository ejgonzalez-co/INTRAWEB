<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Listado de Torres</title>
</head>
<body>
   <table>
      <thead>
         <tr>
            <td>Consecutivo</td>
            <td>Fecha de creación</td>
            <td>Nombre de la marca</td>
            <td>Estado</td>
         </tr>
      </thead>
      <tbody>
         @foreach ( $data as $tower )
         <tr>
             <td>{!! $tower['id'] ?? "N/A" !!}</td>
            <td>{!! $tower['created_at'] ?? "N/A" !!}</td>
            <td>{!! $tower['brand_name'] ?? "N/A" !!}</td>
            <td>{!! $tower['status'] ?? "N/A" !!}</td>
         </tr>
         @endforeach
      </tbody>
   </table>

</body>
</html>