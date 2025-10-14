<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Proceso</h5>
    <div style="padding: 15px">
        <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Fecha de creación de la toma de muestra:</dt>
            <dd class="col-4">@{{ formatDate(dataShow.created_at) }}.</dd>

            <dt class="text-inverse text-left col-2">Hora de la toma de la muestra:</dt>
            <dd class="col-4">@{{ dataShow.hour_from_to }}.</dd>
         </div>
         <br>
         <div v-if="dataShow.reception_date" class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Fecha y hora de la recepción de la muestra:</dt>
            <dd class="col-4">@{{ dataShow.reception_date }}.</dd>
         </div>           
    </div>
</div>

<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Información general del responsable de la toma</h5>
    <div style="padding: 15px">
        <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Identificación asignada por Leca:</dt>
            <dd class="col-4">@{{ dataShow.sample_reception_code }}</dd>

            <dt class="text-inverse text-left col-2">Tipo de agua:</dt>
            <dd class="col-4">@{{ dataShow.type_water }}.</dd>
         </div>
         <br>
         <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Responsable de la entrega de la muestra:</dt>
            <dd class="col-4">@{{ dataShow.user_name }}.</dd>

            <dt class="text-inverse text-left col-2">Firma:</dt>
            <img width="100" class="img-responsive" :src="'{{ asset('storage') }}/'+dataShow.users?.url_digital_signature" alt="">
         </div>           
    </div>
</div>

<div v-if="dataShow.chlorine_reception" class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Parámetros determinados en campo</h5>
    <div style="padding: 15px">
        <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Cloro:</dt>
            <dd class="col-4">@{{ dataShow.chlorine_reception }}</dd>

            <dt class="text-inverse text-left col-2">PH:</dt>
            <dd class="col-4">@{{ dataShow.reception_ph }}.</dd>
         </div>
         <br>
         <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">NTU:</dt>
            <dd class="col-4">@{{ dataShow.ntu_reception }}</dd>

            <dt class="text-inverse text-left col-2">μS/cm:</dt>
            <dd class="col-4">@{{ dataShow.conductivity_reception }}.</dd>
         </div>
         <br>
         <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Otro:</dt>
            <dd class="col-4">@{{ dataShow.other_reception }}</dd>
         </div>
    </div>
</div>

<div v-if="dataShow.type_receipt" class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Recipientes con muestras</h5>
    <div style="padding: 15px">
        <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Tipo:</dt>
            <dd class="col-4">@{{ dataShow.type_receipt }}</dd>

            <dt class="text-inverse text-left col-2">Volumen litros:</dt>
            <dd class="col-4">@{{ dataShow.volume_liters }}.</dd>
         </div>
         <br>
         <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Parametros solicitados:</dt>
            <dd class="col-4">@{{ dataShow.requested_parameters }}</dd>

            <dt class="text-inverse text-left col-2">Adición de preservante:</dt>
            <dd class="col-4">@{{ dataShow.persevering_addiction }}.</dd>
         </div>
    </div>
</div>

<div v-if="dataShow.according_receipt" class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Estado de muestra receocionadas</h5>
    <div style="padding: 15px">
        <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">T°C(inicial):</dt>
            <dd class="col-4">@{{ dataShow.t_initial_receipt }}</dd>

            <dt class="text-inverse text-left col-2">T°C(final):</dt>
            <dd class="col-4">@{{ dataShow.t_final_receipt }}.</dd>
         </div>
         <br>
         <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Conforme:</dt>
            <dd class="col-4">@{{ dataShow.according_receipt }}</dd>

            <dt class="text-inverse text-left col-2">Observaciones:</dt>
            <dd class="col-4">@{{ dataShow.observation_receipt }}.</dd>
         </div>

         <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Hora recepción:</dt>
            <dd class="col-4">@{{ dataShow.reception_hour }}</dd>

            
         </div>
    </div>
</div>


<div v-if="dataShow.is_accepted" class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Información general de la recepción</h5>
    <div style="padding: 15px">
        <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">¿Se acepta?:</dt>
            <dd class="col-4">@{{ dataShow.is_accepted }}</dd>

            <dt class="text-inverse text-left col-2">Responsable de recepción de muestra:</dt>
            <dd class="col-4">@{{ dataShow.name_receipt }}.</dd>
         </div>
         <br>
         <div class="row">
            <!-- User Name Field -->
            <dt class="text-inverse text-left col-2">Firma:</dt>
            <img width="80" class="img-responsive col-4" :src="'{{ asset('storage') }}/'+dataShow.url_receipt" alt="">

            <dt v-if="dataShow.is_accepted == 'No'" class="text-inverse text-left col-2">Justificación:</dt>
            <dd v-if="dataShow.is_accepted == 'No'" class="col-4">@{{ dataShow.justification_receipt }}.</dd>
         </div>
    </div>
</div>


<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Notas</h5>
    <div style="padding: 15px">
        <table class="text-left default" style="width:100%; table-layout: fixed;" border="1">
            <tr>
                <td>(1)	cruda ( C), tratada (T), de proceso (P)(aguas clarificadas o filtradas).</td>
                <td>(6)	T inicial; aplica para muestras con cadena de frío.</td>
            </tr>
            <tr>
                <td>(2)	NTU = turbiedad, µS/cm= conductividad, otros= temperatura, OD.</td>
                <td>(7)	T de llegada; aplica para muestras con cadena de frío y almacenadas.</td>
            </tr>
            <tr>
                <td>(3)	Vidrio ( V), Plástico (P)</td>
                <td>(8)	Conforme. De acuerdo a criterios relacionados en el procedimiento gestión de muestras.</td>
            </tr>
            <tr>
                <td>(4)	Parámetros relacionados en listas</td>
                <td>(9)	No Conforme.  De acuerdo a criterios relacionados en el procedimiento gestión de muestras.</td>
            </tr>
            <tr>
                <td>(5)	Ácidos (A), bases (B), tiosulfato de sodio(TS), otros ( O) Ver listas.</td>
                <td>(10)Observaciones: todo evento que le suceda al ítem de muestreo, solicitudes de clientes, no contemplados R-047.</td>
            </tr>
        </table>
    </div>
</div>