<template>
    <!-- <div class="row"> -->
    <div class="row justify-content-center">
        <div @mouseover="pausa()" @mouseout="continua()" v-if="islink==true">
            <button class="slick-prev mt-3" @click="backImg()"> <b><</b> </button>
            <a :class="classA" :style="styleA" :href="optionsList[contador] ? optionsList[contador]['ruta']:''" target="_blank"><img :src="optionsList[contador] ? optionsList[contador]['url']:''" width="1050" height="400" alt=""></a>
            <button class="slick-next mt-3"  @click="nextImg()"> <b>></b> </button>
        </div>
        <div v-else>
            <img :src="optionsList[contador] ? optionsList[contador]['url']:''" alt="">
        </div>
    </div>
    <!-- </div> -->
</template>
<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import axios from "axios";

/**
 * Componente para un banner 
 *
 * @author José Manuel Marín Londoño. - Dic. 16 - 2021
 * @version 1.0.0
 */
@Component
export default class SliderComponent extends Vue {
    /**
     * Nombre de la entidad a obtener
     */
    @Prop({ type: String, required: true })
    public nameResource: string;

    /**
     * Nombre de la clase
     */
    @Prop({ type: String, required: true })
    public classbtn: string;

    /**
     * Nombre de la clase
     */
    @Prop({ type: String })
    public classA: string;

    /**
     * estilos de banner
     */
    @Prop({ type: String })
    public styleA: string;

    /**
     * Valida si viene con link
     */
    @Prop({ type: Boolean, default: false})
    public islink: boolean;

    /**
     * Lista de opciones
     */
    public optionsList: Array<any>;

    //Numero del item
    public contador: number;

    //Numero de items
    public total: number;

     //Intervalo
    public interval: any;

    /**
     * Constructor de la clase
     *
     * @author José Manuel Marín Londoño. - Dic. 16 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        this.contador=0;
        this.optionsList = [];
    }
    created() {
    }
    /**
     * Se ejecuta cuando el componente ha sido momntado
     */
    mounted() {
        // Carga la lista de elementos
        this.getImages();
        this.interval= setInterval(this.nextImg, 2000);
    }

    /**
     * envia los datos
     *
     * @author José Manuel Marín Londoño. - Dic. 16 - 2021
     * @version 1.0.0
     */
    public getImages(): void {
    // Envia peticion de obtener todos los datos del recurso
    axios
        .get(this.nameResource)
        .then(res => {

            // Llena la lista de datos
            this.optionsList = res.data.data;
            this.total=this.optionsList.length;
        })
        .catch(err => {
            console.log("Error al obtener la lista.");
            });
    }
    public nextImg(){
        this.contador++;
        
        if(this.contador==this.total){
            this.contador=0;
        }
    }
    public backImg(){
        this.contador--; 
        if(this.contador==-1){
            this.contador=this.total-1;
        }
    }
    public pausa(){
        clearInterval(this.interval);
    }
        public continua(){
            this.interval= setInterval(this.nextImg, 2000);
        }
    
}
</script>
<style>

    .slick-prev, .slick-next {
        font-size: 20px;
        color: white;
        background-color: rgba(111, 116, 111, 0.3);
        height: 40px;
        width: 40px;
        outline:none;
        text-align: center;
        border-radius: 50%;
        border: none;
    }
.slick-prev {
    left: 612px;
}
    .slick-prev:hover, .slick-next:hover {
    background-color: #8a8f87;
    }
</style>