<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información general</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Date'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.date_project }}</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Type'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.type }}</label>
                </div>
            </div>
         
            <div class="row" v-if="dataShow.type=='Proyecto de inversión'">

                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Programa'):</strong></label>
                        <label class="col-form-label col-md-8">@{{ dataShow.program }}</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Subprograma'):</strong></label>
                        <label class="col-form-label col-md-8">@{{ dataShow.subprogram }}</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Línea de Proyecto'):</strong></label>
                        <label class="col-form-label col-md-8">@{{ dataShow.lineproject }}</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Proyecto'):</strong></label>
                        <label class="col-form-label col-md-8">@{{ dataShow.project }}</label>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>


<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>2. Justificación técnica (Unidad Organizativa)</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('2.1 Descripción de la necesidad'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.justification_tecnic_description }}</label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('2.2 Planteamiento técnico de solución'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.justification_tecnic_approach }}</label>
                </div>
            </div>
         
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('2.3 Modalidad del Contrato a Celebrar'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.justification_tecnic_modality }}</label>
                </div>
            </div>



        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>3. Fundamentos jurídicos de la modalidad de selección</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('3. Fundamentos Jurídicos de la Modalidad de Selección'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.fundaments_juridics }}</label>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>4. Imputación presupuestal e interventoría</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('4.1 Rubro Presupuestal'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.imputation_budget_rubro }}</label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('4.2 Interventor y/o Supervisor Sugerido'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.imputation_budget_interventor }}</label>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>5. Determinación del objeto contractual (Descripción, Unidad Organizativa) </strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Objeto'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.determination_object }}</label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Valor'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.determination_value }}</label>
                </div>
            </div>

            
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Plazo de Ejecución'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.determination_time_limit }}</label>
                </div>
            </div>

            
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Forma de Pago'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.determination_form_pay }}</label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Obligaciones Principales'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.obligation_principal }}</label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Aplica obligación al objeto del contrato'):</strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.obligation_principal_documentation">Si</label>
                    <label class="col-form-label col-md-8" v-else>No</label>

                </div>
            </div>


        </div>
    </div>
    <!-- end panel-body -->
</div>


<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>6. Situación de predios</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-4">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('La obra a realizar afecta exclusivamente predios públicos'):</strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.situation_estates_public">Si</label>
                    <label class="col-form-label col-md-8" v-else>No</label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Observaciones'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.situation_estates_public_observation }}</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('La obra a realizar afecta un predio privado'):</strong></label>
                    <label class="col-form-label col-md-8" v-if="dataShow.situation_estates_private">Si</label>
                    <label class="col-form-label col-md-8" v-else>No</label>

                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Observaciones'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.situation_estates_private_observation }}</label>
                </div>
            </div>


            <!-- Panel -->
            <div class="panel col-md-12" data-sortable-id="ui-general-1">
                <!-- begin panel-heading -->
                <div class="panel-heading ui-sortable-handle">
                    <h3 class="panel-title"><strong>Solución planteada y avance de la misma</strong></h3>
                </div>
                <!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="row">

                        <!-- Panel -->
                        <div class="panel col-md-12" data-sortable-id="ui-general-1">
                            <!-- begin panel-heading -->
                            <div class="panel-heading ui-sortable-handle">
                                <h3 class="panel-title"><strong>Afectación a servidumbre</strong></h3>
                            </div>
                            <!-- end panel-heading -->
                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('El Predio a intervenir se encuentra afectado por servidumbre'):</strong></label>
                                            <label class="col-form-label col-md-8" v-if="dataShow.solution_servitude">Si</label>
                                            <label class="col-form-label col-md-8" v-else>No</label>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Observaciones'):</strong></label>
                                            <label class="col-form-label col-md-8">@{{ dataShow.solution_servitude_observation }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end panel-body -->
                        </div>


                         <!-- Panel -->
                         <div class="panel col-md-12" data-sortable-id="ui-general-1">
                            <!-- begin panel-heading -->
                            <div class="panel-heading ui-sortable-handle">
                                <h3 class="panel-title"><strong>Solución planteada y avance de la misma</strong></h3>
                            </div>
                            <!-- end panel-heading -->
                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Se requiere trámite de conciliación con el propietario'):</strong></label>
                                            <label class="col-form-label col-md-8" v-if="dataShow.solution_owner">Si</label>
                                            <label class="col-form-label col-md-8" v-else>No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Observaciones'):</strong></label>
                                            <label class="col-form-label col-md-8">@{{ dataShow.solution_owner_observation }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end panel-body -->
                        </div>



                    </div>
                </div>
                <!-- end panel-body -->
            </div>


            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Trámite de conciliación con el propietario'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.process_concilation }}</label>
                </div>
            </div>

            <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th colspan="5">Trámite de licencias y permisos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Trámite</td>
                            <td colspan="2">Si / No</td>
                            <td>Entidad</td>
                            <td>Observaciones</td>
                        </tr>
                        <tr>
                            <td>La obra requiere Licencia Ambiental</td>
                            <td colspan="2"> 
                                <div class="">
                                        <label v-if="dataShow.process_licenses_environment">Si</label>
                                        <label v-else>No</label>
                                </div>
                            </td>
                            <td rowspan="5">Corporación Autónoma Regional del Quindío CRQ</td>
                            <td rowspan="5">Ver: www.crq.gov.co<br>Link Tramites</td>
                        </tr>
                        <tr>
                            <td>La obra requiere permiso ocupación de cauces, playas y lechos</td>
                            <td colspan="2">
                                <div>
                        
                                    <label v-if="dataShow.process_licenses_beach">Si</label>
                                    <label v-else>No</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>La obra requiere permisos de aprovechamiento forestal</td>
                            <td colspan="2">
                            
                                <div>
                                    <label v-if="dataShow.process_licenses_forestal">Si</label>
                                    <label v-else>No</label>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>La obra requiere permiso de corte y aprovechamiento de guadua</td>
                            <td colspan="2">
                                
                                <div>

                                    <label v-if="dataShow.process_licenses_guadua">Si</label>
                                    <label v-else>No</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>La obra requiere permiso de "Aprovechamiento forestal árboles aislados"</td>
                            <td colspan="2">
                            
                                <div>
                                
                                    <label v-if="dataShow.process_licenses_tree">Si</label>
                                    <label v-else>No</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Requiere permiso para la ocupación temporal de carreteras concesionadas</td>
                            <td colspan="2">
                                <div>
                                    <label v-if="dataShow.process_licenses_road">Si</label>
                                    <label v-else>No</label>
                                </div>
                            
                            </td>
                            <td>Instituto Nacional de Concesiones INCO</td>
                            <td>Ver: www.inco.gov.co<br>Link Trámites</td>
                        </tr>
                        <tr>
                            <td>La obra requiere permiso de corte y demolición de pavimento</td>
                            <td colspan="2">
                                <div>

                                    <label v-if="dataShow.process_licenses_demolition">Si</label>
                                    <label v-else>No</label>

                                </div>
                            </td>
                            <td>Secretaria de Infraestructura Municipal</td>
                            <td>De requerirlo el contratista debe enviar un oficio que contemple la Ubicación de la Obra</td>
                        </tr>
                        <tr>
                            <td>La obra requiere permiso para intervención del Árbol Urbano</td>
                            <td colspan="2">
                            
                                <div>
                                    <label v-if="dataShow.process_licenses_tree_urban">Si</label>
                                    <label v-else>No</label>

                                </div>
                            </td>
                            <td>Departamento Administrativo de Planeación</td>
                            <td>Ver: www.armenia.gov.co<br>Link TrámitesVer: www.armenia.gov.co<br>Link Trámites</td>
                        </tr>
                </tbody>
            </table>



        </div>
    </div>
    <!-- end panel-body -->
</div>



<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>7. Tipificación, cuantificación y asignación de riesgos previsibles, no asegurables</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="">
            <div class="">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Riesgos</th>
                                <th>Efecto</th>
                                <th>Probabilidad de ocurrencia (de 1 a 5)</th>
                                <th>Impacto (1 a 5)</th>
                                <th>Asignación del Riesgo</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="(tipification, key) in dataShow.pc_previous_studies_tipifications_h">
                                <td>@{{ tipification.type_danger }}</td>
                                <td>@{{ tipification.danger }}</td>
                                <td>@{{ tipification.effect }}</td>
                                <td>@{{ tipification.probability }}</td>
                                <td>@{{ tipification.impact }}</td>
                                <td>@{{ tipification.allocation_danger }}</td>

                            </tr>
                    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>


<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>8. Indicación de las coberturas de los riesgos asegurables</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('En la etapa Pre-contractual'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.indication_danger_precontractual }}</label>
                </div>
            </div>

            
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('En la etapa de ejecución (Garantía Única)'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.indication_danger_ejecution }}</label>
                </div>
            </div>

        </div>
    </div>
    <!-- end panel-body -->
</div>


