<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información del pensionado</strong></h4>
    </div>

    <!-- state Field -->
    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('category', trans('Category').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-8">
                <select v-model="dataForm.category" name="state" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="Pensionado">Pensionado</option>
                    <option value="Cuota Parte">Cuota Parte</option>
                </select>
                <small>Seleccione una categoría</small>
            </div>
        </div>
    </div>

    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body" v-if="dataForm.category=='Pensionado'">
        <div class="row">
            <div class="col-md-12">

                <b class="text-gray">Importante: </b><span>Ingrese el número de documento del pensionado fallecido. En caso de no aparecer en la lista por favor ingrese a <a href="work-hist-pensioners" target="_blank">Historias laborales de pensionados</a> y verifique que en los datos del pensionado este marcado como "Fallecido".</span>
                <br><br>


                <div class="form-group row m-b-15">
                    {!! Form::label('work_histories_p_id', trans('Number Document').' '.trans('Pensioner').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <autocomplete name-prop="number_document" name-field="work_histories_p_id" :value='dataForm' name-resource="get-deceased" css-class="form-control" :name-labels-display="['name', 'surname','number_document']" reduce-key="id" :key="keyRefresh">
                        </autocomplete>
                        <small>Ingrese el número de documento del pensionado fallecido</small>
                    </div>
                </div>
                    
            </div>
        </div>
    </div>




        <!-- begin panel-body -->
        <div class="panel-body" v-else-if="dataForm.category=='Cuota Parte'">
            <div class="row">
                <div class="col-md-12">

                    <b class="text-gray">Importante: </b><span>Ingrese el número de documento del pensionado fallecido. En caso de no aparecer en la lista por favor ingrese a <a href="quota-parts-pensioners" target="_blank">Historias laborales de pensionados cuotas partes</a> y verifique que en los datos del pensionado este marcado como "Fallecido".</span>
                    <br><br>


                    <div class="form-group row m-b-15">
                        {!! Form::label('work_histories_cp_pensionados_id', trans('Number Document').' '.trans('Pensioner').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <autocomplete name-prop="number_document" name-field="work_histories_cp_pensionados_id" :value='dataForm' name-resource="get-deceased-cp" css-class="form-control" :name-labels-display="['name', 'surname','number_document']" reduce-key="id" :key="keyRefresh">
                            </autocomplete>
                            <small>Ingrese el número de documento del pensionado fallecido</small>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
</div>



<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general del sustituto</strong></h4>
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


            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}

                    <div class="col-md-8">
                        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                        <small>Ingrese el nombre</small>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('surname', trans('Surname').':', ['class' => 'col-form-label col-md-3 required']) !!}

                    <div class="col-md-8">
                        {!! Form::text('surname', null, ['class' => 'form-control', 'v-model' => 'dataForm.surname', 'required' => true]) !!}
                        <small>Ingrese el nombre</small>
                    </div>
                </div>
            </div>



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


            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('depto', trans('Departament').':', ['class' => 'col-form-label col-md-3 required']) !!}

                    <div class="col-md-8">

                        {!! Form::text('depto', null, ['class' => 'form-control', 'v-model' => 'dataForm.depto', 'required' => true]) !!}

                        <small>Ingrese el departamento</small>
                    </div>
                </div>
            </div>



            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('city', trans('City').':', ['class' => 'col-form-label col-md-3 required']) !!}

                    <div class="col-md-8">
                        {!! Form::text('city', null, ['class' => 'form-control', 'v-model' => 'dataForm.city', 'required' => true]) !!}
                        <small>Ingrese la ciudad</small>
                    </div>
                </div>
            </div>



            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('address', trans('Address').':', ['class' => 'col-form-label col-md-3 required']) !!}

                    <div class="col-md-8">
                        {!! Form::text('address', null, ['class' => 'form-control', 'v-model' => 'dataForm.address', 'required' => true]) !!}
                        <small>Ingrese el nombre</small>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('phone', trans('Phone').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        {!! Form::text('phone', null, ['class' => 'form-control', 'v-model' => 'dataForm.phone', 'required' => true]) !!}
                        <small>Ingrese el nombre</small>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3']) !!}

                    <div class="col-md-8">
                        {!! Form::email('email', null, ['class' => 'form-control', 'v-model' => 'dataForm.email', 'required' => false]) !!}
                        <small>Ingrese el nombre</small>
                    </div>
                </div>
            </div>


            <!-- Type Document Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('type_substitute', trans('Type').' '.trans('substitute').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-8">
                        <select v-model="dataForm.type_substitute" name="type_substitute" required="required" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Vitalicio">Vitalicio</option>
                            <option value="Menor de 25 años">Menor de 25 años</option>
                        </select>
                        <small>Seleccione el tipo de sustituto</small>
                    </div>
                </div>
            </div>

            <!-- state Field -->
            <div class="col-md-12" v-if="isUpdate">
                <div class="form-group row m-b-15">
                    {!! Form::label('state', trans('State').' '.trans('substitute').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-8">
                        <select v-model="dataForm.state" name="state" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="1">Pensión Activa</option>
                            <option value="2">Pensión Inactiva</option>
                        </select>
                        <small>Seleccione el estado de la pensión</small>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
