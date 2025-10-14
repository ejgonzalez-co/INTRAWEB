@extends('layouts.default')

@section('title', trans('PQR anónimo'))

@section('section_img', '/assets/img/components/convocatoria.png')

@section('menu')
    @include('pqrs::layouts.menu_ciudadano_anonimo')
@endsection

@section('content')

<!-- Muestra la vista de consulta de PQR anónimos del sitio de Joomla -->
<iframe src="{{ config("app.url_joomla") }}/index.php?option=com_formasonline&formasonlineform=RequerimientosAnonimo&tmpl=component" frameborder="0" style="width: 100%; height: 82vh;"></iframe>

@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-p-q-r-s').on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    });
</script>
@endpush
