<template>

<div class="container mt-3" v-if="type=='field'">
    <div class="position-relative">

      <!-- Botón de ayuda con ChatGPT -->
      <button type="button" class="btn btn-success d-flex align-items-center floating-button" @click="toggleForm" title="¿No sabes qué escribir? Haz clic aquí y la IA te ayudará." data-bs-toggle="tooltip" data-bs-placement="bottom">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu me-2">
          <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
          <rect x="9" y="9" width="6" height="6"></rect>
          <line x1="9" y1="1" x2="9" y2="4"></line>
          <line x1="15" y1="1" x2="15" y2="4"></line>
          <line x1="9" y1="20" x2="9" y2="23"></line>
          <line x1="15" y1="20" x2="15" y2="23"></line>
          <line x1="20" y1="9" x2="23" y2="9"></line>
          <line x1="20" y1="14" x2="23" y2="14"></line>
          <line x1="1" y1="9" x2="4" y2="9"></line>
          <line x1="1" y1="14" x2="4" y2="14"></line>
        </svg>
        Genera textos con inteligencia artificial
      </button>

      <!-- Contenedor del formulario -->
      <div v-if="showFormChat" class="tooltip-container">
        <div class="tooltip-content">

          <!-- Encabezado y selector -->
          <div class="row mt-3 align-items-center">
            <label for="require_answer" class="col-form-label col-md-4">Selecciona y aplica sobre la caja inferior</label>
            <div class="col-md-8">
              <select id="require_answer" class="form-control" v-model="selectedOption">
                <option value="improve" selected>Mejorar la redacción del texto</option>
                <option value="check_spelling">Revisar la ortografía del texto</option>
                <option value="ideas">Sugerir ideas para empezar un documento sobre</option>
              </select>
            </div>
          </div>

          <!-- Área de texto con botón integrado -->
          <div class="input-group mt-3" style="border: 1px solid #dfe1e5; border-radius: 24px; overflow: hidden;">
            <input type="text" class="form-control" v-model="inputText" placeholder="Ingresa un texto inicial..." v-word-limit="maxWord" style="border: none; box-shadow: none;">
            <span class="input-group-text p-0" style="border: none; background: transparent;">
              <button type="button" class="btn btn-success rounded-circle" @click="processText" :disabled="wordCount > maxWord" title="Generar texto" style="background-color: #009688; border: 1px solid #dfe1e5;">
                <i class="fas fa-arrow-down"></i>
              </button>
            </span>
          </div>
          <small class="text-muted mt-2">Coloca aquí una idea que tengas y la IA te ayudará. Por ejemplo: Oficio para solicitud de suministros, Solicitud de vacaciones, etc.</small><br>
          <small class="text-muted">Palabras restantes: {{ maxWord - wordCount }}</small>

          <!-- Resultado y botón de copiar -->
          <div v-if="output" class="mt-3" style="border: 1px solid #dfe1e5; border-radius: 8px; padding: 15px; background-color: #f8f9fa;">
            <div class="d-flex justify-content-between align-items-center">
              <h5 style="margin-bottom: 0;">Texto sugerido:</h5>
              <button type="button" class="btn btn-primary btn-sm" @click="copyOutput">
                <span v-if="!copiedToClipboard">Copiar texto</span>
                <span v-else>¡Texto copiado!</span>
                <i v-if="!copiedToClipboard" class="fas fa-copy ms-1"></i>
                <i v-else class="fas fa-check ms-1"></i>
              </button>
            </div>
            <hr style="margin-top: 5px; margin-bottom: 10px;">
            <div>
              <p ref="outputText" v-html="output" style="background-color: #ffffff; border-radius: 8px; padding: 10px; border: 1px solid #dfe1e5;"></p>
              <small class="text-muted mt-2 d-block">Puedes editar el texto generado según tus necesidades. ChatGPT puede cometer errores. Comprueba la información importante.</small>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
  <div class="container mt-3" v-else>
    <div class="position-relative">

      <!-- Botón de ayuda con ChatGPT -->
      <button type="button" class="btn btn-success d-flex align-items-center floating-button" @click="toggleForm" title="¿No sabes qué escribir? Haz clic aquí y la IA te ayudará." data-bs-toggle="tooltip" data-bs-placement="bottom">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu me-2">
          <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
          <rect x="9" y="9" width="6" height="6"></rect>
          <line x1="9" y1="1" x2="9" y2="4"></line>
          <line x1="15" y1="1" x2="15" y2="4"></line>
          <line x1="9" y1="20" x2="9" y2="23"></line>
          <line x1="15" y1="20" x2="15" y2="23"></line>
          <line x1="20" y1="9" x2="23" y2="9"></line>
          <line x1="20" y1="14" x2="23" y2="14"></line>
          <line x1="1" y1="9" x2="4" y2="9"></line>
          <line x1="1" y1="14" x2="4" y2="14"></line>
        </svg>
        Genera textos con inteligencia artificial
      </button>

      <!-- Contenedor del formulario -->
      <div v-if="showFormChat" class="tooltip-container">
        <div class="tooltip-content">

          <!-- Encabezado y selector -->
          <div class="row mt-3 align-items-center">
            <label for="require_answer" class="col-form-label col-md-4">Selecciona y aplica sobre la caja inferior</label>
            <div class="col-md-8">
              <select id="require_answer" class="form-control" v-model="selectedOption">
                <option value="improve" selected>Mejorar la redacción del texto</option>
                <option value="check_spelling">Revisar la ortografía del texto</option>
                <option value="ideas">Sugerir ideas para empezar un documento sobre</option>
              </select>
            </div>
          </div>

          <!-- Área de texto con botón integrado -->
          <div class="input-group mt-3" style="border: 1px solid #dfe1e5; border-radius: 24px; overflow: hidden;">
            <input type="text" class="form-control" v-model="inputText" placeholder="Ingresa un texto inicial..." v-word-limit="maxWord" style="border: none; box-shadow: none;">
            <span class="input-group-text p-0" style="border: none; background: transparent;">
              <button type="button" class="btn btn-success rounded-circle" @click="processText" :disabled="wordCount > maxWord" title="Generar texto" style="background-color: #009688; border: 1px solid #dfe1e5;">
                <i class="fas fa-arrow-down"></i>
              </button>
            </span>
          </div>
          <small class="text-muted mt-2">Coloca aquí una idea que tengas y la IA te ayudará. Por ejemplo: Oficio para solicitud de suministros, Solicitud de vacaciones, etc.</small><br>
          <small class="text-muted">Palabras restantes: {{ maxWord - wordCount }}</small>

          <!-- Resultado y botón de copiar -->
          <div v-if="output" class="mt-3" style="border: 1px solid #dfe1e5; border-radius: 8px; padding: 15px; background-color: #f8f9fa;">
            <div class="d-flex justify-content-between align-items-center">
              <h5 style="margin-bottom: 0;">Texto sugerido:</h5>
              <button type="button" class="btn btn-primary btn-sm" @click="copyOutput">
                <span v-if="!copiedToClipboard">Copiar texto</span>
                <span v-else>¡Texto copiado!</span>
                <i v-if="!copiedToClipboard" class="fas fa-copy ms-1"></i>
                <i v-else class="fas fa-check ms-1"></i>
              </button>
            </div>
            <hr style="margin-top: 5px; margin-bottom: 10px;">
            <div>
              <p ref="outputText" v-html="output" style="background-color: #ffffff; border-radius: 8px; padding: 10px; border: 1px solid #dfe1e5;"></p>
              <small class="text-muted mt-2 d-block">Puedes editar el texto generado según tus necesidades. ChatGPT puede cometer errores. Comprueba la información importante.</small>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
</template>



<script>
import axios from 'axios';
import {jwtDecode} from 'jwt-decode';

const wordLimitDirective = {
  beforeMount(el, binding) {
    el.addEventListener('input', () => {
      const text = el.value.trim();
      const words = text.split(/\s+/);
      if (words.length > binding.value) {
        el.value = words.slice(0, binding.value).join(' ');
        el.dispatchEvent(new Event('input'));
      }
    });
  }
};

export default {
  name: 'ChatGptComponent',
  directives: {
    wordLimit: wordLimitDirective
  },
  props: {
    type: {
      type: String,
      default: 'help' // Valor por defecto para 'type'
    }
  },
  data() {
    return {
      inputText: '',
      selectedOption: 'improve',
      output: '',
      showFormChat: false,
      copiedToClipboard: false,
      maxWord: 31

    }
  },
  methods: {
    toggleForm() {
      this.showFormChat = !this.showFormChat;
    },
  
    async copyOutput() {
      try {
        const outputElement = this.$refs.outputText;

        // Verifica si el elemento de salida está presente y tiene contenido
        if (outputElement && outputElement.innerText) {
          // Crea un elemento de texto temporal para la selección
          const tempTextarea = document.createElement('textarea');
          tempTextarea.value = outputElement.innerText;
          document.body.appendChild(tempTextarea);

          // Selecciona el texto dentro del elemento de texto temporal
          tempTextarea.select();
          tempTextarea.setSelectionRange(0, 99999); // Para dispositivos móviles

          // Intenta copiar el texto seleccionado
          const successful = document.execCommand('copy');
          document.body.removeChild(tempTextarea);

          if (successful) {
            this.copiedToClipboard = true;
            setTimeout(() => {
              this.copiedToClipboard = false;
            }, 3000); // Restablece el estado después de 3 segundos
          } else {
            throw new Error('No se pudo copiar el resultado.');
          }
        } else {
          throw new Error('No se encontró contenido para copiar. Asegúrese de haber procesado el texto primero.');
        }
      } catch (error) {
        console.error('Error al copiar el resultado:', error);
        this.$swal({
          icon: 'error',
          text: 'Ocurrió un error al intentar copiar el resultado. Por favor, inténtelo nuevamente.'
        });
      }
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
    async processText() {

      this.showLoadingGif("Generando contenido con inteligencia artificial...");

      if (!this.inputText) {
        this.$swal({
          icon: 'info',
          html: 'Por favor, ingrese un texto.'
        });
        return;
      }
      if (!this.selectedOption) {
        this.$swal({
          icon: 'info',
          html: 'Por favor, seleccione una opción.'
        });
        return;
      }

      // Muestra el mensaje de carga
      this.loading = true;
      let contenidoCodificado = encodeURIComponent(this.inputText);

      try {
        // Realiza la solicitud al servicio 'chatgpt'
        const response = await axios.get('chatgpt', {
          params: {
            type: this.selectedOption,
            query: btoa(contenidoCodificado) // Codifica el texto antes de enviarlo (dependiendo de tu requerimiento)
          }
        });

        // Cierra el estado de carga
        this.loading = false;
        (this.$swal).close();

        // Verifica si la respuesta tiene contenido válido
        if (response.data && response.data.content) {
          // Actualiza la variable 'output' con la respuesta recibida
          this.output = response.data.content; // Ajusta según la estructura de respuesta de tu servicio
        } else {
          // Si la respuesta no contiene contenido válido, muestra un mensaje de error
          throw new Error('La respuesta recibida no tiene contenido válido.');
        }
      } catch (error) {
        (this.$swal).close();

        // Cierra el estado de carga en caso de error
        this.loading = false;

        // Muestra un mensaje de error al usuario
        let errorMessage = 'Ocurrió un error al procesar el texto. Por favor, intente nuevamente.';
        if (error.response && error.response.data && error.response.data.error) {
          errorMessage = error.response.data.error; // Si hay un mensaje de error específico en la respuesta
        }
        this.$swal({
          icon: 'error',
          html: errorMessage
        });

        console.error("Error processing text:", error);
      }
    }

  },
  computed: {
    wordCount() {
      return this.inputText.trim().split(/\s+/).length;
    }
  }
}
</script>
<style scoped>
.tooltip-container {
  top: 50px; /* Ajusta la posición vertical según necesites */
  left: 0;
  right: 0;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 4px;
  padding: 15px;
  z-index: 1000; /* Asegura que esté por encima de otros elementos */
}

.tooltip-content {
  max-width: 700px; /* Ancho máximo del contenido */
  margin: auto; /* Centra el contenido horizontalmente */
}

.btn-floating {
  position: absolute;
  top: 20px; /* Ajusta la posición vertical según necesites */
  right: 20px; /* Ajusta la posición horizontal según necesites */
  z-index: 1000; /* Asegura que esté por encima de otros elementos */
}

.btn-floating .feather {
  vertical-align: middle;
}

textarea {
  resize: none;
}
</style>