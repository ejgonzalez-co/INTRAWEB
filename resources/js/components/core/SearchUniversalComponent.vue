<template>
        <div style="margin: auto; display: inline-flex; border-right: 2px solid white; padding-right: 10px; margin-right: 10px;">
            <i class="fas fa-search" style="margin: auto; left: 20px; position: relative;"></i>
            <input
                type="text"
                v-model="query"
                placeholder="Buscador" 
                style="width: 280px; height: 36px; border-radius: 15px; padding-right: 25px; padding-left: 25px;"
                @keyup="buscarInput($event)">
            <i v-show="query" @click="_clean" class="fas fa-times" style="margin: auto; margin-left: -22px; margin-right: 13px; cursor: pointer;"></i>
        </div>
</template>

<script lang="ts">

    import { Component, Vue } from "vue-property-decorator";

    /**
     * Componente de busqueda universal para los componentes de noticias, descargas, difusión de noticas, Encuestas de la entidad.
     *
     * @author German Gonzalez V. - Sep. 21 - 2021
     * @version 1.0.0
     */
    @Component
    export default class SearchUniversalComponent extends Vue {

        /**
         * Texto ingresado a filtrar (modelo del input del buscador)
         */
        public query: string;

        /**
         * Constructor de la clase
         *
         * @author German Gonzalez V. - Sep. 21 - 2021
         * @version 1.0.0
         */
        constructor() {
            super();
            this.query = '';
        }

        /**
         * Limpia el campo de busqueda
         *
         * @author German Gonzalez V. - Sep. 21 - 2021
         * @version 1.0.0
         */
        private _clean() {
            // Limpia el campo de búsqueda del input
            this.query = null;
            /* También se limpia la variable global que almacena el texto de búsqueda, 
               ya que también se utiliza en componente de los resultados de la búsqueda */
            (this.$parent as any).query = this.query;
        }

        /**
         * Ejecuta la búsqueda de registros según el filtro de búsqueda universal
         *
         * @author German Gonzalez V. - Sep. 21 - 2021
         * @version 1.0.0
         */
        private searchMatches(): void {
            // Asigna el valor de quiery del buscador a la variable query local, esto es ya que se va a usar en componente de autobúsquda
            (this.$parent as any).query = this.query;
            // Ejecuta la función del componente que muestra los resultados de la búsqueda
            (this.$parent.$refs["resultado"] as Vue) != undefined ? (this.$parent.$refs["resultado"] as Vue)["runSearch"]() : '';
            // Vuelve vacía la variable data del componente que muestra los resultados, accediendo a el por medio de la referencia
            (this.$parent.$refs["resultado"] as Vue) != undefined ? ((this.$parent.$refs["resultado"] as Vue)["data"] = []) : '';
        }

        /**
         * Obtiene acción de tecleo sobre el input del buscador universal
         *
         * @author German Gonzalez V. - Sep. 21 - 2021
         * @version 1.0.0
         */
        private buscarInput(event: any): void {
            // Si presionó la tecla enter con ascci (13), este invoca la función searchMatches para ejecutar la búsqueda
            if(event.keyCode == '13'){
                this.searchMatches();
            }
        }
    }
</script>
