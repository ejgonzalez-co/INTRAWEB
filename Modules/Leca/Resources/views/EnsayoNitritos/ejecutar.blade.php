@extends('layouts.default')

@section('title', 'Ejecución ensayo Nitrito')

@section('section_img', '/assets/img/components/doc_funcionario.png')

@section('menu')
    @include('leca::layouts.menu_nitrito_ejecucion')
@endsection

@section('content')


    <aluminio-ensayo titulo="Nitrito" name-formulario="nitrito"></aluminio-ensayo>

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
