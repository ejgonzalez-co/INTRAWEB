@extends('layouts.default')

@section('title', 'Validar correspondencia')

@section('section_img', '/assets/img/components/intranet_poll.png')

@section('menu')
    <!-- Valida si el usuario tiene sesión -->
    @if(Auth::user())
        <!-- Si el usuario esta logueado, le muestra el menú completo -->
        @include('correspondence::layouts.menu')
    @else
        <!-- Si el usuario no esta logueado, solo le muestra el item de validar correspondencia -->
        @include('correspondence::layouts.menu_validar_external')
    @endif
@endsection

@section('content')
    <correspondence-validator inline-template>
        <div class="container-fluid">
            <div class="row justify-content-center mt-5">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Validación de correspondencia externa enviada</h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">¿Cómo hacerlo?</p>
                            <ol>
                                <li>Ingrese el número del código de validación que se encuentra en el documento.</li>
                                <li>Haga clic en el botón "Validar correspondencia".</li>
                                <li>Opcionalmente, si requiere una validación más precisa, adjunte el documento haciendo clic en "Seleccionar archivo" y luego en "Validar documento".</li>
                            </ol>
                            <div class="input-group mb-3">
                                {!! Form::text('validation_code', null, [
                                    'v-model' => 'searchFields.validation_code',
                                    'class' => 'form-control',
                                    'placeholder' => 'Ingrese un código de validación',
                                    'id' => 'validation_code',
                                    '@keyup.enter' => 'validar_correspondencia_enviada(searchFields.validation_code)',
                                ]) !!}
                                <div class="input-group-append">
                                    <button @click="validar_correspondencia_enviada(searchFields.validation_code)" type="button" class="btn btn-primary btn-sm">
                                        <i class="fa fa-check mr-2"></i>Validar correspondencia
                                    </button>

                                    {{-- <button @click="validar_correspondencia_enviada(searchFields.validation_code)" type="button" class="btn btn-primary">
                                        <i class="fa fa-check mr-2"></i>Validar correspondencia
                                    </button> --}}
                                </div>
                            </div>
                            <small class="form-text text-muted">Ingrese el código de validación que se encuentra en el documento</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-5" v-if="dataList.length > 0">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-center">Total de registros encontrados: @{{ dataPaginator.total }}</h4>
                        </div>
                        <div class="card-body">
                            <viewer-attachement type="table" ref="viewerDocuments"></viewer-attachement>

                            @include('correspondence::externals_validar_correspondencia.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </correspondence-validator>
@endsection
