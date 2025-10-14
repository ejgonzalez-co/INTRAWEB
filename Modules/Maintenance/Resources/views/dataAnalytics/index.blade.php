@extends('layouts.default')

@section('title', trans('Analítica de datos'))

@section('section_img', '/assets/img/components/estadistica.png')

@section('menu')
    @include('maintenance::layouts.menus.menu_data_analytic')
@endsection 

@section('content')

    <crud name="Analytics" :resource="{default: 'dataAnalytics', get: 'data-analytics'}"  inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
                <li class="breadcrumb-item active">Indicadores</li>
            </ol>
            <!-- end breadcrumb -->

            <!-- begin page-header -->
            <h1 class="page-header text-center m-b-35">Vista principal para administrar analítica de datos
            </h1>
            <!-- end page-header -->

            <!-- begin panel -->
            <div class="panel panel-default">
                <!-- begin #accordion search-->

                <!-- end #accordion search -->
                <div class="panel-body">
                 <form-data-analytics :data-form="dataForm"></form-data-analytics>

                
                 <chart-component-analytics :key="keyRefresh" 
                        :name-labels-display="['placa', 'average']" 
                        name-array-limits="limits" reduce-label="name" title-y-axis="" width="1000"
                        type-chart="column" name-field="invoice_date"
                        :value='searchFields' mode='range' :margin-left-chart="100"
                        :label-plot-line-position-x="-100"  :show-data-labels="true"
                        :labels-plot-lines="true" ref="graphic"></chart-component-analytics>
                
              </div>
            </div>
            <!-- end panel -->


        </div>
    </crud>
@endsection

@push('css')
    {!! Html::style('assets/plugins/gritter/css/jquery.gritter.css') !!}
@endpush

@push('scripts')
    {!! Html::script('assets/plugins/gritter/js/jquery.gritter.js') !!}
    <script>
        // detecta el enter para no cerrar el modal sin enviar el formulario
        $('#modal-form-Indicators').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });

        // Función para imprimir el contenido de un identificador pasado por parámetro
        function printContent(divName) {
            // Se obtiene el elemento del id recibido por parámetro
            var printContent = document.getElementById(divName);
            // Se guarda en una variable la nueva pestaña
            var printWindow = window.open("");
            // Se obtiene el encabezado de la página actual para no peder estilos
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
            }, 90);
        }
    </script>
@endpush
