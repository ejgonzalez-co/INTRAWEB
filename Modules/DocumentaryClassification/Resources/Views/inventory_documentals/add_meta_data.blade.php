<div class="row">
   <div class="col-md-9">
      <div class="panel">
         <div class="panel-heading">
            <h4 class="panel-title"><strong>1. Selecciona un documento</strong></h4>
            <button class="btn" data-toggle="tooltip" data-placement="left" title="Metadatos para navegar sobre la información del documento. Los metadatos son palabras clave que permiten encontrar información de manera eficiente en documentos digitalizados. ¿Cómo funciona? Ingrese una palabra clave en el campo 'Item de información' y asocie el número de página donde se encuentra." disabled>
               <i class="fas fa-question-circle"></i>
            </button>
         </div>

         <div>
            <div class="form-group col-md-12">
               <label class="required">Documento:</label>
               <div>
                  <select class="form-control" v-model="dataForm.selectedAttachment" @change="displayAttachment">
                     <option v-for="(attachment, index) in dataForm.attachment" :value="attachment">@{{ getFileName(attachment) }}</option>
                  </select>
               </div>
               <small class="text-muted">Seleccione el documento al que desea agregar metadatos. Una vez seleccionado, el documento se cargará automáticamente a continuación. Luego, en el panel derecho, podrá agregar metadatos al documento.</small>
            </div>

            <iframe id="attachmentFrame" width="100%" height="400" frameborder="0" src=""></iframe>
         </div>
      </div>
   </div>

   <div class="col-md-3">
      <div class="panel" v-if="dataForm.selectedAttachment">
         <div class="panel-heading">
            <h4 class="panel-title"><strong>2. Agrega Metadatos al documento</strong></h4>
         </div>

         <div class="panel-content">
            <div class="form-group col-md-12">
               <label class="required">Item de información:</label>
               <div>
                  <input type="text" class="form-control" v-model="dataForm.information">
               </div>
               <small class="text-muted">Ingrese una palabra clave para describir la información. Por ejemplo, si está buscando una Minuta, escriba "Minuta".</small>
            </div>
            
            <div class="form-group col-md-12">
               <label class="required">Número de página:</label>
               <div>
                  <input type="text" class="form-control" v-model="dataForm.page">
               </div>
               <small class="text-muted">Ingrese el número de página donde se encuentra la información. Por ejemplo, si la Minuta está en la página 2 del documento, escriba "2". Esto indica que la Minuta se encuentra en la página 2 del documento.</small>
            </div>
            

            <div class="form-group col-md-12 pb-3">
               <div>
                  <button @click="addMetadata()" type="button" class="btn btn-primary">3. Agregar metadato a la lista <i class="fa fa-arrow-down"></i></button>
               </div>
            </div>
         </div>
      </div>
      <div v-else>
         <p class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Seleccione un documento para continuar
         </p>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-12">
      <div v-if="dataForm.metadatos" class="metadata-table">
         <h3>Listado de metadatos:</h3>
         <table class="table">
            <thead>
               <tr>
                  <th>Archivo</th>
                  <th>Item y Páginas</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(metadata, attachment) in dataForm.metadatos" :key="attachment">
                  <td>@{{ getFileName(attachment) }}</td>
                  <td>
                     <table class="w-100">
                        <thead>
                           <tr>
                              <th style="width: 33%;">Item</th>
                              <th style="width: 33%;">Página</th>
                              <th style="width: 33%;">Eliminar</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-for="(meta, metaIndex) in metadata" :key="metaIndex">
                              <td>@{{ meta.item }}</td>
                              <td>@{{ meta.pagina }}</td>
                              <td>
                                 <button @click="removeMetadata(attachment, metaIndex)" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Borrar">
                                    <i class="fa fa-trash"></i>
                                 </button>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
      <div v-else>
         <p class="alert alert-warning">Aún no hay metadatos</p>
      </div>
   </div>
</div>
