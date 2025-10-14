<template>
        <button @click="_execution(fieldUpdate)" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" :title="title"><i  :class="cssClass"></i></button>
</template>

<script lang="ts">
    import Vue from "vue";
    import { Component, Prop } from "vue-property-decorator";
    import axios from "axios";
    import { jwtDecode } from 'jwt-decode';


    /**
     * Componente para ejecutar una ruta o url desde una accion
     *
     * @author Erika Johana Gonzalez C. - Oct. 22 - 2020
     * @version 1.0.0
     */
    @Component
    export default class ExecutionFromActionComponent extends Vue {


        /**
         * Url o ruta a ejecutar
         */
        @Prop({ type: String})
        public route: string;


        /**
         * Nombre del campo a actualizar
         */
        @Prop({type: String})
        public fieldUpdate: any;

        /**
         * Valor del campo a actualizar
         */
         @Prop({})
        public valueUpdate: any;

        /**
         * Clase que se muestra en la accion
         */
        @Prop({ type: String})
        public cssClass: string;

        /**
         * Titulo del campo
         */
        @Prop({ type: String})
        public title: string;

        /**
         * Valor del objeto dataform
         */
        @Prop({type: Object})
        public value: any;

        /**
         * Identificador de objeto
         */
        @Prop({ type: String, default: 'id' })
        public customId: string;

        constructor() {
            super();
        }

        /**
         * Ejecuta ruta y envia actualiza registro según los parametros recibidos
         *
         * @author Erika Johana Gonzalez C. - Oct. 22 - 2020
         * @version 1.0.0
         */
        private _execution(fieldUpdate): void {

             this.$swal({
				title: "Actualizando registro",
				allowOutsideClick: false,
				onBeforeOpen: () => {
                    (this.$swal as any).showLoading();
				},
			});


            let formData = new FormData();
            formData.append('_method', 'put');
            //Campo a actualizar
            formData.append(this.fieldUpdate, this.valueUpdate);

            // Envia peticion de actualizar de datos
            axios.post(`${this.route}/${this.value.id}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
            .then((res) => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({data:{}}, dataPayload);

                // Actualiza elemento modificado en la lista
                Object.assign(this.value, dataDecrypted?.data);

                // Emite notificacion de actualizacion de datos
                this._pushNotification(res.data.message);

                //Cierra swal
                (this.$swal as any).close();
            })
            .catch((err) => {
                (this.$swal as any).close();

                // Emite notificacion de almacenamiento de datos
                this._pushNotification('Error al guardar los datos', false, 'Error');
                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                   console.log(err.response.data.errors);
                }
            });

        }

        /**
         * Visualiza notificacion por accion
         *
         * @author Erika Johana Gonzalez C. - Oct. 22 - 2020
         * @version 1.0.0
         *
         * @param message mesaje de notificacion
         * @param isPositive valida si la notificacion debe ser posiva o negativa
         * @param title titulo de notificacion
         */
        private _pushNotification(message: string, isPositive: boolean = true, title: string = 'OK'): void {

            // Datos de notificacion (Por defecto guardar)
            const toastOptions = {
                closeButton: true,
                closeMethod: 'fadeOut',
                timeOut: 3000,
                tapToDismiss: false
            };
            // Valida el tipo de toast que se debe visualiza
            if (isPositive) {
                // Visualiza toast positivo
                toastr.success(message, title, toastOptions);
            } else {
                toastOptions.timeOut = 0;
                // Visualiza toast negativo
                toastr.error(message, title, toastOptions);
            }
        }


    }

</script>
