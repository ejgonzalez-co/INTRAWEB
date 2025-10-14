<template>
    <div v-if="typeWidget == 'multiple'" :class="classCssCol">
        <!-- begin card -->
        <div :class="['card border-0 text-truncate mb-3 ', classCssColor]">
            <!-- begin card-body -->
            <div class="card-body">
                <!-- begin title -->
                <div class="mb-3">
                    <b class="mb-3">{{ title | uppercase}}</b>
                </div>
                <!-- end title -->
                <!-- begin conversion-rate -->
                <div class="d-flex align-items-center mb-1">
                    <h2 class="mb-0"><span data-animation="number" :data-value="qty">{{ qty }}</span></h2>
                    <div class="ml-auto" v-if="icon">
                        <div><i :class="[icon, 'fa-3x']"></i></div>
                    </div>
                </div>
                <!-- end conversion-rate -->
                <!-- begin info-row -->
                <div class="d-flex mb-2" v-for="(item, key) in itemList" :key="key">
                    <div class="d-flex align-items-center">
                        <i :class="item.iconSeeMore? item.iconSeeMore: 'fa fa-circle f-s-8 mr-2'" ></i>
                        {{ item.name }}
                    </div>
                    <div class="d-flex align-items-center ml-auto">
                        <div class="width-50 text-right pl-2 f-w-600"><span data-animation="number" :data-value="item.qty">{{ item.qty }}</span></div>
                    </div>
                </div>
                <div class="stats-link" v-if="status">
                    <a role="button" @click="filter(status)">{{ titleLinkSeeMore }} <i :class="iconSeeMore"></i></a>
                </div>
                <!-- end info-row -->
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    
    <div v-else-if="typeWidget == 'button'">
        <a :class="['btn',ClassExtra]" :style="'height:'+ alto + ';width:'+ ancho + ';margin-left:'+espacio + ';background:' + classCssColor +';margin-top:8px;'"  @click="filter(status)"><b>{{ titleLinkSeeMore }} : </b><label style="margin-left:10px;">{{ qty }}</label></a>
    </div>

    <div v-else :class="[classCssCol]">
        <fieldset :class="['card shadow', classCssColor]" :style="'border: 1px solid rgb(0 0 0 / 6%); border-radius: 5px; margin-bottom: 30px; padding: 10px; background-color:'+bgColor">
            <div style="display: flex;">
                <legend style="max-width: 55px; top: -20px; position: absolute;">
                    <div :style="'text-align: center; border-radius: 3px; height: 55px; background-color: '+bgColorIcon">
                        <i :class="icon" aria-hidden="true" style="vertical-align: -webkit-baseline-middle; color: white;"></i>
                    </div>
                </legend>
                <h5 class="text-black-transparent-6" style="margin-left: auto; margin-bottom: 0; padding-left: 55px; text-align: right;" v-html="title"></h5>
            </div>
            <h4 style="margin-left: auto; margin-top: 5px;">{{ qty }}</h4>
            <hr style="margin-top: 0px; margin-bottom: 10px;" />
            <a class="text-black-transparent-6" @click="filter(status)" target="_blank" style="cursor: pointer;"><i :class="iconSeeMore"></i> {{ titleLinkSeeMore }}</a>
        </fieldset>
    </div>
    
</template>
<style>
    .width-50 {
        width: 10px !important;
    }
    
    .stats-link {
        cursor: pointer;
    }

    .card {
        min-height: 140px !important; /* o el alto que quieras */
    }
</style>

<script lang="ts">

import { Component, Prop, Watch, Vue, Emit  } from "vue-property-decorator";

    /**
     * Componente de widget de contadores
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 21 - 2020
     * @version 1.0.0
     */
        @Component
        export default class WidgetComponent extends Vue {

        /**
         * Tipo de widget
         */
        @Prop({ type: String, default: 'single' })
        public typeWidget: string;

        /**
         * 
         */
        @Prop({ type: Array })
        public itemList: Array<any>;

        /**
         * Clase css de columna
         */
        @Prop({ type: String, default: 'col-xl-3 col-md-6' })
        public classCssCol: string;

         /**
         * Alto de los botones
         */
        @Prop({ type: String, default: '' })
        public ClassExtra: string;

        /**
         * Alto de los botones
         */
        @Prop({ type: String, default: '40px' })
        public alto: string;

        /**
         * Ancho de los botones
         */
        @Prop({ type: String, default: '180px' })
        public ancho: string;

        /**
         * Espacio entre los botones
         */
        @Prop({ type: String, default: '10px' })
        public espacio: string;

        /**
         * Clase css color de widget
         */
        @Prop({ type: String, default: 'bg-white' })
        public classCssColor: string;

        /**
         * Icono
         */
        @Prop({ type: String})
        public icon: string;

        /**
         * Icono ver mas
         */
        @Prop({ type: String, default: 'fa fa-link'})
        public iconSeeMore: string;

        /**
         * link de ver mas
         */
        @Prop({ type: String})
        public linkSeeMore: string;

        /**
         * nombre del campo
         */
        @Prop({ type: String})
        public nameField: string;

        /**
         * Cantidad
         */
        @Prop({ type: Number, required: true})
        public qty: number;

        /**
         * id del estado
         */
        @Prop()
        public status: any;

        /**
         * id del estado
         */
        @Prop()
        public bgColor: any;

        /**
         * Titulo
         */
        @Prop({ type: String, required: true})
        public title: string;

        /**
         * Titulo de ver mas
         */
        @Prop({ type: String, default: 'Ver detalles'})
        public titleLinkSeeMore: string;

        /**
         * Valor del campo
         */
        @Prop({type: Object})
        public value: any;

        /**
         * Tipo de crud a implementar
         */
         @Prop({ type: Array, default: () => [] })
        public limpiarFiltros: any;

        @Prop({type: String})
        public urlRedirect: string;

        /**
         * Color de fondo del Ícono
         */
         @Prop({default: '#3f51b5'})
        public bgColorIcon: any;

        /**
         * Contructor de la clase
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 21 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();
        }

        /**
         * Filtro por consolidado
         *
         * @author Johan David Velasco 31/08/21
         * @version 1.0.0
         * 
         * @param status
         */

        public filter(status: string) {
            if(this.limpiarFiltros) {
                this.limpiarFiltros.forEach((field: string) => {
                    this.value[field] = null;
                });
            }
            // Valida el comportamiento del evento click del widget
            if(status != "redirect") {
                // Valida si el index principale es de listado avanzado
                if((this.$parent as any)["crudAvanzado"]) {
                    if(status=='all') {
                        (this.$parent as any).clearDataSearchAvanzado();
                    } else {
                        this.value[this.nameField] = status;
                        (this.$parent as any)["pageEventActualizado"](1);
                        (this.$parent as any)._updateKeyRefresh();
                    }
                } else { // Si no es de listado avanzado, es común, no hace las consultas al servidor
                    if(status=='all') {
                        (this.$parent as any).clearDataSearch();
                    } else {
                        this.value[this.nameField] = status;
                        (this.$parent as any)["advancedSearchFilterPaginate"]();
                        (this.$parent as any)._updateKeyRefresh();
                    }
                }
            } else {
                window.location.href = this.urlRedirect;
            }    
        }
    }
</script>