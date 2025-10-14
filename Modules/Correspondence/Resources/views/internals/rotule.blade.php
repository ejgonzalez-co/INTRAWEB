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
    {{-- <button id="btn-rotule-print-rotule" class="btn btn-warning" type="button" onclick="printContent('page-rotule');"><i class="fa fa-print mr-2"></i>1. Imprimir</button>
    <button id="attach" class="btn btn-info" type="button" style="width: 118px; height: 35px;">
        <i class="fa fa-paperclip mr-2"></i>2. Adjuntar
        <input type="file" name="document_pdf" @change="adjuntarDocumento($event)" ref="adjuntarRef" class="btn btn-info" style="margin-top: -26px; margin-left: -13px; width: 117px; float: left; opacity: 0; height: 32px;">
    </button> --}}


    <vue-drag-resize :is-resizable="false" :is-draggable="true" :parent-w="1062" :parent-h="1322" :w="232" :x="815" :y="10" :parent-limitation="true">
        <div id="content-rotule" class="ui-widget-content" style="width: 220px; border: #000 solid 1px; border-radius: 10px; padding: 5px; background-color: #CCC; cursor: pointer; position: absolute; right: 8px;">
    
            <div style="font-family:Arial; font-size:11px; text-align:center; font-weight:bold; border-bottom:#000 solid 1px;">
                {{ config('app.name') }}
            </div>
    
            <div style="font-family:Arial; font-size:11px; text-align:center; font-weight:bold; border-bottom:#000 solid 1px;">
                Correspondencia Interna <br>@{{ dataRotule.consecutive }}
            </div>

            <div style="font-family:Arial; font-size:11px;">
                Funcionario Radicador: <strong>@{{ dataRotule.users_name }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Funcionario Remitente: <strong>@{{ dataRotule.from }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Fecha: <strong>@{{ dataRotule.created_at }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Asunto: <strong>@{{ dataRotule.title }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Anexos: <strong>@{{ dataRotule.annexes }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Destinatarios: <br><strong v-html='dataRotule.recipients'></strong>
            </div>
    
        </div>
        
    </vue-drag-resize>
    



</div>
