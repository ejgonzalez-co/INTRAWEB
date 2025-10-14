<template>
    <div>
        <div class="container">
            <h3>Búsqueda rápida</h3>
            <div class="row mt-5">
                <div class="col">
                    <select
                        class="form-control"
                        name="indicator_type"
                        id="indicator_type"
                        required
                        v-model="dataForm.indicator_type"
                        :key="keyRefresh + 1"
                    >
                        <option value="Recorrido">Recorrido</option>
                        <option value="Trabajo en horas">Trabajo en horas</option>
                        <option value="Consumo combustible">Consumo combustible</option>
                        <option value="Recorrido en Km y horas">Recorrido en Km y horas</option>
                        <option value="Rendimiento en Km por galón">Rendimiento en Km por galón</option>
                        <option value="Rendimiento en horas por galón">Rendimiento en horas por galón</option>
                        <option value="Recorrido en Km y horas por galón">Recorrido en Km y horas por galón</option>
                        <option value="Porcentaje de ejecución por contrato">Porcentaje de ejecución por contrato</option>
                        <option value="Porcentaje de ejecución de todos los contratos">Porcentaje de ejecución de todos los contratos</option>
                        <option value="Valor contratado por contrato">Valor contratado por contrato</option>
                        <option value="Valor ejecutado por contrato">Valor ejecutado por contrato</option>
                        <option value="Cantidad de activos por estado y dependencia">Cantidad de activos por estado y dependencia</option>
                    </select>
                    <small>Seleccione el tipo de indicador.</small>
                </div>
                <div class="col">
                    <select-check
                        css-class="form-control"
                        name-field="dependencias_id"
                        reduce-label="nombre"
                        reduce-key="id"
                        name-resource="get-dependency-indicator"
                        :value="dataForm"
                        :is-required="true"
                        :key="keyRefresh"
                    >
                    </select-check>
                    <small>Seleccionar proceso</small>
                </div>
            </div>
            <div class="row mt-5" v-if="dataForm.indicator_type != 'Porcentaje de ejecución por contrato' && dataForm.indicator_type != 'Porcentaje de ejecución de todos los contratos' && dataForm.indicator_type != 'Valor contratado por contrato' && dataForm.indicator_type != 'Valor ejecutado por contrato' && dataForm.indicator_type != 'Cantidad de activos por estado y dependencia'">
                <div class="col">
                    <select-check
                        css-class="form-control"
                        name-field="mant_asset_type_id"
                        reduce-label="name"
                        reduce-key="id"
                        name-resource="get-type-active-indicator"
                        :value="dataForm"
                        :is-required="true"
                        :key="keyRefresh"
                    >
                    </select-check>

                    <small>Seleccione el tipo de activo.</small>
                </div>
                <div class="col">
                    <select-check
                        css-class="form-control"
                        name-field="name_equipment"
                        :reduce-label="['name_general','complement']"
                        reduce-key="id"
                        :name-resource="
                            'get-active/' +
                                dataForm.mant_asset_type_id +
                                ',' +
                                dataForm.dependencias_id
                        "
                        :value="dataForm"
                        :is-required="true"
                        :key="
                            dataForm.mant_asset_type_id +
                                dataForm.dependencias_id +
                                keyRefresh
                        "
                    >
                    </select-check>
                    <small>Seleccionar nombre del activo</small>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <input
                        type="datetime-local"
                        name="init_date"
                        id="init_date"
                        required
                        v-model="dataForm.init_date"
                        :key="keyRefresh + 1"
                    />
                    <small>Fecha de inicio.</small>
                </div>
                <div class="col">
                    <input
                        type="datetime-local"
                        name="final_date"
                        id="final_date"
                        required
                        v-model="dataForm.final_date"
                        :key="keyRefresh + 1"
                    />
                    <small>Fecha de final.</small>
                </div>
            </div>
            <div class="row mt-5" v-if="dataForm.indicator_type == 'Consumo combustible'">
                <div class="col">
                    <select
                        class="form-control required"
                        name="type_consumption"
                        id="type_consumption"
                        required
                        v-model="dataForm.type_consumption"
                        :key="keyRefresh + 1"
                    >
                        <option value="Consumo por equipo menor"
                            >Consumo por equipo menor</option
                        >
                        <option value="Consumo combustible vehiculo"
                            >Consumo combustible vehiculo</option
                        >
                    </select>
                    <small>Seleccione el tipo de consumo.</small>
                </div>
                <div class="col"></div>
            </div>
            <div class="float-xl-center m-b-15 mt-5">
                <button
                    @click="getPetition()"
                    type="submit"
                    class="btn btn-primary text-white"
                >
                    <i class="fa fa-file-excel mr-2"></i>Exportar a excel
                </button>
                <button
                    class="btn btn-primary text-white"
                    @click="clear()"
                >
                    Limpiar campos
                </button>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from 'jwt-decode';

/**
 * Componente para eportar excel de indicadores
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
 * @version 1.0.0
 */
@Component
export default class FormIndicator extends Vue {
    /**
     * Key autoincrementable y unico para
     * ayudar a refrescar un componente
     */
    public keyRefresh: number;
    /**
     * Valor del campo
     */
    @Prop({ type: Object })
    public dataForm: any;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        this.keyRefresh = 0;
    }
    /**
     * envia los datos del al servidor y confirma con un sweeralert2
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     */
    public getPetition(): void {
        const { indicator_type, init_date, final_date, type_consumption } = this.dataForm;

        if (!indicator_type || !init_date || !final_date) {
            this.$swal({
                title: "Campos incompletos.",
                text: "Debe completar todos los campos.",
                icon: "warning"
            });
            return;
        }

        if (indicator_type === "Consumo combustible" && !type_consumption) {
            this.$swal({
                title: "Seleccione el tipo de consumo.",
                text: "Debe seleccionar un tipo de consumo.",
                icon: "warning"
            });
            return;
        }

        const indicatorMap: Record<string, string> = {
            "Consumo combustible": "Indicador_combustible",
            "Recorrido": "Recorrido",
            "Trabajo en horas": "Trabajo_en_horas",
            "Recorrido en Km y horas": "Recorrido_en_Km_y_horas",
            "Rendimiento en Km por galón": "Rendimiento_en_Km_por_galon",
            "Rendimiento en horas por galón": "Rendimiento_horas_galon",
            "Recorrido en Km y horas por galón": "Recorrido_Km_Horas_galon",
            "Porcentaje de ejecución por contrato": "Porcentaje_ejecucion_por_contrato",
            "Porcentaje de ejecución de todos los contratos": "Porcentaje_ejecucion_contratos",
            "Valor contratado por contrato": "Valor_contratado_por_contrato",
            "Valor ejecutado por contrato": "Valor_ejecutado_por_contrato"
        };

        const fileName = indicatorMap[indicator_type];

        if (!fileName) {
            this.$swal({
                title: "Tipo de indicador no reconocido.",
                text: "No se puede generar el reporte.",
                icon: "error"
            });
            return;
        }

        (this.$parent as any).showLoadingGif("Verificando información");


        axios.post("verify-indicator", this.dataForm)
            .then(res => {
                let centinela;

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({}, dataPayload);
                        
                    centinela = dataDecrypted["data"];

                if (centinela === true) {
                    this.$swal.close();
                    this.createExcel(this.dataForm, fileName);
                } else {
                    this.$swal({
                        title: "No existen registros.",
                        text: "No existen registros con esos filtros.",
                        icon: "warning"
                    });
                }
            })
            .catch(() => {
                console.log("Hay un error");
            });
    }

    /**
     * Crea un excel
     *
     * @author Johan David Velasco Rios. - 12 de mayo - 2025
     * @version 1.0.0
     *
     * @param data datos de archivo a construir
     * @param nameFile nombre de archivo
     */
    public createExcel(data, nameFile): void{

        (this.$parent as any).showLoadingGif("Creando archivo excel..");

        axios.post("create-excel", data, {
            responseType: "blob"
        }).then(res => {
            this.$swal.close();
            this.downloadFile(res.data,nameFile,"xlsx");
        }).catch(error => {

            this.$swal({
            title: "Error",
            text: "Hubo un error al crear el archivo.",
            icon: "error",
        });

            console.log("hay un error");
        });

    }

    /**
     * Descarga un archivo codificado
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     *
     * @param file datos de archivo a construir
     * @param filename nombre de archivo
     * @param fileType tipo de archivo a exportar(extension)
     */
    public downloadFile(
        file: string,
        filename: string,
        fileType: string
    ): void {
        // Crea el archivo tipo blob
        let newBlob = new Blob([file]);

        // IE no permite usar un objeto blob directamente como enlace href
        // en su lugar, es necesario usar msSaveOrOpenBlob
        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob);
            return;
        }

        // Para otros navegadores:
        // Crea un enlace que apunta al ObjectURL que contiene el blob.
        const data = window.URL.createObjectURL(newBlob);
        let link = document.createElement("a");
        link.href = data;
        link.download = `${filename}.${fileType}`;
        link.click();
        setTimeout(() => {
            // Para Firefox es necesario retrasar la revocación de ObjectURL
            window.URL.revokeObjectURL(data);
        }, 100);
    }
    /**
     * Limpia los campos
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     */
    public clear(): void {
        this.dataForm.indicator_type = "";
        this.dataForm.dependencias_id = "";
        this.dataForm.mant_asset_type_id = "";
        this.dataForm.name_equipment = "";
        this.dataForm.init_date = "";
        this.dataForm.final_date = "";
        this.dataForm.type_consumption = "";
        // Actualiza componente de refresco
        this._updateKeyRefresh();
    }
    /**
     * Actualiza el componente que utilize el key
     * cada vez que se cambia de editar a actualizar
     * y borrado de campos de formulario
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     */
    private _updateKeyRefresh(): void {
        this.keyRefresh++;
    }
}
</script>
