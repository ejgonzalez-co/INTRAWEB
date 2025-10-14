<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Listado de sistemas operativos</title>
</head>
<body>
   <table>
      <thead>
         <tr>
            <td>Consecutivo</td>
            <td>Fecha de creación</td>
            <td>Sistema operativo</td>
            <td>Estado</td>
         </tr>
      </thead>
      <tbody>
         @foreach ( $data as $operatingSystem )
         <tr>
             <td>{!! $operatingSystem['id'] ?? "N/A" !!}</td>
            <td>{!! $operatingSystem['created_at'] ?? "N/A" !!}</td>
            <td>{!! $operatingSystem['name'] ?? "N/A" !!}</td>
            <td>{!! $operatingSystem['status'] ?? "N/A" !!}</td>
         </tr>
         @endforeach
      </tbody>
   </table>

</body>
</html>