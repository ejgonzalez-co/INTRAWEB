<div class="panel" style="border-radius: 10px" data-soportable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información General</strong></h4>
    </div>
    <div style="padding: 15px">
        <div class="row" v-if="dataShow . assignment_type == 'Almacén' || dataShow . assignment_type == 'Activo'">
            <!-- Date Register Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Tipo de asignación de la llanta :</dt>
            <dd class="col-3">
                @{{ dataShow . assignment_type }}.</dd>

            <!-- Position Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Fecha de ingreso de la llanta:</dt>
            <dd class="col-3 text-truncate">
                @{{ dataShow . date_register }}.
            </dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Activo'">
            <!-- Depth Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Plaque'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . plaque }}.</dd>

            <!-- Mileage Initial Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Nombre del equipo o maquinaria:</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . name_machinery }}</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Activo'">
            <!-- Depth Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Proceso:</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . name_dependencias }}.</dd>

            <!-- Mileage Initial Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Fecha de asignación de la llanta:</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . date_assignment }}</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Almacén' || dataShow . assignment_type == 'Activo'">
            <!-- Type Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Dimensión de llanta:</dt>
            <dd class="col-3 text-truncate">
                @{{ dataShow . tire_reference }}.
            </dd>

            <!-- Type Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Type Tire'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . type_tire }}.</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Almacén' || dataShow . assignment_type == 'Activo'">
            <!-- Type Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Marca de la llanta:</dt>
            <dd class="col-3 text-truncate">
                @{{ dataShow . tire_brand_name }}.
            </dd>

            <!-- Type Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Referencia de la llanta'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . reference_name }}.</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Almacén' || dataShow . assignment_type == 'Activo'">
            <!-- Available Depth Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Profundidad de la llanta en (mm):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . depth_tire }} mm.</dd>
            <!-- Mileage Initial Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Kilometraje inicial:</dt>
            <dd class="col-3 text-truncate">@{{ currencyFormat(dataShow . mileage_initial) }} km.</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Activo'">
            <!-- Cost Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Cost Tire'):</dt>
            <dd class="col-3 text-truncate">$ @{{ currencyFormat(dataShow . cost_tire) }}.</dd>

            <!-- Position Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Position Tire'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . position_tire }}.</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Activo'">
            <!-- General Cost Mm Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Profundidad disponible:</dt>
            <dd class="col-3 text-truncate">@{{ currencyFormat(dataShow . available_depth) }}.</dd>

            <!-- General Cost Mm Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('General Cost Mm'):</dt>
            <dd class="col-3 text-truncate">$ @{{ currencyFormat(dataShow . general_cost_mm) }}.</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Activo'">
            <!-- Location Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Location Tire'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . location_tire }}.</dd>

            <!-- Code Tire Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Code Tire'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . code_tire }}.</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Activo'">
            <!-- Observation Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . observation_information }}.</dd>

            <!-- State Field -->
            <dt class="text-inverse text-left col-3 text-truncate" v-if="dataShow . descriptionDelete">Observación porque va editar este registro:</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . descriptionDelete }}.</dd>
        </div>

        <div class="row" v-if="dataShow . assignment_type == 'Activo'">
            <!-- Observation Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('State'):</dt>
            <dd class="col-3 text-truncate">@{{ dataShow . state }}.</dd>

        </div>

    </div>
</div>

<div v-if="dataShow.tire_history_mileage?.length > 0" class="panel" style="border-radius: 10px" data-soportable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Historial de kilometraje</strong></h4>
    </div>
   
    <div style="padding: 15px">
        <div style="text-align: center">
            <table class="table-bordered text-center default" style="width:100%; table-layout: fixed;">
                <thead class="thead-light ">
                    <tr>
                        <th scope="col">Fecha de asignación</th>
                        <th scope="col">Placa</th>
                        <th scope="col">Kilometraje inicial</th>
                        <th scope="col">Fecha revisión</th>
                        <th scope="col">Kilometraje en revisión</th>
                        <th scope="col">Total recorrido</th>
                        <th scope="col">Kilometraje de rodamiento</th>
                        <th scope="col">Fecha del registro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="data in dataShow.tire_history_mileage">
                        <td>@{{ data.date_assignment ?? 'N/A' }}</td>
                        <td>@{{ data.plaque ?? 'N/A' }}</td>
                        <td>@{{ data.mileage_initial ?? 'N/A' }}</td>
                        <td>@{{ data.revision_date ?? 'N/A' }}</td>
                        <td>@{{ data.revision_mileage ?? 'N/A' }}</td>
                        <td>@{{ data.route_total ?? 'N/A' }}</td>
                        <td>@{{ data.kilometraje_rodamiento }} km</td>
                        <td>@{{ data.created_at ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div v-if="dataShow.tire_wears?.length > 0" v-for="data in dataShow.tire_wears" class="panel" style="border-radius: 10px" data-soportable-id="ui-general-1">
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Desgaste de la llanta</strong></h4>
    </div>
    <div style="padding: 15px">


        <div class="row">
            <!-- Registration Depth Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Registration Depth'):</dt>
            <dd class="col-3 text-truncate">@{{ data . registration_depth }}.</dd>
 
            <!-- Revision Date Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Revision Date'):</dt>
            <dd class="col-3 text-truncate">@{{ data . revision_date }}.</dd>
        </div>


        <div class="row">
            <!-- Wear Total Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Wear Total'):</dt>
            <dd class="col-3 text-truncate">@{{ data . wear_total }}.</dd>

            <!-- Revision Mileage Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Revision Mileage'):</dt>
            <dd class="col-3 text-truncate">@{{ currencyFormat(data . revision_mileage) }} km.</dd>
        </div>


        <div class="row">
            <!-- Route Total Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Route Total'):</dt>
            <dd class="col-3 text-truncate">@{{ currencyFormat(data . route_total) }} km.</dd>

            <!-- Wear Cost Mm Field -->
            <dt class="text-inverse text-left col-3 text-truncate">Valor actual llanta:</dt>
            <dd class="col-3 text-truncate">$ @{{ currencyFormat(data . wear_cost_mm) }}.</dd>
        </div>


        <div class="row">
            <!-- Cost Km Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Cost Km'):</dt>
            <dd class="col-3 text-truncate">@{{ data . cost_km }}.</dd>

            <!-- Revision Pressure Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Revision Pressure'):</dt>
            <dd class="col-3 text-truncate">@{{ data . revision_pressure }}.</dd>
        </div>


        <div class="row">
            <!-- Observation Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
            <dd class="col-3 text-truncate">@{{ data . observation }}.</dd>
        </div>



    </div>
</div>
