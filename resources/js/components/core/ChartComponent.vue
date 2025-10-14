<template>
   <div>
     <!-- <apexchart :width="width" :type="typeChart" :options="options" :series="series"></apexchart>-->
      <div :width="width" id="container">

      </div> 
   </div>
</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import * as bootstrap from 'bootstrap';

   // import VueApexCharts from 'vue-apexcharts'

   import utility from '../../utility';

   import { Locale } from "v-calendar";

   import * as Highcharts from 'highcharts';

   import { jwtDecode } from 'jwt-decode';

   // Load the exporting module.
   import exporting from "highcharts/modules/exporting";
   // import * as ExportingCharts from 'highcharts/modules/exporting';

   exporting(Highcharts);

   const locale = new Locale();

   /**
   * Componente para agregar activos tic a la mesa de ayuda
   *
   * @author Carlos Moises Garcia T. - Oct. 13 - 2020
   * @version 1.0.0
   */
   @Component({
      components: {
         /**
          * Se importa de esta manera por error de referencia circular
          * 
          */
         // apexchart: () => import('vue-apexcharts')
         // highcharts: () => import('highcharts')
      }
   })
   export default class ChartComponent extends Vue {

      /**
       * Identificador de objeto
       */
      @Prop({ type: String, default: 'column' })
      public typeChart: string;

      /**
       * Nombre de la entidad a obtener
       */
      @Prop({ type: String, required: true })
      public nameResource: string;

      
      /**
       * Lista con los nombres de los campos de visualizacion de datos
       *
       */
      @Prop({ type: Array, required: true})
      public nameLabelsDisplay: Array<string>;

      /**
       * Nombre de la entidad a obtener
       */
      @Prop()
      public customData: any;
      


      /**
       * Titulo del grafico
       */
      @Prop({ type: String })
      public title: string;

      /**
       * Titulo del grafico
       */
      @Prop({ type: String, default: null })
      public subTitle: string;

      /**
       * Nombre de valor visualizacion
       */
      @Prop({ type: String, default: 'name'})
      public reduceLabel: string;

      /**
       * Ancho del grafico
       */
      @Prop({ type: String, default: '100%' })
      public width: String;
   
      /**
       * Ancho del grafico
       */
      @Prop({ type: String})
      public titleYAxis: String;


      /**
       * Propiedades del eje y
       */
      @Prop({ type: Object, default: () => ({})})
      public yAxis: Object;

      @Prop({ type: Object, default: () => ({})})
      public plotOptions: Object;

      /**
       * Propiedades de la leyenda del grafico
       */
      @Prop({ type: Object, default: () => ({})})
      public legend: Object;
      
      /**
       * Lista de opciones
       */
      public optionsList: any;

      public options: any;

      public series: any;

      public langCharts: any;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Oct. 13 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();

         this.optionsList = [];

         this.options = {};

         this.series= [];

         // Cambia el idioma de las opciones de grafico
         this.langCharts = {
            viewFullscreen: "Ver en pantalla completa",
            printChart: "Imprimir gráfico",
            downloadPNG: "Descagar PNG",
            downloadJPEG: "Descagar JPEG",
            downloadCSV: "Descarga CSV",       
            downloadPDF: "Descagar PDF",
            downloadSVG: "Descagar SVG",
            exitFullscreen: "Salir de la pantalla completa"
         };

         
         if (this.customData) {
            
            let data = utility.clone(this.customData);

            let dataPie = [];

            data.forEach((itemData, index) => {
               // Asigna el dato del eje x
               let dataX = itemData[this.nameLabelsDisplay[0]];

               let dataY = itemData[this.nameLabelsDisplay[1]];

               // Valida si la grafica es tipo pastel
               if (this.typeChart == "pie") {

                  dataPie.push(
                     {	
                        name: dataX,
                        y: dataY,
                     }
                  );
               }

               else if (this.typeChart == "column") {

                  // Asigna los valores de eje x  y el eje y
                  this.series[index] = {
                     type: this.typeChart,
                     name: dataX,
                     data: [[
                        dataX, dataY
                     ]]
                  };
               }
            });

            this._generateChart();
               
         } else {
             this._getDataOptions();
         }
      }

       /**
       * Se ejecuta cuando el componente ha sido momntado
       */
      mounted() {
         // Carga la lista de elementos
         // this._getDataOptions();
         // this._getDataOptions();
      }

       /**
       * Obtiene la lista de opciones
       *
       * @author Carlos Moises Garcia T. - Oct. 26 - 2020
       * @version 1.0.0
       */
      private _getDataOptions(): void {
         // Envia peticion de obtener todos los datos del recurso
         axios.get(this.nameResource)
         .then((item)=>{
            let dataPayload = jwtDecode(item.data.data);

            const dataDecrypted = Object.assign({data:[]}, dataPayload);

            let data = dataDecrypted?.data;

            let dataPie = [];

            data.forEach((itemData, index) => {

               // Asigna el dato del eje x
               let dataX = itemData[this.nameLabelsDisplay[0]];

               let dataY = itemData[this.nameLabelsDisplay[1]];

               // Valida si la grafica es tipo pastel
               if (this.typeChart == "pie") {

                  dataPie.push({	
                     name: dataX,
                     y: dataY,
                  });
               }
               // Valida que el tipo de grafica es tipo barras
               else if (this.typeChart == "column") {

                  // Asigna los valores de eje x  y el eje y
                  this.series[index] = {
                     type: this.typeChart,
                     name: dataX,
                     data: [[
                        dataX, dataY
                     ]]
                  };
               }
            });
               // Valida si la grafica es tipo pastel
            if (this.typeChart == "pie") {

               // Asigna los valores
               this.series= [{
                  name: 'Prueba',
                  data: dataPie
               }];

               this.optionsList = {
                  pie: {
                     allowPointSelect: true,
                     cursor: 'pointer',
                     showInLegend: true
                  }
               };

            }

            else if (this.typeChart == "column") {

               this.optionsList = {
                  column: {
                     stacking: 'normal',
                     pointPadding: 0.2,
                     borderWidth: 0
                  }
               }; 
            }

            
            this._generateChart();
         })
         .catch((err) => {
            console.log('Error al obtener la lista.');
         });
      }

      private _generateChart(): void {

         if (this.series != 'undefined' ) {

         // Genera el grafico
         Highcharts.chart('container', {
            credits: {
               enabled: false
            },
            lang: this.langCharts,
            chart: {
               type: this.typeChart
            },
            title: {
               text: this.title
            },
            subtitle: {
               text: this.subTitle
            },
            xAxis: {
               type: "category",
            },
            yAxis: this.yAxis,
            legend: this.legend,
            tooltip: {
               headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
               pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                     '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
               footerFormat: '</table>',
               shared: true,
               useHTML: true
            },
            plotOptions: this.optionsList,
            series: this.series,
         });
         }
      }
   }
</script>
