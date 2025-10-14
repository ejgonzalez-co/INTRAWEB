<template>
  <div v-if="dataForm.indicator_type">
    <!-- <apexchart :width="width" :type="typeChart" :options="options" :series="series"></apexchart> -->
    <div :width="width" id="container"></div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import * as bootstrap from "bootstrap";

// import VueApexCharts from 'vue-apexcharts'

import utility from "../../utility";

import { Locale } from "v-calendar";

import * as Highcharts from "highcharts";

import { jwtDecode } from 'jwt-decode';

// Load the exporting module.
import exporting from "highcharts/modules/exporting";
// import * as ExportingCharts from 'highcharts/modules/exporting';
import * as moment from "moment";

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
    // apexchart: () => import("vue-apexcharts"),
    // highcharts: () => import('highcharts')
  },
})
export default class ChartComponentAnalytics extends Vue {
  /**
   * Contiene el nombre del tipo de grafico a mostrar
   */
  @Prop({ type: String, default: "column" })
  public typeChart: string;

  /**
   * Lista con los nombres de los campos de visualizacion de datos
   *
   */
  @Prop({ type: Array, required: true })
  public nameLabelsDisplay: Array<string>;

  /**
   * Lista con los nombres de los campos campos que contienen limites y los labels de estos(Aplica solo para el tipo de grafico lline y areaspline)
   *
   */
  @Prop({ type: Array })
  public nameLimits: Array<string>;

  /**
   * Titulo del grafico
   */
  @Prop({ type: String })
  public title: string;

  /**
   * Subtitulo del grafico
   */
  @Prop({ type: String, default: null })
  public subTitle: string;

  /**
   * Subtitulo del grafico
   */

  public char: any;

  /**
   * Ancho del grafico
   */
  @Prop({ type: String, default: null })
  public width: String;

  /**
   * Titulo del eje Y
   */
  @Prop({ type: String })
  public titleYAxis: String;

  /**
   * Numero minimo eje Y, sirve para el typeChart line, column y areaspline
   */
  @Prop({ type: Number, default: null })
  public minYAxis: number;

  /**
   * Numero maximo eje Y, sirve para el typeChart line, column y areaspline
   */
  @Prop({ type: Number, default: null })
  public maxYAxis: number;

  /**
   * Define el margin left del grafico
   */
  @Prop({ type: Number, default: null })
  public marginLeftChart: number;

  /**
   * Define la posicion en el eje x del label del plotLine(los valores deben ser negativos)
   */
  @Prop({ type: Number, default: null })
  public labelPlotLinePositionX: number;

  /**
   * Define el marginleft del grafico
   */
  @Prop({ type: Boolean, default: false })
  public labelsPlotLines: boolean;

  /**
   * Valida si va a generar el grafico por rango de fecha
   */
  @Prop({ type: String, default: null })
  public mode: string;

  /**
   * Objeto que contiene el campo a filtrar
   */
  @Prop({ type: Object })
  public value: any;

  /**
   * Nombre del campo
   */
  @Prop({ required: true })
  public nameField: any;

  /**
   * Habilita label en los puntos de registro(propiedad del grafico tipo line)
   */
  @Prop({ type: Boolean, default: false })
  public showDataLabels: boolean;

  /**
   * Objeto con las propiedades predeterminadas de la libreria propiedades del eje y
   */
  @Prop({ type: Object, default: () => ({}) })
  public yAxis: Object;

  /**
   * Objeto con las propiedades predeterminadas de la libreria del eje x
   */
  @Prop({ type: Object, default: () => ({}) })
  public xAxis: Object;

  @Prop({ type: Object, default: () => ({}) })
  public plotOptions: Object;

  /**
   * Propiedades de la leyenda del grafico
   */
  @Prop({ type: Object, default: () => ({}) })
  public legend: Object;

  /**
   * Lista de opciones
   */
  public optionsList: any;

  public options: any;

  public series: any;

  public xAxisList: any;

  public yAxisList: any;

  public legendList: any;

  public langCharts: any;

  public chartList: any;

  public dataList: any;

  public dataForm: any;

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

    this.series = [];

    this.xAxisList = [];

    this.yAxisList = [];

    this.legendList = [];

    this.chartList = [];

    this.dataForm = [];

    // Cambia el idioma de las opciones de grafico
    this.langCharts = {
      viewFullscreen: "Ver en pantalla completa",
      printChart: "Imprimir gráfico",
      downloadPNG: "Descagar PNG",
      downloadJPEG: "Descagar JPEG",
      downloadCSV: "Descarga CSV",
      downloadPDF: "Descagar PDF",
      downloadSVG: "Descagar SVG",
      exitFullscreen: "Salir de la pantalla completa",
    };

    // valida si el grafico se generara sin rango de fecha
    if (this.mode == null) {
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

    axios
      .post("verify-data-analytics", this.dataForm)
      .then((item) => {
        let data;

        
        let dataPayload = item.data.data ? jwtDecode(item.data.data) : null;
        const dataDecrypted = Object.assign({}, dataPayload);
            
        data = dataDecrypted["data"];

        if (item.data.message =="El grafico no se pudo realizar, por favor validar la información diligenciada.") {
          return this.$swal({
            title:
              "El grafico no se pudo realizar, por favor validar la información diligenciada.",
            icon: "info",
          });
        }

        if (data == undefined) {
          this.$swal({
            title: "No existen registros.",
            text: "No existen registros con esos filtros.",
            icon: "warning",
          });
        }

        this.assignList(data);
        // Asigna el dato del eje x
        let dataX = null;

        dataX = this.nameLabelsDisplay[0];

        let dataY = null;
        dataY = this.nameLabelsDisplay[1];

        this.xAxisList[0] = {
          type: "category",
        };
        // Valida que el tipo de grafica es tipo barras
        if (this.typeChart == "column") {
          this.assignColumn(data, dataX, dataY);
        }
      })
      .catch((err) => {
        console.log("Error al obtener la lista.", err);
      });
  }
  private assignList(data): void {
    this.dataList = data;
  }

  /**
   * Asigna los valores a los objetos o arrays que ayudan a generar el grafico de columnas
   *
   * @author Andres Stiven Pinzon G. - Abr. 19 - 2022
   * @version 1.0.0
   */
  private assignColumn(data, dataX, dataY): void {
    console.log("en linea 306", this.dataForm);
    data.forEach((itemData, index) => {
      Highcharts.setOptions({
        colors: ["#003863"],
      });

      // Asigna los valores de eje x  y el eje y
      this.series[index] = {
        type: this.typeChart,
        name: itemData[dataX],
        data: [[itemData[dataX], itemData[dataY]]],
      };
    });

    this.yAxisList = [
      {
        title: {
          text: this.titleYAxis,
        },
        min: this.minYAxis,
        max: this.maxYAxis,
      },
    ];

    this.xAxisList[0]["categories"] = 0;

    if (this.dataForm.indicator_type == "Ejecución de los contratos") {
      this.optionsList = {
        column: {
          stacking: "normal",
          pointPadding: 0.2,
          borderWidth: 0,
          dataLabels: {
            enabled: true,
            format: "{point.y:.1f}%",
          },
        },
      };
    } else {
      this.optionsList = {
        column: {
          stacking: "normal",
          pointPadding: 0.2,
          borderWidth: 0,
          dataLabels: {
            enabled: true,
          },
        },
      };
    }
    // muestra los puntos de cada columna si esta en true de lo contrario no
    this.legendList = {
      enabled: false,
    };

    this._generateChart();
  }
  private _generateChart(): void {
    // Contiene las propiedades de la propiedad chart
    this.chartList = {
      type: this.typeChart,
      width: this.width,
      marginLeft: this.marginLeftChart,
    };

    if (this.series != "undefined") {
      let titulo = "";
      let subtitulo = "";
      let tituloY = "";
      if (this.dataForm.indicator_type == "Ejecución de los contratos") {
        titulo = `Ejecución de los contratos desde ${this.dataForm.init_date} hasta ${this.dataForm.final_date}`;
        tituloY = "Porcentaje de ejecución de los contratos";
      } else if (this.dataForm.indicator_type == "Rendimiento de combustible") {
        let initDate = this.formatDate(this.dataForm.init_date);
        let finalDate = this.formatDate(this.dataForm.final_date);
        titulo = `Rendimiento de combustible desde ${initDate} hasta ${finalDate}`;
        subtitulo = this.dataForm.type_promedio;
        tituloY = `Total rendimiento de combustible (gln)`;
      } else if (this.dataForm.indicator_type == "Consumo combustible") {
        let initDate = this.formatDate(this.dataForm.init_date);
        let finalDate = this.formatDate(this.dataForm.final_date);
        titulo = `Consumo de combustible desde ${initDate} hasta ${finalDate}`;
        subtitulo = "";
        tituloY = "Total consumo combustible (gln)";
      } else if (this.dataForm.indicator_type == "Recorrido por tanqueo") {
        let initDate = this.formatDate(this.dataForm.init_date);
        let finalDate = this.formatDate(this.dataForm.final_date);
        titulo = `Recorrido por tanqueo desde ${initDate} hasta ${finalDate}`;
        subtitulo = this.dataForm.type_promedio;
        tituloY = "Total suma de variación (gln)";
      } else {
        let initDate = this.formatDate(this.dataForm.init_date);
        let finalDate = this.formatDate(this.dataForm.final_date);
        titulo = `Activos desde ${initDate} hasta ${finalDate}`;
        subtitulo = "Cantidad de activos";
        tituloY = "Total cantidad de activos";
      }

      // Genera el grafico
      this.char = Highcharts.chart("container", {
        credits: {
          enabled: false,
        },
        lang: this.langCharts,
        chart: this.chartList,
        title: {
          text: titulo,
        },
        subtitle: {
          text: subtitulo,
        },
        xAxis: this.xAxisList,
        yAxis: {
          title: {
            text: tituloY,
          },
        },
        legend: this.legendList,
        tooltip: {
          headerFormat:
            '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat:
            '<tr><td style="color:red;padding:0">Valor: </td>' +
            '<td style="padding:0"><b>{point.y} </b></td></tr>',
          footerFormat: "</table>",
          shared: true,
          useHTML: true,
        },
        plotOptions: this.optionsList,

        exporting: {
          enabled: true,
        },

        series: this.series,
      });
    }
    this.series = [];
    this.xAxisList = [];
    this.yAxisList = [];
  }

  public formatDate(date: string): string {
    return moment(date).format("YYYY-MM-DD");
  }
}
</script>