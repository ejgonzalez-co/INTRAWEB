@extends('layouts.default')

@section('title', 'Validar documentos del expediente')

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
    <!-- Valida si el usuario tiene sesión -->
    @if(Auth::user())
        <!-- Si el usuario esta logueado, le muestra el menú completo -->
        @include('expedienteselectronicos::layouts.menu')
    @else
        <!-- Si el usuario no esta logueado, solo le muestra el item de validar correspondencia -->
        @include('expedienteselectronicos::layouts.menu_validar_documentos')
    @endif
@endsection

@section('content')
    <documentos-expediente-validator inline-template>
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item"><a href="{!! url('/dashboard') !!}">@lang('home')</a></li>
                <li class="breadcrumb-item active">Validar documentos del expediente</li>
            </ol>
            <!-- end breadcrumb -->

            <!-- begin page-header -->
            <h1 class="page-header text-center m-b-35">Validación de documentos del expediente</h1>
            <!-- end page-header -->
            <div class="mb-2" style="color: inherit; background-color: #eee; border-radius: 0.5rem; !important; padding: 1rem;">
                <p>¿Cómo hacerlo?</p>
                <ol>
                    <li>Ingrese el número del código de validación que se encuentra en el documento.</li>
                    <li>Haga clic el el botón: Validar documento.</li>
                    <li>Opcional: Si requiere de una validación mas precisa, adjunte el documento en el botón de "Seleccionar archivo" y de clic en la acción de "Validar documento".</li>
                </ol>
            </div>
            <div class="col-md-4 pl-0">
                {!! Form::text('validation_code', null, [
                    'v-model' => 'searchFields.validation_code',
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese un código de validación',
                    'id' => 'validation_code',
                    '@keyup.enter' => 'validar_documento_expediente(searchFields.validation_code)',
                ]) !!}
                <small>Ingrese el código de validación que se encuentra en el documento</small>
            </div>

            <!-- begin main buttons -->
            <div class="m-t-20">

                <button @click="validar_documento_expediente(searchFields.validation_code)" type="button" class="btn btn-primary m-b-10">
                    <i class="fa fa-check mr-2"></i>Validar documento
                </button>

            </div>
            <!-- end main buttons -->

            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center">Total de registros encontrados: @{{ dataPaginator.total }}</h5>

                    </div>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                            data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <viewer-attachement type="table" ref="viewerDocuments"></viewer-attachement>
                    @include('expedienteselectronicos::expedientes.table_validar_documento')
                </div>
            </div>
            <!-- end panel -->

            {{-- <documentos-expediente-validator ref="corr_validator_ref"></documentos-expediente-validator> --}}
        </div>
    </documentos-expediente-validator>
@endsection
