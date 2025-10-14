{{-- <table class="text-center default" style="width:100%; table-layout: fixed;" border="1">
    <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Estado</th>
        <th>Observación</th>
    </tr>
    <tr v-for="history in dataShow.history_evaluation">
        <td style="padding: 15px">@{{ history.created_at }}</td>
        <td style="padding: 15px">@{{ history.user_name }}</td>
        <td style="padding: 15px">@{{ history.status }}</td>
        <td style="padding: 15px">@{{ history.observation }}</td>
    </tr>
</table> --}}
<!-- Panel Historial de cambios -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
              
                <!-- Vertical Timeline -->
                <section id="conference-timeline">

                    <div class="timeline-start"><p>Inicio</p></div>
                    <div class="conference-center-line"></div>

                    <div class="conference-timeline-content">
                    <!-- Article -->

                    <div class="timeline-article" v-for="(history, key) in dataShow.history_evaluation">

                        <div  style="cursor: pointer;" data-toggle="collapse"  v-bind:class="{
                            'content-left-container': key % 2 === 0,
                            'content-right-container': key % 2 !== 0
                          }">
                          <span class="timeline-author"><b>Actualizado por:</b> @{{ history.user_name }}</span>

                            <div v-bind:class="{
                                'content-left': key % 2 === 0,
                                'content-right': key % 2 !== 0
                            }">
                                <p>
                                    <strong style="color:#00B0BD ">Estado: @{{ history.status }}</strong> <br>

                                    <strong>Fecha y hora:</strong>  @{{ history.date_format.day }} de @{{ history.date_format.monthcompleto }} de @{{ history.date_format.year }} @{{ history.date_format.hour }}<br>
                                    <strong>Observación:</strong>  @{{ history.observation ?? 'N/A'}} <br>

                                    <span class="article-number">
                                    <strong>@{{ key + 1 }}</strong>
                                    </span>
                                </p>    
                                
                                </div>

                        </div>

                
                        <div class="meta-date">
                            <span class="date">@{{ history.date_format.day }}</span>
                            <span class="month">@{{ history.date_format.month }}</span>
                        </div>
                    </div>
                    <!-- // Article -->
                    
                    </div>
                    <div class="timeline-end">Última actualización</div>
                </section>


            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>
