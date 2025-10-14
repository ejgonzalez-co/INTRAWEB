<template>
    <div>
        <hr />
        <!-- Contenedor de Imagen -->
        <div v-if="imageSrc" class="image-container col-md-12" style="text-align: center;">
            <div style="display: inline-block; position: relative;">
                <img :src="imageSrc" ref="image" @click="addLink" @load="onImageLoad" style="max-width: 90%; display: block; margin: auto;"/>
                <!-- Áreas de Hipervínculos -->
                <vue-drag-resize
                    v-for="link in (!loadingImg ? links : [])"
                    :key="keyRefresh"
                    :x="link.desplazamiento_x"
                    :y="link.desplazamiento_y"
                    :w="link.ancho"
                    :h="link.alto"
                    :parent="true"
                    :resizable="true"
                    :draggable="true"
                    @resizing="(newRect) => updateSize(newRect, link)"
                    @dragging="(newRect) => updatePosition(newRect, link)"
                    @click.native="editLink(link.link_id)"
                    :title="link.url"
                >
                    <div class="hotspot">
                        <input
                            v-if="link.editing"
                            type="text"
                            v-model="link.url"
                            placeholder="Ingrese URL"
                            @blur="stopEditing(link.link_id)"
                            @keyup.enter="stopEditing(link.link_id)"
                            :ref="'input-' + link.link_id"
                        />
                        <span v-else @click="editLink(link.link_id)">🔗</span>
                        <button @click="removeLink(link.link_id)" title="Eliminar">❌</button>
                    </div>
                </vue-drag-resize>

                <div v-if="loadingImg" style="height: 20rem;">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>

        <button @click="saveLinks" type="button" class="btn btn-primary m-t-15 m-b-20" :disabled="links.length <= 0">
            <i class="fa fa-save mr-2"></i>Guardar zonas en el mapa de procesos
        </button>

        <button @click="eliminarMapa" type="button" class="btn btn-danger m-t-15 m-b-20">
            <i class="fa fa-trash mr-2"></i>Eliminar mapa de procesos
        </button>

        <!-- Botón flotante -->
        <button @click="mostrarProcesos = !mostrarProcesos" class="btn-flotante">
            Procesos de la entidad
        </button>

        <!-- Nube deslizante -->
        <transition name="slide-up">
            <div v-if="mostrarProcesos" class="nube-procesos">
                <button @click="mostrarProcesos = false" style="position: absolute; top: 8px; right: 12px; background: none; border: none; font-size: 18px; cursor: pointer;">✖</button>
                <h5>Procesos disponibles</h5>
                <div v-for="(proceso, index) in procesos" :key="index" class="proceso-item">
                    <span>{{ proceso.nombre }}</span>
                    <button @click="copiarEnlace(proceso.enlace)" style="margin-left: auto;">Copiar enlace</button>
                    <button @click="addLink(null, proceso.enlace)">Agregar zona</button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import VueDragResize from "vue-drag-resize";
import { v4 as uuidv4 } from "uuid";
import { debounce } from 'lodash';

export default {
    components: { VueDragResize },
    props: {
        imageSrc: {
            type: String,
            default: "",
            required: true
        },
        links: {
            type: Array,
            default: [],
            required: true
        },
        imageId: {
            type: Number,
            required: true
        },
        procesos: {
            type: Array,
            default: [],
        }
    },
    data() {
        return {
            loadingImg: true,
            keyRefresh: 1,
            mostrarProcesos: false
        };
    },
    mounted() {
        this.debouncedUpdate = debounce(this.updateAllLinkPositions, 200);
        window.addEventListener('resize', this.debouncedUpdate);
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.debouncedUpdate);
    },
    methods: {
        onImageLoad() {
            this.loadingImg = false;
            this.updateAllLinkPositions();
        },
        updateAllLinkPositions() {
            const img = this.$refs.image;
            if (!img) return;
            const imgWidth = img.clientWidth;
            const imgHeight = img.clientHeight;

            this.links.forEach((link, index) => {
                this.$set(this.links, index, {
                    ...link,
                    desplazamiento_x: link.porcentaje_x * imgWidth,
                    desplazamiento_y: link.porcentaje_y * imgHeight,
                    ancho: link.porcentaje_w * imgWidth,
                    alto: link.porcentaje_h * imgHeight
                });
            });
            this.keyRefresh++;
        },
        addLink(event, url = '') {
            const img = this.$refs.image;
            const rect = img.getBoundingClientRect();
            const clickX = event ? event.clientX - rect.left : img.clientWidth / 2;
            const clickY = event ? event.clientY - rect.top : img.clientHeight / 2;

            const newLink = {
                link_id: uuidv4(),
                url: url,
                editing: true,
                porcentaje_x: clickX / img.clientWidth,
                porcentaje_y: clickY / img.clientHeight,
                porcentaje_w: 0.1, // por ejemplo 10% de ancho inicial
                porcentaje_h: 0.1, // por ejemplo 10% de alto inicial
                desplazamiento_x: clickX,
                desplazamiento_y: clickY,
                ancho: img.clientWidth * 0.1,
                alto: img.clientHeight * 0.1
            };

            this.links.push(newLink);
            if(url) {
                this.mostrarProcesos = !this.mostrarProcesos;
                this.$parent["_pushNotification"]("Zona agregada al mapa de procesos");
            }
        },
        updatePosition(newRect, link) {
            const img = this.$refs.image;
            if (!img) return;

            this.$set(link, 'desplazamiento_x', newRect.left);
            this.$set(link, 'desplazamiento_y', newRect.top);
            this.$set(link, 'porcentaje_x', newRect.left / img.clientWidth);
            this.$set(link, 'porcentaje_y', newRect.top / img.clientHeight);
        },

        updateSize(newRect, link) {
            const img = this.$refs.image;
            if (!img) return;

            link.ancho = newRect.width;
            link.alto = newRect.height;
            link.porcentaje_w = newRect.width / img.clientWidth;
            link.porcentaje_h = newRect.height / img.clientHeight;
        },
        editLink(id) {
            this.links.forEach(link => {
                link.editing = link.link_id === id;
            });
            this.$nextTick(() => {
                const input = this.$refs['input-' + id];
                if (input && input[0]) input[0].focus();
            });
        },
        stopEditing(id) {
            const link = this.links.find(l => l.link_id === id);
            if (link) {
                link.editing = false;
            }
        },
        removeLink(id) {
            this.links = this.links.filter(l => l.link_id !== id);
        },
        async saveLinks() {
            await fetch(`guardar-links-mapa-procesos/${this.imageId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") // Obtiene el token de la meta tag
                },
                body: JSON.stringify(this.links),
            });
            this.$parent["_pushNotification"]("Vínculos guardados");
        },
        async eliminarMapa() {

            // Visualiza alerta de eliminacion de elemento
            this.$swal({
                icon: 'question',
                title: "¿Esta seguro(a) de eliminar el mapa de procesos?",
                html: "Esto eliminará el mapa de procesos y las zonas creadas",
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
            })
            .then(async (res) => {
                // Valida si la opcion selecionada es positiva
                if (res.value) {
                    await fetch(`eliminar-mapa-procesos/${this.imageId}`, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") // Obtiene el token de la meta tag
                        },
                    });
                    this.$parent.dataList.shift(); // Elimina el primer elemento
                    this.$parent["_pushNotification"]("Mapa eliminado");
                }
            });
        },
        copiarEnlace(link) {
            navigator.clipboard.writeText(link).then(() => {
                this.$parent["_pushNotification"]("Enlace copiado en el portapapeles");
            });
        },
    }
};
</script>

<style scoped>
    .image-container {
        position: relative;
        display: inline-block;
    }
    .hotspot {
        position: relative;
        width: 100%;
        height: 100%;
        background: rgba(0, 123, 255, 0.2);
        border: 2px dashed #007bff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    .hotspot input {
        width: 100%;
        height: 100%;
        border: none;
        background: rgba(255, 255, 255, 0.8);
        text-align: center;
        padding: 5px;
    }
    .hotspot button {
        position: absolute;
        top: -10px;
        right: -20px;
        background: transparent;
        border: none;
        font-size: 11px;
        padding: 0;
    }

    /* Botón flotante */
    .btn-flotante {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 25px;
        padding: 12px 20px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        z-index: 1021;
    }

    /* Nube deslizante */
    .nube-procesos {
        position: fixed;
        bottom: 80px;
        right: 20px;
        background: #ffffff;
        border: 1px solid #ccc;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        width: 590;
        max-height: 300px;
        overflow-y: auto;
        z-index: 998;
    }

    .proceso-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .proceso-item button {
        background: none;
        border: none;
        color: #007bff;
        font-size: 12px;
        cursor: pointer;
    }

    /* Transición */
    .slide-up-enter-active,
    .slide-up-leave-active {
        transition: all 0.3s ease;
    }
    .slide-up-enter,
    .slide-up-leave-to {
        transform: translateY(20px);
        opacity: 0;
    }
</style>
