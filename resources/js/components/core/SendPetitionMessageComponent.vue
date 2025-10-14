<template>
    <div>

    </div>
</template>
<script lang="ts">
 import { Component, Prop, Vue } from "vue-property-decorator";

   import axios from "axios";
   import { jwtDecode } from 'jwt-decode';
   import type { AxiosRequestConfig } from 'axios';

    /**
   * Componente para enviar peticiones al servidor con swal alert
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
   @Component
   export default class SendPetitionMessageComponent extends Vue {

    //titulo de la ventaana sweet alert
    @Prop({type: String, required: true })
     public titleAlert: string;

    //texto de la ventaana sweet alert
    @Prop({type: String, required: true })
     public textAlert: string;

    //Alerta de cargando datos
    @Prop({type: Boolean, default: false })
    public loadingAlert: boolean;

    //Valida si se necesuita agregar un elemento a la lista
    @Prop({type: Boolean, default: true })
    public assignElement: boolean;

    //Valida si se requiere exportar la data
    @Prop({type: Boolean, default: false })
    public downloadExportData: boolean;

    //Valida si se requiere exportar la data
    @Prop({type: String, default: 'get' })
    public requestType: string;

    //Nombre del archivo exportados
    @Prop({type: String, required: false, default: 'datos_exportados' })
     public nameExport: string;

    //extencion del archivo a exportar
    @Prop({type: String, required: false, default: 'pdf' })
     public typeFyleExport: string;

    //texto de la ventaana de cargando en el sweetalert
    @Prop({type: String, required: false, default: 'Cargando...' })
     public textLoading: string;

     //ruta de la peticion al servidor
    @Prop({type: String, required: true })
     public resourceAlert: string;


      //id del objeto
    @Prop({type: Number })
     public objectId: number;


    //titulo de la ventaana de cuando se confirma el sweeralert
    @Prop({type: String, required: true })
     public titleConfirmationSwal: string;

    //texto de la ventaana de cuando se confirma el sweeralert
    @Prop({type: String, required: true })
     public textConfirmationSwal: string;


      /**
       * Constructor de la clase
       *
       * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();

      }

        /**
       * envia los datos del al servidor y confirma con un sweeralert2
       *
       * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
       * @version 1.0.0
       */
        public getPetition(): void {
                this.$swal({
                title: this.titleAlert,
                text: this.textAlert,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText:'No'
            }).then((result) => {
            if (result.value) {

                if (this.loadingAlert) {
                    (this.$parent as any)['showLoadingGif'](this.textLoading);
                }

                    const params={
                        id: this.objectId
                    }
                    //enviar peticion al servidor
                    axios({
                        method: this.requestType,
                        url: `${this.resourceAlert}`,
                        ...(this.requestType === 'post' ? { responseType: 'blob' } : {})
                    }).then(res=>{

                        if (this.loadingAlert) {
                            (this.$swal as any).close(); 
                        }

                        if (this.assignElement) {
                            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                            const dataDecrypted = Object.assign({data:{id:null}}, dataPayload);

                            const request = dataDecrypted?.data;

                            // Actualiza elemento modificado en la lista
                            Object.assign((this.$parent as any)._findElementById

                            (request.id, false), request);
                        }

                        if (this.downloadExportData) {
                            (this.$parent as any)['downloadFile'](res.data, this.nameExport, this.typeFyleExport);
                        }
                        

                        this.$swal({
                        title: this.titleConfirmationSwal,
                        text: this.textConfirmationSwal,
                        icon:'success'
                    })
                    })
                    .catch(error=>{
                        this.$swal({
                        title: 'Error al enviar',
                        text:'Error al enviar la solicitud' + error,
                        icon:'error'
                        })
                    })

                }
            })

    }


     /**
     * Descarga un archivo codificado
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param file datos de archivo a construir
     * @param filename nombre de archivo
     */
    public downloadFile(
        file: string,
        filename: string
    ): void {
        console.log(filename);
        // Crea el archivo tipo blob
        let newBlob = new Blob([file]);

        // Para otros navegadores:
        // Crea un enlace que apunta al ObjectURL que contiene el blob.
        const data = window.URL.createObjectURL(newBlob);
        let link = document.createElement("a");
        link.href = data;
        link.download = `${filename}`;
        link.click();
        setTimeout(() => {
            // Para Firefox es necesario retrasar la revocación de ObjectURL
            window.URL.revokeObjectURL(data);
        }, 100);
    }

    /**
     * Aca se exportan los datos
     *
     * @author Seven Soluciones Informáticas. - Diciembre. 25 - 2023
     * @version 1.0.0
     */
    public exportFormatoNecesidadesGoogle(datos) {
        this.$swal({
            title: "Exportando datos",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });
        axios
            .post(
                `${this.resourceAlert}`,
                {
                    datos
                },
                { responseType: "blob" }
            )
            .then(res => {
                // Descagar el archivo generado
                this.downloadFile(res.data, res.headers['content-disposition'].split('filename=')[1].split('"')[1]);
                (this.$swal as any).close();
            })
            .catch(err => {
                console.error(err);
                (this.$swal as any).close();
            }
            );
    }

}
</script>
