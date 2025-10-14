<template>




    <div class="panel-body">
        <div class="row">
        <h5><strong>Quienes han leido la correspondencia</strong></h5>
            <table class="table-bordered text-center default" style="width:100%; table-layout: fixed; margin-bottom: 30px;">
                <tr>
                    <td>Usuario</td>
                    <td>Fecha</td>
                </tr>
                <tr v-for="data in this.usersLeidos">
                    <td>{{ data.nombre }}</td>
                    <td>
                        <ul v-for="listFecha in data.fecha">
                            <li>{{ listFecha }}</li>
                        </ul>
                    </td>
                </tr>
            </table>


            <h5><strong>Anotaciones de la correspondencia</strong></h5>
            <table class="table-bordered text-center default" style="width:100%; table-layout: fixed;">
                <tr>
                    <td>Fecha</td>
                    <td>Usuario</td>
                    <td>Anotación</td>
                </tr>
                <tr v-for="data in this.annotations">
                    <td>{{ data.cf_created }}</td>
                    <td>{{ data.nombre_usuario }}</td>
                    <td style="text-align: start; padding: 5px;"><label v-html="data.anotacion"></label></td>
                </tr>
            </table>
        </div>
    </div>

</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

    import axios from "axios";
    import { jwtDecode } from 'jwt-decode';

    import utility from '../../utility';


@Component
export default class AnnotationAndRead extends Vue {

    @Prop({ type: String })
    public leido: any;

    @Prop({ type: String, required: true })
    public routeAnnotation: any;

    @Prop({ type: [Number, String] })
    public idCorrespondence: any;

    @Prop({ type: String, required: true })
    public tableAnnotations: any;

    @Prop({ type: String, required: true })
    public campo: any;

    public annotations: any;

    public usersLeidos: any;


    constructor(){
        super();

        this.usersLeidos = {};
        this.annotations = {};
    }


    //Detecta cuando el prop cambia su valor y activa la funcion
    @Watch('leido')
    onLeidoChange(newVal: any, oldVal: any) {
        this.usersLeidos = {};
        this.annotations = {};

        this.buildTable();
    }

    //Construye las tablas de anotaciones y leidos
    buildTable(){
       this.usersLeidos = JSON.parse(this.leido);
       axios
        .get(
            `${this.routeAnnotation}/${this.idCorrespondence}-${this.tableAnnotations}-${this.campo}`
        )
        .then(res => {

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            const request = dataDecrypted["data"];

            this.annotations = request;
        })
        .catch(err => {

        });

    }

}
</script>
