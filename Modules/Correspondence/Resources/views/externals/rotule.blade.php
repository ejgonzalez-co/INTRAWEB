<div class="row">
    <div class="alert alert-success w-100" role="alert">
        Si desea editar la información que radicó, por favor cierre esta ventana y haga clic en la opción de editar en el listado.
    </div>
    <div class="mb-2 mr-3 ml-3">
        <button id="btn-rotule-print-rotule" class="btn btn-warning" type="button" onclick="printContent('page-rotule');">
            <i class="fa fa-print mr-2"></i>1. Imprimir</button>

    </div>
    <div class="mb-2" id="attach-rotule">
        <label for="document_pdf" class="btn btn-info" style="width: 100%; cursor: pointer;">
            <i class="fa fa-paperclip mr-2"></i>2. Adjuntar
        </label>
        <input type="file" name="document_pdf" id="document_pdf"
             @change="adjuntarDocumento($event)"  ref="adjuntarRef"
            class="btn btn-info" style="display: none;">
    </div>
</div>
<div id="page-rotule"
    style="width:28cm; height:35cm; position:relative; border:#000 solid 1px;  background-repeat:repeat;" class="list">
    

    <vue-drag-resize  :is-resizable="false" :is-draggable="true" :parent-w="1062"  :parent-h="1322" :w="232"  :x="815" :y="10" :parent-limitation="true">
      
        <div id="content-rotule" class="ui-widget-content"
      style="width: 220px; height: auto; border: #000 solid 1px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding: 5px; background-color: #CCC; cursor: pointer; position: absolute; right: 8px;">
 

      <div
          style="width:100%; height:auto; font-family:Arial; text-align:center; font-weight:bold; border-bottom:#000 solid 1px; font-size:11px;">
          {{ config('app.name') }}</div>
      <div
          style="width:100%; height:auto; font-family:Arial; text-align:center; font-weight:bold; border-bottom:#000 solid 1px; font-size:11px;">
          Correspondencia Enviada <br />@{{ dataRotule.consecutive }}</div>
        <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Funcionario radicador:&nbsp;
        <strong>
            @{{ dataRotule.users_name }} 
        </strong>
        </div>
      <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Funcionario Remitente:&nbsp;
          <strong>
             @{{ dataRotule.from }} 
          </strong>
      </div>
      <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Fecha:&nbsp;
          <strong>@{{ dataRotule.created_at }}</strong>
      </div>

      <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Asunto:&nbsp;
          <strong>
             @{{ dataRotule.title }}
          </strong>
      </div>
      <!-- <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Folios:&nbsp; <strong>{folios}</strong></div> -->


      <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Anexos:&nbsp;
          <strong>@{{ dataRotule.annexes_description }}</strong>
      </div>

      <!--los destinatarios que apareceran segun sean los escogidos-->
      <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Ciudadano:&nbsp; 
        <strong v-if="dataRotule.citizens?.length > 1">@{{ dataRotule.citizens[0]["citizen_name"] }} y otros</strong>
        <strong v-else >@{{ dataRotule.citizens ? dataRotule.citizens[0]["citizen_name"] : "" }}</strong>
      </div>
      
      <div style="font-family:Arial; font-size:11px; width:100%;height:auto;">&nbsp;Código de validación:&nbsp;
            <strong>@{{ dataRotule.validation_code ?? '' }}</strong>
      </div>


  </div>

  
      </vue-drag-resize>



</div>
