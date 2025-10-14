@extends('layouts.default_citizen')

@section('title', trans('Encuesta Actualización de Datos Personales'))

@section('section_img', '../assets/img/components/solicitudes.png')

@section('menu')
    @include('update_citizen_data::layouts.menu_citizen')
@endsection

@section('content')

<crud name="udc-requests" :resource="{default: 'udc-requests-citizen', get: 'get-udc-requests-citizen'}" inline-template>
    <div>
    
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Encuesta Actualización de Datos Personales</h1>
        <!-- end page-header -->

        <p class="text-justify">
            Cláusula de protección de datos personales: Los datos personales aquí consignados tiene carácter confidencial, razón por la cual es un deber y un compromiso de los participantes y de Empresas Publicas de Armenia ESP, no divulgar información alguna o usuaria en propósitos diferentes al objetivo por la cual es diligenciado este registro, so pena de las sanciona legales a que haya lugar. Lo anterior en cumplimiento de las políticas de seguridad de la información de Empresas Publicas de Armenia ESP.(Ley 1581 DE 2012, reglamentada por Decreto 1377 de 2013).<br>
            <br> <br>
            Reciba un cordial saludo, el objetivo del diligenciamiento de este formato es realizar la actualización de datos de todos nuestros usuarios , con el fin de contar con información real y pertinente que permita tener un contacto mas cercano . gracias por su colaboración.       
        </p>


        <!-- begin main buttons -->
        <div class="row">
            <button @click="add()" type="button" class="btn btn-primary m-b-10 btn-lg" data-backdrop="static" data-target="#modal-form-udc-requests" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Diligenciar @lang('poll')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin #modal-form-udc-requests -->
        <div class="modal fade" id="modal-form-udc-requests">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-udc-requests">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">@lang('form_of') @lang('poll')</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('update_citizen_data::udc_requests.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('Send') datos</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-udc-requests -->

        <!-- banner -->
        <div class="row justify-content-center">
            <img src="{{ asset('assets/img/default/banner_update_citizen_data.jpeg')}}" alt="" style="width: 1050px"/>
        </div>

    </div>
</crud>
@endsection


@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>

    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-udc-requests').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );

    //valida que solo ingresen numero en un campo especifico. 
    //Se tuvo que crear esta funcion porque las funciones on o keypress no funcionaban alguna extraña razon. 
    function noLetters() {
        let value_payment_account_number = $("#payment_account_number").val();
        //expresión regular 
        const regex = /^[0-9]*$/;
        const onlyNumbers = regex.test(value_payment_account_number); // true
        if(!onlyNumbers){
            $("#payment_account_number").val("")
        }
    }

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
            }, 10);
    }


</script>
@endpush




