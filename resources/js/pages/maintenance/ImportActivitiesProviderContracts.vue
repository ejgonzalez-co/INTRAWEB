<template>
   <div>
      <!-- begin #modal-form-import-spare-parts-provider-contracts -->
      <div class="modal fade" id="modal-form-import-activities-provider-contracts">
         <div class="modal-dialog modal-lg">
            <form @submit.prevent="store()" id="form-import-activities-provider-contracts">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">Formulario de importación de actividades</h4>
                     <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body">
                     <p>El documento a importar debe estar en formato excel (.xlsx o xls) y debe tener la siguiente estructura conservando el orden y el encabezado como primera fila del archivo: </p>

                  <a href="/storage/maintenance/documents_public/Modelo importacion de actividades.xlsx" target="_blank">Descargar formato de importación de actividades.</a>

                     <div class="row mt-3" style="margin: auto;">
                        <div class="table-responsive">
                           <table class="table table-responsive table-bordered">
                              <thead>
                                 <tr>
                                    <th>Item</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Sistema</th>
                                    <th>Unidad de medida</th>
                                    <th>Cantidad</th>
                                    <th>Valor unitario</th>
                                    <th>Iva</th>
                                    <th>Valor total</th>
                                 </tr>
                              </thead>
                           </table>
                        </div>
                     </div>
                     <hr />
                     <!-- File Import Field -->
                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="file_import" >{{ 'Seleccione el archivo de importación de información' | trans }}:</label>
                        <div class="col-md-9">
                           <input type="file" @change="inputFile($event, 'file_import')" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" id="file_import" :class="{'is-invalid':dataErrors.file_import }" required>                                       
                        </div>
                        <br><br>
                           <h5>Recuerde que los datos que se van a importar podrá consultarlos de primero para su verificación, sin embargo, al actualizar la página podrá consultar los registros en orden ascendente.</h5>
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
      <!-- end #modal-form-import-spare-parts-provider-contracts -->
   </div>
</template>

<script lang="ts">
   import { Component, Vue } from "vue-property-decorator";

   import axios from "axios";
   import {jwtDecode} from 'jwt-decode';

   import { Locale } from "v-calendar";


   const locale = new Locale();

   /**
   * Componente para agregar activos tic a la mesa de ayuda
   *
   * @author Andres Stiven Pinzón G. - Ago. 11 - 2021
   * @version 1.0.0
   */
   @Component
   export default class AssetsCreate extends Vue {
      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      /**
       * Constructor de la clase
       *
       * @author Andres Stiven Pinzón G. - Ago. 11 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         this.dataErrors = {};

         this.lang = (this.$parent as any).lang;
      }

      /**
         * Evento de asignacion de archivo
         *
       * @author Andres Stiven Pinzón G. - Ago. 11 - 2021
         * @version 1.0.0
         *
         * @param event datos de evento
         * @param fieldName nombre de campo
         */
        public inputFile(event, fieldName: string): void {
            this.dataForm[fieldName] = event.target.files[0];
        }

      /**
       * Limpia los datos del fomulario
       *
       * @author Andres Stiven Pinzón G. - Ago. 11 - 2021
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del dataform
         this.dataForm = {};
         // Limpia errores
         this.dataErrors = {};
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
      }

      /**
       * Cargar los datos en modo edición
       *
       * @author Andres Stiven Pinzón G. - Ago. 11 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadAssets(dataElement: number): void {
         this.dataForm = {contract_id: dataElement};
         $('#modal-form-import-activities-provider-contracts').modal('show');
      }

      /**
       * Crea el formulario de datos para guardar
       *
       * @author Andres Stiven Pinzón G. - Ago. 11 - 2021
       * @version 1.0.0
       */
      public makeFormData(): FormData {
         let formData = new FormData();

         // Recorre los datos del formulario
         for (const key in this.dataForm) {
            if (this.dataForm.hasOwnProperty(key)) {
               const data = this.dataForm[key];
               // Valida si no es un objeto y si es un archivo
               if ( typeof data != 'object' || data instanceof File || data instanceof Date || data instanceof Array) {
                  // Valida si es un arreglo
                  if (Array.isArray(data)) {
                     data.forEach((element) => {
                        if (typeof element == 'object') {
                           formData.append(`${key}[]`,JSON.stringify(element));
                        } else {
                           formData.append(`${key}[]`, element);
                        }
                     });
                  } else if (data instanceof Date) {
                     formData.append(key,  locale.format(data, "YYYY-MM-DD hh:mm:ss"));
                  } else {
                     formData.append(key, data);
                  }
               }
            }
         }
         return formData;
      }


      /**
       * Guarda la informacion en base de datos
       *
       * @author Andres Stiven Pinzón G. - Ago. 11 - 2021
       * @version 1.0.0
       */
      public store(): void {

         this.$swal({
				title: this.lang.get('trans.loading_save'),
				allowOutsideClick: false,
				onBeforeOpen: () => {
               (this.$swal as any).showLoading();
				},
			});

         // Envia peticion de guardado de datos
         axios.post("import-acti-provider-cont/"+this.dataForm.contract_id, this.makeFormData(), { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {

            res.data.data = jwtDecode(res.data.data);

            // Recorre la lista de elementos guardados en la tabla de importaciones de contrato de proveedores
            (res.data.data.data).forEach((element: any) => {
               // Agrega elemento nuevo a la lista
               (this.$parent as any).dataList.unshift(element);   
            });
         
            (this.$swal as any).close();

            // Cierra fomrulario modal
            $(`#modal-form-import-activities-provider-contracts`).modal('toggle');

            // Limpia datos ingresados
            this.clearDataForm();
            // Emite notificacion de almacenamiento de datos
            (this.$parent as any)._pushNotification(res.data.message);
         })
         .catch((err) => {
            console.log(err.response);
            (this.$swal as any).close();
            // Issues data storage notification
            (this.$parent as any)._pushNotification(err.response.data.message, false, 'Error');
            // Validate if there are errors associated with the form
            if (err.response.data.errors) {
               this.dataErrors = err.response.data.errors;
            }
         });
      }
   }
</script>
