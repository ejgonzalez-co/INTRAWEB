<script lang="ts">
import { Component, Prop, Mixins } from "vue-property-decorator";

import { Bar, mixins } from 'vue-chartjs';
/**
 * Componente para la edicion del perfil del usuario logueado
 *
 * @author Jhoan Sebastian Chilito S. - Ago. 04 - 2020
 * @version 1.0.0
 */
@Component({
    name: 'bar-chart',
    extends: Bar, // this is important to add the functionality to your component
    mixins: [mixins.reactiveProp]
})
export default class BarChart extends Mixins(mixins.reactiveProp, Bar) {

    mounted() {
        this.renderChart({
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                    label: 'GitHub Commits',
                    backgroundColor: [
                        '#f87979', '#A87979', '#B87979', '#f87979', '#f87979', '#f87979',
                        '#f87979', '#f87979', '#f87979', '#f87979', '#f87979', '#f87979'
                    ],
                    data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
                }
            ]
        },
        { responsive: true, maintainAspectRatio: false });
    }

}

</script>
