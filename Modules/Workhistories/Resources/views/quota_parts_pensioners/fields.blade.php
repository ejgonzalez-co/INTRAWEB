<div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Información general del pensionado</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <!-- Type Document Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('type_document', trans('Type Document').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            <select v-model="dataForm.type_document" name="type_document" required="required" class="form-control">  
                                <option value="">Seleccione</option>
                                <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                                <option value="Cédula extranjera">Cédula extranjera</option>
                            </select>
                            <small>Seleccione el tipo de documento del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- number_document Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('number_document', trans('Number Document').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::text('number_document', null, ['class' => 'form-control', 'v-model' => 'dataForm.number_document', 'required' => true]) !!}
                            <small>Ingrese el número de documento</small>
                        </div>
                    </div>
                </div>

                
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('date_document', trans('Fecha de de expedición del').' '.trans('document').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        {!! Form::date('date_document', null, ['class' => 'form-control', 'id' => 'date_document',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_document', 'required' => true]) !!}
                        <small>Seleccione la fecha de de expedición del documento</small>
                    </div>
                </div>
            </div>


                <!-- birth_date Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('birth_date', trans('Birth Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::date('birth_date', null, ['class' => 'form-control', 'id' => 'birth_date',
                            'placeholder' => 'Select Date', 'v-model' => 'dataForm.birth_date', 'required' => true]) !!}
                            <small>Seleccione la fecha de nacimiento del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- name Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                            <small>Ingrese el nombre del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- surname Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('surname', trans('Surname').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::text('surname', null, ['class' => 'form-control', 'v-model' => 'dataForm.surname', 'required' => true]) !!}
                            <small>Ingrese el apellido del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- Gender Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('gender', trans('Gender').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            <select v-model="dataForm.gender" name="gender" required="required" class="form-control">  
                                <option value="">Seleccione</option>
                                <option value="1">F</option>
                                <option value="0">M</option>
                                <option value="2">No determinado</option>
                                
                            </select>
                            <small>Seleccione el genéro del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- group_ethnic Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('group_ethnic', trans('Group Ethnic').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            <select v-model="dataForm.group_ethnic" name="group_ethnic" required="required" class="form-control">  
                                <option value="">Seleccione</option>
                                <option value="Ninguno">Ninguno</option>
                                <option value="Comunidades indígenas">Comunidades indígenas</option>
                                <option value="Comunidades negras o afrocolombianas">Comunidades negras o afrocolombianas</option>
                                <option value="Comunidad raizal">Comunidad raizal</option>
                                <option value="Pueblo Rom o Gitano">Pueblo Rom o Gitano</option>                                
                            </select>
                            <small>Seleccione el grupo étnico del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- rh otrs Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('rh', trans('RH').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('rh', null, ['class' => 'form-control', 'v-model' => 'dataForm.rh', 'required' => false]) !!}
                            <small>Ingrese el RH</small>
                        </div>
                    </div>
                </div>                

                <!-- level_study Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                        {!! Form::label('level_study', trans('Level study').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            <select v-model="dataForm.level_study" name="level_study" required="required" class="form-control">  
                                <option value="">Seleccione</option>
                                <option value="Técnico Profesional">Técnico Profesional</option>
                                <option value="Tecnológico">Tecnológico</option>
                                <option value="Profesional">Profesional</option>
                                <option value="Otros">Otros</option>
                            </select>
                            <small>Seleccione el nivel de estudio del funcionario</small>
                        </div>
                    </div>
                </div>

                
                <!-- nivel estudio otrs Field -->
                <div class="col-md-12" v-if="dataForm.level_study=='Otros'">
                    <div class="form-group row m-b-15">
                    {!! Form::label('level_study_other', trans('Level study').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('level_study_other', null, ['class' => 'form-control', 'v-model' => 'dataForm.level_study_other', 'required' => false]) !!}
                            <small>Ingrese el nivel de estudio del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- time Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('time_work', trans('Time work').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::text('time_work', null, ['class' => 'form-control', 'v-model' => 'dataForm.time_work', 'required' => true]) !!}
                            <small>Ingrese tiempo de trabajo del funcionario</small>
                        </div>
                    </div>
                </div>


            </div>
        </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Información de contacto del funcionario</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <!-- address Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('address', trans('Address').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::text('address', null, ['class' => 'form-control', 'v-model' => 'dataForm.address', 'required' => true]) !!}
                            <small>Ingrese la dirección del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- phone Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('phone', trans('Phone').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-8">
                            {!! Form::text('phone', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone', 'required' => true]) !!}
                            <small>Ingrese el teléfono del funcionario</small>
                        </div>
                    </div>
                </div>

                <!-- email Field -->
                <div class="col-md-12">
                    <div class="form-group row m-b-15">
                    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::email('email', null, ['class' => 'form-control', 'v-model' => 'dataForm.email', 'required' => false]) !!}
                            <small>Ingrese el email del funcionario</small>
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
