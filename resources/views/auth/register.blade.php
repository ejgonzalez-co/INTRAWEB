<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Login | {{ config('app.name') }}</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	{!!Html::style('assets/css/default/app.min.css')!!}
	<!-- ================== END BASE CSS STYLE ================== -->
</head>
<body class="pace-top">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show">
		<span class="spinner"></span>
	</div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin login -->
		<div id="app" class="login login-with-news-feed">
			<!-- begin news-feed -->
            @php
            $ultima_configuracion = DB::table('configuration_general')->latest()->first();
            @endphp
			<div class="news-feed" style="right: 1000px;">
				{{-- <div class="news-image" style="background-image: url(../assets/img/default/login_banner_vuv.png)"></div> --}}
                <div class="news-image" style="background-image: url({{ asset('storage')}}/{{ $ultima_configuracion->imagen_fondo }})"></div>


				<div class="news-caption">
					<h4 class="caption-title"><b></b> </h4>
					<p>

					</p>
				</div>
			</div>
			<!-- end news-feed -->
			<!-- begin right-content -->
			<div class="right-content" style="width: 1000px;">
				<!-- begin login-header -->
				<div class="login-header">
                    <a href="/login" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left mr-2"></i>@lang('back')</a>

                    <div class="brand">
                      <img src="{{ asset('assets/img/default/icon_login_vuv.png') }}" style="width:30px;height:auto">
                      <b>{{ config('app.name') }}</b>
                      <small>Formulario para el registro de ciudadanos</small>
                    </div>

                    @if ($errors->has('email'))
                        <div class="">
                            <div class="d-flex justify-content-start align-items-center pt-3">
                                <div class="alert alert-info rounded-pill text-center" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i> Su cuenta ya ha sido creada. Para establecer o cambiar su contraseña, siga este enlace:
                                    <a href="/password/reset">Cambiar mi contraseña</a>
                                </div>
                            </div>
                        </div>
                    @endif
                  </div>

				<!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
                    <register-component inline-template>
                        <div>
                            <form method="post" action="{{ url('/register') }}">
                                @csrf
                                <div class="row">
                                    <!--  Type Person Field -->
                                    <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('type_person', trans('Type Person').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('type_person') ? ' has-error' : '' }}">
                                            <select v-model="dataForm.type_person" @change="asignarTipoDocumento()" name="type_person" id="type_person" required="required" class="form-control" value="{{ old('type_person') }}">
                                                <option value="1">Persona natural</option>
                                                <option value="2">Persona jurídica</option>
                                            </select>
                                            <small>@lang('Select the') tipo de persona</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('type_person'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('type_person') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!--  Type Document Field -->
                                    <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('type_document', trans('Type Document').':', ['class' => 'col-form-label col-md-4 required',]) !!}
                                        <div class="col-md-8{{ $errors->has('type_document') ? ' has-error' : '' }}">
                                            <select v-model="dataForm.type_document" name="type_document" required="required" class="form-control" value="{{ old('type_document') }}">
                                                <option value="1">Cédula de ciudadanía</option>
                                                <option value="2">Cédula de Extranjería</option>
                                                <option value="3">Tarjeta Identidad</option>
                                                <option value="7">NIUP- Número Unico de identificación personal</option>
                                                <option value="8">PPT - Permiso por Protección Temporal</option>
                                                <option value="4">Pasaporte</option>
                                                <option value="5">NIT</option>
                                                <option value="6">Otro</option>
                                            </select>
                                            <small>@lang('Select the') el tipo de documento</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('type_document'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('type_document') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>


                    
                                <!-- Document Number Field -->
                                <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('document_number', trans('Document Number').':', ['class' => 'col-form-label col-md-4 required', 'v-if' => 'dataForm.type_person == 1 || !dataForm.type_person']) !!}
                                        {!! Form::label('document_number', trans('NIT').':', ['class' => 'col-form-label col-md-4 required', 'v-else']) !!}
                                        <div class="col-md-8{{ $errors->has('document_number') ? ' has-error' : '' }}">
                                            <input required="required" name="document_number" type="text" id="document_number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" value="{{ old('document_number') }}">
                                            <small>@lang('Enter the') número de documento</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('document_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('document_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- First Name Field -->
                                    <div v-if="dataForm.type_person == 1 || !dataForm.type_person" class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('first_name', trans('First Name').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                            <input required="required" name="first_name" type="text" id="first_name" class="form-control" value="{{ old('first_name') }}">
                                            <small>@lang('Enter the') el primer nombre</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('first_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Second Name Field -->
                                    <div v-if="dataForm.type_person == 1 || !dataForm.type_person" class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('second_name', trans('Second Name').':', ['class' => 'col-form-label col-md-4']) !!}
                                        <div class="col-md-8{{ $errors->has('second_name') ? ' has-error' : '' }}">
                                            <input name="second_name" type="text" id="second_name" class="form-control" value="{{ old('second_name') }}">
                                            <small>@lang('Enter the') el segundo nombre</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('second_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('second_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- First Surname Field -->
                                    <div v-if="dataForm.type_person == 1 || !dataForm.type_person" class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('first_surname', trans('First Surname').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('first_surname') ? ' has-error' : '' }}">
                                            <input required="required" name="first_surname" type="text" id="first_surname" class="form-control" value="{{ old('first_surname') }}">
                                            <small>@lang('Enter the') el primer apellido</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('first_surname'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('first_surname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Second Surname Field -->
                                    <div v-if="dataForm.type_person == 1 || !dataForm.type_person" class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('second_surname', trans('Second Surname').':', ['class' => 'col-form-label col-md-4']) !!}
                                        <div class="col-md-8{{ $errors->has('second_surname') ? ' has-error' : '' }}">
                                            <input name="second_surname" type="text" id="second_surname" class="form-control" value="{{ old('second_surname') }}">
                                            <small>@lang('Enter the') el segundo apellido</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('second_surname'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('second_surname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Name Field -->
                                    <div v-else class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('name', trans('Razón social').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <input required="required" name="name" type="text" id="name" class="form-control" value="{{ old('name') }}">
                                            <small>@lang('Enter the') el nombre de la razón social</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Email Field -->
                                    <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <input required="required" name="email" type="email" id="email" class="form-control" class="form-control" value="{{ old('email') }}">
                                            <small>@lang('Enter the') el correo electrónico</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- States Id Field -->
                                    <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('states_id', trans('Department').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('states_id') ? ' has-error' : '' }}">
                                            <select-check
                                                css-class="form-control"
                                                name-field="states_id"
                                                reduce-label="name"
                                                reduce-key="id"
                                                name-resource="/get-states-by-country/48"
                                                :value="dataForm"
                                                :is-required="true"
                                                :enable-search="true"
                                                :ids-to-empty="['city_id']">
                                            </select-check>
                                            <input type="hidden" name="states_id" :value="dataForm.states_id">
                                            <small class="form-text text-muted">@lang('Select the') el departamento</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('states_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('states_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- City Id Field -->
                                    <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('city_id', trans('City').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('city_id') ? ' has-error' : '' }}">
                                            <select-check
                                                css-class="form-control"
                                                name-field="city_id"
                                                reduce-label="name"
                                                reduce-key="id"
                                                :name-resource="'/get-cities-by-state/'+dataForm.states_id"
                                                :value="dataForm"
                                                :key="dataForm.states_id"
                                                :is-required="true"
                                                :enable-search="true">
                                            </select-check>
                                            <input type="hidden" name="city_id" :value="dataForm.city_id">
                                            <small class="form-text text-muted">@lang('Select the') la ciudad</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('city_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('city_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Address Field -->
                                    <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('address', trans('Address').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('address') ? ' has-error' : '' }}">
                                            <input required="required" name="address" type="text" id="address" class="form-control" value="{{ old('address') }}">
                                            <small>@lang('Enter the') la dirección</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('address'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Phone Field -->
                                    <div class="form-group row m-b-15 col-sm-6">
                                        {!! Form::label('phone', trans('Phone').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('phone') ? ' has-error' : '' }}">
                                            <input required="required" name="phone" type="text" id="phone" class="form-control" value="{{ old('phone') }}">
                                            <small>@lang('Enter the') el número telefónico</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }} row m-b-15 col-sm-6">
                                        {!! Form::label('password', trans('Contraseña').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <input required="required" type="password" class="form-control" name="password">
                                            <small>Ingrese una contraseña que tenga mínimo 12 caracteres, debe contener al menos una letra mayúscula, una minúscula, un número y un símbolo - /; 0) & @.?! %*.</small><br />
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }} row m-b-15 col-sm-6">
                                        {!! Form::label('password', trans('Confirmar contraseña').':', ['class' => 'col-form-label col-md-4 required']) !!}
                                        <div class="col-md-8{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <input required="required" type="password" name="password_confirmation" class="form-control">
                                            <small>Por favor confirme la contraseña que ingresó.</small>
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('password_confirmation'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('captcha') ? ' has-error' : '' }} row m-b-15 col-sm-12">
                                        {!! Form::label('captcha', trans('Captcha').':', ['class' => 'col-form-label col-md-2 required']) !!}
                                        <div class="col-md-10 pl-1{{ $errors->has('captcha') ? ' has-error' : '' }}">
                                            <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-7">
                                        <div class="checkbox icheck">
                                            <label>
                                                <input required="required" type="checkbox" id="terminos_condiciones" name="terminos_condiciones" value="{{ old('terminos_condiciones') }}"> Acepto los <a href="#" data-toggle="modal" data-target="#terminos_condiciones_modal">términos y condiciones</a>
                                            </label>
                                        </div>
                                        <div class="checkbox icheck">
                                            <label>
                                                <input type="checkbox" name="solicitudes_via_email" id="solicitudes_via_email" value="{{ old('solicitudes_via_email') }}"> Autorizo a las Empresas Públicas a enviarme notificaciones sobre las respuestas a través de correo electrónico.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="terminos_condiciones_modal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Política de Privacidad y de Términos y Condiciones de Uso de mis datos.</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @include('includes.terminos_condiciones')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <br />
                                <div class="row">
                                    <!-- /.col -->
                                    <div class="col-xs-5">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat">Registrarme como ciudadano</button>
                                    </div>
                                    <!-- /.col -->
                                </div>

                            </form>
                        </div>
                    </register-component>
                    <a href="{{ url('/login') }}" class="text-center">Ya tengo una cuenta</a>
                </div>
                <!-- end login-content -->
            </div>
            <!-- end right-container -->
        </div>
        <!-- end login -->

        <!-- begin scroll to top btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <!-- end scroll to top btn -->
    </div>
    <!-- end page container -->
<script type="text/javascript">
    window.oldData = @json(old());
</script>
<!-- ================== BEGIN BASE JS ================== -->
{!!Html::script('assets/js/app.min.js')!!}
{!!Html::script('assets/js/theme/default.min.js')!!}
<!-- ================== END BASE JS ================== -->
{{-- Se importa el archivo JS para poder usar Vue --}}
<script src="{{ mix('js/app.js') }}"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
</body>
<script type="text/javascript">


    $(".btn-refresh").click(function(){
      $.ajax({
         type:'GET',
         url:'/refresh_captcha',
         success:function(data){
            $(".captcha span").html(data.captcha);
         }
      });
    });


    </script>
</html>

<style>
    .form-group .has-error .help-block {
        color: #dd4b39
    }

    .form-group .has-error .form-control {
        border-color: #dd4b39;
        box-shadow: none
    }

    label.required:after {
        color: #cc0000;
        content: "*";
        font-weight: bold;
        margin-left: 5px;
    }
</style>
