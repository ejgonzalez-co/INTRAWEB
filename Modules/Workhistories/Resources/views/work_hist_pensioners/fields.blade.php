<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general del pensionado</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- name Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                        <small>Ingrese el nombre del pensionado</small>
                    </div>
                </div>
            </div>

            <!-- surname Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('surname', trans('Surname').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('surname', null, ['class' => 'form-control', 'v-model' => 'dataForm.surname', 'required' => true]) !!}
                        <small>Ingrese el apellido del pensionado</small>
                    </div>
                </div>
            </div>

            <!-- Type Document Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('type_document', trans('Type Document').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.type_document" name="type_document" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                            <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                            <option value="Cédula extranjera">Cédula extranjera</option>
                        </select>
                        <small>Seleccione el tipo de documento del pensionado</small>
                    </div>
                </div>
            </div>

            <!-- number_document Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('number_document', trans('Number Document').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('number_document', null, ['class' => 'form-control', 'v-model' => 'dataForm.number_document', 'required' => true]) !!}
                        <small>Ingrese el número de documento</small>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('date_document', trans('Fecha de de expedición del').' '.trans('document').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::date('date_document', null, ['class' => 'form-control', 'id' => 'date_document',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_document', 'required' => true]) !!}
                        <small>Seleccione la fecha de de expedición del documento</small>
                    </div>
                </div>
            </div>


            <!-- birth_date Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('birth_date', trans('Birth Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::date('birth_date', null, ['class' => 'form-control', 'id' => 'birth_date',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.birth_date', 'required' => true]) !!}
                        <small>Seleccione la fecha de nacimiento del pensionado</small>
                    </div>
                </div>
            </div>



            <!-- Gender Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('gender', trans('Gender').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.gender" name="gender" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Hombre">Hombre</option>
                            <option value="Mujer">Mujer</option>
                            <option value="Indefinido">Indefinido</option>
                            
                        </select>
                        <small>Seleccione el genéro del pensionado</small>
                    </div>
                </div>
            </div>

            <!-- rh otrs Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('rh', trans('RH').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">

                        <select v-model="dataForm.rh" name="rh" class="form-control">  
                                <option value="">Seleccione</option>
                                <option value="O-">O-</option>
                                <option value="A-">A-</option>
                                <option value="B-">B-</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="A+">A+</option>
                                <option value="B+">B+</option>
                                <option value="AB+">AB+</option>
                        </select>
                        <small>Seleccione el RH</small>
                    </div>
                </div>
            </div>          

            <!-- state_civil Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('state_civil', trans('State').' civil:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.state_civil" name="state_civil" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Ninguno">Ninguno</option>
                            <option value="Soltero(a)">Soltero(a)</option>
                            <option value="Casado(a)">Casado(a)</option>
                            <option value="Divorciado (a)">Divorciado(a)</option>
                            <option value="Unión libre(a)">Unión libre(a)</option>
                            <option value="Viudo(a)">Viudo(a)</option>

                        </select>
                    </div>
                </div>
            </div>

            <!-- group_ethnic Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('group_ethnic', trans('Group Ethnic').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.group_ethnic" name="group_ethnic" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Ninguno">Ninguno</option>
                            <option value="Comunidades indígenas">Comunidades indígenas</option>
                            <option value="Comunidades negras o afrocolombianas">Comunidades negras o afrocolombianas</option>
                            <option value="Comunidad raizal">Comunidad raizal</option>
                            <option value="Pueblo Rom o Gitano">Pueblo Rom o Gitano</option>                                
                        </select>
                        <small>Seleccione el grupo étnico del pensionado</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de contacto del pensionado</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- phone Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('phone', trans('Phone').' 1:', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('phone', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone', 'required' => true]) !!}
                        <small>Ingrese el teléfono del pensionado</small>
                    </div>
                </div>
            </div>

            <!-- phone Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('phone2', trans('Phone').' 2:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('phone2', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone2']) !!}
                        <small>Ingrese el teléfono del pensionado</small>
                    </div>
                </div>
            </div>

            <!-- address Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('address', trans('Address').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('address', null, ['class' => 'form-control', 'v-model' => 'dataForm.address', 'required' => true]) !!}
                        <small>Ingrese la dirección del pensionado</small>
                    </div>
                </div>
            </div>


            <!-- email Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::email('email', null, ['class' => 'form-control', 'v-model' => 'dataForm.email', 'required' => false]) !!}
                        <small>Ingrese el email del pensionado</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de estudios</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
           
            <!-- level_study Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('level_study', trans('Level study').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.level_study" name="level_study" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Primaria">Primaria</option>
                            <option value="Secundaria">Secundaria</option>
                            <option value="Técnico">Técnico</option>
                            <option value="Tecnólogo">Tecnólogo</option>
                            <option value="Pregrado">Pregrado</option>
                            <option value="Postgrado">Postgrado</option>
                        </select>
                        <small>Seleccione el nivel de estudio del pensionado</small>
                    </div>
                </div>
            </div>

            
            <!-- nivel estudio otros Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('level_study_other', trans('Titulo adquirido o grado de escolaridad').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('level_study_other', null, ['class' => 'form-control', 'v-model' => 'dataForm.level_study_other', 'required' => false]) !!}
                        <small>Ingrese el titulo adquirido o grado de escolaridado</small>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>



<div class="panel" data-sortable-id="ui-general-1">
<!-- begin panel-heading -->
<div class="panel-heading ui-sortable-handle">
    <h4 class="panel-title"><strong>Datos Laborales</strong></h4>
</div>
<!-- end panel-heading -->
<!-- begin panel-body -->
<div class="panel-body">
    <div class="row">
    
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('date_admission', trans('Date admission').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::date('date_admission', null, ['class' => 'form-control', 'id' => 'date_admission',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_admission', 'required' => true]) !!}
                        <small>Seleccione la fecha de ingreso del pensionado</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('type_laboral', trans('Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.type_laboral" name="type_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Libre Nombramiento">Libre Nombramiento</option>
                            <option value="Trabajador oficial">Trabajador oficial</option>
                        </select>
                        <small>Seleccione el tipo</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12" v-if="dataForm.type_laboral=='Libre Nombramiento'">
                <div class="form-group row m-b-15">
                {!! Form::label('level_laboral', trans('Level').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.level_laboral" name="level_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Asesor">Asesor</option>
                            <option value="Directivo">Directivo</option>
                            <option value="Profesional">Profesional</option>
                        </select>
                        <small>Seleccione el nivel</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12" v-else>
                <div class="form-group row m-b-15">
                {!! Form::label('level_laboral', trans('Level').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.level_laboral" name="level_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Asistencial">Asistencial</option>
                            <option value="Profesional">Profesional</option>
                            <option value="Ténico">Ténico</option>
                        </select>
                        <small>Seleccione el nivel</small>
                    </div>
                </div>
            </div>


            <!---Denominacion nomenclatura legal-->

            <div class="col-md-12" v-if="(dataForm.level_laboral=='Asesor' || dataForm.level_laboral=='Directivo' || dataForm.level_laboral=='Profesional') && dataForm.type_laboral=='Libre Nombramiento'">
                <div class="form-group row m-b-15">
                {!! Form::label('denomination_laboral', trans('Denomination').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">

                        <select v-if="dataForm.level_laboral=='Asesor'" v-model="dataForm.denomination_laboral" name="denomination_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Jefe de Oficina (Director) - 105">Jefe de Oficina (Director) - 105</option>
                        </select>


                        <select v-else-if="dataForm.level_laboral=='Directivo'" v-model="dataForm.denomination_laboral" name="denomination_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Gerente General - 50">Gerente General - 50</option>
                            <option value="Subgerente - 90">Subgerente - 90</option>
                            <option value="Director - 9">Director - 9</option>
                            <option value="Jefe de Oficina - 6">Jefe de Oficina - 6</option>

                        </select>

                        <select v-else-if="dataForm.level_laboral=='Profesional'" v-model="dataForm.denomination_laboral" name="denomination_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Profesional">Profesional</option>
                            <option value="Profesional Especializado (Gestor) - 222">Profesional Especializado (Gestor) - 222</option>
                            <option value="Profesional Especializado (Asistente de Gerencia) - 222">Profesional Especializado (Asistente de Gerencia) - 222</option>
                            <option value="Tesorero General - 201">Tesorero General - 201</option>
                        </select>

                        <small>Seleccione la denominación (Nomenclatura Legal)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12"  v-if="(dataForm.level_laboral=='Asistencial' || dataForm.level_laboral=='Profesional' || dataForm.level_laboral=='Ténico') && dataForm.type_laboral=='Trabajador oficial'">
                <div class="form-group row m-b-15">
                {!! Form::label('denomination_laboral', trans('Denomination').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">

                        <select v-if="dataForm.level_laboral=='Asistencial'" v-model="dataForm.denomination_laboral" name="denomination_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Auxiliar Administrativo - 407">Auxiliar Administrativo - 407</option>
                            <option value="Operario - 487">Operario - 487</option>
                            <option value="Conductor - 480">Conductor - 480</option>
                            <option value="Conductor Mecánico - 482">Conductor Mecánico - 482</option>
                            <option value="Conductor de Gerencia - 480">Conductor de Gerencia - 480</option>
                            <option value="Celador - 477">Celador - 477</option>
                            <option value="Auxiliar de Servicios Generales - 470">Auxiliar de Servicios Generales - 470</option>
                        </select>

                        <select v-else-if="dataForm.level_laboral=='Profesional'" v-model="dataForm.denomination_laboral" name="denomination_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Profesional">Profesional</option>
                            <option value="Profesional Especializado - 222">Profesional Especializado - 222</option>
                            <option value="Profesional Universitario - 219">Profesional Universitario - 219</option>
                        </select>

                        <select v-else-if="dataForm.level_laboral=='Ténico'" v-model="dataForm.denomination_laboral" name="denomination_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="Técnico Administrativo - 367">Técnico Administrativo - 367</option>
                            <option value="Técnico Operativo - 314">Técnico Operativo - 314</option>
                        </select>

                        <small>Seleccione la denominación (Nomenclatura Legal)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12" v-if= "dataForm.denomination_laboral!==''">
                <div class="form-group row m-b-15">
                {!! Form::label('grade_laboral', trans('Grade').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select v-model="dataForm.grade_laboral" name="grade_laboral" required="required" class="form-control">  
                            <option value="">Seleccione</option>
                            <option value="N/A">N/A</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                        </select>
                        <small>Seleccione el grado</small>
                    </div>
                </div>
            </div>


            <!-- Id Dependencia Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('dependencias_id', trans('Dependency').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="dependencias_id" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="dataForm" :is-required="true" ></select-check>
                        <small>Seleccione la dependencia a la cual pertenece el pensionado.</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('numer_account', trans('Número de cuenta bancaria').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('numer_account', null, ['class' => 'form-control', 'v-model' => 'dataForm.numer_account', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

            <!--<div class="col-md-6">
                <div class="form-group row m-b-15">
                {!! Form::label('area', trans('Área').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('area', null, ['class' => 'form-control', 'v-model' => 'dataForm.area', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>-->

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                {!! Form::label('salary', trans('Salary').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('salary', null, ['class' => 'form-control', 'v-model' => 'dataForm.salary', 'required' => true]) !!}
                        <small></small>
                    </div>
                </div>
            </div>

    </div>
</div>

</div>


<div class="panel" data-sortable-id="ui-general-1">
<!-- begin panel-heading -->
<div class="panel-heading ui-sortable-handle">
    <h4 class="panel-title"><strong>Seguridad Social Integral y Embargos</strong></h4>
</div>
<!-- end panel-heading -->
<!-- begin panel-body -->
<div class="panel-body">
    <div class="row">
    
        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('health', trans('Health').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select v-model="dataForm.health" name="health" required="required" class="form-control">  
                        <option value="">Seleccione</option>
                        <option value="Sanitas">Sanitas</option>
                        <option value="Sura">Sura</option>
                        <option value="Nueva EPS">Nueva EPS</option>
                        <option value="Coomeva">Coomeva</option>
                        <option value="Salud total">Salud total</option>
                        <option value="Salud vida">Salud vida</option>
                        <option value="Asmet salud">Asmet salud</option>
                        <option value="Medimas">Medimas</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('arl', trans('ARL').':', ['class' => 'col-form-label col-md-3 required']) !!}
                
                <div class="col-md-9">
                    <select v-model="dataForm.arl" name="arl" required="required" class="form-control">  
                        <option value="">Seleccione</option>
                        <option value="Positiva">Positiva</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('pension', trans('Pension').':', ['class' => 'col-form-label col-md-3 required']) !!}
                
                <div class="col-md-9">
                    <select v-model="dataForm.pension" name="pension" required="required" class="form-control">  
                        <option value="">Seleccione</option>
                        <option value="Colpensiones">Colpensiones</option>
                        <option value="Fondo Nacional del Ahorro">Fondo Nacional del Ahorro</option>
                        <option value="Protección">Protección</option>
                        <option value="Porvenir">Porvenir</option>
                        <option value="Horizonte">Horizonte</option>

                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('layoffs', trans('Layoffs').':', ['class' => 'col-form-label col-md-3 required']) !!}

                <div class="col-md-9">
                    <select v-model="dataForm.layoffs" name="layoffs" required="required" class="form-control">  
                        <option value="">Seleccione</option>
                        <option value="Fondo Nacional del Ahorro">Fondo Nacional del Ahorro</option>
                        <option value="Porvenir">Porvenir</option>
                        <option value="Protección">Protección</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
                {!! Form::label('embargo', trans('Embargo').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select v-model="dataForm.embargo" name="embargo" required="required" class="form-control">  
                        <option value="">Seleccione</option>
                        <option value="No">No</option>
                        <option value="Si">Si</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6" v-if="dataForm.embargo=='Si'">
            <div class="form-group row m-b-15">
            {!! Form::label('embargo_value', trans('Value').' embargo:', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('embargo_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.embargo_value', 'required' => true]) !!}
                    <small></small>
                </div>
            </div>
        </div>

        <div class="col-md-6" v-if="dataForm.embargo=='Si'">
            <div class="form-group row m-b-15">
            {!! Form::label('embargo_owner', trans('Entity').' embargo:', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('embargo_owner', null, ['class' => 'form-control', 'v-model' => 'dataForm.embargo_owner', 'required' => true]) !!}
                    <small></small>
                </div>
            </div>
        </div>


    </div>
</div>

</div>



<div class="panel" data-sortable-id="ui-general-1">
<!-- begin panel-heading -->
<div class="panel-heading ui-sortable-handle">
    <h4 class="panel-title"><strong>Notificar en caso de evento</strong></h4>
</div>
<!-- end panel-heading -->
<!-- begin panel-body -->
<div class="panel-body">
    <div class="row">
    
        <!-- name_event Field -->
        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('name_event', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('name_event', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_event', 'required' => true]) !!}
                    <small></small>
                </div>
            </div>
        </div>

        <!-- phone_event Field -->
        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('phone_event', trans('Phone').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('phone_event', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone_event', 'required' => true]) !!}
                    <small></small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('address_event', trans('Address').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('address_event', null, ['class' => 'form-control', 'v-model' => 'dataForm.address_event', 'required' => true]) !!}
                    <small></small>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group row m-b-15">
            {!! Form::label('relationship_event', trans('Relationship').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('relationship_event', null, ['class' => 'form-control', 'v-model' => 'dataForm.relationship_event', 'required' => true]) !!}
                    <small></small>
                </div>
            </div>
        </div>

        
    </div>
</div>

</div>


<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información de fallecimiento del pensionado</strong> <b class="text-red">(Diligencie esta sección solo en caso de fallecimiento del pensionado)</b></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <!-- deceased Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('deceased', trans('Deceased').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8">
                        <select v-model="dataForm.deceased" name="deceased" class="form-control">  
                            <option value="No">No</option>
                            <option value="Si">Si</option>
                        </select>
                        <small>Seleccione si el pensionado falleció</small>
                    </div>
                </div>
            </div>

            <!-- observation_deceased Field -->
            <div class="col-md-12" v-if="dataForm.deceased=='Si'">
                <div class="form-group row m-b-15">
                {!! Form::label('observation_deceased', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8">
                        {!! Form::text('observation_deceased', null, ['class' => 'form-control', 'v-model' => 'dataForm.observation_deceased', 'required' => false]) !!}
                        <small>Ingrese una observación corta sobre el fallecimiento del pensionado</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
