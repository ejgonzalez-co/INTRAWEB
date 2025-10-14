<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Actividades que componen la meta del plan de mejoramiento</strong></div>
    </div>
    <div class="panel-body">
        <dynamic-list label-button-add="Agregar responsable" :data-list.sync="dataForm.goal_responsibles"
        :data-list-options="[
            { label: 'Nombre actividad', name: 'activity', nameObjectKey: ['activity_object', 'activity_name'], isShow: true },
            { label: 'Responsable de apoyo', name: 'responsibles_names', nameObjectKey:['responsibles', 'name'], isShow: true, refList: 'responsables' },
        ]"
        class-container="col-md-12" class-table="table table-bordered" :niveles-dataform="2">
            <template #fields="scope">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3 required">Nombre de la actividad:</label>
                    <div class="col-md-8">
                        <select-check css-class="form-control" name-field="activity" reduce-label="activity_name"
                        reduce-key="id" :name-resource="'activities-to-assign-responsibles/' + dataForm.id" :value="scope.dataForm" :is-required="true"
                        name-field-object="activity_object" :enable-search="true" :key="keyRefresh">
                        </select-check>
                        <small>Ingrese el nombre de la actividad.</small>
                    </div>
                </div>
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-3 required">Responsable de apoyo:</label>
                    <div class="col-md-8">

                        <autocomplete
                            :is-update="isUpdate"
                            name-prop="name"
                            name-field="responsibles_names"
                            :value='scope.dataForm'
                            name-resource="get-active-users"
                            css-class="form-control"
                            :name-labels-display="['name']"
                            reduce-key="id"
                            :is-required="true"
                            name-field-object="responsibles"
                            ref="responsables"
                            :key="keyRefresh"
                            :activar-blur="true"
                            >
                        </autocomplete>
                        <small>Ingrese y seleccione el nombre del usuario de apoyo para añadirlo.</small>
                        {{-- <add-list-autocomplete 
                        :value="scope.dataForm" 
                        name-prop="nombre"
                        name-field-autocomplete="search_field" 
                        name-field="responsibles_names"
                        name-resource="get-active-users" 
                        name-options-list="responsibles"
                        :name-labels-display="['name']" 
                        reduce-key="id"
                        help="Ingrese el nombre de la dependencia y seleccione una opción del listado." 
                        :key="keyRefresh">
                        </add-list-autocomplete> --}}
                    </div>
                </div>

            </template>
        </dynamic-list>
    </div>
</div>