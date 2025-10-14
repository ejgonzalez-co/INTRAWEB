<template>
    <div>
        <cropper
            classname="cropper"
            :src="imgPreview"
            :stencil-props="{
                previewClassname: 'preview',
                minAspectRatio: 8/8,
                maxAspectRatio: 10/10
            }"
            stencil-component="circle-stencil"
            @change="cropImage"
            ref="cropper">
        </cropper>
        <input type="file"  @change="_uploadImage($event)" accept="image/*">
    </div>
</template>

<script lang="ts">

    import { Prop, Component, Watch, Vue } from "vue-property-decorator";
    import axios from "axios";

    /**
     * Componente de subida de imagen de perfil
     *
     * @author Jhoan Sebastian Chilito S. - Ago. 11 - 2020
     */
    @Component
    export default class CropperImageComponent extends Vue {

        /**
         * Url de la imagen a mostrar
         */
        @Prop({ type: String, required: true})
        public img: any;

        /**
         * Imagen de perfil para subir
         */
        public imgFile: File;

        /**
         * Previsualizacion de imagen
         */
        public imgPreview: any;

        /**
         * Nombre de campo de input de actuliazacion
         */
        @Prop({ type: String})
        public nameField: string;

        /**
         * Recurso donde se va a subir la imagen
         */
        @Prop({ type: String})
        public resourceUpload: string;

        /**
         * Objeto de valor de actuaizacion
         */
        @Prop()
        public value: any;

        /**
         * Valida si es ingresa la imagen de perfil por primera vez
         */
        private _isFirst: boolean;

        /**
         * Constructor de la clases
         *
         * @author Jhoan Sebastian Chilito S. - Ago. 11 - 2020
         */
        constructor() {
            super();
            this.imgFile = null;
            this._isFirst = true;
            this.imgPreview = '';
        }

        /**
         * Organiza la imagen seleccionada cada vez que detecta un cambio en el cropper
         *
         * @author Jhoan Sebastian Chilito S. - Ago. 11 - 2020
         *
         * @param coordinates datos de imagen recortada
         * @param canvas datos de imagen recortada
         */
        public cropImage({ coordinates, canvas }): void {
            // Valida si detecta el cambio por primera vez
            if (this._isFirst === false) {
                this.value[this.nameField] = this._convertBase64ToFile(canvas);
            } else {
                this._isFirst = false;
            }
		}

        /**
         * Convierte una imagen de base 64 a tipo file
         *
         * @author Jhoan Sebastian Chilito S. - Ago. 11 - 2020
         *
         * @param dataCanvas  datos de imagen recortada
         */
        private _convertBase64ToFile(dataCanvas: HTMLCanvasElement): File {
            let arr = dataCanvas.toDataURL('image/png').split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);

            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }

            return new File([u8arr], 'image', {type:mime})
        }

        /**
         * Evento de seleccion de archivo
         *
         * @author Jhoan Sebastian Chilito S. - Ago. 01 - 2020
         *
         * @param event evento de subida de archivos de input file
         */
        private _uploadImage(event): void {
            // Reference to the DOM input element
            const input = event.target;
            // Ensure that you have a file before attempting to read it
            if (input.files && input.files[0]) {
                // create a new FileReader to read this image and convert to base64 format
                const reader = new FileReader();
                // Define a callback function to run, when FileReader finishes its job
                reader.onload = (e) => {
                // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
                // Read image as base64 and set to imageData
                    this.imgPreview = e.target.result;
                };
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsDataURL(input.files[0]);
            }
        }

        /**
         * Detecta la actualizacion de la variable img en cada cambio
         *
         * @author Jhoan Sebastian Chilito S. - Ago. 13 - 2020
         */
        @Watch("img")
        public watchImage(): void {
            this.imgPreview = this.img;
        }

    }
</script>
