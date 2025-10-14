@extends('layouts.default')

@section('title', trans('modules'))

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')

    @if(Auth::user()->accept_service_terms)
        <h1 class="page-header text-center m-b-40 m-t-30">Módulos habilitados del sistema</h1>

        <div class="row row-space-10">
        @foreach($dataModule as $key => $module)
            @if (!empty($module['slug']))
            <div class="col-md-2 mb-4">
                <a href="<?php
                    
                    /**
                     * Algunos módulos se redireccionan directamente a una vista principal del mismo módulo omitiendo la vista de componentes, 
                     * los siguientes módulos que se validan por el slug, son redireccionados a un vista en particular, 
                     * si el módulo no esta en ninguna de las condiciones, se redirecciona a la vista de componentes del mismo.
                     */

                    if(Auth::user()->hasRole('Ciudadano') && $module['slug']=='PQRS') {
                        echo url('/pqrs/p-q-r-s-ciudadano');
                    } else if($module['slug']=='PQRS') {
                        echo url('/pqrs/p-q-r-s');
                    } else if($module['slug']=='DocumentaryClassification') {
                        echo url('/documentary-classification/inventory-documentals');
                    } else if($module['slug']=='Intranet') {
                        echo url('/intranet/users');
                    } else if(!Auth::user()->hasRole('Administrador intranet') && $module['slug']=='HerramientasColaborativas') {
                        echo url('/intranet/notices-public');
                    } else if($module['slug']=='Configuracion') {
                        echo url('/configuracion/configuration-generals');
                    } else if($module['slug']=='Correspondence') {
                        echo url('/correspondence/external-receiveds');
                    } else {
                        echo url('/components?module='.$module['slug']);
                    }

                    ?>" class="p-0">
                    <div class="card text-black-transparent-7 shadow">
                        <img class="card-img-top" src="{!! asset($module['img'] ?? 'assets/img/gallery/gallery-14.jpg' ) !!}" alt="Card image cap">
                        <div class="card-body" style="min-height: 200px">
                            <h3 class="card-title">{!! $module['title'] !!}</h3>
                            <p class="card-text">{!! $module['description'] !!}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        @endforeach
        </div>
    @else
        <h1 class="page-header text-center m-b-40 m-t-30">Terminos del servicio</h1>

        <p style="text-align: justify;">
            
            La empresa <strong>{{ env("APP_NAME") }}</strong> ofrece un servicio de publicación de documentos electrónicos en la {{ env("APP_NAME") }}. Este servicio permite a los usuarios publicar documentos en formato PDF o DOCX para que sean accesibles por otros usuarios de la {{ env("APP_NAME") }}.

            Al utilizar el servicio de publicación de documentos electrónicos en la {{ env("APP_NAME") }}, usted acepta los siguientes términos y condiciones:<br />
            <ul>
                <li>Usted es responsable del contenido de los documentos que publica. La empresa no se responsabiliza de la exactitud, integridad o legalidad del contenido de los documentos publicados.</li>
                <li>Los documentos publicados deben cumplir con las políticas y procedimientos de la empresa. La empresa se reserva el derecho de eliminar cualquier documento que no cumpla con estas políticas y procedimientos.</li>
                <li>La empresa puede cambiar estos términos y condiciones en cualquier momento. Usted será notificado de cualquier cambio por correo electrónico o a través de la {{ env("APP_NAME") }}.</li>
            </ul>
        </p>
        
        <hr>

        <p class="mb-4">Adicional a la aceptación de términos del servicio, debe cambiar la contraseña, ya que es primer vez que ingresa al sistema de {{ env("APP_NAME") }}.</p>

        <form action="{{ route('service-terms-change-password') }}" method="POST">
            @csrf
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }} row m-b-15 col-sm-6">
                {!! Form::label('password', trans('Nueva contraseña').':', ['class' => 'col-form-label col-md-4 required']) !!}
                <div class="col-md-6{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input required="required" type="password" class="form-control" name="password">
                    <small>Ingrese una contraseña que tenga mínimo 8 caracteres, debe contener al menos una letra mayúscula, una minúscula, un número y un símbolo - /; 0) & @.?! %*.</small><br />
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }} row m-b-15 col-sm-6">
                {!! Form::label('password', trans('Confirmar nueva contraseña').':', ['class' => 'col-form-label col-md-4 required']) !!}
                <div class="col-md-6{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input required="required" type="password" name="password_confirmation" class="form-control">
                    <small>Por favor confirme la contraseña que ingresó.</small>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-12" style="display: flex; justify-content: center;">
                <button type="submit" class="btn btn-success"><i class="fa fa-check mr-2"></i>Aceptar términos y continuar</button>
            </div>
        </form>



    @endif

    

@endsection
