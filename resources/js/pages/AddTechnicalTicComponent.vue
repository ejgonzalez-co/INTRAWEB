<template>
   <div>
      <!-- begin #modal-form-users -->
      <div class="modal fade" id="modal-form-users">
         <div class="modal-dialog modal-xl">
            <form @submit.prevent="save()" id="form-users" enctype="multipart/form-data">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">Formulario de técnicos</h4>
                     <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body hljs-wrapper" >

                     <div v-if="!isUpdate" class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Validar usuario</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12">
                                 <!-- User Id Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-3" for="user_id">Funcionario:</label>
                                    <div class="col-md-6">
                                       <autocomplete
                                          name-prop="name"
                                          name-field="user_id"
                                          :value='dataForm'
                                          name-resource="/intranet/get-users"
                                          css-class="form-control"
                                          :name-labels-display="['name', 'email']"
                                          reduce-key="id"
                                          :function-change="validateUser"
                                          :key="keyRefresh"
                                       >
                                       </autocomplete>
                                       <small>Consulte sí el usuario que desea crear ya existe. En caso de no existir presione el botón "Crear nuevo técnico".</small>
                                       
                                    </div>
                                    <button type="button" class="form-control btn btn-primary col-md-2" @click="validateUser()"><i class="fas fa-user-plus mr-2"></i> Crear nuevo técnico</button>
                                 </div>

                                 <div v-if="dataUser.id" class="col-md-12">

                                    <div class="panel" data-sortable-id="ui-general-1">
                                       <!-- begin panel-heading -->
                                       <div class="panel-heading ui-sortable-handle">
                                          <h4 class="panel-title"><strong>Detalles del usuario</strong></h4>
                                       </div>
                                       <!-- end panel-heading -->
                                       <!-- begin panel-body -->
                                       <div class="panel-body">
                                          <div class="row">

                                             <!-- Dependency -->
                                             <div class="col-md-12">
                                                <div class="form-group row m-b-15">
                                                   <label class="col-form-label text-capitalize col-md-3">{{ 'trans.dependency' | trans }}:</label>
                                                   <div class="col-md-9">
                                                      <span class="form-control-plaintext">{{ dataUser.dependencies? dataUser.dependencies.nombre: '' }}</span>
                                                   </div>
                                                </div>
                                             </div>
                                             
                                             <!-- Position -->
                                             <div class="col-md-12">
                                                <div class="form-group row m-b-15">
                                                   <label class="col-form-label text-capitalize col-md-3">{{ 'trans.position' | trans }}:</label>
                                                   <div class="col-md-9">
                                                      <span class="form-control-plaintext">{{ dataUser.positions? dataUser.positions.nombre: '' }}</span>
                                                   </div>
                                                </div>
                                             </div>

                                             <!-- Name -->
                                             <div class="col-md-12">
                                                <div class="form-group row m-b-15">
                                                   <label class="col-form-label col-md-3">{{ 'trans.Name' | trans }}:</label>
                                                   <div class="col-md-9">
                                                      <span class="form-control-plaintext">{{ dataUser.name }}</span>
                                                   </div>
                                                </div>
                                             </div>

                                             <!-- Email -->
                                             <div class="col-md-12">

                                                <div class="form-group row m-b-15">
                                                   <label class="col-form-label col-md-3">{{ 'trans.Email' | trans }}:</label>
                                                   <div class="col-md-9">
                                                      <span class="form-control-plaintext">{{ dataUser.email }}</span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- end panel-body -->
                                    </div>
                                 </div>
                                  
                              </div>
                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>

                     <div v-if="addUser">

                        <div class="panel" data-sortable-id="ui-general-1">
                           <!-- begin panel-heading -->
                           <div class="panel-heading ui-sortable-handle">
                              <h4 class="panel-title"><strong>Estructura organizacional</strong></h4>
                           </div>
                           <!-- end panel-heading -->
                           <!-- begin panel-body -->
                           <div class="panel-body">
                              <div class="row">
                                    <!-- Id Cargo Field -->
                                    <div class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4 required" for="id_cargo">{{ 'trans.positions' | trans }}:</label>
                                          <div class="col-md-8">
                                             <select-check css-class="form-control" name-field="id_cargo" reduce-label="nombre" name-resource="/intranet/get-positions" :value="dataForm" :is-required="true" ></select-check>
                                             <small>Seleccione el cargo al cual pertenece el funcionario</small>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Id Dependencia Field -->
                                    <div class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4 required" for="id_dependencia">{{ 'trans.dependencies' | trans }}:</label>
                                          <div class="col-md-8">
                                             <select-check css-class="form-control" name-field="id_dependencia" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="dataForm" :is-required="true" ></select-check>
                                             <small>Seleccione la dependencia a la cual pertenece el funcionario.</small>
                                          </div>
                                       </div>
                                    </div>
                              </div>
                           </div>
                           <!-- end panel-body -->
                        </div>

                        <div class="panel" data-sortable-id="ui-general-1">
                           <!-- begin panel-heading -->
                           <div class="panel-heading ui-sortable-handle">
                              <h4 class="panel-title"><strong>Detalles de la cuenta de usuario</strong></h4>
                           </div>
                           <!-- end panel-heading -->
                           <!-- begin panel-body -->
                           <div class="panel-body">
                              <div class="row">
                                    <!-- Name Field -->
                                    <div class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4 required" for="name">{{ 'trans.Name' | trans }}:</label>
                                          <div class="col-md-8">
                                             <input type="text" id="name" :class="{'form-control':true, 'is-invalid':dataErrors.name }" v-model="dataForm.name" required>
                                             <small>Ingrese el nombre completo del funcionario (Nombres y apellidos).</small>
                                             <div class="invalid-feedback" v-if="dataErrors.name">
                                                <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Username Field -->
                                    <div class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4 required" for="username">{{ 'trans.Username' | trans }}:</label>
                                          <div class="col-md-8">
                                             <input type="text" id="username" :class="{'form-control':true, 'is-invalid':dataErrors.username }" v-model="dataForm.username" required>
                                             <small>Ingrese el nombre de usuario que va a utilizar en la cuenta.</small>
                                             <div class="invalid-feedback" v-if="dataErrors.username">
                                                <p class="m-b-0" v-for="error in dataErrors.username">@{{ error }}</p>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Password Field -->
                                    <div class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4 required" for="password">{{ 'trans.Password' | trans }}:</label>
                                          <div class="col-md-8">
                                             <input type="password" id="password" :class="{'form-control':true, 'is-invalid':dataErrors.password }" v-model="dataForm.password" :required="dataForm.change_user">
                                             <small>Ingrese una contraseña que contenga mínimo 6 caracteres.</small>
                                             <div class="invalid-feedback" v-if="dataErrors.password">
                                                <p class="m-b-0" v-for="error in dataErrors.password">@{{ error }}</p>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Confirm Password Field -->
                                    <div class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4 required" for="password_confirmation">{{ 'trans.Confirm Password' | trans }}:</label>
                                          <div class="col-md-8">
                                             <input type="password" id="password_confirmation" :class="{'form-control':true, 'is-invalid':dataErrors.password_confirmation }" v-model="dataForm.password_confirmation" :required="dataForm.change_user">
                                             <small>Por favor confirme la contraseña que ingresó.</small>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Email Field -->
                                    <div class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4 required" for="email">{{ 'trans.Email' | trans }}:</label>
                                          <div class="col-md-8">
                                             <input type="email" id="email" :class="{'form-control':true, 'is-invalid':dataErrors.email }" v-model="dataForm.email" required>
                                             <small>Ingrese un correo electrónico válido, ej: xxxxx@gmail.com</small>
                                             <div class="invalid-feedback" v-if="dataErrors.email">
                                                <p class="m-b-0" v-for="error in dataErrors.email">@{{ error }}</p>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Block Field -->
                                    <div v-show="!dataForm.change_user" class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4" for="block">{{ 'trans.Block' | trans }}:</label>
                                          <!-- switcher -->
                                          <div class="switcher col-md-8 m-t-5">
                                             <input type="checkbox" name="block" id="block" v-model="dataForm.block">
                                             <label for="block"></label>
                                             <small>Si bloquea la cuenta el usuario no podrá ingresar a ninguno de los sistemas.</small>
                                          </div>
                                       </div>
                                    </div>

                                    <!-- Url Img Profile Field -->
                                    <div v-show="!dataForm.change_user" class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4" for="url_img_profile">{{ 'trans.Url Img Profile' | trans }}:</label>
                                          <div class="col-md-8">
                                             <input type="file" name="url_img_profile" id="url_img_profile" @change="inputFile($event, 'url_img_profile')">
                                             <small>Relacione una imagen al perfil de la cuenta.</small>
                                          </div>
                                       </div>
                                    </div>
                                    
                                    <!-- Sendemail Field -->
                                    <div v-show="!dataForm.change_user" class="col-md-6">
                                       <div class="form-group row m-b-15">
                                          <label class="col-form-label col-md-4" for="sendEmail">{{ 'trans.Sendemail' | trans }}:</label>
                                          <!-- Sendemail switcher -->
                                          <div class="switcher col-md-6 m-t-5">
                                             <input type="checkbox" name="sendEmail" id="sendEmail" v-model="dataForm.sendEmail">
                                             <label for="sendEmail"></label>
                                          </div>
                                       </div>
                                    </div>
                              </div>
                           </div>
                           <!-- end panel-body -->
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- end #modal-form-users -->
   </div>
</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import * as bootstrap from 'bootstrap';

   import { Locale } from "v-calendar";

   import utility from '../utility';

   const locale = new Locale();

   /**
    * Componente para agregar tecnicos a la mesa de ayuda
    *
    * @author Carlos Moises Garcia T. - Nov. 04 - 2020
    * @version 1.0.0
    */
   @Component
   export default class AddTechnicalTicComponent extends Vue {

      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String, required: true, default: 'modal-form-assets-tics' })
      public name: string;

      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

      /**
       * Valida si los valores del formulario
       * son para actualizar o crear
       */
      public isUpdate: boolean;



      public addUser: boolean;

      /**
       * Datos del formulario
       */
      public dataUser: any;


      /**
       * Key autoincrementable y unico para
       * ayudar a refrescar un componente
       */
      public keyRefresh: number;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Nov. 04 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();

         this.keyRefresh = 0;

         this.dataErrors = {};

         // Inicializa valores del dataform
         this._initValues();

         this.isUpdate = false;

         this.addUser = false;

         this.dataUser = {};
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del dataform
         this._initValues();

         this.dataUser = {};

         // Limpia errores
         this.dataErrors = {};
         // Actualiza componente de refresco
         this._updateKeyRefresh();
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
      }

      /**
       * Evento de asignacion de archivo
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 17 - 2020
       * @version 1.0.0
       *
       * @param event datos de evento
       * @param fieldName nombre de campo
       */
      public inputFile(event, fieldName: string): void {
         this.dataForm[fieldName] = event.target.files[0];
      }

      
      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadAddUser(dataElement: object): void {

         // this.clearDataForm();

         if (dataElement) {
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = utility.clone(dataElement);
            // Habilita actualizacion de datos
            this.isUpdate = true;
            // Habilita el formulario de los datos del usuario
            this.addUser  = true;
            // Habilita formulario
            (this.$parent as any).openForm = true;
            // Actualiza componente de refresco
            this._updateKeyRefresh();

         } else{
            this.addUser  = false;
            this.isUpdate = false;
         }

         $(`#modal-form-${this.name}`).modal('show');
      }

      /**
       * Valida si existe o no el usuario previamente registrador
       *
       * @author Carlos Moises Garcia T. - Nov. 04 - 2020
       * @version 1.0.0
       */
      public validateUser(data): void {
         // Valida que exista datos en el autocompletar usuarios
         if (data) {
            this.addUser = false;
            this.dataUser = data;
         } else{
            this.clearDataForm();
            this.addUser = true;
         }
      }

      /**
      * Guarda los datos del formulario
      *
      * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
      * @version 1.0.0
      */
      public save(): void {
         // Limpia los errores anteriores
         this.dataErrors = {};
         // Valida si los datos son para actualizar
         if (this.isUpdate) {
            (this.$parent as any).dataForm = utility.clone(this.dataForm);

            // Actualiza un registro existente
            (this.$parent as any).update();
            
         } else {
            (this.$parent as any).dataForm = utility.clone(this.dataForm);
            // Almacena un nuevo registro
            (this.$parent as any).store();
         }
      }

      /**
       * Inicializa valores del dataform
       *
       * @author Carlos Moises Garcia T. - Nov. 04 - 2020
       * @version 1.0.0
       */
      private _initValues(): void {
         this.dataForm = utility.clone((this.$parent as any).initValues);
      }

      /**
       * Actualiza el componente que utilize el key
       * cada vez que se cambia de editar a actualizar
       * y borrado de campos de formulario
       *
       * @author Jhoan Sebastian Chilito S. - Jul. 06 - 2020
       * @version 1.0.0
       */
      private _updateKeyRefresh(): void {
         this.keyRefresh ++;
      }

   }
</script>