@extends('layouts.default')

@section('title', 'Encuesta de satisfacción de PQRS')

@section('section_img', '/assets/img/components/solicitudes.png')



@section('content')

<crud
    name="survey-satisfaction-pqrs"
    :resource="{default: 'survey-satisfaction-pqrs', get: 'get-survey-satisfaction-pqrs?cHFyX2lk={{$pqr_id}}'}"
    :init-values="{pqr_id: '{!! $pqr_id ?? null !!}'}"
    inline-template>
    <div>
        <!-- begin page-header -->
        <h1 class="page-header text-center m-b-35">Encuesta de satisfacción de PQRS {{ $pqr->pqr_id }}</h1>
        <!-- end page-header -->

        <!-- begin #modal-form-survey-satisfaction-pqrs -->
                <form @submit.prevent="save()" id="form-survey-satisfaction-pqrs" style="max-width: 900px; margin: auto;">
                    <div class="modal-content border-1">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">Formulario de encuesta de satisfacción de PQR <strong> {{ $pqr->pqr_id }} </strong> </h4>
                        </div>
                        <div class="modal-body" >
                            @include('pqrs::survey_satisfaction_pqrs.fields')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" @click="window.close()">
                                <i class="fa fa-times mr-2"></i>@lang('crud.close')
                            </button>
                            
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>
                </form>
        <!-- end #modal-form-survey-satisfaction-pqrs -->
    </div>
</crud>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-survey-satisfaction-pqrs').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
