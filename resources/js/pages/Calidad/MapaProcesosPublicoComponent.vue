<script>
 import VueDragResize from "vue-drag-resize";
    import { debounce } from 'lodash';

    export default {
    components: { VueDragResize },
    data() {
      return {
        activeTab: 'general',
        loadingImg: true,
        keyRefresh: 1,
        zonasCalculadas: [] // zonas convertidas en píxeles
      };
    },
    mounted() {
        this.debouncedUpdate = debounce(this.calcularZonas, 200);
        window.addEventListener('resize', this.debouncedUpdate);
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.debouncedUpdate);
    },
    methods: {
        // Esta función se ejecuta cuando la imagen ha cargado completamente
        onImageLoad() {
            // Oculta el indicador de carga de la imagen
            this.loadingImg = false;
            // Llama a la función que calcula las zonas interactivas sobre la imagen
            this.calcularZonas();
        },

        // Calcula las posiciones y tamaños de las zonas interactivas en la imagen
        calcularZonas() {
            // Obtiene la referencia de la imagen desde el template usando $refs
            const img = this.$refs.image;

            // Si no se encuentra la imagen, termina la función
            if (!img) return;

            // Obtiene el ancho y alto actual de la imagen renderizada en el navegador
            const imgWidth = img.clientWidth;
            const imgHeight = img.clientHeight;

            // Calcula las zonas a partir de los datos proporcionados por el componente padre
            // Se asume que las coordenadas están en porcentaje (valores entre 0 y 1)
            this.zonasCalculadas = this.$parent.dataExtra.mapa_procesos_links[0].mapa_procesos_links.map(link => {
                return {
                    url: link.url, // URL de destino al hacer clic en la zona
                    // Ajusta la posición horizontal y agrega 20 píxeles extra
                    left: (link.porcentaje_x * imgWidth) + 20,
                    // Ajusta la posición vertical
                    top: link.porcentaje_y * imgHeight,
                    // Calcula el ancho de la zona
                    width: link.porcentaje_w * imgWidth,
                    // Calcula el alto de la zona
                    height: link.porcentaje_h * imgHeight
                };
            });
        }
    }
  };
  </script>
