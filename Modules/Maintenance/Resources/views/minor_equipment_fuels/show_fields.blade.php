<div class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Información general</h4>
    <div class="row mt-3">
        <div class="col">
            <div class="row">
                <!-- Responsible Process Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Responsible Process'):</dt>
                <dd class="col-9 ">
                    @{{ dataShow . dependencias_responsable ? dataShow . dependencias_responsable . nombre : '' }}.
                </dd>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <!-- Supply Date Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Supply Date'):</dt>
                <dd class="col-9 ">@{{ dataShow . supply_date }}.</dd>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div class="row mt-2">
                <!-- Supply Hour Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Supply Hour'):</dt>
                <dd class="col-9 ">@{{ dataShow . supply_hour }}.</dd>
            </div>

        </div>
        <div class="col">

            <div class="row mt-2">
                <!-- Start Date Fortnight Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Start Date Fortnight'):</dt>
                <dd class="col-9 ">@{{ dataShow . start_date_fortnight }}.</dd>
            </div>

        </div>
    </div>


    <div class="row mt-2">
        <div class="col">
             <div class="row mt-2">
                <!-- End Date Fortnight Field -->
                <dt class="text-inverse text-left col-3 ">@lang('End Date Fortnight'):</dt>
                <dd class="col-9 ">@{{ dataShow . end_date_fortnight }}.</dd>
            </div>
        </div>
        <div class="col">
            <div class="row mt-2">
                <!-- Start Date Fortnight Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Tipo de combustible'):</dt>
                <dd class="col-9 ">@{{ dataShow . fuel_type }}.</dd>
            </div>
        </div>
    </div>




    <div class="row mt-2">
        <div class="col">
            <div class="row mt-2">
                <!-- More Buy Fortnight Field -->
                <dt class="text-inverse text-left col-3 ">@lang('More Buy Fortnight'):</dt>
                <dd class="col-9 ">@{{ formatNumber(dataShow . more_buy_fortnight,4) }} gal.</dd>
            </div>
        </div>
        <div class="col">
            <div class="row mt-2">
                <!-- Initial Fuel Balance Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Initial Fuel Balance'):</dt>
                <dd class="col-9 ">@{{ formatNumber(dataShow . initial_fuel_balance,4) }} gal.</dd>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div class="row mt-2">
                <!-- Final Fuel Balance Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Final Fuel Balance'):</dt>
                <dd class="col-9 ">@{{ formatNumber(dataShow . final_fuel_balance,4) }} gal.</dd>
            </div>
        </div>
        <div class="col">
            <div class="row mt-2">
                <!-- Less Fuel Deliveries Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Less Fuel Deliveries'):</dt>
                <dd class="col-9 ">@{{ formatNumber(dataShow . less_fuel_deliveries,4) }} gal.</dd>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div class="row mt-2">
                <!-- Gallon Value Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Gallon Value'):</dt>
                <dd class="col-9 ">$ @{{ dataShow . gallon_value }}.</dd>
            </div>
        </div>
        <div class="col">
            <div class="row mt-2">
                <!-- Bill Number Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Bill Number'):</dt>
                <dd class="col-9 ">@{{ dataShow . bill_number }}.</dd>
            </div>


        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            
            <div class="row mt-2">
                <!-- Cost In Pesos Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Cost In Pesos'):</dt>
                <dd class="col-9 ">$ @{{ dataShow . cost_in_pesos }}.</dd>
            </div>

        </div>
        <div class="col">
            <div class="row mt-2">
                <!-- Checked Fuel Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Checked Fuel'):</dt>
                <dd class="col-9 ">@{{ dataShow . checked_fuel }} gal.</dd>
            </div>

        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
           

            <div class="row mt-2">
                <!-- Position Field -->
                <dt class="text-inverse text-left col-3 ">Cargo:</dt>
                <dd class="col-9 ">@{{ dataShow . position }}.</dd>
            </div>

        </div>
        <div class="col">
            <div class="row mt-2">
                <!-- Name Field -->
                <dt class="text-inverse text-left col-3 ">Nombre del responsable:</dt>
                <dd class="col-9 ">@{{ dataShow . name }}.</dd>
            </div>

        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
             
            <div class="row mt-2">
                <!-- Process Leader Name Field -->
                <dt class="text-inverse text-left col-3">@lang('Process Leader Name'):</dt>
                <dd class="col-9 ">@{{ dataShow . name_leader }}.</dd>
            </div>

        </div>
        <div class="col">

            <div class="row mt-2">
                <!-- Approved Process Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Approved Process'):</dt>
                <dd class="col-9 ">
                    @{{ dataShow . dependencias_aprobo ? dataShow . dependencias_aprobo . nombre : '' }}.
                </dd>
            </div>

        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
           

        </div>
        <div class="col">

        </div>
    </div>
</div>
<div class="panel" style="border: 200px; padding: 8px;" v-if="dataShow.mant_documents_minor_equipments?.length "> 
    <br><br>
    <div>
        <h5 class="text-center">Documentos relacionados con los registros de combustible de equipos menores.</h5>
        <div class="container">
            <div class="row justify-content-center">
                <table class="text-center default" border="1">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Adjunto</th>
                    </tr>
                    <tr v-for="attachment in dataShow.mant_documents_minor_equipments">
                        <td style="padding: 15px">@{{ attachment . name }}</td>
                        <td style="padding: 15px">@{{ attachment . observation }}</td>
                        <td style="padding: 15px">
                            <div>
                                <span v-for="url in attachment.url.split(',')"><a class="col-9 "
                                        :href="'{{ asset('storage') }}/'+url" target="_blank">Ver
                                        adjunto</a><br /></span>
                            </div>
                        </td>

                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
    <br><br>
<div class="panel" style="border: 200px; padding: 8px;" v-if="dataShow.mant_equipment_fuel_consumptions?.length ">
    <div>
        <h5 class="text-center">Consumo de combustible por equipo</h5>
        <div class="container">
            <div class="row justify-content-center">
                <table class="text-center default" border="1">
                    <tr>
                        <th>Fecha de registro</th>
                        <th>Descripción del equipo</th>
                        <th>Proceso</th>
                        <th>Galones suministrado</th>
                        <th>Documentos</th>
                    </tr>
                    <tr v-for="attachment in dataShow.mant_equipment_fuel_consumptions">
                        <td style="padding: 15px">@{{ attachment . created_at }}</td>
                        <td style="padding: 15px">
                            @{{ attachment . mant_resume_equipment_machinery . name_equipment }}
                        </td>
                        <td style="padding: 15px">@{{ attachment . dependencias . nombre }}</td>
                        <td style="padding: 15px">@{{ attachment . gallons_supplied }} gal.</td>
                        <td style="padding: 15px">
                            <div v-for="attachmentTwo in attachment.mant_documents_minor_equipments">
                                <span>@{{ attachmentTwo . name }}</span>
                                <br>
                                <span v-for="url in attachmentTwo.url.split(',')"><a class="col-9 "
                                        :href="'{{ asset('storage') }}/'+url" target="_blank">Ver
                                        adjunto</a><br />
                                </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="panel" style="border: 200px; padding: 8px;">
<div v-if="dataShow.mant_documents_minor_equipments">
    <h5 class="text-center">Historial de los registros de combustible de equipos menores.</h5>
    <div class="container">
        <div class="row justify-content-center">
            <table class="text-center default" border="1">
                <tr>
                    <th>Proceso responsable</th>
                    <th>Usuario autor</th>
                    <th>Acción</th>
                    <th>Proceso que aprobó</th>
                    <th>Lider del proceso</th>
                    <th>Fecha de la modificacion</th>
                </tr>
                
                <tr v-for="attachment in dataShow.mant_history_minor_equipments">
                    <td style="padding: 15px">@{{attachment . dependencias ? attachment . dependencias.nombre : '' }}</td>
                    <td style="padding: 15px">@{{ attachment . users ?attachment . users.name: '' }}</td>
                    <td style="padding: 15px">@{{ attachment . name }}</td>
                    <td style="padding: 15px">@{{attachment . dependencias_approved ? attachment . dependencias_approved.nombre :''}}</td>
                    <td style="padding: 15px">@{{  attachment . process_leader_name }}</td>
                    <td style="padding: 15px">@{{ attachment . create }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>