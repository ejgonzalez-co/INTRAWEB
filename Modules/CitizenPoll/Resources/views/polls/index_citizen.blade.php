@extends('layouts.default_citizen')

@section('title', trans('polls'))

@section('section_img', '../assets/img/components/solicitudes.png')

@section('menu')
    @include('citizen_poll::layouts.menu_citizen')
@endsection

@section('content')

<crud name="polls" :resource="{default: 'polls-citizen', get: 'get-polls-citizen'}" inline-template>
    <div>
    
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Encuesta de Percepción y Satisfacción del Usuario</h1>
        <!-- end page-header -->

        <p class="text-justify">
            Cláusula de protección de datos personales: Los datos personales aquí consignados tiene carácter confidencial, razón por la cual es un deber y un compromiso de los participantes y de Empresas Públicas de Armenia ESP, no divulgar información alguna de los usuarios en propósitos diferentes al objetivo por la cual es diligenciado este registro, so pena de las sanciones legales a que haya lugar. Lo anterior en cumplimiento de las políticas de seguridad de la información de Empresas Públicas de Armenia ESP.(Ley 1581 DE 2012, reglamentada por Decreto 1377 de 2013).<br>
            <br> 
            Reciba un cordial saludo, el objetivo del diligenciamiento de ésta encuesta es conocer el nivel de percepción que las partes interesadas y/o grupos de interés tiene acerca de la prestación de los servicios de Acueducto, Alcantarillado y Aseo, con el fin de evaluar los resultados y tomar las acciones necesarias para el mejoramiento del servicios a nuestros usuarios. Gracias por su colaboración.       
        </p>

        <!-- begin main buttons -->
        <div class="row">
            <button @click="add()" type="button" class="btn btn-primary m-b-10 btn-lg" data-backdrop="static" data-target="#modal-form-polls" data-toggle="modal">
                <i class="fa fa-plus mr-2"></i>Diligenciar @lang('poll')
            </button>
        </div>
        <!-- end main buttons -->

        <!-- begin #modal-form-polls -->
        <div class="modal fade" id="modal-form-polls">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-polls">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formulario de la encuesta</h4>
                            <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                        </div>
                        <div class="modal-body" v-if="openForm">
                            @include('citizen_poll::polls.fields')
                        </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>@lang('crud.close')</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-modal-polls -->

        <!-- banner -->
        <div class="row justify-content-center">
            {{-- <img src="{{ asset('assets/img/default/banner_citizen_poll.jpeg')}}" alt="" style="width: 1050px"/> --}}
        </div>        
            <slider-show name-resource="get-slider" :islink="true" classbtn="btnSlider">
            </slider-show>
    </div>
</crud>
@endsection

@push('css')
<style>
    .vue-feedback-reaction div:last-child{
        display: none;
    }

</style>
@endpush


@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-polls').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
    
    //valida que solo ingresen numero en un campo especifico. 
    //Se tuvo que crear esta funcion porque las funciones on o keypress no funcionaban alguna extraña razon. 
    function noLetters() {
        let number_account = $("#number_account").val();
        //expresión regular 
        const regex = /^[0-9]*$/;
        const onlyNumbers = regex.test(number_account); // true
        if(!onlyNumbers){
            $("#number_account").val("")
        }
    }
   
</script>
@endpush
