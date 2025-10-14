@extends('layouts.default')

@section('title', trans('Documentos Electrónicos'))

@section('section_img', '')

@section('menu')
    @include('documentoselectronicos::layouts.menu_sign')
@endsection

@section('content')

    <sign-external-component
        execute-url-axios="/documentos-electronicos/firmar-documento"  execute-url-axios-enviar="/documentos-electronicos/enviar-documento" id-tabla-firmar="{{ $id }}"  execute-url-axios-validar="/documentos-electronicos/validar-codigo-ingresado"></sign-external-component>

@endsection

@push('css')
    {!! Html::style('assets/plugins/gritter/css/jquery.gritter.css') !!}
@endpush

@push('scripts')
    {!! Html::script('assets/plugins/gritter/js/jquery.gritter.js') !!}
    <script>
        // document.addEventListener('contextmenu', function(e) {
        //     e.preventDefault();
        //     document.onselectstart = function() {
        //         return false;
        //     };
        //     });

        //     document.addEventListener('mouseup', function() {
        //     document.onselectstart = null;
        //     });
        // detecta el enter para no cerrar el modal sin enviar el formulario
        $('#modal-form-documentos').on('keypress', ':input:not(textarea):not([type=submit])', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });
    </script>
@endpush
