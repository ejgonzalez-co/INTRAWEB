<template>
    <div :class="classContainer">
        <!-- Formulario de campos -->
        <form @submit.prevent="addToList()" class="form-horizontal">
            <!-- Campos de formulario -->
            <slot name="fields" :data-form="dataForm">
            </slot>
            <!-- Boton de guardado de datos de los campos ingresados-->
            <div class="form-group m-t-15" v-if="isAdding">
                <button type="submit" :class="[classButtonAdd, 'btn btn-md']">
                    <i class="fa fa-plus mr-1"></i> {{ labelButtonAdd }}
                </button>
            </div>
        </form>
        <!-- Tabla de datos agregados desde el formulario -->
        <div class="row" v-if="dataList && dataList.length > 0">
            <div class="table-responsive">
                <table :class="['table', classTable]">
                    <thead>
                        <tr :class="classTableHeader">
                            <!-- Obtiene los nombre de columna que se pueden visualizar -->
                            <th v-for="(option, key) in nameColumnOptions" :key="key">{{ option.label }}</th>
                            <th v-if="hasActions">{{ labelColActions }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Visualiza todos los elementos ingresados de la lista -->
                        <tr v-for="(row, key) in getDataColumValue" :key="key">
                            <!-- <td v-for="(col, k) in nameColumnOptions" :key="k">{{ row[col.name] }}</td> -->
                            <!-- <td v-for="(col, k) in nameColumnOptions" :key="k">{{ col.nameObjectKey ? row[col.nameObjectKey[0]][col.nameObjectKey[1]] : row[col.name] }}</td> -->
                            <!-- <td v-for="(col, k) in nameColumnOptions" :key="k"><span v-if="!col.isEditable">{{ col.nameObjectKey ? row[col.nameObjectKey[0]][col.nameObjectKey[1]] : row[col.name] }}</span><input v-model="$parent['dataForm']['ordenes_entradas'][key][col.name]" v-else :type="col.inputType ? col.inputType : 'text'" class="form-control" :required="col.isRequired"></td> -->


                            <td v-for="(col, k) in nameColumnOptions" :key="k">
                                <!-- Si la columna NO es editable -->
                                <span v-if="!col.isEditable">
                                    {{ col.nameObjectKey ? row[col.nameObjectKey[0]][col.nameObjectKey[1]] : row[col.name] }}
                                </span>

                                <!-- Si es editable y es unidad de medida -->
                                <select
                                    v-else-if="col.name === 'unidad_medida_conversion'"
                                    v-model="$parent['dataForm']['ordenes_entradas'][key][col.name]"
                                    class="form-control"
                                    :required="col.isRequired"
                                >
                                    <option value="No aplica">No aplica</option>
                                   
                                    <!-- Para Cuñete -->
                                    <option value="Cuñete / Libras" v-if="row.unidad === 'Cuñete'">Cuñete / Libras</option>
                                    <option value="Cuñete / Galones" v-if="row.unidad === 'Cuñete'">Cuñete / Galones</option>

                                    <!-- Para Tambor -->
                                    <option value="Tambor / Cuartos" v-if="row.unidad === 'Tambor'">Tambor / Cuartos</option>
                                    <option value="Tambor / Galones" v-if="row.unidad === 'Tambor'">Tambor / Galones</option>

                                    <!-- Para Caja -->
                                    <option value="Caja / Cuartos" v-if="row.unidad === 'Caja'">Caja / Cuartos</option>
                                </select>

                                <!-- Para el resto de inputs editables -->
                                <input
                                    v-else
                                    v-model="$parent['dataForm']['ordenes_entradas'][key][col.name]"
                                    :type="col.inputType ? col.inputType : 'text'"
                                    class="form-control"
                                    :required="col.isRequired"
                                    :step="col.step || (col.inputType === 'number' ? '0.01' : null)"
                                >
                            </td>


                            <td v-if="hasActions">
                                <!-- Boton sube el elemento de la lista -->
                                <button v-if="key != 0" type="button" @click="moveUpElement(key)" :class="[classButtonDropElement, 'btn btn-icon btn-sm']">
                                    <i class="fa fa-arrow-up"></i>
                                </button>
                                <!-- Boton que baja el elemento de la lista -->
                                <button v-if="key != (dataList.length - 1)" type="button" @click="moveDownElement(key)" :class="[classButtonDropElement, 'btn btn-icon btn-sm']">
                                    <i class="fa fa-arrow-down"></i>
                                </button>
                                <!-- Boton que elimina un elemento de la lista -->
                                <button v-if="isDelete" type="button" @click="dropElementList(key)" :class="[classButtonDropElement, 'btn btn-icon btn-sm']">
                                    <i class="fa fa-trash"></i>
                                </button>
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

import utility from '../../utility';

import axios from 'axios';

/**
 * Componente de formulario con lista dinamica
 *
 * @author Jhoan Sebastian Chilito S. - Ene. 06 - 2021
 * @version 1.0.0
 */
@Component
export default class DynamicListEditableComponent extends Vue {

    /**
     * Clase css para el color del boton de agregar a la lista
     */
    @Prop({default: 'btn-primary'})
    public classButtonAdd: String;

    /**
     * Clase css para el color del boton de agregar a la lista
     */
    @Prop({default: 'btn-default'})
    public classButtonDropElement: String;

    /**
     * Clase css para contenedor del componente
     */
    @Prop({default: 'p-10'})
    public classContainer: String;

    /**
     * Clase css para el contenido de la tabla
     */
    @Prop({default: 'table-hover text-inverse'})
    public classTable: String;

    /**
     * Clase css para el contenido de la tabla
     */
    @Prop({default: 'text-left'})
    public classTableHeader: String;

    /**
     * Texto del boton de agregar a la lista
     */
    @Prop({default: 'Agregar a la lista'})
    public labelButtonAdd: String;

    /**
     * Texto del boton de agregar a la lista
     */
    @Prop({default: 'Acciones'})
    public labelColActions: String;

    /**
     * Lista de datos agregados
     */
    @Prop({required: true})
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
    @Prop({type: Array, default: () => []})
    public dataListOptions: any;

    /**
     * Campos del formulario
     */
    public dataForm: any;

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
    @Prop({type: Number, default: 100})
    public timeout: number;

    @Prop({type: Boolean, default: false})
    public isActivity: boolean;

    @Prop({type: Boolean, default: true})
    public isDelete: boolean;

    /**
     * Valida si se muestra o no el boton de agregar
     */
     @Prop({type: Boolean,default: false})
    public isAdding: boolean;

    /**
     * Valida si debe mostrar la columna de acciones
     */
     @Prop({type: Boolean,default: true})
    public hasActions: boolean;

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

        // Detecta las peticiones pendientes
        this.detectRequestPending();

        // console.log("Agregando",this.isAdding);
        
    }

    /**
     * Detecta las peticiones pendientes
     *
     * @author Jhoan Sebastian Chilito S. - Feb. 04 - 2021
     */
    public detectRequestPending(): void {
         // Add a request interceptor
        axios.interceptors.request.use( (config) => {
            this.numberOfAjaxCallPending++;
            return config;
        }, (error) => {
            return Promise.reject(error);
        });

        // Add a response interceptor
        axios.interceptors.response.use( (response) => {
            this.numberOfAjaxCallPending--;
            return response;
        }, (error) => {
            this.numberOfAjaxCallPending--;
            return Promise.reject(error);
        });
    }

    /**
     * Detecta la cantidad de peticiones pendientes
     *
     * @author Jhoan Sebastian Chilito S. - Feb. 04 - 2021
     */
    @Watch('numberOfAjaxCallPending')
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

 
    @Watch('dataList', { deep: true })
    onDataListChanged(newList: any[]) {
    newList.forEach((item) => {
        // Si no existe el campo de feedback, lo inicializamos
        if (item.conversion_feedback_text === undefined) {
        this.$set(item, 'conversion_feedback_text', '');
        }

        // Tomamos la cantidad ingresada en la tabla (campo editable correcto)
        const cantidadBase = parseFloat(item.cantidad_entrada) || 0;

        let resultado = '';
        switch (item.unidad_medida_conversion) {
            case 'Cuñete / Libras':
                resultado = `${cantidadBase} cuñetes × 35 = ${cantidadBase * 35} libras`;
                break;
            case 'Cuñete / Galones':
                resultado = `${cantidadBase} cuñetes × 5 = ${cantidadBase * 5} galones`;
                break;
            case 'Tambor / Cuartos':
                resultado = `${cantidadBase} tambores × 220 = ${cantidadBase * 220} cuartos`;
                break;
            case 'Tambor / Galones':
                resultado = `${cantidadBase} tambores × 55 = ${cantidadBase * 55} galones`;
                break;
            case 'Caja / Cuartos':
                resultado = `${cantidadBase} cajas × 24 = ${cantidadBase * 24} cuartos`;
                break;
            case 'No aplica':
            default:
                resultado = `${cantidadBase} (sin conversión)`;
                break;
        }

        // Guardamos el feedback en el mismo objeto
        item.conversion_feedback_text = resultado;
    });
    }


    /**
     * Agrega los datos del formulario ingresados a la lista
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 08 - 2021
     * @version 1.0.0
     */
    public addToList(): void {

        if (this.isActivity) {

            let total = this.dataList.reduce((accumulator, element) => {
                // Asegurémonos de que valor_total sea un número válido
                const weight = parseFloat(element.valor_total) || 0;
                return accumulator + weight;
            }, 0);

            // Calcula el porcentaje máximo disponible
            const valorMaximo = (this.$parent as Vue)['dataForm']['valor_disponible'] - total;

            if ( this.dataForm.valor_total > valorMaximo) {
                this.$swal({
                        icon: 'info',
                        html: `<p>
                            El valor de la necesidad que intentas ingresar (<strong>$${Number(this.dataForm.valor_total).toLocaleString()}</strong>) supera el límite disponible del rubro. Por favor, ajusta la cantidad solicitada o elige otra descripción.
                        </p>
                        <p>
                            El valor máximo disponible del rubro es: $<strong>${Number(valorMaximo).toLocaleString()}</strong>.
                        </p>
                        <p>
                            Actualmente, la suma total de las necesidades es: <strong>$${Number(total).toLocaleString()}</strong>.
                        </p>`,
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false,
                    });


                return null;
            }else{

                const nuevoTotalSolicitud = total + this.dataForm.valor_total;

                // Actualiza la propiedad total_solicitud en dataForm
                // this.$parent.$data.dataForm.total_solicitud = nuevoTotalSolicitud.toLocaleString();
               (this.$parent as Vue)['dataForm']['total_solicitud'] = nuevoTotalSolicitud.toLocaleString();


            }
        }

        // Agrega un nuevo elemento a la lista
        this.dataList.push(utility.clone(this.dataForm));
        // Limpia los campos del formulario
        this.dataForm = {};
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
        Vue.delete(this.dataList, index);

        if (this.isActivity) {
            let total = this.dataList.reduce((accumulator, element) => {
                // Asegurémonos de que valor_total sea un número válido
                const weight = parseFloat(element.valor_total) || 0;
                return accumulator + weight;
            }, 0);

        
                // Actualiza la propiedad total_solicitud en dataForm
                this.$parent.$data.dataForm.total_solicitud = total.toLocaleString();
            }
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
    };

    /**
     * Obtiene el valor de la columna de la tabla por fila
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 21 - 2021
     * @version 1.0.0
     */
    get getDataColumValue() {
        return utility.clone(this.dataList).map((data) => {

            // console.log(data);
            this.nameColumnOptions.forEach(async (colOption) => {
                // Valida que sea una lista referida
                if (colOption.refList) {
                    await new Promise((resolve) => setTimeout(resolve, 2500))
                    .then(() => {
                        const selectRef = this.getSelectRef(colOption.refList);
                        // Valida si la referencia de la lista existe
                        if (selectRef) {
                            // Obtiene las opciones del select
                            const printerOpts = (selectRef as any).children;

                            if (colOption.selectItem) {
                                
                                let datoColOption = data[colOption.name].map(Number);
                                // datoColOption.map(Number);

                                // console.log(datoColOption);
                                if (typeof datoColOption == 'object') {
                                    let options = [];
                                    // Recorre la lista de opiones
                                    for (let i = 0; i < printerOpts.length; i++) {
                                        const option = printerOpts[i];
                                        // Valida que la opcion de la lista sea la seleccionada
                                        if (datoColOption.includes(Number(option.value))) {
                                            // Obtiene el nombre del valor seleccionado
                                            options.push(option.text);
                                        }
                                    }
                                    data[colOption.name] = options.join(', ');
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
                            console.log('No existe la referencia de la la lista', colOption.label);
                        }
                    });
                }
                if (colOption.selectItem) {

                    if (typeof data[colOption.name] == 'object') {

                        const printerOpts = data[colOption.name];
                        let option = [];
                        // Recorre la lista de opiones
                        for (let i = 0; i < printerOpts.length; i++) {
                            option.push(printerOpts[i][colOption.selectItem]);
                        }
                        // console.log(option);
                        data[colOption.name] = option.join(', ');
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
        return this.dataListOptions.filter((option) => {
            // console.log("Option",option);
            
            return option.isShow;
        });
    }

}

</script>
