<template>
    <div>
      <table class="responsive-table ">
        <thead>
          <tr>
            <th>Cód. del rubro</th>
            <th>Nombre del rubro</th>
            <th>Cód. centro de costo</th>
            <th>Nombre del centro de costo</th>
            <th>Valor disponible del rubro</th>
            <th>Valor de la edición</th>
            <th>Valor total del rubro</th>
          </tr>
        </thead>
        <tbody>
          <!-- <tr v-for="(rubro, index) in rubros" :key="index" :class="index % 2 === 0 ? 'even-row' : 'odd-row'"> -->
        <tr v-for="rubro in this.rubros">
            <td>{{ rubro.code_cost }}</td>
            <td>{{ rubro.name }}</td>
            <td>{{ rubro.cost_center }}</td>
            <td>{{ rubro.cost_center_name }}</td>
            <td>${{ rubro.value_avaible }}</td>
            <td><input number="Espera" type="text" class="form-control" /></td>
            <td>{{  }} </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
    import axios from 'axios';
    
    export default {
        data() {
        return {
            rubros: [],
        };
        },
        created() {
        // Realizar la petición Axios para obtener los datos de la ruta
        axios.get('datos-cost-item')
            .then((response) => {
            this.rubros = response.data;
            console.log(response.data);
            })
           
            .catch((error) => {
            console.error('Error al obtener los datos:', error);
            });
        },
        methods: {
        calcularValorTotal(rubro) {
            // Aquí puedes calcular el valor total del rubro en función de los datos
            // Si tienes una fórmula específica, reemplaza el siguiente ejemplo:
            return rubro.valorDisponible + parseFloat(rubro.valorEdicion || 0);
        },
        },
    };
  </script>
  
  <style scoped>
   /* Estilos para la tabla responsiva y líneas de cebra */
.responsive-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1rem;
}

.responsive-table th,
.responsive-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.responsive-table th {
  background-color: #f2f2f2;
}

.responsive-table tr:nth-child(even) {
  background-color: #f2f2f2;
}
  
  </style>