<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title" >Información general</h4> 
    </div>

    <div class="panel-body">

        <div class="form-group row m-b-15">

            <!-- Dependencies Id Field -->
            {!! Form::label('element_name', trans('element_name').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('element_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.element_name }", 'v-model' => 'dataForm.element_name', 'required' => true]) !!}
                <div class="invalid-feedback" v-if="dataErrors.element_name">
                    <p class="m-b-0" v-for="error in dataErrors.element_name">@{{ error }}</p>
                </div>
            </div>

            <!-- Cantidad de combustible -->
            <label class="col-form-label col-md-2 required" for="fuel_quantity" >Grupo: </label>
            <div class="col-md-4">
                <select name="group" id="component" v-model="dataForm.group" class="form-control" required>
                    <option value="Desgaste" >Desgaste</option>
                    <option value="Contaminantes" >Contaminantes</option>
                    <option value="Aditivos" >Aditivos</option>
                    <option value="Propiedades" >Propiedades</option>
                </select>            
            </div>


        
        </div>

        <!-- rank_higher Field -->
        <div class="form-group row m-b-15">

            <!-- Cantidad de combustible -->
            <label class="col-form-label col-md-2 required" for="fuel_quantity" >Componente:</label>
            <div class="col-md-4">
                <select name="component" id="component" v-model="dataForm.component" class="form-control" required>
                    <option value="Motor" >Motor</option>
                    <option value="Caja" >Caja</option>
                    <option value="Transmisión (Dif. 1)" >Transmisión (Dif. 1)</option>
                    <option value="Transmisión (Dif. 2)" >Transmisión (Dif. 2)</option>
                    <option value="Refrigerante" >Refrigerante</option>
                    <option value="Otro" >Otro</option>
                </select>            
            </div>

            {!! Form::label('rank_higher', trans('rank_higher').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('rank_higher', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.rank_higher }", 'v-model' => 'dataForm.rank_higher', 'required' => true]) !!}
                <div class="invalid-feedback" v-if="dataErrors.rank_higher">
                    <p class="m-b-0" v-for="error in dataErrors.rank_higher">@{{ error }}</p>
                </div>
            </div>


        </div>

        <!-- Invoice Number Field -->
        <div class="form-group row m-b-15">

            <!-- Asset Name Field -->  
            {!! Form::label('rank_lower',trans('rank_lower').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('rank_lower', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.rank_lower }", 'v-model' => 'dataForm.rank_lower', 'required' => true]) !!}
                <div class="invalid-feedback" v-if="dataErrors.rank_lower">
                    <p class="m-b-0" v-for="error in dataErrors.rank_lower">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation','rows' => 2]) !!}
                <div class="invalid-feedback" v-if="dataErrors.observation">
                    <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
                </div>
            </div>

        </div>






        

    </div>
</div>

