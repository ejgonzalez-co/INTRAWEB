<template>
    <div>
        hola
    </div>
</template>

<script lang="ts">
    import Vue from "vue";
    import { Component, Prop } from "vue-property-decorator";

    /**
     * Componente de ejemplo de estructura
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 08 - 2020
     * @version 1.0.0
     */
    @Component
    export default class ExampleComponent extends Vue {

        @Prop({})
        public example: any;
        constructor() {
            super();

            console.log('Ejemplo herencia datos', this.example);

        }
    }

</script>
