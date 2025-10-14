<template>
    <div>
        <input
            class="form-control"
            type="text"
            v-model="value[nameField]"
            disabled
        />
    </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import axios from "axios";

/**
 * Componente para consultar un dato y darle el valor al input
 *
 * @author Kleverman Salazar Florez. - Sep. 04 - 2023
 * @version 1.0.0
 */
@Component
export default class InputRandomComponent extends Vue {
    /**
     * Nombre del campo
     */
    @Prop({ type: String, required: true })
    public nameField: string;

    /**
     * Valor del campo
     */
    @Prop({ type: Object, required: true })
    public value: any;

    /**
     * Prefijo
     */
    @Prop({ type: String, default:"" })
    public prefix: string;

    /**
     * Sufijo
     */
    @Prop({ type: String, default:"" })
    public suffix: string;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salzar Florez. - Sep. 04 - 2023
     * @version 1.0.0
     */
    constructor() {
        super();
        if(!this.value[this.nameField]){
            this.generateRandomNumber();
        }
    }

    /**
     * Genera un numero aleatorio
     *
     * @author Kleverman Salzar Florez. - Sep. 04 - 2023
     * @version 1.0.0
     */
    public generateRandomNumber() : void{
        this.value[this.nameField] = this.prefix + (Math.floor(Math.random() * 100) + 1) + this.suffix;
    }
}
</script>
