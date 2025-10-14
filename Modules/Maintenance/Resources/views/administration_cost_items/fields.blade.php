<div class="panel" style="border: 200px; padding: 15px;">

    <div class="row">
        <div style="padding: 15px" class="col">
            <div class="form-group row m-b-15">
               <label for="name" class="col-form-label col-md-3 required">Nombre del rubro:</label>
                    <div class="col-md-9">
                    <select-check
                        css-class="form-control"
                        name-field="mant_heading_id"
                        reduce-label="name_heading"
                        reduce-key="id"
                        name-resource="get-heading"
                        :value="dataForm"
                        :is-required="true"
                        :key="keyRefresh"
                        :enable-search="true"
                    >
                    </select-check>
                    <div class="invalid-feedback" v-if="dataErrors.name">
                        <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                    </div>
                    <small>Seleccione nombre del rubro. </small>
                </div>
            </div>
        </div>
        <div style="padding: 15px" class="col">
            <!-- Code Cost Field -->
            <div class="form-group row m-b-15">                            
                    <input-dynamic
                    :v-model="dataForm.code_cost"
                    name-resource="get-cod-item/"
                    :id-resource="parseInt(dataForm.mant_heading_id)"
                    name-field="code_cost"
                    :value="dataForm"
                    :key="dataForm.mant_heading_id"
                    :enable-search="true"
                ></input-dynamic>
            </div>
        </div>
    </div>

    <div class="row">
        <div style="padding: 15px" class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('cost_center_name', trans('Nombre del centro de costos') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select-check 
                        css-class="form-control" 
                        name-field="mant_center_cost_id" 
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-center-cost" 
                        :value="dataForm" 
                        :is-required="true"
                        :key="keyRefresh"
                        :enable-search="true"
                    >

                    </select-check>
                    <small>Seleccione nombre del centro de costos. </small>
                    <div class="invalid-feedback" v-if="dataErrors.cost_center_name">
                        <p class="m-b-0" v-for="error in dataErrors.cost_center_name">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 15px" class="col">
            <!-- Code Cost Field -->
            <div class="form-group row m-b-15">
                <input-dynamic
                :v-model="dataForm.code_cost"
                name-resource="get-cod-center-item/"
                :id-resource="parseInt(dataForm.mant_center_cost_id)"
                 name-field="cost_center"
                :value="dataForm"
                :key="dataForm.mant_center_cost_id"
            ></input-dynamic>
            </div>
        </div>
    </div>

    <div class="row">
        <div style="padding: 15px" class="col">
            <!-- Value Item Field -->
            <div class="form-group row m-b-15">
              {!! Form::label('value_available', trans('Valor disponible del contrato') . ':', ['class' => 'col-form-label col-md-3']) !!}
              <div class="col-md-9">
                  <input-data 
                        type='text'
                        prefix="$ "
                        class-input="form-control" 
                        :name-resource="'get-avaible-contract/'+dataForm.mant_budget_assignation_id"
                        name-field="value_available"
                        :value="dataForm"
                    ></input-data>
                  <small>Este es el valor disponible del contrato</small>
                  <div class="invalid-feedback" v-if="dataErrors.value_item">
                      <p class="m-b-0" v-for="error in dataErrors.value_item">@{{ error }}</p>
                  </div>
              </div>
          </div>
      </div>
        <div style="padding: 15px" class="col">
            <!-- Value Item Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('value_item', trans('Valor disponible del rubro') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <currency-input
                    v-model="dataForm.value_item"
                    required="true"
                    :currency="{'prefix': '$ '}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                    </currency-input>
                    <small>@lang('Enter the') el valor disponible</small>
                    <div class="invalid-feedback" v-if="dataErrors.value_item">
                        <p class="m-b-0" v-for="error in dataErrors.value_item">@{{ error }}</p>
                    </div>
                </div>
            </div>

        </div>
      
    </div>
</div>
<div class="panel" style="border: 200px; padding: 15px;">
    <!-- Observation Field -->
    <div class="form-group row m-b-15">
        {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-9">
            {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
            <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
            <div class="invalid-feedback" v-if="dataErrors.observation">
                <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
            </div>
        </div>
    </div>
</div>

