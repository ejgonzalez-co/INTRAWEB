<template>
        <div class="col-md-8" v-if="this.filter == 'Si' && this.listCriterios.length > 0 && this.validationFilter == 'Si'">
            <h5>Criterios de búsqueda para la serie o subserie</h5>
            <!--  Other officials Field destination-->
            <br>
            <div class="form-group row m-b-15" v-for="(info, k) in this.listCriterios">

                <label class="col-form-label col-md-3 required" v-if="info.requerido == 'Si'">{{ info.nombre }}</label>
                <label class="col-form-label col-md-3" v-else>{{ info.nombre }}</label>
                    <div class="col-md-9" v-if="info.tipo_campo == 'Texto'">
                        <input class="form-control" v-if="info.requerido == 'Si'" v-on:keyup="insertDataSearch()" v-model="dataForm.texto_criterio" required type="text">
                        <input class="form-control" v-else v-on:keyup="insertDataSearch()" v-model="dataForm.texto_criterio" type="text">
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9" v-else-if="info.tipo_campo == 'Lista'">
                        <select v-if="info.requerido == 'Si'" v-on:change="insertDataSearch()" required class="form-control" v-model="dataForm.lista_criterio">
                            <option selected>Seleccione</option>
                            <option v-for="(item, k) in info.opciones" :value="item">{{ item }}</option>
                        </select>
                        <select v-else class="form-control" v-on:change="insertDataSearch()" v-model="dataForm.lista_criterio">
                            <option selected>Seleccione</option>
                            <option v-for="(item, k) in info.opciones" :value="item">{{ item }}</option>
                        </select>
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9"  v-else-if="info.tipo_campo == 'Contenido'">
                        <textarea v-if="info.requerido == 'Si'" v-on:keyup="insertDataSearch()" class="form-control" cols="5" required v-model="dataForm.contenido_criterio"></textarea>
                        <textarea v-else class="form-control" placeholder="" v-on:keyup="insertDataSearch()" cols="5" v-model="dataForm.contenido_criterio"></textarea>
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9" v-if="info.tipo_campo == 'Número'">
                        <input class="form-control" v-if="info.requerido == 'Si'" v-on:keyup="insertDataSearch()" v-model="dataForm.numero_criterio" required type="number">
                        <input class="form-control" v-else v-on:keyup="insertDataSearch()" v-model="dataForm.numero_criterio" type="text">
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9" v-if="info.tipo_campo == 'Fecha'">
                        <input class="form-control" v-if="info.requerido == 'Si'" v-on:change="insertDataSearch()" v-model="dataForm.fecha_criterio" required type="date">
                        <input class="form-control" v-else v-on:change="insertDataSearch()" v-model="dataForm.fecha_criterio" type="date">
                        <small>{{ info.texto_ayuda }}</small>
                    </div>
                </div>
        </div>


        <div class="col-md-12" v-else-if="this.filter == 'No'">
            <label style="font-style: italic; color: rgb(153, 153, 153);"> <strong>¡Importante!:</strong> Los criterios de búsqueda son información puntual para buscar documentos, estos criterios son configurados y relacionados a cada serie documental.</label>
            <!--  Other officials Field destination-->
            <br>
            <div class="form-group row m-b-15" v-for="(info, k) in this.listCriterios">

                <label class="col-form-label col-md-3 required" v-if="info.requerido == 'Si'">{{ info.nombre }}</label>
                <label class="col-form-label col-md-3" v-else>{{ info.nombre }}</label>
                    <div class="col-md-9" v-if="info.tipo_campo == 'Texto'">
                        <input class="form-control" v-if="info.requerido == 'Si'" v-on:keyup="insertDataForm()" v-model="dataForm.texto_criterio" required type="text">
                        <input class="form-control" v-else v-on:keyup="insertDataForm()" v-model="dataForm.texto_criterio" type="text">
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9" v-else-if="info.tipo_campo == 'Lista'">
                        <select v-if="info.requerido == 'Si'" v-on:change="insertDataForm()" required class="form-control" v-model="dataForm.lista_criterio">
                            <option selected>Seleccione</option>
                            <option v-for="(item, k) in info.opciones" :value="item">{{ item }}</option>
                        </select>
                        <select v-else class="form-control" v-on:change="insertDataForm()" v-model="dataForm.lista_criterio">
                            <option selected>Seleccione</option>
                            <option v-for="(item, k) in info.opciones" :value="item">{{ item }}</option>
                        </select>
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9"  v-else-if="info.tipo_campo == 'Contenido'">
                        <textarea v-if="info.requerido == 'Si'" v-on:keyup="insertDataForm()" class="form-control" cols="5" required v-model="dataForm.contenido_criterio"></textarea>
                        <textarea v-else class="form-control" placeholder="" v-on:keyup="insertDataForm()" cols="5" v-model="dataForm.contenido_criterio"></textarea>
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9" v-if="info.tipo_campo == 'Numero'">
                        <input class="form-control" v-if="info.requerido == 'Si'" v-on:keyup="insertDataForm()" v-model="dataForm.numero_criterio" required type="number">
                        <input class="form-control" v-else v-on:keyup="insertDataForm()" v-model="dataForm.numero_criterio" type="text">
                        <small>{{ info.texto_ayuda }}</small>
                    </div>

                    <div class="col-md-9" v-if="info.tipo_campo == 'Fecha'">
                        <input class="form-control" v-if="info.requerido == 'Si'" v-on:change="insertDataForm()" v-model="dataForm.fecha_criterio" required type="date">
                        <input class="form-control" v-else v-on:change="insertDataForm()" v-model="dataForm.fecha_criterio" type="date">
                        <small>{{ info.texto_ayuda }}</small>
                    </div>
                </div>
        </div>
</template>
<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";

import axios from "axios";

import { jwtDecode } from 'jwt-decode';
import { arrayMax } from "highcharts";
import utility from '../../utility';


@Component
export default class CriteriosBusquedaSave extends Vue {


    public dataForm: any;

    public listOptionsSelect: any;

    public listCriterios: any;

    public validationFilter: any;

    @Prop({})
    public idSerie: any;

    @Prop({})
    public dataEdit: any;

    @Prop({})
    public edit: any;

    @Prop({})
    public filter: any;


    /**
   * Constructor de la clase
   *
   * @author Johan David Velasco. - Oct. 10 - 2023
   * @version 1.0.0
   */
  constructor() {
    super();
    this.dataForm = {};
    this.listOptionsSelect = {};
    this.listCriterios = {};
    this.validationFilter = '';
    this.filter = '';

    // this.listFieldsDynamic = [];
  }

    created() {
        if (this.edit == 'Si') {
            this.filter = 'No';
            this.consultDataIndex(this.idSerie);
        }
    }


     //Detecta el cambio en el prop
    @Watch('idSerie')
    onMiPropChanged(newVal: string, oldVal: string) {
        // Llama a la función que deseas ejecutar cuando el prop cambie
        if (this.filter == 'Si') {
            this.validationFilter = 'Si';
        }
        this.consultDataIndex(newVal);
    }


    //consulta los datos
    consultDataIndex(id){

        axios
        .post('consult-data-index', {'id':id})
        .then((res) => {

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            const request = dataDecrypted["data"];

            const criterios = request.criterios_busqueda;

            criterios.forEach(value =>{
            if(value.tipo_campo == 'Lista'){
                value.opciones = JSON.parse(value.opciones)

            }
                return value;
            });



            this.listCriterios = criterios;


            if (this.edit == 'Si') {
                this.dataForm = this.dataEdit[0];
            }

        })
        .catch((error) => {
        //   console.log(Error: ${error});
        });
    }

    public insertDataForm(){
        const data = (this.$parent as any).dataForm;

        const json = JSON.stringify(this.dataForm);

        data.criterios_value = json;

        (this.$parent as any).dataForm = data;
        this.$forceUpdate();

    }

    public insertDataSearch(){
        const data = (this.$parent as any).searchFields;

        if (this.dataForm.texto_criterio) {
            data.texto_criterio = this.dataForm.texto_criterio;
        }

        if (this.dataForm.lista_criterio) {
            data.lista_criterio = this.dataForm.lista_criterio;
        }

        if (this.dataForm.contenido_criterio) {
            data.contenido_criterio = this.dataForm.contenido_criterio;
        }

        if (this.dataForm.numero_criterio) {
            data.numero_criterio = this.dataForm.numero_criterio;
        }

        if (this.dataForm.fecha_criterio) {
            data.fecha_criterio = this.dataForm.fecha_criterio ;
        }


        (this.$parent as any).searchFields = utility.clone(data);

        (this.$parent as any)["advancedSearchFilterPaginate"]();
        (this.$parent as any)._updateKeyRefresh();

        this.$forceUpdate();

    }

    public clearDataSearch(){
        this.dataForm = {};
        this.listCriterios = {};
        (this.$parent as any)["clearDataSearch"]();
    }

    destroyed() {
        this.dataForm = {};
        this.filter = '';
        this.dataEdit = '';
        this.idSerie = '';
        this.edit = '';

    }




}
</script>
