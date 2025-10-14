@push('styles')
    <style>
        .table-arial-12 {
            font-family: Arial, sans-serif;
            font-size: 12px;
            border-collapse: collapse;
        }
        .table-arial-12 th,
        .table-arial-12 td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
@endpush
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: rgb(152, 176, 233);">
            <th colspan="14" style="text-align: center; border: 1px solid black;">Indice electrónico del expediente {{ $info_expediente[0]["consecutivo"] }}</th>
        </tr>
        <tr style="background: rgb(124, 154, 227);">
            <th style="text-align: center; border: 1px solid black;"><b>Consecutivo</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Nombre del documento</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Ubicación del documento</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Tipología del documento</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Fecha de creación</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Fecha de incorporación al expediente</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Origen del documento</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Orden documental</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Pag. inicio</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Pag. final</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Valor huella</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Función resumen</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Formato</b></th>
            <th style="text-align: center; border: 1px solid black;"><b>Observación</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="border: 1px solid black;">{!! $item->consecutivo?$item->consecutivo:null !!}</td>
                <td style="border: 1px solid black;">{!! $item->nombre_documento?$item->nombre_documento:null !!}</td>
                <td style="border: 1px solid black;">{!! $item->ubicacion_documento ? $item->ubicacion_documento : null !!}</td>
                <td style="border: 1px solid black;">{!! $item->ee_tipos_documentales_id ?$item->ee_tipos_documentales_id :null !!}</td>
                <td style="border: 1px solid black;">{!! $item->fecha_documento?$item->fecha_documento:null !!}</td>
                <td style="border: 1px solid black;">{!! $item->created_at?$item->created_at:null !!}</td>
                <td style="border: 1px solid black;">{!! $item->modulo_intraweb ? $item->modulo_intraweb : null !!}</td>
                <td style="border: 1px solid black;">{!! $item->orden_documento?$item->orden_documento:null !!}</td>
                <td style="border: 1px solid black;">{!! $item->pagina_inicio?$item->pagina_inicio : '' !!}</td>
                <td style="border: 1px solid black;">{!! $item->pagina_fin?$item->pagina_fin : '' !!}</td>
                <td style="border: 1px solid black;">{!! $item->hash_value?$item->hash_value : '' !!}</td>
                <td style="border: 1px solid black;">{!! $item->hash_algoritmo?$item->hash_algoritmo : '' !!}</td>
                <td style="border: 1px solid black;">{!! empty($item->adjunto) ? ($item->formato_corr_recibida_email ?? 'Sin documento') : (strpos($item->adjunto, ',') !== false ? 'Varios' : strtoupper(substr(strrchr($item->adjunto, '.'), 1))) !!}</td>
                <td style="border: 1px solid black;">{!! $item->descripcion?$item->descripcion:null !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($info_expediente[0]["estado"] == "Cerrado")
    <div class="container">
        <div style="text-align: center; margin-top: 20px">Cierre del índice electrónico {{ date($info_expediente[0]["updated_at"]) }}</div>
        <div style="text-align: center">
            <img src="storage/{{ $info_expediente[0]['users']['url_digital_signature'] }}" alt="Firma">
            <div>{{ $info_expediente[0]["user_name"] }}</div>
            <div>ID firma: {{ $info_expediente[0]["id_firma_caratula_apertura"] }}</div>
        </div>
    </div>
@endif
