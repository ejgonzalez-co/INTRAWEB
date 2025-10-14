<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-body -->
    <div class="panel-body">

         <!-- Novelty Type Field update -->
         <div  v-if="isUpdate" class="form-group row m-b-15">
            {!! Form::label('novelty_type','Tipo de novedad:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-10">
                <select 
                    disabled
                    class="form-control disabled"
                    v-model="dataForm.novelty_type"
                    name="novelty_type"
                    id="novelty_type" 
                    required>
                    <option  value="Asignación presupuestal">Asignación presupuestal</option>
                    <option value="Adición al contrato">Adición al contrato</option>
                    <option value="Prórroga">Prórroga</option>
                    <option value="Suspensión">Suspensión</option>
                    <option value="Reinicio">Reinicio</option>
                </select>
                <div class="invalid-feedback" v-if="dataErrors.novelty_type">
                    <p class="m-b-0" v-for="error in dataErrors.novelty_type">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Novelty Type Field -->
        <div v-else class="form-group row m-b-15">
            {!! Form::label('novelty_type','Tipo de novedad:', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-10">
                <select 
                    class="form-control"
                    v-model="dataForm.novelty_type"
                    name="novelty_type"
                    id="novelty_type" 
                    required>
                    <option value="Adición al contrato">Adición al contrato</option>
                    <option value="Prórroga">Prórroga</option>
                    <option value="Suspensión">Suspensión</option>
                    <option value="Reinicio">Reinicio</option>
                </select>
                <div class="invalid-feedback" v-if="dataErrors.novelty_type">
                    <p class="m-b-0" v-for="error in dataErrors.novelty_type">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div v-if="dataForm.novelty_type == 'Asignación presupuestal'" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Asignación presupuestal</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div class="form-group row m-b-12">
            <!-- Valor del CDP field -->
            <div style="" class="row col-md-6">
                {!! Form::label('value_cdp', 'Valor del CDP:', ['class' => 'col-form-label col-md-4 required']) !!}
                <div class="col-md-6">
                    {!! Form::number('value_cdp', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.value_cdp }", 'v-model' => 'dataForm.value_cdp', 'required' => true]) !!}
                   
                    <small>Ingrese el valor de la adición del CDP.</small>
                </div>
            </div>

            <!--Consecutivo del CDP field -->
            <div class="form-group row col-md-6">
                {!! Form::label('consecutiveCdp', 'Consecutivo del CDP', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-6">
                    {!! Form::text('consecutiveCdp', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consecutiveCdp }", 'v-model' => 'dataForm.consecutive_cdp', 'required' => true]) !!}
                    <small>Ingrese el Consecutivo del CDP.</small>
                        <p class="m-b-0" v-for="error in dataErrors.consecutiveCdp">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-12">
            <!-- Valor del contrato field -->
            <div style="" class="row col-md-6">
                {!! Form::label('value_contract', 'Valor del contrato:', ['class' => 'col-form-label col-md-4 required']) !!}
                <div class="col-md-6">
                    {!! Form::number('value_contract', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consecutive_cdp }", 'v-model' => 'dataForm.value_contract', 'required' => true]) !!}
                    <small>Ingrese el valor del contrato.</small>
                </div>
            </div>

            <!--valor disponible del CDP field -->
            <div class="row col-md-6">
                {!! Form::label('cdp_available', 'CDP Disponible', ['class' => 'col-form-label col-md-4 required']) !!}
                <div  style="margin-left:-25px" class="col-md-6">
                    <input-operation-change
                    name-field="cdp_available"
                    :number-one="parseInt(dataForm.value_cdp)"
                    :number-two="parseInt(dataForm.value_contract)"
                    :key="dataForm.value_cdp + dataForm.value_contract"
                    :value="dataForm"                    
                    operation="resta"
                    prefix='$ '
                    ></input-operation-change>
                    <small>@lang('Enter the') @{{ `@lang('Cdp Available')` | lowercase }}</small>
                </div>
            </div>
        </div>
        <hr>
        <!-- Observation Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-7">
                {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- attached Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('attachment', 'Adjuntos:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-7">
                <input-file 
                    :value="dataForm" 
                    name-field="attachment" 
                    :max-files="4"
                    :max-filesize="6" 
                    file-path="public/maintenance/documents_provider_contract"
                    message="Arrastre o seleccione los archivos" 
                    help-text="Lista de archivos adjuntos."
                    :is-update="isUpdate" 
                    :key="keyRefresh">
                </input-file>
                <div class="invalid-feedback" v-if="dataErrors.observation">
                        <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>

    </div>
    <!-- end panel-body -->
</div>

<div v-if="dataForm.novelty_type == 'Adición al contrato'" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Adición al contrato</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <!-- Name Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('number_cdp', 'Número CDP', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::number('number_cdp', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.number_cdp }", 'v-model' => 'dataForm.number_cdp', 'required' => true]) !!}
                <small>Ingrese el número del CDP</small>
            </div>
        </div>

        <div style="" class="form-group row m-b-15">
            {!! Form::label('value_cdp', 'Valor del CDP:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {{-- {!! Form::number('value_cdp', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.value_cdp }", 'v-model' => 'dataForm.value_cdp', 'required' => true]) !!} --}}
                <currency-input
                    v-model="dataForm.value_cdp"
                    id='value_cdp'
                    name="value_cdp"
                    required="true"
                    :currency="{'prefix': '$ '}"
                    locale="es"
                    class="form-control"
                    :key="keyRefresh"
                    >
                </currency-input>
                <small>Ingrese el valor del CDP.</small>
            </div>
        </div>


        <!-- Event Date Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('date_cdp', 'Fecha del CDP:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_cdp', null, ['v-model' => 'dataForm.date_cdp', 'class' => 'form-control' ]) !!}
                <small>Fecha del CDP</small>
            </div>
        </div>

         <!-- Event Date Field -->
         <div class="form-group row m-b-15">
            {!! Form::label('date_modification', 'Fecha del modificatorio:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_modification', null, ['v-model' => 'dataForm.date_modification', 'class' => 'form-control' ]) !!}
                <small>Fecha del modificatorio</small>
                <div class="invalid-feedback" v-if="dataErrors.date_modification">
                    <p class="m-b-0" v-for="error in dataErrors.date_modification">@{{ error }}</p>
                </div>
            </div>
        </div>


       
        <div style="" class="form-group row m-b-15">
            {!! Form::label('value_available', 'Valor disponible:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <input-operation-change
                    name-field="cdp_available"
                    :number-one="parseInt(dataForm.value_cdp)"
                    :number-two="parseInt(dataForm.sumatoria)"
                    :key="dataForm.sumatoria + dataForm.value_cdp + keyRefresh"
                    :value="dataForm"                    
                    operation="resta"
                    prefix='$ '
                ></input-operation-change>
                <small>ingrese el valor disponible.</small>
            </div>
        </div>

      

        <!-- observation Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-7">
                {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>

        @php
            $rubros = DB::table('mant_administration_cost_items')->where('mant_budget_assignation_id', $mpc)->whereNull('deleted_at')->get()->toArray();

        @endphp

        <table class="responsive-table ">
            <thead>
              <tr>
                <th>Cód. del rubro</th>
                <th>Nombre del rubro</th>
                <th>Cód. centro de costo</th>
                <th>Nombre del centro de costo</th>
                <th>Valor disponible del rubro</th>
                <th>Valor de la adición</th>
                <th>Valor total del rubro</th>
              </tr>
            </thead>
            <tbody>
                
                @foreach ($rubros as $key => $rubrito)
                <tr>
                    <td>{{ $rubrito->code_cost }}</td>
                    <td>{{ $rubrito->name }}</td>
                    <td>{{ $rubrito->cost_center }}</td>
                    <td>{{ $rubrito->cost_center_name }}</td>
                    
                    <td>${{ $rubrito->value_item }}</td>
                    <td>
    
                {{-- <input  v-model="dataForm['rubro' + {{$key}} + ',' + {{ $rubrito->id}}]"   type="number" name="proceso"  class="form-control"> --}}
                    <currency-input
                        v-model="dataForm['rubro' + {{$key}} + '_' + {{ $rubrito->id}}]"
                        id='proceso'
                        name="proceso" 
                        {{-- required="false" --}}
                        :currency="{'prefix': '$ '}"
                        locale="es"
                        class="form-control"
                        :key="keyRefresh"
                        >
                    </currency-input>
                </td>

                <td>
                    <input-operation-change
                    :precision = 0
                    name-field="total_total"
                    :number-one="parseInt(dataForm['rubro' + {{$key}} + '_' + {{ $rubrito->id}}])"
                    :number-two="parseInt( {{$rubrito->value_item}} )"
                    :key="dataForm['rubro' + {{$key}} + '_' + {{ $rubrito->id}}]  + keyRefresh"
                    :value="dataForm"                    
                    operation="suma"
                    prefix='$ '

                ></input-operation-change>
                </td>
              </tr>
        @endforeach
   
            </tbody>
          </table>
          <button  class="btn btn-success" type="button" onclick="imprimirValorProceso(); "></i> Calcular</button><br><br>


        

        <input type="hidden" id="resultadoSuma" v-model="dataForm.sumatoria" required>

    </div>
</div>

<div v-if="dataForm.novelty_type == 'Prórroga'" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Prórroga</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <div  class="form-group row m-b-15">
            {!! Form::label('time_extension', 'Tiempo de prórroga:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('time_extension', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.time_extension }", 'v-model' => 'dataForm.time_extension', 'required' => true]) !!}
                <small>Ingrese el tiempo de la prórroga.</small>
            </div>
        </div>

        <!-- Event Date end contract -->
        <div class="form-group row m-b-15">
            {!! Form::label('date_contract_term', 'Fecha terminación de contrato:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_contract_term', null, ['v-model' => 'dataForm.date_contract_term', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha de terminación de contrato</small>
                <div class="invalid-feedback" v-if="dataErrors.date_cdp">
                    <p class="m-b-0" v-for="error in dataErrors.date_cdp">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Event Date end modificatory -->
        <div class="form-group row m-b-15">
            {!! Form::label('date_modification', 'Fecha del modificatorio:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_modification', null, ['v-model' => 'dataForm.date_modification', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha del modificatorio</small>
                <div class="invalid-feedback" v-if="dataErrors.date_modification">
                    <p class="m-b-0" v-for="error in dataErrors.date_modificatory">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- observatio Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-7">
                {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation']) !!}
                <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!--  Field attached-->
        <div class="form-group row m-b-15">
            {!! Form::label('attachment', 'Adjunto:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::file('attachment', ['@change' => 'inputFile($event, "attachment")']) !!}
                <br>
                <label v-if="dataForm.attachment" style="margin-top: 5px">Adjunto actual: <a class="col-3 text-truncate":href="'{{ asset('storage') }}/'+dataForm.attachment"target="_blank">Ver adjunto</a></label>
                <small v-else>Seleccione un adjunto</small> 
                <div class="invalid-feedback" v-if="dataErrors.attachment">
                    <p class="m-b-0" v-for="error in dataErrors.attachment">@{{ error }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

<div v-if="dataForm.novelty_type == 'Suspensión'" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Suspensión </strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
         
       
         <!-- Suspension Type Field -->
         <div class="form-group row m-b-15">
            {!! Form::label('type_suspension','Tipo de suspensión:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select 
                    class="form-control"
                    v-model="dataForm.type_suspension"
                    name="type_suspension"
                    id="type_suspension" 
                    required>
                    <option value="Término fijo">Término fijo</option>
                    <option value="Término indefinido">Término indefinido</option>
                </select>
                <small>Seleccione el tipo de de termino de la suspensión</small>
            </div>
        </div>

        {{-- Tiempo de suspension --}}
        <div  v-if="dataForm.type_suspension == 'Término fijo'" class="form-group row m-b-15">
            {!! Form::label('time_suspension', 'Tiempo de suspensión:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('time_suspension', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.time_suspension }", 'v-model' => 'dataForm.time_suspension', 'required' => true]) !!}
                <small>Ingrese el tiempo de la suspensión.</small>
            </div>
        </div>

        <!--Date start suspension -->
        <div v-if="dataForm.type_suspension == 'Término fijo'" class="form-group row m-b-15">
            {!! Form::label('date_start_suspension', 'Fecha inicio de suspensión:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_start_suspension', null, ['v-model' => 'dataForm.date_start_suspension', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha de inicio de la suspensión</small>
            </div>
        </div>

        <!-- Date end suspension -->
        <div v-if="dataForm.type_suspension == 'Término fijo'" class="form-group row m-b-15">
            {!! Form::label('date_end_suspension', 'Fecha fin de suspensión:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_end_suspension', null, ['v-model' => 'dataForm.date_end_suspension', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha de finalización de suspensión</small>
                <div class="invalid-feedback" v-if="dataErrors.date_end_suspension">
                    <p class="m-b-0" v-for="error in dataErrors.date_end_suspension">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- date _termination_contract -->
        <div v-if="dataForm.type_suspension == 'Término fijo'" class="form-group row m-b-15">
            {!! Form::label('date_contract_term', 'Fecha terminación de contrato:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_contract_term', null, ['v-model' => 'dataForm.date_contract_term', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha de terminación de contrato</small>
            </div>
        </div>

        <!-- Date end suspension -->
        <div v-if="dataForm.type_suspension == 'Término indefinido'" class="form-group row m-b-15">
            {!! Form::label('date_start_suspension', 'Fecha del modificatorio:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_start_suspension', null, ['v-model' => 'dataForm.date_start_suspension', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha de finalización de suspensión</small>
                <div class="invalid-feedback" v-if="dataErrors.date_start_suspension">
                    <p class="m-b-0" v-for="error in dataErrors.date_start_suspension">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- date_act_suspension -->
        <div class="form-group row m-b-15">
            {!! Form::label('date_act_suspension', 'Fecha acta de suspensión:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_act_suspension', null, ['v-model' => 'dataForm.date_act_suspension', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha acta de suspensión</small>
            </div>
        </div>

        <!-- observatio Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-7">
                {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!--  Field attached-->
        <div class="form-group row m-b-15">
            {!! Form::label('attachment', 'Adjunto:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::file('attachment', ['@change' => 'inputFile($event, "attachment")']) !!}
                <br>
                <label v-if="dataForm.attachment" style="margin-top: 5px">Adjunto actual: <a class="col-3 text-truncate":href="'{{ asset('storage') }}/'+dataForm.attachment"target="_blank">Ver adjunto</a></label>
                <small v-else>Seleccione un adjunto</small> 
                <div class="invalid-feedback" v-if="dataErrors.attachment">
                    <p class="m-b-0" v-for="error in dataErrors.attachment">@{{ error }}</p>
                </div>
            </div>
        </div>


    </div>
</div>

<div v-if="dataForm.novelty_type == 'Reinicio'" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Reinicio</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

        <!--Date date_last_suspension -->
        <div class="form-group row m-b-15">
            {!! Form::label('date_last_suspension', 'Fecha última suspensión:', ['class' => 'col-form-label col-md-3 required disabled']) !!}
            <div class="col-md-9">
            <input-data-date
                    type= "date"
                    name-field="date_last_suspension"
                    class-input="form-control" 
                    :name-resource=" 'get-date-last-suspension/' + dataForm.mant_provider_contract_id"
                    :value="dataForm"
                    :key="dataForm.responsible_process"
                    :readonly = "true"
            ></input-data-date>
                <small>Ingrese la fecha de la ultima suspensión</small>
            </div>
        </div>

        <!-- Date end modificatorio -->
        <div class="form-group row m-b-15">
            {!! Form::label('date_modification', 'Fecha del modificatorio:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_modification', null, ['v-model' => 'dataForm.date_modification', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha del reinicio</small>
                <div class="invalid-feedback" v-if="dataErrors.date_modification">
                    <p class="m-b-0" v-for="error in dataErrors.date_end_suspension">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- date _termination_contract -->
        <div  class="form-group row m-b-15">
            {!! Form::label('date_contract_term', 'Fecha terminación de contrato:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('date_contract_term', null, ['v-model' => 'dataForm.date_contract_term', 'class' => 'form-control' ]) !!}
                <small>Ingrese la fecha de terminación de contrato</small>
            </div>
        </div>

        <!-- observatio Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-7">
                {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!--  Field attached-->
        <div class="form-group row m-b-15">
            {!! Form::label('attachment', 'Adjunto', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::file('attachment', ['@change' => 'inputFile($event, "attachment")']) !!}
                <br>
                <label v-if="dataForm.attachment" style="margin-top: 5px">Adjunto actual: <a class="col-3 text-truncate":href="'{{ asset('storage') }}/'+dataForm.attachment"target="_blank">Ver adjunto</a></label>
                <small v-else>Seleccione un adjunto</small> 
                <div class="invalid-feedback" v-if="dataErrors.attachment">
                    <p class="m-b-0" v-for="error in dataErrors.attachment">@{{ error }}</p>
                </div>
            </div>
        </div>

    </div>
</div>




@push('css')
<style scoped>
    /* Estilos para la tabla responsiva y líneas de cebra */
 .responsive-table {
   width: 100%;
   border-collapse: collapse;
   margin-bottom: 1rem;
 }
 
 .responsive-table th,
 .responsive-table td {
   border: 1px solid #ddd;
   padding: 8px;
   text-align: left;
 }
 
 .responsive-table th {
   background-color: #f2f2f2;
 }
 
 .responsive-table tr:nth-child(even) {
   background-color: #f2f2f2;
 }
   
   </style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</script>
@endpush

@push('scripts')
            {!!Html::script('assets/plugins/gritter/js/jquery.gritter.js')!!}
            <script type="text/javascript">

                function imprimirValorProceso() {
                    // Obtener todos los elementos con el atributo name="proceso"
                    var elementos = document.getElementsByName("proceso");
                    console.log(elementos[0].$ci.numberValue);


                    // Inicializar una variable para almacenar la suma
                    var suma = 0;

                    // Iterar a través de los elementos y sumar sus valores
                    for (var i = 0; i < elementos.length; i++) {
                        suma += parseFloat(elementos[i].$ci.numberValue) || 0;
                    }

                    // Obtener el valor de dataForm.value_cdp
                    var value_cdp = window.value_cdp.value;

                    // Obtener el valor de dataForm.value_cdp
            

                    // Eliminar caracteres no numéricos y el símbolo de moneda "$"
                    var cantidadLimpia = value_cdp.replace(/[$.]/g, '');
                    // Convertir la cadena limpia en un número decimal
                    var cantidadDecimal = parseFloat(cantidadLimpia);
                    

                    if (suma === cantidadDecimal) {
                    // Mostrar la suma en un elemento HTML
                    document.getElementById("resultadoSuma").value =  suma;
                    // $('#resultadoSuma').prop('disabled', true);
                    const event = new Event('input', {
                        bubbles: true,
                        cancelable: true
                        });

                    document.getElementById("resultadoSuma").dispatchEvent(event);
                    // Swal que avisa que se distribulló bien el cdp
                    Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Se agregaron los valores correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })

                    } else{
                        Swal.fire({
                        icon: 'error',
                        
                        text: 'La distribución del valor del CDP en los rubros excede los límites establecidos. Para poder continuar, asegúrese de que el valor sea igual al valor del CDP, ya que no puede ser mayor ni menor al valor del CDP.',
                        // footer: '<a href="">Why do I have this issue?</a>'
                        })
                    }
            }
            </script>
        @endpush

