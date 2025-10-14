<div class="panel" v-if="dataForm.id == null">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información de la meta</strong></div>
    </div>
    <div class="panel-body">
        <!-- Goal Type Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('goal_type', trans('Tipo de meta') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select class="form-control" v-model="dataForm.goal_type" required>
                    <option value="Cuantitativa">Cuantitativa</option>
                    <option value="Cualitativa">Cualitativa</option>
                </select>
                <small>Seleccione el tipo de meta.</small>
                <div class="invalid-feedback" v-if="dataErrors.goal_type">
                    <p class="m-b-0" v-for="error in dataErrors.goal_type">@{{ error }}</p>
                </div>
            </div>

            <!-- Goal Name Field -->
            {!! Form::label('goal_name', trans('Nombre de la meta') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('goal_name', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.goal_name }",
                    'v-model' => 'dataForm.goal_name',
                    'required' => true,
                ]) !!}
                <small>Ingrese el nombre de la meta.</small>
                <div class="invalid-feedback" v-if="dataErrors.goal_name">
                    <p class="m-b-0" v-for="error in dataErrors.goal_name">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Goal Weight Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('goal_weight', 'Porcentaje de contribución de esta Meta al cumplimiento de la oportunidad de mejora:', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}

            <div class="col-md-4">

                <div class="input-group">
                    {!! Form::number('goal_weight', null, [
                        'class' => "form-control",
                        'v-model' => 'dataForm.goal_weight',
                        'required' => true,
                        'min' => 1,
                        'max' => 100,
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
                <small>
                    Ingrese el porcentaje de la meta.
                </small>
                <div class="custom-tooltip" title="">
                    <small class="custom-tooltip-trigger">
                        <i class="fas fa-question-circle"></i> Ayuda
                    </small>
                    <div class="custom-tooltip-content">
                        <small>
                            Cada meta tiene un peso en el cumplimiento de la oportunidad de mejora no conforme, representado en porcentaje. La suma de los porcentajes de todas las metas debe alcanzar el 100%.
                        </small>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="input-group">
                    {!! Form::number('goal_weight', null, [
                        'class' => "form-control",
                        'v-model' => 'dataForm.goal_weight',
                        'required' => true,
                        'min' => 1,
                        'max' => 100,
                    ]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
                
                <small>Cada meta tiene un peso en el cumplimiento de la oportunidad de mejora no conforme, representado en porcentaje. La suma de los porcentajes de todas las metas debe alcanzar el 100%.</small>
                <div class="invalid-feedback" v-if="dataErrors.goal_weight">
                    <p class="m-b-0" v-for="error in dataErrors.goal_weight">@{{ error }}</p>
                </div>
            </div> --}}

            <!-- Indicator Description Field -->
            {!! Form::label('indicator_description', trans('Descripción del indicador') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                <textarea class="form-control" v-model="dataForm.indicator_description" cols="30" rows="5" required></textarea>
                <small>Ingrese la descripción del indicador de la meta.</small>
                <div class="invalid-feedback" v-if="dataErrors.indicator_description">
                    <p class="m-b-0" v-for="error in dataErrors.indicator_description">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Goal Weight Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('goal_weight', trans('Fecha del compromiso') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                <date-picker :value="dataForm" name-field="commitment_date" :input-props="{required: true}">
                </date-picker>
                <small>Seleccione la fecha del compromiso.</small>
                <div class="invalid-feedback" v-if="dataErrors.goal_weight">
                    <p class="m-b-0" v-for="error in dataErrors.goal_weight">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" id="actividades_meta">
    <div class="panel-heading">
        <div class="panel-title"><strong>Actividades que componen la meta del plan de mejoramiento</strong></div>
    </div>
        <div v-if="!dataForm.goal_type" class="alert alert-info mt-3" role="alert">
            <i class="fas fa-info-circle mr-2"></i>
            Selecciona un tipo de meta para crear las actividades.
        </div>
    
        <div class="panel-body">
        <div class="form-group row m-b-15">
            <dynamic-list v-if="dataForm.goal_type=='Cuantitativa'" label-button-add="Agregar actividad" :data-list.sync="dataForm.goal_activities_cuantitativas" :is-activity="true"
                :data-list-options="[
                    { label: 'Nombre actividad', name: 'activity_name', isShow: true },
                    { label: 'Peso(porcentaje) actividad', name: 'activity_weigth', isShow: true, end:'%'  },
                    { label: 'Cantidad', name: 'activity_quantity', isShow: true },
                    { label: 'Linea base para la meta', name: 'baseline_for_goal', isShow: true },
                    { label: 'Brecha para cumplimiento de la meta', name: 'gap_meet_goal', isShow: true },
                ]"
                class-container="col-md-12" class-table="table table-bordered">
                <template #fields="scope">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2 required">Nombre de la actividad:</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" v-model="scope.dataForm.activity_name" required>
                            <small>Ingrese el nombre de la actividad.</small>
                        </div>
                        <label class="col-form-label col-md-2 required">Peso de la actividad (%):</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="number" class="form-control" v-model="scope.dataForm.activity_weigth" min="1" max="100" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small>Ingrese el peso de la actividad (no puede ser más de 100%).</small>
                        </div>
                        
                    </div>

                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2 required">Fecha inicio de la actividad:</label>
                        <div class="col-md-4">
                            <date-picker :value="scope.dataForm" name-field="start_date" :input-props="{required: true}">
                            </date-picker>
                            <small>Seleccione la fecha inicio de la actividad.</small>
                        </div>
                        <label class="col-form-label col-md-2 required">Fecha fin de la actividad:</label>
                        <div class="col-md-4">
                            <date-picker :value="scope.dataForm" name-field="end_date" :input-props="{required: true}">
                            </date-picker>
                            <small>Seleccione la fecha fin de la actividad.</small>
                        </div>
                    </div>

                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2 required">Cantidad:</label>
                       
                        <div class="col-md-4">
                            <input
                                required
                                min="1"
                                type="number"
                                class="form-control"
                                v-model="scope.dataForm.activity_quantity"
                                @@input="scope.dataForm.gap_meet_goal = (parseFloat(scope.dataForm.activity_quantity) || 0) - (parseFloat(scope.dataForm.baseline_for_goal) || 0)"
                            >
                            <small>
                                Ingrese la cantidad deseada para alcanzar la meta. Por ejemplo, si necesitas 10 computadores, introduce el número 10.
                            </small>
                        
                        </div>
                        
                        <label class="col-form-label col-md-2 required">Linea base para la meta:</label>
                        <div class="col-md-4">
                            <input
                                required
                                :max="scope.dataForm.activity_quantity-1"
                                :min="0"
                                type="number"
                                class="form-control"
                                v-model="scope.dataForm.baseline_for_goal"
                                @@input="scope.dataForm.gap_meet_goal = (parseFloat(scope.dataForm.activity_quantity) || 0) - (parseFloat(scope.dataForm.baseline_for_goal) || 0)"
                            >
                            <small>
                                Ingresa la cantidad actual disponible, llamada línea base. Por ejemplo, si buscas 10 computadoras y ya posees 5, introduce 5. Si no tienes cantidad disponible, ingresa 0.
                            </small>
                            
                        </div>

                        <label class="col-form-label col-md-2 required">Brecha para cumplimiento de la meta:</label>
                        <div class="col-md-4">
                            <input required type="number" class="form-control" v-model="scope.dataForm.gap_meet_goal" min="1" readonly>
                            <div class="custom-tooltip" title="">
                                <small class="custom-tooltip-trigger">
                                    <i class="fas fa-question-circle"></i> Ayuda
                                </small>
                                <div class="custom-tooltip-content">
                                    <small>
                                        La brecha para el cumplimiento de la meta se calcula automáticamente y representa la diferencia entre la cantidad deseada y la cantidad actual (línea base). Es la cantidad que necesitas agregar para alcanzar tu meta.
                                    </small>
                                </div>
                            </div>
                        </div>
                        


                    </div>
                        

                </template>
            </dynamic-list>
            <dynamic-list v-if="dataForm.goal_type=='Cualitativa'" label-button-add="Agregar actividad" :data-list.sync="dataForm.goal_activities_cualitativas" :is-activity="true"
            :data-list-options="[
                { label: 'Nombre actividad', name: 'activity_name', isShow: true },
                { label: 'Peso actividad %', name: 'activity_weigth', isShow: true, end:'%' },
            ]"
            class-container="col-md-12" class-table="table table-bordered">
            <template #fields="scope">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-2 required">Nombre de la actividad:</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" v-model="scope.dataForm.activity_name" required>
                        <small>Ingrese el nombre de la actividad.</small>
                    </div>
                    <label class="col-form-label col-md-2 required">Peso de la actividad (%):</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="number" class="form-control" v-model="scope.dataForm.activity_weigth" min="1" max="100" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small>Ingrese el peso de la actividad (no puede ser más de 100%).</small>
                    </div>
                </div>

            </template>
        </dynamic-list>
        </div>
    </div>
</div>

<div class="panel" v-if="dataForm.id == null">
    <div class="panel-heading">
        <div class="panel-title"><strong>Dependencias de apoyo</strong></div>
    </div>
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <dynamic-list label-button-add="Agregar dependencia" :data-list.sync="dataForm.goal_dependencies"
                :data-list-options="[
                    { label: 'Dependencia', name: 'dependence_name', isShow: true }
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                <template #fields="scope">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2">Nombre de la dependencia:</label>
                        <div class="col-md-10">
                            <select-check css-class="form-control" name-field="dependence_name" reduce-label="nombre"
                                reduce-key="nombre" name-resource="get-dependences" :value="scope.dataForm" ref-select-check="dependencias_ref" :is-required="false" :enable-search="true">
                            </select-check>
                            <small>Seleccione el nombre de la dependencia de apoyo.</small>
                        </div>
                    </div>

                </template>
            </dynamic-list>
        </div>
    </div>
</div>
