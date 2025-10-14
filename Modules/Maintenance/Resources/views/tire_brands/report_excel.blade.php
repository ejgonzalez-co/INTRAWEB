<table border="1">
    <thead>
        <tr>
            <td>Nombre de la Marca de la llanta</td>
            <td>Referencias de la llanta</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['brand_name'] !!}</td>
                <td>
                    @if (isset($item['tire_references']))
                        @foreach ($item['tire_references'] as $tireReference)
                            {!! $tireReference->reference_name . ',' !!}
                        @endforeach
                    @endif

                </td>
            </tr>
        @endforeach
    </tbody>
</table>