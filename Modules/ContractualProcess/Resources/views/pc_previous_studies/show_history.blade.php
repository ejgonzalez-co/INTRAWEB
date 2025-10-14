<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
   
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="justify-content-center">

        <div v-if="dataShow.pc_previous_studies_news">
            <div class="table-responsive">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr style="background: #e0e0e0;">
                            
                            <td>Fecha</td>
                            <td>Usuario</td>
                            <td>Fecha del proyecto</td>
                            <td>Tipo de proyecto</td>
                            <td>Estado</td>

                            <td>Programa</td>
                            <td>Subprograma</td>
                            <td>Línea de Proyecto</td>
                            <td>Proyecto</td>


                            <td>2.1 Descripción de la necesidad</td>
                            <td>2.2 Planteamiento técnico de solución</td>
                            <td>2.3 Modalidad del Contrato a Celebrar</td>


                            <td>3. Fundamentos jurídicos de la modalidad de selección</td>

                            <td>4.1 Rubro Presupuestal</td>
                            <td>4.2 Interventor y/o Supervisor Sugerido</td>

                            <td>Objeto</td>
                            <td>Valor</td>
                            <td>Plazo de Ejecución</td>
                            <td>Forma de Pago</td>
                            <td>Obligaciones Principales</td>


                            <td>En la etapa Pre-contractual</td>
                            <td>En la etapa de ejecución (Garantía Única)</td>

                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="(history, key) in dataShow.pc_previous_studies_history">
                        
                            <td>@{{ history.created_at }}</td>
                            <td>@{{ history.users_name }}</td>
                            <td>@{{ history.date_project }}</td>
                            <td>@{{ history.type }}</td>

                                  
                            <td class="w-25" v-if="history.state==1"><p class="bg-blue text-white rounded text-center">En elaboración</p></td>
                            <td class="w-25" v-else-if="history.state==2"><p class="bg-warning text-white rounded text-center">En revisión - Asistente de gerencia</p></td>
                            <td class="w-25" v-else-if="history.state==3"><p class="bg-warning text-white rounded text-center">Estudio previo radicado en jurídica</p></td>
                            <td class="w-25" v-else-if="history.state==4"><p class="bg-danger text-white rounded text-center">Devuelto para cambios</p></td>
                            <td class="w-25" v-else-if="history.state==5"><p class="bg-warning text-white rounded text-center">En revisión de aspectos jurídicos</p></td>
                            <td class="w-25" v-else-if="history.state==5"><p class="bg-success text-white rounded text-center">Estudio previo aprobado</p></td>

                            
                            <td v-if="history.type=='Proyecto de inversión'">@{{ history.program }}</td>
                            <td v-else>No aplica</td>
                            <td v-if="history.type=='Proyecto de inversión'">@{{ history.subprogram }}</td>
                            <td v-else>No aplica</td>

                            <td v-if="history.type=='Proyecto de inversión'">@{{ history.lineproject }}</td>
                            <td v-else>No aplica</td>

                            <td v-if="history.type=='Proyecto de inversión'">@{{ history.project }}</td>
                            <td v-else>No aplica</td>


                            <td>@{{ history.justification_tecnic_description }}</td>
                            <td>@{{ history.justification_tecnic_approach }}</td>
                            <td>@{{ history.justification_tecnic_modality }}</td>
                            <td>@{{ history.fundaments_juridics }}</td>
                            <td>@{{ history.imputation_budget_rubro }}</td>
                            <td>@{{ history.imputation_budget_interventor }}</td>

                            <td>@{{ history.determination_object }}</td>
                            <td>@{{ history.determination_value }}</td>
                            <td>@{{ history.determination_time_limit }}</td>
                            <td>@{{ history.determination_form_pay }}</td>
                            <td>@{{ history.obligation_principal }}</td>


                            <td>@{{ history.indication_danger_precontractual }}</td>
                            <td>@{{ history.indication_danger_ejecution }}</td>
                      

                        </tr>
                   
                    </tbody>
                </table>
            </div>
        </div>
        


        </div>
    </div>
    <!-- end panel-body -->
</div>
