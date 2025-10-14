<template>
<div class="container">
    <!-- Modal para mostrar el componente para firmar -->
    <b-modal v-model="mostrarModal" :title="fieldTitle+fieldTitleVarValue" @hide="cerrarModal" size="xl" class="" header-class="bg-blue text-white" hide-footer>
    <div class="container">
        <!-- Botón para mostrar/ocultar campos de nueva anotación -->
        <button @click="abrirCampos" class="btn btn-primary mb-3">
            <i class="fa fa-chevron-down mr-2"></i> Mostrar Formulario de Creación de Anotaciones
        </button>


        <!-- Campos de nueva anotación -->
        <div v-if="mostrarCampos" class="contenedorFormFuncionarioObs container-fluid bg-light">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                    <div class="card-body">
                        <div class="form-group row m-b-15">
                    <div class="col-form-label col-md-3 required">Contenido de la anotación:</div>
                    <div class="col-md-9">
                    <!-- Editor de texto para el contenido -->
                    <text-area-editor
                        :value="dataForm"
                        :name-field="nameContent"
                        placeholder='Ingrese el contenido'
                        :hide-modules="{
                        'image': true,
                        'code': true,
                        'link': true,
                        'underline': true,
                        'table': true,
                        'headings': true,
                        'removeFormat': true,

                        
                        }"
                    ></text-area-editor>
                    </div>
                </div>
                    </div>
                </div>
                </div>
               
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row m-b-15">
                                <div class="col-form-label col-md-3">Lista de archivos adjuntos de la anotación:</div>
                                <div class="col-md-9">
                                <!-- Selector de archivos adjuntos -->
                                <input-file
                                    :value="dataForm"
                                    :name-field="nameAttached"
                                    :max-files="10"
                                    :max-filesize="10"
                                    :file-path="filePath"
                                    message="Arrastre o seleccione los archivos"
                                    help-text="Lista de archivos adjuntos."
                                ></input-file>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row m-b-15">
                                <span class="alert alert-success w-100" role="alert">
                                    Por favor revise detenidamente el contenido de la anotación y el archivo adjunto antes de Guardar la anotación. No podrá cambiar ni eliminar después.
                                </span>
                            </div>
                            <!-- Botón para guardar la anotación -->
                            <button @click="_execution" class="btn btn-add">
                                <i class="fa fa-save mr-2"></i> Guardar anotación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de anotaciones -->
    <div v-if="valorEntrante && valorEntrante[nameList] && valorEntrante[nameList].length > 0">           
        <section id="timeline">
        <article v-for="(anotacion, key) in valorEntrante[nameList]" :key="key">
            <div class="inner">
            <!-- Fecha de la anotación -->
            <span class="date">
                <span class="day"> {{ anotacion.date_format.day }}</span>
                <span class="month"> {{ anotacion.date_format.month }}</span>
                <span class="year"> {{ anotacion.date_format.year }}</span>
            </span>
            <!-- Información del usuario -->
            <h2 style="display: flex; align-items: center;">
                <div style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%; margin-right: 10px;">
                <img v-if="anotacion.users.url_img_profile !== '' && anotacion.users.url_img_profile !== 'users/avatar/default.png'" :src="'/storage/' + anotacion.users.url_img_profile" alt="" style="width: 100%; height: auto;">
                <img v-else src="/assets/img/user/profile.png" alt="" style="width: 100%; height: auto;">
                </div>
                <span style="width: 80%;">{{ anotacion[nameUsersName] ?? anotacion.users.name }}</span>
            </h2>
            <!-- Contenido de la anotación -->
            <p>
                <strong>Fecha y hora:</strong> {{ anotacion.date_format.day }} de {{ anotacion.date_format.monthcompleto }} de {{ anotacion.date_format.year }} {{ anotacion.date_format.hour }}<br>
                <strong>Anotación:</strong>
                <span class="contenidotext" v-html="anotacion[nameContent]"></span>
                <br>
                <!-- Archivos adjuntos -->
                <span v-if="anotacion[nameAttached]">
                <strong>Documento:</strong>
                <viewer-attachement :link-file-name="true" :list="anotacion[nameAttached]" :key="anotacion[nameAttached]"></viewer-attachement>
                </span>
            </p>
            </div>
        </article>
        </section>
    </div>
    <div v-else class="d-flex justify-content-center align-items-center pt-3">
        <div class="alert alert-info rounded-pill text-center" role="alert">
            <i class="fas fa-info-circle mr-2"></i> Aún no hay anotaciones. Para crear una anotación, use el botón "Mostrar formulario de creación de anotaciones".
        </div>
    </div>



    <div class="modal-footer">
        <button class="btn btn-white" @click="cerrarModal"><i class="fa fa-times mr-2"></i>Cerrar</button>
    </div>

    </b-modal>
</div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";
import axios from "axios";
import { jwtDecode } from 'jwt-decode';

@Component
export default class AnnotationsGeneralComponent extends Vue {
  @Prop({ type: String }) public route: string;
  @Prop({ type: Object }) public value: any;

  @Prop({ type: String, default: 'anotaciones' }) public nameList: string;
  @Prop({ type: String, default: 'content' }) public nameContent: string;
  @Prop({ type: String, default: 'attached' }) public nameAttached: string;
  @Prop({ type: String, default: 'users_name' }) public nameUsersName: string;
  @Prop({ type: String, default: 'public/container/anotations' }) public filePath: string;
  
 
  @Prop({ type: String, default: 'Anotaciones' }) public fieldTitle: string;
  @Prop({ type: String, default: 'consecutive' }) public fieldTitleVar: string;

  public mostrarModal = false;
  public mostrarCampos = true;
  public dataForm: any = {};
  public valorEntrante: any;
  public fieldTitleVarValue: any;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez.  2024
     * @version 1.0.0
     */
    constructor() {
        super();
        this.valorEntrante = {};
        this.mostrarCampos = true;
        this.dataForm = {};
        this.fieldTitleVarValue= "";

    }
    //Funciones del componente
    public abrirCampos(): void {
        // Alternar la visibilidad de los campos de nueva anotación
        this.mostrarCampos = !this.mostrarCampos;
    }

    public abrirModal(row: any): void {

        this.mostrarCampos = true;
        // Abrir el modal y establecer los datos de la anotación seleccionada
        this.valorEntrante = row;
        this.fieldTitleVarValue = row[this.fieldTitleVar]; 

        this.mostrarModal = true;

    }

    public cerrarModal(): void {
        // Cerrar el modal y restablecer los datos
        this.mostrarModal = false;
        this.valorEntrante = {};
        this.mostrarCampos = false;

    }

    public async _execution(): Promise<void> {

        if (typeof this.dataForm[this.nameContent] !== 'undefined' && this.dataForm[this.nameContent] !== null && this.dataForm[this.nameContent].trim() !== '') {
            try {
                // Mostrar el indicador de carga
                this.showLoadingGif("Guardando anotación");

                // Crear el formulario de datos
                const formData = new FormData();
                formData.append(this.nameContent, this.dataForm[this.nameContent]);
                formData.append(this.nameAttached, this.dataForm[this.nameAttached]);

                // Realizar la petición para guardar o actualizar la anotación
                const res = await axios.post(`${this.route}/${this.valorEntrante.id}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
                const dataDecrypted = res.data.data ? jwtDecode(res.data.data) : null;

                // Agregar la anotación a la lista
                this.addElementToList(dataDecrypted["data"]);

                // Limpiar los campos del formulario
                this.$set(this.dataForm, this.nameContent, '');
                this.$set(this.dataForm, this.nameAttached, []);
                $('input[type=file]').val(null);
                
                // Ocultar los campos de nueva anotación
                this.mostrarCampos = false;
                
                // Cerrar el indicador de carga
                this.$swal.close();
                
                // Mostrar notificación de éxito
                this._pushNotification("Guardado con éxito");
            } catch (error) {
                // Manejar errores de la petición
                if (error.response && error.response.data && error.response.data.errors) {
                console.log(error.response.data.errors);
                }
            }
        }else{
            this.$swal({
            title: "¡Ingrese un texto a la anotación!",
            text: "Por favor, complete el texto de la anotación antes de guardarla.",
            icon: "info",
            confirmButtonText: "Entendido",
            });
        }
    }

    public addElementToList(elementToAdd: any): void {
        // Agregar una nueva anotación a la lista
        this.valorEntrante[this.nameList].unshift(elementToAdd);
    }

    private showLoadingGif(message: string): void {
        // Mostrar un indicador de carga
        this.$swal({
            html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>' + message + '.</span>',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }

    private _pushNotification(message: string, isPositive: boolean = true, title: string = '¡Éxito!'): void {
        // Mostrar una notificación
        const toastOptions = { closeButton: true, closeMethod: 'fadeOut', timeOut: 3000, tapToDismiss: false };
        if (isPositive) {
            toastr.success(message, title, toastOptions);
        } else {
            toastOptions.timeOut = 0;
            toastr.error(message, title, toastOptions);
        }
    }

}
</script>