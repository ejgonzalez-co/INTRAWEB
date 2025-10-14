@extends('layouts.default_leca')

@section('title', trans('Publica'))

@section('content')

<crud
    name="sampleTakings"
    :resource="{default: 'public-sample', get: 'get-public-sample?lc_qr={!! $lc_qr !!}'}" 
    :init-values="{lc_qr: '{!! $lc_qr ?? null !!}'}"
    inline-template>
    <div>
        <div id="showFields">
            <table border=4  style="margin-left: 3px;">
                <tr>
                    <td rowspan="4" colspan="3"><img src="/assets/img/components/epaimagen.jpeg" style="width: 200px;" alt="" /></td>
                    <td colspan="13"><b>Código: LECA-ET-002 </b></td>
                </tr>
                <tr>
                    <td colspan="13"><b>Versión: 04</b></td>
                </tr>
                <tr>
                    <td colspan="13"><b>Fecha de emisión: 19-12-18</b></td>
                </tr>
                <tr>
                    <td colspan="13"><b>Página: 1/1</b></td>
                </tr>
                <tr>
                    <th colspan="16" scope="col"><b>Identificación Muestra</b></th>
                </tr>
                <tr>
                    <td rowspan="3" colspan="2"><b>Fecha toma de muestra</b></td>
                </tr>
                <tr>
                    <td colspan="2"><b>AA</b></td>
                    <td colspan="2"><b>MM</b></td>
                    <td colspan="2"><b>DD</b></td>
                    <td rowspan="2" colspan="3"><b>Hora</b></td>
                    <td rowspan="2" colspan="5">{{$hourSample ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$year ?? 'N/A'}}</td>
                    <td colspan="2">{{$month ?? 'N/A'}}</td>
                    <td colspan="2">{{$day ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Tipo de Muestra:</b></td>
                    <td colspan="4">1.Agua Tratada</td>
                    <td>{{$treatedWaterNew}}</td>
                    <td colspan="5">2.Agua Cruda</td>
                    <td>{{$rawWaterNew}}</td>
                    <td>3.Agua en proceso</td>
                    <td>{{$processWaterNew}}</td>
                </tr>
                <tr>
                    <td rowspan="6" colspan="8"><b>Grupo de Parámetros </b><br>
                    1.Físicos <label>{{$finalPhysicals}}</label><br>
                    2.Químicos <label>{{$finalChemicals}}</label><br>
                    3.Microbiológicos <label>{{$finalMicrobiological}}</label></td>
                    <td colspan="8"><b>Preservación</b></td>
                </tr>
                <tr>
                    <td rowspan="5" colspan="5">1.Refrigeración <br>
                    2.Muestra filtrada <br>
                    3.HNO<sub>3</sub> <br>
                    4.H<sub>2</sub>SO<sub>4</sub></td>
                </tr>
                <tr>
                    <td>{{$refrigeration}}</td>
                    <td rowspan="4">
                        5.HCI <br>
                        6.NaOH <br>
                        7.Acetato <br>
                        8.Ácido ascórbico
                    </td>
                    <td>{{$hci}}</td>
                </tr>
                <tr>
                    <td>{{$filteredSample}}</td>
                    <td>{{$naoh}}</td>
                </tr>
                <tr>
                    <td>{{$hno3}}</td>
                    <td>{{$acetate}}</td>
                </tr>
                <tr>
                    <td>{{$h2so4}}</td>
                    <td>{{$ascorbic_acid}}</td>
                </tr>
                <tr>
                    <td rowspan="4" colspan="2" class="text-center"><h1><b>{{$codeSAmple ?? 'N/A'}}</b></h1>  </td>
                    <td colspan="14"><b>Responsable de la muestra</b></td>
                </tr>
                <tr>
                    <td colspan="2"><b>Firma: </b></td>
                    {{-- <td colspan="12">{{$buena ?? 'N/A'}}</td> --}}
                    {{-- <td colspan="12"><img src="C:\Users\desar\Desktop\VUV2 SEVEN\INTRAEPA\public\storage\users\signature\dGdqc15Ttwbf1W2aAFnpBtEvbIsT2iIPiJhHch71.png" style="width: 200px;" alt=""/></td> --}}
                    <td colspan="12"><img class="firma" src="{{'/storage/'.$signatureUsers}}"></td>
                </tr>
                <tr>
                    <td colspan="2"><b>Nombre: </b></td>
                    <td colspan="12">{{$userName ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Cargo: </b></td>
                    <td colspan="12">{{$charge ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><b>Código Muestra</b></td>
                    {{-- {{$codeSAmple ?? 'N/A'}} --}}
                    <td colspan="2"><b>Proceso: </b></td>
                    <td colspan="12">{{$process ?? 'N/A'}}</td>
                </tr>
            </table>
        </div>
        <br>
        <button class="btn btn-warning" type="button" onclick="printContent('showFields');"><i class="fa fa-print mr-2"></i>@lang('print')</button>
    </div>
</crud>
@endsection

@push('css')
<style>
.firma{
    width: 150px;
    height: 30px;
}
</style>
{{-- {!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!} --}}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-sampleTakings').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });

    function printContent(divName) {
        
        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no perder estilos
        var headContent = document.getElementsByTagName('head')[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 5000);
    }
</script>
@endpush
