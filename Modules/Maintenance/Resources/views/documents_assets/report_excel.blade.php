<table border="1">
    <thead>
        <tr>
            <td style="background: #BDBDBD; font-size:14px;">Fecha de creación</td>

            <td style="background: #BDBDBD; font-size:14px;">Nombre</td>
            <td style="background: #BDBDBD; font-size:14px;">Descripción</td>



        </tr>
    </thead>
    <tbody>

        @foreach ($data as $key => $item)
        <tr>            
            <td>{!! $item['created_at'] ?? null !!}</td>
            <td>{!! $item['name'] ?? null !!}</td>
            <td>{!! $item['description'] ?? null !!}</td>

        </tr> 
    @endforeach

    </tbody>
</table>