<template>
  <div></div>
</template>
<script lang="ts">
import { Component, Vue } from "vue-property-decorator";

/**
 * Componente para gestionar y conservar los datos del dataForm del formulario de registro en Intraweb
 *
 * @author Seven Soluciones Informáticas S.A.S - Feb. 4 - 2025
 * @version 1.0.0
 */
@Component
export default class RegisterComponent extends Vue {
    /**
     * Objeto para almacenar algunos datos de los campos del formulario
     */
    public dataForm: any;

    /**
     * Constructor de la clase
     *
     * @author Seven Soluciones Informáticas S.A.S. - Feb. 4 - 2025
     * @version 1.0.0
     */
    constructor() {
        super();
        this.dataForm = {};
    }

    mounted() {
        // Calcula el tamaño del objeto
        const isObjectFilled = Object.keys(window["oldData"]).length > 0;
        // Si el objeto "oldData" tiene valores, quiere decir que es una redirección del formulario de registro de Intraweb
        if(isObjectFilled) {
            // Formatea los ids de los campos "states_id" y "city_id" a entero para que sea interpretado correctamente en el select
            window["oldData"]["states_id"] = parseInt(window["oldData"]["states_id"]);
            window["oldData"]["city_id"] = parseInt(window["oldData"]["city_id"]);
            // Asigna los valores devueltos por el servidor al dataForm, para ser mostrados nuevamente en el formulario
            this.dataForm = window["oldData"];
        }
    }

    /**
     * Asigna una valor por defecto al listado de "tipo de documento", dependiendo del valor del select "tipo de persona"
     */
    asignarTipoDocumento() {
        this.dataForm["type_person"] == 1 ? this.$set(this.dataForm, "type_document", "") : this.$set(this.dataForm, "type_document", 5);
    }

}
</script>
