<!-- panel:Informacion de interes  -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title text-center" style="font-size: 20px">Encuesta de Percepción y Satisfacción del Usuario</h4> 
    </div>
    
    <div class="panel-heading ui-sortable-handle">
        <p>Cláusula de protección de datos personales: Los datos personales aquí consignados tiene carácter confidencial, razón por la cual es un deber y un compromiso de los participantes y de Empresas Públicas de Armenia ESP, no divulgar información alguna de los usuarios en propósitos diferentes al objetivo por la cual es diligenciado este registro, so pena de las sanciones legales a que haya lugar. Lo anterior en cumplimiento de las políticas de seguridad de la información de Empresas Públicas de Armenia ESP.(Ley 1581 DE 2012, reglamentada por Decreto 1377 de 2013).</p>
    </div>

    <div class="panel-heading ui-sortable-handle">
        <p>Reciba un cordial saludo, el objetivo del diligenciamiento de ésta encuesta es conocer el nivel de percepción que las partes interesadas y/o grupos de interés tiene acerca de la prestación de los servicios de Acueducto, Alcantarillado y Aseo, con el fin de evaluar los resultados y tomar las acciones necesarias para el mejoramiento del servicios a nuestros usuarios.Gracias por su colaboración.</p>        
    </div>
    
    <div class="panel-heading ui-sortable-handle">
        <h6 style="color: red">Obligatorio (*)</h6>
    </div>

    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de interés</strong></h4> 
    </div>

    <div class="panel-heading ui-sortable-handle">
        <h6>Señor ciudadano, la siguiente información le servirá para que resuelva la encuesta correctamente(sección de preguntas con calificación de estrellas):</h6><br>
    </div>

    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="row row-space-10 justify-content-center">
                <img src="{{ asset('assets/img/default/udc_recibo.png')}}" alt="" class="w-75 p-20"/>
            </div>

            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                {!! Form::label('number_account', trans('Ingrese el número de cuenta para consultas y pagos').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-8">
                    <input v-model ="dataForm.number_account" onkeyup="noLetters();" required="required" name="number_account"  minlength="5" maxlength="6" type="text" id="number_account" class="form-control">
                    <small>Ingrese solo números</small>
                </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- panel:informacion del ciudadano -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información del ciudadano</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <h6 style="margin-bottom: 30px">Sus datos son importantes para mejorar nuestro servicio.</h6>
        <div class="row">

            <!-- name Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('name', trans('Nombres y Apellidos').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- gender Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('gender', trans('Gender').':', ['class' => 'col-form-label col-md-3']) !!}  
                    <div class="col-md-8">
                        {!! Form::select('gender',['Masculino'=>'Masculino','Femenino'=>'Femenino','Otro'=>'Otro'],null,['class'=>'form-control','v-model' => 'dataForm.gender']) !!}
                    </div>
                </div>
            </div>

            <!-- email Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        {!! Form::email('email', null, ['class' => 'form-control', 'v-model' => 'dataForm.email', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- direction_state Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('direction_state', trans('Direction State').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        {!! Form::text('direction_state', null, ['class' => 'form-control', 'v-model' => 'dataForm.direction_state', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- phone Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('phone', trans('Phone').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8">
                        {!! Form::text('phone', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone']) !!}
                    </div>
                </div>
            </div>

            <!-- suscriber quality Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('suscriber_quality', trans('¿Qué calidad de Suscriptor cumple?').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        {!! Form::select('suscriber_quality',['Propietario'=>'Propietario','Arrendatario'=>'Arrendatario'],null,['class'=>'form-control','v-model' => 'dataForm.suscriber_quality','required' => true]) !!} 
                    </div>                                       
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- panel:servicio prestado -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>En cuanto al servicio prestado</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <h6 style="margin-bottom: 30px">¿Empresas públicas de Armenia ESP, le presta el servicio de?:</h6>
        <div class="row">

    <!-- select: Acueducto -->
    <div class="col-md-12" >
        <div class="form-group row m-b-15">
        {!! Form::label('aqueduct', trans('Aqueduct').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-8" >
                {!! Form::select('aqueduct',['Si'=>'Si','No'=>'No'],null,['class'=>'form-control','v-model' => 'dataForm.aqueduct']) !!}                            
            </div>
        </div>
    </div>

            <!-- select: Alcantarillado -->
            <div class="col-md-12">
                <div class="form-group row m-b-15" >
                {!! Form::label('sewerage', trans('Sewerage').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8" >
                        {!! Form::select('sewerage',['Si'=>'Si','No'=>'No'],null,['class'=>'form-control','v-model' => 'dataForm.sewerage']) !!}                       
                    </div>
                </div>
            </div>

            <!-- select: Aseo -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('cleanliness', trans('Cleanliness').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8" >
                        {!! Form::select('cleanliness',['Si'=>'Si','No'=>'No'],null,['class'=>'form-control','v-model' => 'dataForm.cleanliness']) !!}                       
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- end panel-body -->
</div>


<!-- panel:calificacion en cuanto al servicio -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Servicio acueducto y alcantarillado</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="row">
            
            <!-- calificacion: prestacion del servicio de acueducto y alcantarillado -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                {!! Form::label('aqueduct_benefit_service', '¿Cómo califica la prestación del servicio de acueducto y alcantarillado?' .':', ['class' => 'col-form-label col-md-5']) !!}
                <vue-feedback-reaction v-model="dataForm.aqueduct_benefit_service" :labels="['Malo','Regular','Bueno','Excelente','o']"></vue-feedback-reaction>
                </div>
            </div>

            <!-- calificacion: continuidad en la prestacion del servicio de acueducto  -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                {!! Form::label('aqueduct_continuity_service', '¿Cómo considera usted la continuidad en la prestación del servicio de acueducto, medido este en la cantidad de horas?' .':', ['class' => 'col-form-label col-md-5']) !!}
                <vue-feedback-reaction v-model="dataForm.aqueduct_continuity_service" :labels="['Malo','Regular','Bueno','Excelente','o']"></vue-feedback-reaction>
                {{-- <star-rating v-model="dataForm.aqueduct_continuity_service" v-bind:max-rating="4" animate rounded-corners></star-rating> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- panel:calificacion Servicio de aseo -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Servicio de aseo</strong></h4>
    </div>
    <!-- end panel-heading -->
    
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- calificacion: ¿La oportunidad fue?(Llegarón a tiempo)  -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                {!! Form::label('chance', '¿Cómo califica usted la calidad del servicio público de aseo domiciliario (vías, separadores, parques, calles, otros) en su lugar de residencia?' .':', ['class' => 'col-form-label col-md-5']) !!}
                <vue-feedback-reaction v-model="dataForm.chance" :labels="['Malo','Regular','Bueno','Excelente','o']"></vue-feedback-reaction>
                {{-- <star-rating v-model="dataForm.chance" v-bind:max-rating="4" animate rounded-corners></star-rating> --}}
                </div>
            </div>

            <!-- calificacion: Efectividad con la que arreglarron el daño  -->
            <div class="col-md-12" >
                <div class="form-group row m-b-15">
                {!! Form::label('reports_effectiveness', '¿Cómo califica usted el servicio de recoleccion de residuos sólidos de acuerdo con los horarios y los dias establecidos?' .':', ['class' => 'col-form-label col-md-5']) !!}
                <vue-feedback-reaction v-model="dataForm.reports_effectiveness" :labels="['Malo','Regular','Bueno','Excelente','o']"></vue-feedback-reaction>
                {{-- <star-rating v-model="dataForm.reports_effectiveness" v-bind:max-rating="4" animate rounded-corners></star-rating> --}}
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>


<!-- panel:calificacion Servicio de aseo -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Reporte de daños</strong></h4>
    </div>
    <!-- end panel-heading -->
    
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- select: Aseo -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('damage_report', trans('have_reported').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8" >
                        {!! Form::select('damage_report',['Si'=>'Si','No'=>'No'],null,['class'=>'form-control','v-model' => 'dataForm.damage_report','required']) !!}                       
                    </div>
                </div>
            </div>

            <!-- calificacion: ¿La oportunidad fue?(Llegarón a tiempo)  -->
            <div class="col-md-12" v-if="dataForm.damage_report=='Si'">
                <div class="form-group row m-b-15">
                {!! Form::label('they_arrived_time', '¿La oportunidad fue?(Llegarón a tiempo)'.':', ['class' => 'col-form-label col-md-5']) !!}
                <vue-feedback-reaction v-model="dataForm.they_arrived_time" :labels="['Malo','Regular','Bueno','Excelente','o']"></vue-feedback-reaction>
                {{-- <star-rating v-model="dataForm.they_arrived_time" v-bind:max-rating="4" animate rounded-corners></star-rating> --}}
                </div>
            </div>

            <!-- calificacion: Efectividad con la que arreglarron el daño  -->
            <div class="col-md-12" v-if="dataForm.damage_report=='Si'">
                <div class="form-group row m-b-15">
                {!! Form::label('fixed_damage', '¿La efectividad fue? (Arreglaron bien el daño)' .':', ['class' => 'col-form-label col-md-5']) !!}
                <vue-feedback-reaction v-model="dataForm.fixed_damage" :labels="['Malo','Regular','Bueno','Excelente','o']"></vue-feedback-reaction>
                {{-- <star-rating v-model="dataForm.fixed_damage" v-bind:max-rating="4" animate rounded-corners></star-rating> --}}
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>