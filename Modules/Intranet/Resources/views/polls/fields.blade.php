<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>@lang('General information')</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Title Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('title', trans('Title').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::text('title', null, ['class' => 'form-control', 'v-model' => 'dataForm.title', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- Description Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- Date Open Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('date_open', trans('Date Open').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::date('date_open', null, ['class' => 'form-control', 'id' => 'date_open',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_open', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <!-- Hour Open Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('hour_open', trans('Hour Open').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::time('hour_open', null, ['class' => 'form-control', 'v-model' => 'dataForm.hour_open', 'required' => true]) !!}
                    </div>
                </div>
            </div> --}}

            <!-- Date Close Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('date_close', trans('Date Close').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::date('date_close', null, ['class' => 'form-control', 'id' => 'date_close',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_close', 'required' => true]) !!}
                    </div>
                </div>
            </div>


            <!-- Hour Close Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('hour_close', trans('Hour Close').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::time('hour_close', null, ['class' => 'form-control', 'v-model' => 'dataForm.hour_close', 'required' => true]) !!}
                    </div>
                </div>
            </div> --}}

        </div>
    </div>


    <div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Participantes</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">

                <div class="col-md-12">
                    <!--  Work groups Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('work_groups', trans('work_groups').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <add-list-autocomplete  
                                :value="dataForm"
                                name-prop="name"
                                name-field-autocomplete="search_field"
                                name-field="poll_work_groups"
                                name-resource="get-work-groups-poll" 
                                name-options-list="work_groups"
                                :name-labels-display="['name']"
                                name-key="id"
                                help="Ingrese el nombre del grupo de trabajo en la caja y seleccione para agregar a la lista"
                                :key="keyRefresh"
                                >
                            </add-list-autocomplete>
                            <small>Si desea conocer los grupos de trabajo y que funcionarios pertenecen, por favor ingrese a <a href="work-groups" target="_blank">Información de grupos de trabajo</a></small>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <!--  Other officials Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('users', trans('Otros funcionarios').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <add-list-autocomplete  
                                :value="dataForm"
                                name-prop="name"
                                name-field-autocomplete="search_field"
                                name-field="poll_users"
                                name-resource="get-users-poll" 
                                name-options-list="users"
                                :name-labels-display="['users_name']"
                                name-key="id"
                                help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                :key="keyRefresh"
                                >
                            </add-list-autocomplete>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- end panel-body -->
    </div>

</div>