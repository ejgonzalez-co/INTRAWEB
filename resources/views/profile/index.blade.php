@extends('layouts.default')

@section('title', trans('profile'))

@section('menu')
    <!--@include('layouts.menu')-->
@endsection

@section('content')
<edit-profile-page url-storage="{{ asset('storage') }}/"></edit-profile-page>
@endsection

@push('css')
{!!Html::style('assets/plugins/gritter/css/jquery.gritter.css')!!}
@endpush

@push('scripts')
{!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
<script>
    // detecta el enter para no cerrar el modal sin enviar el formulario
    $('#modal-form-headquarters').on('keypress', ( e ) => {
        if ( e.keyCode === 13 ) { e.preventDefault(); }
    } );
</script>
@endpush
