<template>
    <div :class="classContainer">
        <!-- Tabla de datos agregados desde el formulario -->
        <div class="row" v-if="dataList && dataList.length > 0">
            <div class="table-responsive">
                <table :class="['table', classTable]">
                    <thead>
                        <tr>
                            <!-- Obtiene los nombre de columna que se pueden visualizar -->
                            <th style="background-color: rgb(172, 183, 172)"
                                v-for="(option, key) in nameColumnOptions"
                                :key="key"
                            >
                                {{ option.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Visualiza todos los elementos ingresados de la lista -->
                        <tr v-for="(row, key) in getDataColumValue" :key="key">
                            <td v-for="(col, k) in nameColumnOptions" :key="k">
                                <select required v-if="col.name == 'status'"
                                    class="form-control"
                                    v-model="$parent['dataForm']['evaluation_criteria'][key]['status']"
                                >
                                    <option value="Conforme">Conforme</option>
                                    <option value="No conforme"
                                        >No conforme</option
                                    >
                                </select>
                                <small v-if="col.name == 'status'">Seleccione el estado del criterio.</small>
                                <textarea v-if="col.name == 'observations'" v-model="$parent['dataForm']['evaluation_criteria'][key]['observations']" class="form-control" required></textarea>
                                <p v-if="col.name == 'criteria_name'"> {{ row[col.name] }}</p>
                                <small v-else-if="col.name == 'observations'">Seleccione la observación de la evaluación.</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Prop, Component, Vue, Watch } from "vue-property-decorator";

import utility from "../../utility";

import axios from "axios";

/**
 * Componente de formulario con lista dinamica
 *
 * @author Jhoan Sebastian Chilito S. - Ene. 06 - 2021
 * @version 1.0.0
 */
@Component
export default class DynamicListSelectComponent extends Vue {
    /**
     * Clase css para el color del boton de agregar a la lista
     */
    @Prop({ default: "btn-primary" })
    public classButtonAdd: String;

    /**
     * Clase css para el color del boton de agregar a la lista
     */
    @Prop({ default: "btn-default" })
    public classButtonDropElement: String;

    /**
     * Clase css para contenedor del componente
     */
    @Prop({ default: "p-10" })
    public classContainer: String;

    /**
     * Clase css para el contenido de la tabla
     */
    @Prop({ default: "table-hover text-inverse" })
    public classTable: String;

    /**
     * Texto del boton de agregar a la lista
     */
    @Prop({ default: "Agregar a la lista" })
    public labelButtonAdd: String;

    /**
     * Texto del boton de agregar a la lista
     */
    @Prop({ default: "Acciones" })
    public labelColActions: String;

    /**
     * Lista de datos agregados
     */
    @Prop({ required: true })
    public dataList: any;

    /**
     * Configuracion de nombres de lista dinamica
     *
     * Debe tener las siguientes propiedades:
     *  - label: Nombre de visualizacion de la propiedad (Nombre header tabla)
     *  - name: Nombre de la propiedad del objeto
     *  - isShow: Valida si se debe mostrar como columna de la tabla
     *  - refList: Nombre de select de referencia (Solamente para las listas (Select html))
     */
    @Prop({ type: Array, default: () => [] })
    public dataListOptions: any;

    /**
     * Valida si se debe recomputarizar
     */
    @Prop({ type: Boolean, default: false })
    public isRecompute: boolean;

    /**
     * Valida si se debe eliminar elemento de la lista
     */
    @Prop({ type: Boolean, default: true })
    public isRemove: boolean;

    /**
     * Campos del formulario
     */
    public dataForm: any;

    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;

    /**
     * Numero de peticiones pendientes
     */
    public numberOfAjaxCallPending: number;

    /**
     * Valida si no hay peticiones http
     */
    public isCharge: boolean;

    /**
     * Tiempo de espera para organizar
     */
    @Prop({ type: Number, default: 100 })
    public timeout: number;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Feb. 04 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();

        this.dataForm = {};
        // Inicializa numero de peticiones pendientes
        this.numberOfAjaxCallPending = 0;
        // Inicializa si se ha cargado todas las peticiones
        this.isCharge = false;
        this.lang = (this.$parent as any).lang;

        // Detecta las peticiones pendientes
        this.detectRequestPending();
    }

    /**
     * Detecta las peticiones pendientes
     *
     * @author Jhoan Sebastian Chilito S. - Feb. 04 - 2021
     */
    public detectRequestPending(): void {
        // Add a request interceptor
        axios.interceptors.request.use(
            config => {
                this.numberOfAjaxCallPending++;
                return config;
            },
            error => {
                return Promise.reject(error);
            }
        );

        // Add a response interceptor
        axios.interceptors.response.use(
            response => {
                this.numberOfAjaxCallPending--;
                return response;
            },
            error => {
                this.numberOfAjaxCallPending--;
                return Promise.reject(error);
            }
        );
    }

    /**
     * Detecta la cantidad de peticiones pendientes
     *
     * @author Jhoan Sebastian Chilito S. - Feb. 04 - 2021
     */
    @Watch("numberOfAjaxCallPending")
    public changePendingRequest() {
        // Valida si no hay peticiones en espera
        if (this.numberOfAjaxCallPending == 0) {
            // Cambia el estado para velidar que no hay peticiones pendientes
            setTimeout(() => {
                this.isCharge = true;
            }, this.timeout);
        } else {
            // Faltan peticiones por realizarse
            this.isCharge = false;
        }
    }

    /**
     * Agrega los datos del formulario ingresados a la lista
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 08 - 2021
     * @version 1.0.0
     */
    public addToList(): void {
        let dataList1 = utility.clone(this.dataForm);

        // console.log(dataList1);
        // Agrega un nuevo elemento a la lista
        this.dataList.push(utility.clone(this.dataForm));
        // Limpia los campos del formulario
        // this.dataForm = {};

        this.recomputeDataColumns();
        this.addedToListEvent();
        this.$forceUpdate();
        this.clean();
    }

    /**
     *
     *
     * @author Jhoan Sebastian Chilito S. - Marz. 02 - 2021
     * @version 1.0.0
     */
    public addedToListEvent() {
        this.$emit("addedToListEvent", this.dataList);
    }

    /**
     * Elimina un elemento de la lista por la posicion
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 08 - 2021
     * @version 1.0.0
     *
     * @param index indice del elemento a eliminar
     */
    public dropElementList(index: number): void {
        this.$swal({
            icon: "error",
            title: this.lang.get("trans.are_you_sure_drop"),
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: this.lang.get("trans.drop"),
            cancelButtonText: this.lang.get("trans.cancel"),
            customClass: {
                confirmButton: "btn m-r-5 btn-danger",
                cancelButton: "btn btn-default"
            }
        }).then(res => {
            // Valida si la opcion selecionada es positiva
            if (res.value) {
                // Elimina el elemento
                Vue.delete(this.dataList, index);
                this.recomputeDataColumns();
                this.addedToListEvent();
                this.$forceUpdate();
            }
        });
    }

    /**
     * Obtiene la referencia del selector
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 21 - 2021
     * @version 1.0.0
     *
     * @param refName nombre de la referencia del selector
     */
    public getSelectRef(refName: string) {
        // Valida si es una referencia de un select normal
        if (this.$parent.$refs[refName]) {
            return this.$parent.$refs[refName];
        } else {
            // Recorre todos los componentes hijos
            for (let i = 0; i < this.$children.length; i++) {
                const childComponent = this.$children[i];
                // Valida que sea una referencia de un select de un componente hijo
                if (childComponent.$refs[refName]) {
                    return childComponent.$refs[refName];
                }
            }
        }
        return null;
    }

    /**
     * Elimina un elemento de la lista por la posicion
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 08 - 2021
     * @version 1.0.0
     *
     * @param index indice del elemento a eliminar
     */
    public moveDownElement(index: number): void {
        this.move(this.dataList, index, 1);
        this.$forceUpdate();
    }

    /**
     * Elimina un elemento de la lista por la posicion
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 08 - 2021
     * @version 1.0.0
     *
     * @param index indice del elemento a eliminar
     */
    public moveUpElement(index: number): void {
        this.move(this.dataList, index, -1);
        this.$forceUpdate();
    }

    /**
     * Mueve los elementos de un arreglo
     *
     * @param array lista a organizar
     * @param index ubicacion del elemento en la lista
     * @param delta variable de configuracion (1 = para bajar, -1 para subir) el elemento
     *
     * @see https://gist.github.com/albertein/4496103 documentacion de script
     */
    public move(array: any, index: number, delta: number): void {
        const newIndex = index + delta;
        if (newIndex < 0 || newIndex == array.length) return; // Already at the top or bottom.
        const indexes = [index, newIndex].sort((a, b) => a - b); // Sort the indixes (fixed)
        array.splice(indexes[0], 2, array[indexes[1]], array[indexes[0]]); // Replace from lowest index, two elements, reverting the order
        this.recomputeDataColumns();
    }

    /**
     * Recomputarizar de una propiedad computada de un componente por medio del nombre de la propiedad
     *
     * @author Jhoan Sebastian Chilito S. - Marz. 01 - 2021
     * @version 1.0.0
     */
    public recomputeDataColumns() {
        // Valida si se recomputarizar la propiedad computada
        if (this.isRecompute) {
            (this as any).$recompute("getDataColumValue");
        }
    }

    /**
     * Obtiene el valor de la columna de la tabla por fila
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 21 - 2021
     * @version 1.0.0
     */
    get getDataColumValue() {
        return utility.clone(this.dataList).map(data => {
            // console.log(data);
            this.nameColumnOptions.forEach(async colOption => {
                // Valida que sea una lista referida
                if (colOption.refList) {
                    await new Promise(resolve =>
                        setTimeout(resolve, 2500)
                    ).then(() => {
                        const selectRef = this.getSelectRef(colOption.refList);
                        // Valida si la referencia de la lista existe
                        if (selectRef) {
                            // Obtiene las opciones del select
                            const printerOpts = (selectRef as any).children;

                            if (colOption.selectItem) {
                                let datoColOption = data[colOption.name].map(
                                    Number
                                );
                                // datoColOption.map(Number);

                                // console.log(datoColOption);
                                if (typeof datoColOption == "object") {
                                    let options = [];
                                    // Recorre la lista de opiones
                                    for (
                                        let i = 0;
                                        i < printerOpts.length;
                                        i++
                                    ) {
                                        const option = printerOpts[i];
                                        // Valida que la opcion de la lista sea la seleccionada
                                        if (
                                            datoColOption.includes(
                                                Number(option.value)
                                            )
                                        ) {
                                            // Obtiene el nombre del valor seleccionado
                                            options.push(option.text);
                                        }
                                    }
                                    data[colOption.name] = options.join(", ");
                                    this.$forceUpdate();
                                }
                            } else {
                                // Recorre la lista de opiones
                                for (let i = 0; i < printerOpts.length; i++) {
                                    const option = printerOpts[i];
                                    // Valida que la opcion de la lista sea la seleccionada
                                    if (option.value == data[colOption.name]) {
                                        // Obtiene el nombre del valor seleccionado
                                        data[colOption.name] = option.text;
                                        this.$forceUpdate();
                                    }
                                }
                            }
                        } else {
                            // console.log('No existe la referencia de la la lista', colOption.label);
                        }
                    });
                }
                if (colOption.selectItem) {
                    if (typeof data[colOption.name] == "object") {
                        const printerOpts = data[colOption.name];
                        console.log(colOption);
                        let option = [];
                        // Recorre la lista de opiones
                        for (let i = 0; i < printerOpts.length; i++) {
                            option.push(printerOpts[i][colOption.selectItem]);
                        }
                        // console.log(option);
                        data[colOption.name] = option.join(", ");
                        this.$forceUpdate();
                    }
                }
            });
            return data;
        });
    }

    /**
     * Obtiene los nombre de columna que se puede visualizar
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 08 - 2021
     * @version 1.0.0
     */
    get nameColumnOptions(): Array<any> {
        this.$forceUpdate();
        return this.dataListOptions.filter(option => {
            return option.isShow;
        });
    }

    /**
     *
     *
     * @author Nicolas Ortiz. - Agosto. 18 - 2022
     * @version 1.0.0
     */
    public clean() {
        this.dataForm = {};
    }
}
</script>
