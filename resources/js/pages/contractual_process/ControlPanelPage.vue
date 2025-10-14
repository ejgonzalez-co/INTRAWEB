<template>

    <!-- begin #content -->
    <div id="content" class="content ml-0">

        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">Tablero de control - Proceso precontractual</h1>
        <!-- end page-header -->

        <!-- begin row -->
        <div class="row">
            <!-- begin col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-blue">
                    <div class="stats-icon"><i class="fa fa-desktop"></i></div>
                    <div class="stats-info">
                        <h4>TOTAL DE ESTUDIOS PREVIOS</h4>
                        <p>{{ informationWidgets.total }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="/contractual-process/pc-previous-studies" target="_blank">Ver detalles<i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-info">
                    <div class="stats-icon"><i class="fa fa-link"></i></div>
                    <div class="stats-info">
                        <h4>ESTUDIOS PREVIOS POR FUNCIONAMIENTO</h4>
                        <p>{{ informationWidgets.fun_qty }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="/contractual-process/pc-previous-studies?f=RjE=" target="_blank">Ver detalles<i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-orange">
                    <div class="stats-icon"><i class="fa fa-users"></i></div>
                    <div class="stats-info">
                        <h4>ESTUDIOS PREVIOS POR INVERSIÓN</h4>
                        <p>{{ informationWidgets.inv_qty }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="/contractual-process/pc-previous-studies?f=RjI=" target="_blank">Ver detalles<i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- end col-3 -->
            <!-- begin col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-red">
                    <div class="stats-icon"><i class="fa fa-clock"></i></div>
                    <div class="stats-info">
                        <h4>ESTUDIOS PREVIOS POR FUNCIONAMIENTO E INVERSIÓN</h4>
                        <p>{{ informationWidgets.fun_inv_qty }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="/contractual-process/pc-previous-studies?f=RjI=" target="_blank">Ver detalles<i class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- end col-3 -->
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-5">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="index-6">
                    <div class="panel-heading">
                        <h4 class="panel-title">Consolidado - Estudios previos por roles</h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-valign-middle table-panel mb-0">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Cantidad</th>
                                    <th>Funcionamiento</th>
                                    <th>Inversión</th>
                                    <th>Funcionamiento e inversión</th>
                                </tr>
                            </thead>              
                            <tbody v-for="(studie, key) in rolesPreviousStudies" :key="key">
                                <tr>
                                    <td>{{ studie['name_role'] }}</td>
                                    <td>{{ studie['total']  }}</td>
                                    <td>{{ studie['fun_qty'] }}</td>
                                    <td>{{ studie['inv_qty'] }}</td>
                                    <td>{{ studie['fun_inv_qty'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <div class="col-xl-7">

                <div class="card border border-dark">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                    <!-- <bar-chart> </bar-chart> -->
                        <bar-chart
                         :chart-data="{
                            labels: ['FUNCIONAMIENTO','INVERSIÓN','FUNCIONAMIENTO E INVERSIÓN'],
                            datasets: [{
                            label: 'Porcentaje solicitudes por tipo de estudio',
                            data: dataGraphic,
                            backgroundColor: [
                            'rgba(0, 188, 212, 0.2)',
                            'rgba(255, 152, 0, 0.2)',
                            'rgba(244, 67, 54, 0.2)'
                            
                            ],
                            borderColor: [
                            'rgb(0, 188, 212)',
                            'rgb(255, 152, 0)',
                            'rgb(244, 67, 54)'
                            
                            ],
                            borderWidth: 2
                                    }],
                                                
                        } " 
                                >
                        </bar-chart>
                    </div>
                    

                </div>
                
            </div>
        </div>

        <!-- begin row -->
        <div class="row">
            <!-- begin col-4 -->
            <div class="col-xl-5">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="index-6">
                    <div class="panel-heading">
                        <h4 class="panel-title">Consolidado - Estudios previos por estados</h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-valign-middle table-panel mb-0">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Cantidad</th>
                                    <th>Funcionamiento</th>
                                    <th>Inversión</th>
                                    <th>Funcionamiento e inversión</th>
                                </tr>
                            </thead>
                            <tbody v-for="(studie, key) in statesPreviousStudies" :key="key" >
                                <tr v-if="studie!=null">
                                    <td><span :class="studie.state_colour">{{ studie.state_name }}</span></td>
                                    <td>{{ studie.total }}</td>
                                    <td>{{ studie.fun_qty }}</td>
                                    <td>{{ studie.inv_qty }}</td>
                                    <td>{{ studie.fun_inv_qty }}</td>

                                </tr>
                                <tr v-else>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-4 -->
        </div>
        <!-- end row -->


    </div>

</template>

<script lang="ts">
import axios from "axios";
import { log } from "util";
import { Component, Prop, Vue } from "vue-property-decorator";

import BarChart from "../../components/charts/BarChart.vue";

/**
 * Componente para la edicion del perfil del usuario logueado
 *
 * @author Jhoan Sebastian Chilito S. - Ago. 04 - 2020
 * @version 1.0.0
 */
@Component({
    components: {
        BarChart
    }
})
export default class ControlPanelPage extends Vue {

    @Prop({type: String, required: true})
    public publicPath: string;

    public dataGraphic: any;
    public statesPreviousStudies: any;
    public rolesPreviousStudies: any;
    public informationWidgets: any;

    /**
     *
     */
    constructor() {
        super();

        this.dataGraphic = [];
        this.statesPreviousStudies = [];
        this.rolesPreviousStudies = [];
        this.informationWidgets = [];

        this.getStatesPreviousStudies();
        this.getRolesPreviousStudies();
        this.getInformationWidgets();

    }

    /**
     *
     */
    public getStatesPreviousStudies(): void {
        axios.get('/contractual-process/get-states-previous-studies')
        .then((res) => {
            this.statesPreviousStudies = res.data.data;
        })
        .catch((err) => {
            console.log('Response', err);
        });


    }

    /**
     *
     */
    public getInformationWidgets(): void {

        axios.get('/contractual-process/get-information-previous-studies')
        .then((res) => {
            this.informationWidgets = res.data.data;
            this.dataGraphic[0] = (this.informationWidgets.fun_qty * this.informationWidgets.total)/100;
            this.dataGraphic[1] = (this.informationWidgets.inv_qty * this.informationWidgets.total)/100;
            this.dataGraphic[2] = (this.informationWidgets.fun_inv_qty * this.informationWidgets.total)/100;

            console.log('Response widwet', this.informationWidgets);

        })
        .catch((err) => {
            console.log('Response', err);
        });
      
    }

    /**
     *
     */
    public getRolesPreviousStudies(): void {
        axios.get('/contractual-process/get-rols-previous-studies')
        .then((res) => {
            this.rolesPreviousStudies = res.data.data;
        })
        .catch((err) => {
            console.log('Response', err);
        });
    }


}

</script>
