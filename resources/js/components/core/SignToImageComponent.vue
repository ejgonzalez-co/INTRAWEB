<template>
  <div class="container">
    <div class="row col-md-12">
      <div class="col-md-6">
        <button @click.prevent="mostrarDibujar = true" class="btn btn-primary btn-block mb-3" :class="{ active: mostrarDibujar }">
          <i class="fas fa-pencil-alt"></i> Dibujar
        </button>
      </div>
      <div class="col-md-6">
        <button @click.prevent="mostrarDibujar = false" class="btn btn-primary btn-block mb-3" :class="{ active: !mostrarDibujar }">
          <i class="fas fa-file-upload"></i> Cargar imagen
        </button>
      </div>
    </div>
    <hr>

    <div id="containerDibujar" v-show="mostrarDibujar" class="container-dibujar table-responsive">
      <h4>Dibuje su firma en el cuadro. </h4>
      <div class="row">
        <div class="col-md-12">
          <div class="canvas-container">
            <canvas id="canvas" ref="canvasRef"></canvas>
          </div>
        </div>
        <div class="col-md-12">
          <button @click.prevent="limpiar" class="btn btn-grey btn-sm">
            <i class="fas fa-trash-alt"></i> Limpiar
          </button>
        </div>
      </div>
      <small class="d-block">Utilice todo el espacio del recuadro para dibujar su firma.<br>Si comete algún error, puede limpiarla usando el botón "Limpiar".</small>
    </div>

    <div id="containerCargar" v-show="!mostrarDibujar">
      <h4>Suba su firma</h4>
      <input-file :value="firmaSrc" name-field="attached" :max-files="1"
            :max-filesize="10" :required="true" file-path="public/firmas" accepted-files=".jpeg,.jpg,.png"
            message="Arrastre o seleccione una imagen de su firma"  :almacenar-a-w-s="false"
            help-text="Por favor, adjunte una imagen con fondo blanco o sin fondo. Esta imagen será utilizada en el documento, por lo que debe tener buena calidad."
      >
      </input-file>
    </div>
  </div>
</template>


  <!-- <div class="buttons">
      <button @click.prevent="limpiar">
        <i class="fas fa-trash-alt"></i> Limpiar
      </button>
      <button @click.prevent="descargar">
        <i class="fas fa-download"></i> Descargar
      </button>
      <button @click.prevent="guardarImagen">
        <i class="fas fa-save"></i> Guardar
      </button>
    </div> -->

<script lang="ts">
import axios from "axios";

export default {
  name: 'SignToImageComponent',
  data() {
    return {
      firmaSrc: {},
      contexto: null,
      xAnterior: 0,
      yAnterior: 0,
      xActual: 0,
      yActual: 0,
      haComenzadoDibujo: false,
      mostrarDibujar: true,
      mostrarCargar: false,
      rutaImagen : ''
    };
  },
  mounted() {
    const dpr = window.devicePixelRatio || 1;
    this.canvasWidth = 600 * dpr;
    this.canvasHeight = 200 * dpr;
    this.$refs.canvasRef.width = this.canvasWidth;
    this.$refs.canvasRef.height = this.canvasHeight;
    this.contexto = this.$refs.canvasRef.getContext('2d');
    this.contexto.scale(dpr, dpr); // Scale drawing context for sharper lines
    this.iniciarDibujo();
  },
  methods: {
    vercontainerDibujar() {
      this.mostrarDibujar = true;
      this.mostrarCargar = false;
    },
    vercontainerCargar() {
      this.mostrarDibujar = false;
      this.mostrarCargar = true;
    },
    limpiar() {
      this.contexto.fillStyle = "white";
      this.contexto.fillRect(0, 0, this.$refs.canvasRef.width, this.$refs.canvasRef.height);
    },
    descargar() {
      const enlace = document.createElement('a');
      enlace.download = "Firma.png";
      enlace.href = this.$refs.canvasRef.toDataURL();
      enlace.click();
    },
    obtenerImagenLocal() {
      return this.$refs.canvasRef.toDataURL();
    },
    obtenerImagen() {
        // Obtener referencia al canvas y su contexto
        const canvas = this.$refs.canvasRef;
        const contexto = canvas.getContext('2d');

        // Crear una imagen vacía para comparar con la que el usuario "dibujo"
        const canvasVacio = document.createElement('canvas');
        canvasVacio.width = canvas.width;
        canvasVacio.height = canvas.height;

        // Compara las dos imágenes, si se cumple, el canvas está vacío
        if (contexto.getImageData(0, 0, canvas.width, canvas.height).data.toString() ===
            canvasVacio.getContext('2d').getImageData(0, 0, canvasVacio.width, canvasVacio.height).data.toString()) {
            return null; // Termina la ejecución
        } else {
            const imagenBase64 = this.$refs.canvasRef.toDataURL();
            this.imagenBase64 = imagenBase64;
            return JSON.stringify({ imagen: imagenBase64 });
        }
    },
    guardarImagen() {
      return new Promise((resolve, reject) => {
        // this.showLoadingGif("Guardando imagen");

        const imagenBase64 = this.obtenerImagen();
        if(!imagenBase64) {
            return resolve("imagen_vacia"); // Rechaza la promesa si no hay imagen
        }
        const formData = new FormData();
        formData.append('imagen', imagenBase64);
        formData.append('ruta', 'firmas/');

        axios.post('/guardar-imagen-firma', formData, {
          headers: {
            'Content-Type': 'multipart/form-data' // Opcional si no está definido por defecto
          }
        })
        .then((response) => {

          // (this.$swal as any).close();
          resolve(response.data.ruta_imagen);
        })
        .catch((error) => {
          console.error('Error al guardar la imagen');
          console.error(error.response.data);
          (this.$swal as any).close();
          reject("Error al guardar la imagen");
        });
      });
    },

    iniciarDibujo() {
      const obtenerXReal = (clientX) => clientX - this.$refs.canvasRef.getBoundingClientRect().left;
      const obtenerYReal = (clientY) => clientY - this.$refs.canvasRef.getBoundingClientRect().top;

      const onClicOToqueIniciado = evento => {
        this.xAnterior = this.xActual;
        this.yAnterior = this.yActual;
        this.xActual = obtenerXReal(evento.clientX);
        this.yActual = obtenerYReal(evento.clientY);
        this.contexto.beginPath();
        this.contexto.fillStyle = "black";
        this.contexto.fillRect(this.xActual, this.yActual, 2, 2);
        this.contexto.closePath();
        this.haComenzadoDibujo = true;
      }

      const onMouseODedoMovido = evento => {
        evento.preventDefault();
        if (!this.haComenzadoDibujo) {
          return;
        }
        let target = evento;
        if (evento.type.includes("touch")) {
          target = evento.touches[0];
        }
        this.xAnterior = this.xActual;
        this.yAnterior = this.yActual;
        this.xActual = obtenerXReal(target.clientX);
        this.yActual = obtenerYReal(target.clientY);
        this.contexto.beginPath();
        this.contexto.moveTo(this.xAnterior, this.yAnterior);
        this.contexto.lineTo(this.xActual, this.yActual);
        this.contexto.strokeStyle = "black";
        this.contexto.lineWidth = 2;
        this.contexto.stroke();
        this.contexto.closePath();
      }

      const onMouseODedoLevantado = () => {
        this.haComenzadoDibujo = false;
      };

      ["mousedown", "touchstart"].forEach(nombreDeEvento => {
        this.$refs.canvasRef.addEventListener(nombreDeEvento, onClicOToqueIniciado);
      });

      ["mousemove", "touchmove"].forEach(nombreDeEvento => {
        this.$refs.canvasRef.addEventListener(nombreDeEvento, onMouseODedoMovido);
      });

      ["mouseup", "touchend"].forEach(nombreDeEvento => {
        this.$refs.canvasRef.addEventListener(nombreDeEvento, onMouseODedoLevantado);
      });
    },

    showLoadingGif(mensaje) {
      this.$swal({
          html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>'+mensaje+'.</span>',
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false
      });
    },
    async retornarRutaImagen() {

        if(this.mostrarDibujar){
            this.rutaImagen = await this.guardarImagen();

            if(this.rutaImagen === "imagen_vacia") {
                (this.$swal as any).close();
                await this.$swal({
                    icon: 'warning',
                    html: 'No se detectó ninguna firma en el área de dibujo. Asegúrese de dibujar su firma o cargar una imagen para que podamos procesar su documento correctamente.',
                    confirmButtonText: "Aceptar",
                    allowOutsideClick: false,
                });
                return "imagen_vacia";
            } else {
                return this.rutaImagen;
            }
        } else {
          console.log("entro al .firmaSrc.attac");
            this.rutaImagen = this.firmaSrc.attached;
            console.log("entro al .firmaSrc.rutaImagens3"+this.rutaImagen);

            if(this.rutaImagen == "") {
              console.log("entro al .firmaSrc.rutaImagens");

                (this.$swal as any).close();
                await this.$swal({
                    icon: 'warning',
                    html: 'Por favor, suba una imagen válida. Si ya se subió una, intente subirla nuevamente, ya que no se ha detectado correctamente.',
                    confirmButtonText: "Aceptar",
                    allowOutsideClick: false,
                });
                return "imagen_vacia";
            } else {
                return this.rutaImagen;
            }
        }
    },
  },
  watch: {
    firmaSrc() {
      if (this.firmaSrc) {
        window.print();
      }
    }
  }
};
</script>

<style scoped>
.container {
  /* Eliminamos el color de fondo */
  background-color: transparent;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h2 {
  margin-bottom: 10px;
}


html, body {
  overscroll-behavior: none;
}


canvas {
  width: 600px; /* Replace with your desired width */
  height: 200px; /* Replace with your desired height */
  border: 1px solid black;
  border-radius: 5px;
}
</style>
