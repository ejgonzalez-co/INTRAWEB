<template>
        <div v-if="resultsData.length < 0">
            Buscando conincidencias. Por favor espere... <i class="fas fa-sync fa-spin"></i>
        </div>
        <div v-else>
            <fieldset class="card shadow col-md-12 mt-3" style="'border: 1px solid rgb(0 0 0 / 6%); border-radius: 5px; margin-bottom: 30px; padding: 10px;">
                <div style="display: flex;">
                    <legend style="max-width: 50px; top: -20px; position: absolute;">
                        <div style="text-align: center; border-radius: 3px; height: 50px; background-color: #2196f3e8">
                            <i class="fa fa-search" aria-hidden="true" style="vertical-align: bottom; color: white;"></i>
                        </div>
                    </legend>
                    <h5 class="text-black-transparent-6" style="margin-left: 80px; margin-bottom: 20px;">{{ tituloEncabezado }}</h5>
                </div>
                <div class="table-responsive">
                    <table-component
                        id="entradas-recientes"
                        :data="resultsData"
                        sort-by="recientes"
                        sort-order="asc"
                        table-class="table table-hover m-b-0"
                        :show-filter="false"
                        :show-caption="false"
                        filter-placeholder="Filtro rápido"
                        filter-no-results="No se encontraron coincidencias"
                        filter-input-class="form-control col-md-4"
                        :cache-lifetime="0"
                        >
                        <table-column show="updated_at" label="Fecha de actualización"></table-column>

                        <table-column show="consecutive" label="Consecutivo"></table-column>

                        <table-column show="title" label="Título/Asunto"></table-column>

                        <table-column show="state" label="Estado"></table-column>

                        <table-column show="module" label="Módulo"></table-column>

                        <table-column label="Acciones" :sortable="false" :filterable="false">
                            <template slot-scope="row">
                                <a :href="row.link" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" :title="(row.module != 'Interna' && row.state != 'Público') || (row.module == 'Interna' && row.permission_edit) ? 'Tramitar' : 'Ver Detalles'"><i :class="(row.module != 'Interna' && row.state != 'Público') || (row.module == 'Interna' && row.permission_edit) ? 'fas fa-pencil-alt' : 'fas fa-search'"></i></a>
                            </template>
                        </table-column>

                    </table-component>
                </div>
            </fieldset>
        </div>
</template>

<style>

.content-result>p{
    width: 100%;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    height: 20px;
}
</style>

<script lang="ts">

    import { Component, Prop, Vue } from "vue-property-decorator";

    import axios from "axios";
    import { jwtDecode } from 'jwt-decode';

    /**
     * Componente que muestra los resultados de la búsqueda del componente universal
     *
     * @author German Gonzalez V. - Sep. 21 - 2021
     * @version 1.0.0
     */
    @Component
    export default class SearchResultUniversalComponent extends Vue {
        // Arreglo que almacena los resultados de la búsqueda realizada
        public resultsData: Array<any>;
        // Almacena el id del registro del cual el usuario va a ver los detalles después de la búsqueda
        @Prop()
        public idRegistro: number;
        // Almacena el módulo y dependiendo de el realiza la consulta del registro y muestra los detalles
        @Prop()
        public tipoModulo: String;
        // Almacena el módulo y dependiendo de el realiza la consulta del registro y muestra los detalles
        @Prop({required: true})
        public rutaGetDatos: String;
        // Muestra el título en la parte superior de la tabla
        @Prop({default: "Resultados de la búsqueda..."})
        public tituloEncabezado: String;

        /**
         * Constructor de la clase
         *
         * @author German Gonzalez V. - Sep. 21 - 2021
         * @version 1.0.0
         */
        constructor() {
            super();
            // Almacena los resultados de la búsqueda realizada
            this.resultsData = [];
        }

        /**
         * Se ejecuta cuando el componente ha sido creado
         */
        created() {
            // Valida que si exite el id del registro, entonces va a consultar los detalles de un registro del resultado de la búsqueda
            if(this.idRegistro) {
                // Consulta el registro según el id del registro, el tipo de módulo y muestra los detalles del mismo
                this.checkRegistrationViewDetails();
            }
        }

        /**
         * Obtiene la lista de coincidencias
         *
         * @author German Gonzalez V. - Sep. 21 - 2021
         * @version 1.0.0
         */
        public checkRegistrationViewDetails(): void {
            // (this.$parent as Vue)["searchFields"].id = 671;
            (this.$parent.$refs["crud"] as any)["searchFields"].id = this.idRegistro;
            // Envia peticion de obtener todos los datos del recurso
            axios.get("get-registro-consulta-"+this.tipoModulo, {params: {query: this.idRegistro}})
            .then((res) => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({data:{}}, dataPayload);

                (this.$parent.$refs["crud"] as Vue)["show"](dataDecrypted.data);
                $(`#modal-view-`+this.tipoModulo).modal('show');
            })
            .catch((err) => {
                console.log('Error al obtener la lista del buscador universal.');
            });
        }

        /**
         * Ejecuta y obtiene la lista de coincidencias según el filtro de búsqueda
         *
         * @author German Gonzalez V. - Sep. 21 - 2021
         * @version 1.0.0
         */
        public runSearch(): void {
            if((this.$parent as any).query) {
                axios.get(this.rutaGetDatos.toString(), {params: {query: (this.$parent as any).query}})
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({data:[]}, dataPayload);

                    // Llena la lista de datos
                    this.resultsData = dataDecrypted?.data;
                })
                .catch((err) => {
                    console.log('Error al obtener los datos del resultado del buscador.');
                });
            }
        }
    }
</script>
