<template>
    <div>
        
   </div>   
</template>
<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import axios from "axios";

import { Locale } from "v-calendar";

import utility from "../../utility";
import { fireEvent } from "highcharts";

const locale = new Locale();

/**
 * Componente para index de gestion de laboratorio
 *
 * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
 * @version 1.0.0
 */
@Component({
    components: {
        /**
         * Se importa de esta manera por error de referencia circular
         * @see https://vuejs.org/v2/guide/components-edge-cases.html#Circular-References-Between-Components
         */
        paginate: () => import("vuejs-paginate")
    }
})
export default class RequestNeedComponent extends Vue {

    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;

    /**
     * Data form del formulario
     */
    public dataForm: any;


    @Prop({ type: String, required: false, default: "get-formato-identificacion-necesidades" })
    public endpoint: String;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    constructor() {
        super();
        this.lang = (this.$parent as any).lang;
        this.dataForm = {};
    }

    /**
     * Descarga un archivo codificado
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param file datos de archivo a construir
     * @param filename nombre de archivo
     */
    public downloadFile(
        file: string,
        filename: string
    ): void {
        console.log(filename);
        // Crea el archivo tipo blob
        let newBlob = new Blob([file]);

        // Para otros navegadores:
        // Crea un enlace que apunta al ObjectURL que contiene el blob.
        const data = window.URL.createObjectURL(newBlob);
        let link = document.createElement("a");
        link.href = data;
        link.download = `${filename}`;
        link.click();
        setTimeout(() => {
            // Para Firefox es necesario retrasar la revocación de ObjectURL
            window.URL.revokeObjectURL(data);
        }, 100);
    }

    /**
     * Aca se exportan los datos
     *
     * @author Seven Soluciones Informáticas. - Diciembre. 25 - 2023
     * @version 1.0.0
     */
    public exportFormatoNecesidadesGoogle(datos) {
        this.$swal({
            title: "Exportando datos",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });
        axios
            .post(
                `${this.endpoint}`,
                {
                    datos
                },
                { responseType: "blob" }
            )
            .then(res => {
                // Descagar el archivo generado
                this.downloadFile(res.data, res.headers['content-disposition'].split('filename=')[1].split('"')[1]);
                (this.$swal as any).close();
            })
            .catch(err => {
                console.error(err);
                (this.$swal as any).close();
            }
            );
    }
}
</script>
