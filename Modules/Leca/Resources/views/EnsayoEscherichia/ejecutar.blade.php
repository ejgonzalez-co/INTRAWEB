@extends('layouts.default')

@section('title', 'Ejecución ensayo Escherichia')

@section('section_img', '/assets/img/components/doc_funcionario.png')

@section('menu')
    @include('leca::layouts.menu_ejecucion_escherichia')
@endsection

@section('content')


    <microbiologicos-ensayo titulo="Escherichia" name-formulario="escherichia"></microbiologicos-ensayo>

@endsection

@push('css')
    {!! Html::style('assets/plugins/gritter/css/jquery.gritter.css') !!}
@endpush

@push('scripts')
    {!! Html::script('assets/plugins/gritter/js/jquery.gritter.js') !!}
    <script>
        // detecta el enter para no cerrar el modal sin enviar el formulario>
        $('#modal-form-monthlyRoutinesHasUsers').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });
    </script>
@endpush
