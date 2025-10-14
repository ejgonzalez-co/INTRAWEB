<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
   
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="justify-content-center">

        <div v-if="dataShow.pc_previous_studies_news">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr style="background: #e0e0e0;">
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Observación</th>

                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="(new_studie, key) in dataShow.pc_previous_studies_news">
                        
                            <td>@{{ new_studie.created_at }}</td>
                            <td>@{{ new_studie.user_name }}</td>
                            
                            <td>
                                <span :class="new_studie.state_colour">
                                    @{{ new_studie.state_name }}
                                </span>
                            </td>

                            <td>@{{ new_studie.observation }}</td>

                        </tr>
                   
                    </tbody>
                </table>
            </div>
        </div>
        


        </div>
    </div>
    <!-- end panel-body -->
</div>
