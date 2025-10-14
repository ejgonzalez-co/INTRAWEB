<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Listado de actividades</strong></div>
    </div>
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <dynamic-list label-button-add="Agregar actividad"
                :data-list.sync="dataForm.activities_plans"
                :data-list-options="[
                    { label: 'Actividades a modificar', name: 'activity_name', isShow: true }
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                <template #fields="scope">
                    <!-- Name Field -->
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-2 required">Actividades a modificar:</label>
                        <div class="col-md-5">
                            <select-check css-class="form-control" :key="keyRefresh" name-field="activity_name" reduce-label="activity_name"
                                reduce-key="id" :name-resource="'evaluation-activities/'+dataForm.id" :value="dataForm" :is-required="true" :enable-search="true">
                            </select-check>
                            <small>Seleccione las actividades que desea modificar</small>
                        </div>
                    </div>

                </template>
            </dynamic-list>
        </div>
        <div class="form-group row m-b-15">
            {!! Form::label('objective_evaluation', trans('Observation').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <textarea cols="30" rows="10" class="form-control" required v-model="dataForm.observation"></textarea>
                <small>Agregue una observación sobre por que solicita la modificacion de esas actividades.</small>
                <div class="invalid-feedback" v-if="dataErrors.objective_evaluation">
                    <p class="m-b-0" v-for="error in dataErrors.objective_evaluation">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>