<template>
    <div class="section">
        <div class="users">
            <i class="fas fa-users"></i>
            <p>Usuarios</p>
            <p><span :style="{color: (((this.capacityInformation[0]['number_users'] * 100) / this.capacityInformation[0]['limit_number_users'])) >= 100 ? 'red' : (((this.capacityInformation[0]['number_users'] * 100) / this.capacityInformation[0]['limit_number_users'])) >= 70 ? 'orange' : ( (((this.capacityInformation[0]['number_users'] * 100) / this.capacityInformation[0]['limit_number_users'])) >= 50 ? 'yellow' : 'blue')}">{{ this.capacityInformation[0]["number_users"] }}</span><strong>/</strong><span>{{ this.capacityInformation[0]["limit_number_users"] }}</span></p>
        </div>
        <div class="storage">
            <i class="fas fa-cloud"></i>
            <p>Almacenamiento</p>
            <progress :value="this.capacityInformation[0]['storage_quantity_used']" :max="this.capacityInformation[0]['storage_limit_quantity']" style="width:100%"></progress>
            <p style="font-size: 12px; font-weight:bold;">{{this.capacityInformation[0]['storage_quantity_used_in_gb']}} de {{ this.capacityInformation[0]['storage_limit_quantity_in_gb'] }} usado</p>
            <p style="font-size: 12px;text-align: left;font-weight:bold;">{{ this.capacityInformation[0]['storage_progress_used']  }}% usado</p>
        </div>
    </div>
</template>
<style>
progress{
    height: 10px;
}
progress::after {
  content: "";
}
.title {
  max-width: 200px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.section {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin: 20px auto;
    width: 40%;
    padding: 20px;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.users,
.storage {
    text-align: center;
}

.users i {
    font-size: 3em;
}

.users p {
    font-size: 16px;
    margin-top: 5px;
}

.users span {
    font-size: 24px;
    font-weight: bold;
}

.storage i {
    font-size: 3em;
}

.storage p {
    font-size: 16px;
    margin-top: 5px;
}

.progress-bar {
    width: 100%;
    height: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 10px;
}

.full {
    height: 100%;
    background-color: #2196f3;
    border-radius: 5px;
}
.percentage {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 14px;
  font-weight: bold;
  color: #fff;
}
</style>
<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from "jwt-decode";
import { sign } from 'jsonwebtoken';

/**
 * Componente para mostrar una alerta de confirmacion con peticion
 *
 * @author Kleverman Salazar Florez. - Sep. 22 - 2022
 * @version 1.0.0
 */
@Component
export default class CapacityComponent extends Vue {

    /**
     * Texto secundario despues de un error en el envio
     */
    public capacityInformation: Array<any>;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salzar Florez. - Sep. 22 - 2022
     * @version 1.0.0
     */
    constructor() {
        super();

        this.capacityInformation = [];

    }

    mounted(){
                        // Envia peticion al servidor
                        axios
                    .get(`user-storage-capacity`)
                    .then((res) => {
                        // Convertir el string a un objeto JavaScript
                        let dataPayload = res.data.data
                            ? jwtDecode(res.data.data)
                            : null;

                        const dataDecrypted = Object.assign(
                            {},
                            dataPayload
                        );

                        this.capacityInformation.push(dataDecrypted["data"]);
                        console.log("Información de la capacidad",this.capacityInformation);
                    })
                    .catch((error) => {
                    });
    }
}
</script>
