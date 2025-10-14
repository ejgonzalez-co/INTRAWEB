<template>
   <div>
      <wysiwyg
         :id="nameField"
         v-model='value[nameField]'
         :options="editorConfig"
         placeholder='Ingrese un texto...'
      >
      </wysiwyg>
   </div>
</template>
<script lang="ts">

   import { Component, Prop, Vue } from "vue-property-decorator";

   /**
     * Componente de campo de editor de texto
     *
     * @author Carlos Moises Garcia T. - Mar. 18 - 2021
     * @version 1.0.0
     */
   @Component
   export default class TextAreaEditorComponent extends Vue {

      /**
       * Nombre del campo
       */
      @Prop({type: String, required: true })
      public nameField: string;

     /**
      * Valida si el campo es requerido
      */
      @Prop({ type: Boolean, default: true})
      public isRequired: boolean;


      /**
       * Valor del campo
       */
      @Prop({type: Object})
      public value: any;

      /**
       * Modulos que se ocultan
       */
      @Prop({type: Object})
      public hideModules: any;

      public editorConfig: any;
      

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Mar. 18 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();

         this.editorConfig = {
            // { [module]: boolean (set true to hide) }
            hideModules: this.hideModules,

            // you can override icons too, if desired
            // just keep in mind that you may need custom styles in your application to get everything to align
            // iconOverrides: { "bold": "<i class='your-custom-icon'></i>" },

            // if the image option is not set, images are inserted as base64
            image: {
               uploadURL: "/api/myEndpoint",
               dropzoneOptions: {}
            },
            placeholder : 'Ingrese un texto....',
            // limit content height if you wish. If not set, editor size will grow with content.
            maxHeight: "500px",

            // set to 'true' this will insert plain text without styling when you paste something into the editor.
            forcePlainTextOnPaste: true,

            // specify editor locale, if you don't specify this, the editor will default to english.
            locale: 'es'
         }
      }
   }
</script>