<template>
   <div>
<select v-model="selectedHora"  name="horaSelect" id="hora" @change="getPetition()" :disabled="disabled" >
<option value="00">00</option>
<option value="1">01</option>
<option value="2">02</option>
<option value="3">03</option>
<option value="4">04</option>
<option value="5">05</option>
<option value="6">06</option>
<option value="7">07</option>
<option value="8">08</option>
<option value="9">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
</select> :
<select v-model="selectedMinutos" name="minutosSelect" id="minutos"  @change="getPetition()" :disabled="disabled">
<option value="00">00</option>
<option value="1">01</option>
<option value="2">02</option>
<option value="3">03</option>
<option value="4">04</option>
<option value="5">05</option>
<option value="6">06</option>
<option value="7">07</option>
<option value="8">08</option>
<option value="9">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
</select> :
<select v-model="selectedSegundos" name="segundosSelect" id="segundos"  @change="getPetition()" :disabled="disabled">
<option value="00">00</option>
<option value="1">01</option>
<option value="2">02</option>
<option value="3">03</option>
<option value="4">04</option>
<option value="5">05</option>
<option value="6">06</option>
<option value="7">07</option>
<option value="8">08</option>
<option value="9">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
</select>

   </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

/**
 * Componente que establece un input como hora militar
 *
 * @author Leonardo F Herrera Marzo - 2024
 * @version 1.0.0
 */
@Component
export default class HourMilitary extends Vue {
   
   /**
     * Nombre del campo
     */
    @Prop({ type: String, required: true })
    public nameField: string;

     /**
     * deshabilita los campos
     */
     @Prop({ type: Boolean })
    public disabled: false;

    /**
     * Valor del campo
     */
    @Prop({ type: Object })
    public value: any;

    @Prop({ type: Boolean })
    public ceros: false;

      //Usar v-model
      public selectedHora: any;
      public selectedMinutos: any;
      public selectedSegundos: any;

    
    /**
     * Valor del campo
     */
   
    public hora: number;

        /**
     * Valor del campo
     */
   
    public minutos: number;


        /**
     * Valor del campo
     */
    
    public segundos: number;
   //El string que se va guardar
    public result: string;

    /**
     * Constructor de la clase
     *
     * @author Leonardo F Herrera Marzo - 2024
     * @version 1.0.0
     */
    constructor() {
      super();
      
      //Valida si el campo ya tiene un valor asignado
      if(this.value[this.nameField]){
         let hoy= new Date();
         let array = this.value[this.nameField].split(':');

         this.selectedHora=parseInt(array[0]);
         this.selectedMinutos=parseInt(array[1]) ;
         this.selectedSegundos=parseInt(array[2]);

     
      }else{
         //Si el objeto no tiene un valor asignado le asigna los nuevos valores
         if (this.ceros) {
            
         this.selectedHora='00';
         this.selectedMinutos='00';
         this.selectedSegundos='00';
         }else {
           
            let hoy= new Date();
            let cero = 0;

            this.selectedHora = hoy.getHours().toString();
            this.selectedMinutos=hoy.getMinutes().toString();
            this.selectedSegundos=hoy.getSeconds().toString();
            
            // Formatear la hora
            let hora = hoy.getHours() < 10 ? '0' + this.selectedHora : hoy.getHours();

            // Formatear los minutos
            let minutos = hoy.getMinutes() < 10 ? '0' + this.selectedMinutos : hoy.getMinutes();

            // Formatear los segundos
            let segundos = hoy.getSeconds() < 10 ? '0' + this.selectedSegundos : hoy.getSeconds();

            // Combinar las partes en una cadena de tiempo
            let result = hora + ":" + minutos + ":" + segundos;

            // Asignar el resultado
            this.value[this.nameField] = result;
         }
        
      }
   }

    /**
     * Asigna el valor al modelo
     *
     * @author leonardo F H. 01 de mayo 2024
     * @version 1.0.0
     */
   public getPetition(): void {
      

      // Formatear la hora
      let hora = parseInt(this.selectedHora) < 10 ? '0' + this.selectedHora : this.selectedHora;

      // Formatear los minutos
      let minutos = parseInt(this.selectedMinutos) < 10 ? '0' + this.selectedMinutos : this.selectedMinutos;

      // Formatear los segundos
      let segundos = parseInt(this.selectedSegundos) < 10 ? '0' + this.selectedSegundos : this.selectedSegundos;

      // Combinar las partes en una cadena de tiempo
      let result = hora + ":" + minutos + ":" + segundos;

      // Asignar el resultado
         this.value[this.nameField] = result;
   }

}
</script>
