<template>
    <div></div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

import utility from "../../utility";

import { Locale } from "v-calendar";
import { jwtDecode } from 'jwt-decode';

const locale = new Locale();

/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Seven Soluciones Informáticas S.A.S - Jun. 4 - 2023
 * @version 1.0.0
 */
@Component
export default class DashboardComponent extends Vue {

    /**
     * Campos de busqueda
     */
    public searchFields: any;

    /**
     * Valor del campo
     */
    @Prop({type: Object})
    public dataForm: any;

    public loadedImages: number;

    public loadedImagesComplete: boolean;

    @Prop({ type: [] })
    public listadoNoticias: any;

    /**
     * Permite visualizar el formulario de busqueda
     * avanzada
     */
    public showSearchOptions: boolean;

    /**
     * Constructor de la clase
     *
     * @author Seven Soluciones Informáticas S.A.S. - Ene. 14 - 2024
     * @version 1.0.0
     */
    constructor() {
        super();
        this.searchFields = {};
        this.loadedImagesComplete = false;
        this.loadedImages = 0;
        this.showSearchOptions = true;
    }

    /**
     * Se ejecuta cuando el componente ha sido creado
     */
    created() {
        this.obtener_noticias();
        this.obtener_totales();
        this.obtener_entradas_recientes();
    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Seven Soluciones Informáticas S.A.S - Ene. 14 - 2024
     * @version 1.0.0
     */
    public obtener_totales(): void {

        // Envia peticion para obtener los valores de los registros según el trámite
        axios
            .get("obtener-totales-dashboard")
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                let totales = dataDecrypted["data"];

                this.dataForm.total_corr_recibida = totales.CorrRecibida.totalCorrRecibidas ?? 0;


                this.dataForm.total_corr_enviada = totales.CorrEnviada.totalCorrEnviadas ?? 0;
                this.dataForm.total_corr_enviada_cp = totales.CorrEnviada.totalCorrEnviadasCP ?? 0;
                this.dataForm.corr_enviada_firmar = totales.CorrEnviada.totalEstados.firmar_varios ?? 0;
                this.dataForm.corr_enviada_aprobar = totales.CorrEnviada.totalEstados.aprobacion ?? 0;
                this.dataForm.corr_enviada_elaboracion = totales.CorrEnviada.totalEstados.elaboracion ?? 0;
                this.dataForm.corr_enviada_revision = totales.CorrEnviada.totalEstados.revision ?? 0;
                this.dataForm.corr_enviada_devuelto = totales.CorrEnviada.totalEstados.devuelto_para_modificaciones ?? 0;


                this.dataForm.total_pqrs = totales.PQRS.totalPQRS ?? 0;
                this.dataForm.pqrs_vencidos = totales.PQRS.linea_tiempo.vencidos ?? 0;
                this.dataForm.pqrs_proximo_vencer = totales.PQRS.linea_tiempo.proximo_vencer ?? 0;
                this.dataForm.pqrs_a_tiempo = totales.PQRS.linea_tiempo.a_tiempo ?? 0;


                // this.dataForm.total_corr_interna = totales.CorrInterna.totalCorrInternas ?? 0;
                this.dataForm.total_corr_interna_cp = totales.CorrInterna.totalCorrInternasCP ?? 0;
                this.dataForm.corr_interna_firmar = totales.CorrInterna.totalEstados.firmar_varios ?? 0;
                this.dataForm.corr_interna_aprobar = totales.CorrInterna.totalEstados.aprobacion ?? 0;
                this.dataForm.corr_interna_elaboracion = totales.CorrInterna.totalEstados.elaboracion ?? 0;
                this.dataForm.corr_interna_revision = totales.CorrInterna.totalEstados.revision ?? 0;
                this.dataForm.corr_interna_devuelto = totales.CorrInterna.totalEstados.devuelto_para_modificaciones ?? 0;

                this.dataForm.total_documentos_electronicos = totales.DocumentosElectronicos.totalDocumentosElectronicos ?? 0;

                this.$parent.$forceUpdate();
            })
            .catch(err => {
                console.log("Error obteniendo la información del Dashboard");
            });
    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Seven Soluciones Informáticas S.A.S - Ene. 14 - 2024
     * @version 1.0.0
     */
    public obtener_entradas_recientes(): void {

        // Envia peticion para obtener los valores de los registros según el trámite
        axios
            .get("obtener-entradas-recientes")
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                let totales = dataDecrypted["data"];

                (this.$parent as Vue)["dataList"] = dataDecrypted["data"];

                this.$parent.$forceUpdate();
        })
        .catch(err => {
            console.log("Error obteniendo la información del Dashboard");
        });
    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Seven Soluciones Informáticas S.A.S - Ene. 14 - 2024
     * @version 1.0.0
     */
     public obtener_noticias(): void {

        // Envia peticion para obtener los valores de los registros según el trámite
        axios
            .get("/intranet/get-notices-public-dashboard")
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                this.$set(this.dataForm, "noticias", dataDecrypted["data"]);
        })
        .catch(err => {
            console.log("Error obteniendo la información del Dashboard");
        });
    }

    /**
     * Prepara la informacion para mostrar en el modal
     *
     * @author Se. - Nov. 20 - 2024
     * @version 1.0.0
     *
     * @param dataElement elemento a mostrar
     */
    public show(dataElement: object): void {
        // Asigna elemento a mostrar
        (this.$parent as Vue)["dataShow"] = dataElement;
        // Envia peticion de obtener todos los datos de la noticia, sin embargo en este caso, solo se usa para hacer el incremento de las bistas (views)
        axios.get('intranet/notices/'+dataElement["id"])
        .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);
        })
        .catch((err) => {
            (this.$parent as Vue)["_pushNotification"]((this.$parent as Vue)["lang"].get('trans.Failed to get data list'), false, 'Error');
        });

    }

    public imageLoaded() {
        this.loadedImages++;
        if (this.loadedImages === this.listadoNoticias.length) {
        this.loadedImagesComplete = true;
        }
    }

    /**
     * Permite cambiar el estado (mostrar u ocultar) del panel de noticias del dasboard
     *
     * @author Seven Soluciones Informáticas. - Nov. 29 - 2024
     * @version 1.0.0
     */
    public toggleNews(): void {
        this.showSearchOptions = !this.showSearchOptions;
    }
}
</script>
