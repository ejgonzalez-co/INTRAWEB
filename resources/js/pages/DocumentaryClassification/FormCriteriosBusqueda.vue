<template>
    <div>
        <div class="modal fade" id="modal-view-publication">
              <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Crear o editar criterio de búsqueda</h4>
                        <button type="button" class="close" @click="clearDataForm()" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="panel" style="border-radius: 10px; padding: 10px;" data-soportable-id="ui-general-1">
                            <div>
                                <h5>Información del criterio a crear</h5>
                            </div>
                            <br>
                                <div class="col">

                                    <div class="col-md-10">
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3 required">Nombre</label>
                                            <div class="col-md-9">
                                                <input type="text"  :required="true" class="form-control" v-model="dataForm.nombre">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3 required">Tipo de campo:</label>
                                            <div class="col-md-9">
                                                <select class="form-control"  :required="true" v-model="dataForm.tipo_campo">
                                                    <option value="Texto">Texto</option>
                                                    <option value="Contenido">Contenido</option>
                                                    <option value="Fecha">Fecha</option>
                                                    <option value="Número">Número</option>
                                                    <option value="Lista">Lista</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="dataForm.tipo_campo != ''">
                                        <div class="col-md-10">
                                            <div class="form-group row m-b-15">
                                                <label class="col-form-label col-md-3">Texto de ayuda</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" v-model="dataForm.texto_ayuda">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-10">
                                            <div class="form-group row m-b-15">
                                                <label class="col-form-label col-md-3">Obligatorio(*): </label>
                                                <div class="col-md-9">
                                                    <select class="form-control" v-model="dataForm.requerido">
                                                        <option value="Si">Si</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-10" v-if="dataForm.tipo_campo == 'Lista'">
                                            <div class="form-group row m-b-15">
                                                <label class="col-form-label col-md-3">Opciones: </label>
                                                <div class="col-md-9">
                                                <input type="text" class="form-control" style="width: 90% !important; display: math;"
                                                    v-model="dataForm.optionsSelect">

                                                    <button @click="addOptions()" class="buttonSearch" type="button" style="width: 35px; height: 34px;">
                                                <i class="fas fa-plus"></i>
                                                </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-10" v-if="dataForm.tipo_campo == 'Lista'">
                                            <div class="form-group row m-b-15">
                                                <label class="col-form-label col-md-3"></label>
                                                <div class="col-md-9">
                                                    <ul style="margin-top:10px;">
                                                        <li v-for="(col, k) in this.listOptionsSelect">{{ col }} <button @click="deleteOption(k)" style="border:none; background-color: white;" type="button" title="Eliminar opción">
                                                        <i class="fas fa-times"></i>
                                                    </button></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="panel" style="border-radius: 10px; padding: 10px;" data-soportable-id="ui-general-1">
                            <div>
                                <h5>Lista de series o subseries relacionadas al criterio</h5>
                                <br>
                                <label style="font-style: italic; color: rgb(153, 153, 153);"> Ayuda: Cuando ingrese la serie o subserie relacionada al criterio, haga clic en el botón: Agregar serie o subserie a la lista.</label>
                                <hr>
                            </div>
                            <br>
                            <div class="col">
                                <div class="col-md-10">
                                    <div class="form-group row m-b-15">
                                        <label class="col-form-label col-md-3 required">Serie o subserie: </label>
                                        <div class="col-md-9">
                                            <select-check
                                            css-class="form-select form-control row col-md-12"
                                            name-field="id_series_subseries"
                                            :reduce-label="['type','name','no_serieosubserie']"
                                            :name-resource="'get-series-subseries?query=all'"
                                            reduce-key="id_series_subseries"
                                            :value="dataForm"
                                            :is-required="true"
                                            name-field-object="serie_documental"
                                            :enable-search="true"
                                        >
                                        </select-check>

                                            <small style="font-style: italic; color: rgb(153, 153, 153);">Si seleccione una serie al criterio y requiere que este incluya en sus subseries el mismo criterio de búsqueda, utilice el botón "Relacionar el criterio a la serie y sus subseries".</small>
                                        </div>
                                    </div>
                                    <div class="form-group row m-b-15">
                                        <label class="col-form-label col-md-3">Tipo documental: </label>
                                        <div class="col-md-9">
                                            <select-check
                                                css-class="form-control"
                                                name-field="id_type_documentaries"
                                                reduce-label="name"
                                                reduce-key="id_type_documentaries"
                                                :options-list-manual="dataForm.serie_documental?.types_list"
                                                :value="dataForm"
                                                :is-requered="true">
                                            </select-check>

                                            <small style="font-style: italic; color: rgb(153, 153, 153);">Si seleccione una serie al criterio y requiere que este incluya en sus subseries el mismo criterio de búsqueda, utilice el botón "Relacionar el criterio a la serie y sus subseries".</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-20">
                                    <div class="form-group row m-b-20">
                                        <label class="col-form-label col-md-3"></label>

                                        <div class="col-md-20" style="margin-left: 50px;">
                                                <button class="btn btn-success bg-blue" style="margin-left: 5px;" @click="addSeleccion()"><i class="fas fa-plus"></i> Relacionar el criterio a las series o subseries</button>

                                                <button class="btn btn-success bg-blue" style="margin-left: 5px;" @click="addSeleccionMultiple()"><i class="fas fa-plus"></i> Relacionar el criterio a la serie y sus subseries</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-20">
                                    <table class="table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Id</th>
                                                <th scope="col">Código</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Tipo documental</th>
                                                <th scope="col">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(col, k) in this.listSerieSubseries">
                                                <td>{{ col.id }}</td>
                                                <td>{{ col.no_subserie ? col.no_subserie : col.no_serie }}</td>
                                                <td v-if="col.no_subserie != ''">{{ col.name_subserie }}</td>
                                                <td v-else>{{ col.name_serie }}</td>
                                                <td>{{ col.tipo_documental?.name }}</td>
                                                <td>
                                                    <button @click="deleteSerieSubserie(k)" class="btn btn-white btn-icon btn-md" title="Eliminar">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                            <button @click="crudData()" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .buttonSearch{
        border: 1px solid #ccc;
        border-radius: 0px 10px 10px 0px;

    }
</style>
<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import axios from "axios";

import { jwtDecode } from 'jwt-decode';

@Component
export default class FormCriteriosBusqueda extends Vue {


    public dataForm: any;

    public listOptionsSelect: any;

    public countOptions: any;

    public listSerieSubseries: any;

    public crud: any;


    /**
   * Constructor de la clase
   *
   * @author Johan David Velasco. - Oct. 10 - 2023
   * @version 1.0.0
   */
  constructor() {
    super();
    this.dataForm = {
        tipo_campo : ''
    };

    this.crud = '';
    this.listOptionsSelect = {};
    this.listSerieSubseries = {};
    // this.listFieldsDynamic = [];
    this.countOptions = 0;
  }

    //Funcion que abre la modal a la hora de crear
    public showModalCreate(){

        this.crud = 'create';

        this.dataForm = {
            tipo_campo : ''
        };
        this.listOptionsSelect = {};
        $("#modal-view-publication").modal("show");

    }

    //Funcion que abre la modal a hora de editar
    public showModalEdit(data){

        this.crud = 'edit';

        this.dataForm = data;

        this.listSerieSubseries = data.series_subseries;

        if (data.opciones) {
            this.listOptionsSelect = JSON.parse(data.opciones);
        }
        // this.listOptionsSelect = {};
        $("#modal-view-publication").modal("show");

    }

    //Funcion para agregar la opciones que iran en la lista dinamica
    public addOptions(){
        this.listOptionsSelect['option'+this.countOptions] = this.dataForm.optionsSelect;
        this.dataForm.optionsSelect = '';
        this.countOptions++;
    }

        //Funcion para eliminar las opciones de la lista dinamica
    public deleteOption(key){
        Vue.delete(this.listOptionsSelect, key);
    }

    //Consulta la informacion de la serie y la inserta en la tabla
    public addSeleccion(){
        if(this.dataForm.id_series_subseries == undefined || this.dataForm.id_series_subseries == "") {
            // Si no ingresó un código de validación, muestra un mensaje indicándole al usuario que debe ingresar uno
            this.$swal({
                icon: "warning",
                title: "Advertencia",
                html: "Debe seleccionar una serie o subserie",
                confirmButtonText: "Aceptar"
            }).then((res) => {
                // Se esperan 300 milisegundos para enfocar el campo del código de validación
                setTimeout(() => {
                    jQuery("select[name=id_series_subseries]").focus();
                }, 300);
            });
        } else {
            this.$swal({
                title: 'Cargando',
                html: 'consultando datos',
                onBeforeOpen: () => {
                    (this.$swal as any).showLoading();
                }
            });

            axios
            .post('add-serie-subserie', this.dataForm)
            .then((res) => {
                (this.$swal as any).close();

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                const request = dataDecrypted["data"];

                let count = this.listSerieSubseries.length ?? 0;;


                if(count === 0){
                    this.listSerieSubseries = request;

                }else{
                    request.forEach(value =>{
                        this.$set(this.listSerieSubseries, count,value)
                        count++;
                    });
                }
                // Deja vacío los select para relacionar los criterios de búsqueda con las serires y/o tipos documentales
                this.$set(this.dataForm, "id_series_subseries", "");
                this.$set(this.dataForm, "id_type_documentaries", "");
            })
            .catch((error) => {
            //   console.log(Error: ${error});
            });
        }
    }

    //Consulta las series y sub series
    public addSeleccionMultiple(){


        (this.$parent as any)["showLoadingGif"]('Cargando datos');


        axios
        .post('adds-series-subseries', this.dataForm)
        .then((res) => {
            (this.$swal as any).close();

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            const request = dataDecrypted["data"];



            let count = this.listSerieSubseries.length ?? 0;;


            if(count === 0){
                this.listSerieSubseries = request;

            }else{
                request.forEach(value =>{
                    this.$set(this.listSerieSubseries, count,value)
                    count++;
                });
            }




        })
        .catch((error) => {
        //   console.log(Error: ${error});
        });
    }

    //Elimina las series que desee
    public deleteSerieSubserie(key){

        Vue.delete(this.listSerieSubseries, key);
    }
    
    public validateForm() {
            if (!this.dataForm.nombre || this.dataForm.nombre.trim() === '') {
                this.$swal({
                    icon: 'warning',
                    text: 'El campo "Nombre" es obligatorio.',
                    confirmButtonText: 'Aceptar'
                });
                return false;
            }

            if (!this.dataForm.tipo_campo || this.dataForm.tipo_campo.trim() === '') {
                this.$swal({
                    icon: 'warning',
                    text: 'El campo "Tipo de campo" es obligatorio.',
                    confirmButtonText: 'Aceptar'
                });
                return false;
            }

            if (!this.dataForm.serie_subserie || this.dataForm.serie_subserie.length === 0) {
                this.$swal({
                    icon: 'warning',
                    text: 'Debe seleccionar al menos una serie o subserie.',
                    confirmButtonText: 'Aceptar'
                });
                return false;
            }

            return true;
        }

    public crudData(){

        this.dataForm.serie_subserie = this.listSerieSubseries;
        this.dataForm.list_options = this.listOptionsSelect;

           // ✅ Llamar a la validación antes de continuar
            if (!this.validateForm()) {
                return;
            }
        (this.$parent as any)["showLoadingGif"]('Cargando datos');


        if (this.crud == 'create') {
            axios
            .post('save-criterios-busqueda', this.dataForm)
            .then((res) => {

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                const request = dataDecrypted["data"];

                (this.$swal as any).close();

                if (res.data.type_message) {
                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.success == true) {
                        // Cierra fomrulario modal
                        $('#modal-view-publication').modal("toggle");
                        // Limpia datos ingresados
                        this.clearDataForm();
                        // Agrega elemento nuevo a la lista
                        (this.$parent as any).dataList.unshift(request);

                    }
                    // Abre el swal de la respusta de la peticion
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar'
                    });
                }

            })
            .catch((error) => {
            //   console.log(Error: ${error});
            });
        }else{

            axios
            .post('edit-criterios-busqueda', this.dataForm)
            .then((res) => {

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                const request = dataDecrypted["data"];

                (this.$swal as any).close();
                // Valida el tipo de alerta que de debe mostrar
                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.success == true) {
                        // Cierra fomrulario modal
                        $('#modal-view-publication').modal("toggle");
                        // Agrega elemento nuevo a la lista
                        Object.assign(
                            (this.$parent as any)._findElementById(
                                this.dataForm.id,
                                false
                            ),
                            request
                        );

                        // Limpia datos ingresados
                        this.clearDataForm();
                    }
                    // Abre el swal de guardando datos
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar'
                    });


            })
            .catch((error) => {
            //   console.log(Error: ${error});
            });

        }


    }


    public clearDataForm(){
            this.dataForm = {};
            this.listSerieSubseries = {};
            this.listOptionsSelect = {};
            this.crud = '';

    }




}
</script>
