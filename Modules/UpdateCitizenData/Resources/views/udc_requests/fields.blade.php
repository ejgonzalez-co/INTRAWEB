
<p class="text-center"><b>Encuesta Actualización de Datos Personales</b></p>
<p class="text-justify">
    Cláusula de protección de datos personales: Los datos personales aquí consignados tiene carácter confidencial, razón por la cual es un deber y un compromiso de los participantes y de Empresas Publicas de Armenia ESP, no divulgar información alguna o usuaria en propósitos diferentes al objetivo por la cual es diligenciado este registro, so pena de las sanciona legales a que haya lugar. Lo anterior en cumplimiento de las políticas de seguridad de la información de Empresas Publicas de Armenia ESP.(Ley 1581 DE 2012, reglamentada por Decreto 1377 de 2013).<br>
    Reciba un cordial saludo, el objetivo del diligenciamiento de este formato es realizar la actualización de datos de todos nuestros usuarios , con el fin de contar con información real y pertinente que permita tener un contacto mas cercano . gracias por su colaboración.
</p>
<p class="text-danger">* Obligatorio</p>
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="row row-space-10 justify-content-center">
                <img src="{{ asset('assets/img/default/udc_recibo.png')}}" alt="" class="w-75 p-20"/>
            </div>
        
            <!-- Payment Account Number Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('payment_account_number', trans('Payment Account Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <input v-model ="dataForm.payment_account_number" onkeyup="noLetters();" required="required" name="payment_account_number"  minlength="5" maxlength="6" type="text" id="payment_account_number" class="form-control">
                        <small>Ingrese solo números</small>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos personales del ciudadano</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- Subscriber Quality Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('subscriber_quality', trans('Subscriber Quality').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.subscriber_quality" name="subscriber_quality" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Propietario">Propietario</option>
                            <option value="Arrendatario">Arrendatario</option>
                        </select>
                        <small></small>
                    </div>
                </div>
            </div>

            <!-- Citizen Name Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('citizen_name', trans('Citizen Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('citizen_name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'v-model' => 'dataForm.citizen_name', 'required' => true]) !!}
                    </div>
                </div>
            </div>


            <!-- Document Type Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('document_type', trans('Document Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.document_type" name="document_type" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                            <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Nit">Nit</option>
                        </select>
                    </div>
                </div>
            </div>


            <!-- Identification Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('identification', trans('Identification').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('identification', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'v-model' => 'dataForm.identification', 'required' => true]) !!}
                    </div>
                </div>
            </div>



            <!-- Gender Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('gender', trans('Gender').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.gender" name="gender" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Masculino">Masculino</option>
                            <option value="No determinado">No determinado</option>
                        </select>
                        <small></small>
                    </div>
                </div>
            </div>


            <!-- Telephone Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('telephone', trans('Telephone').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('telephone', null, ['class' => 'form-control','maxlength' => 45,'maxlength' => 45, 'v-model' => 'dataForm.telephone', 'required' => true]) !!}
                    </div>
                </div>
            </div>


            <!-- Email Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::email('email', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'v-model' => 'dataForm.email', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- Date Birth Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('date_birth', trans('Birth Date').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::date('date_birth', null, ['class' => 'form-control', 'id' => 'date_birth',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_birth']) !!}
                        <small></small>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>

