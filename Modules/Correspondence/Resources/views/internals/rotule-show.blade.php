@php
$ultima_configuracion = DB::table('configuration_general')->latest()->first();
@endphp

<div class="row">
    <div class="alert alert-success w-100" role="alert">
        Si desea editar la información que radicó, por favor cierre esta ventana y haga clic en la opción de editar en el listado.
    </div>
    <div class="mb-2 mr-3 ml-3">
        <button id="btn-rotule-print" class="btn btn-warning" type="button" onclick="printContent('page');">
            <i class="fa fa-print mr-2"></i>1. Imprimir
        </button>
    </div>
    <div class="mb-2" id="attach">
        <label for="document_pdf" class="btn btn-info" style="width: 100%; cursor: pointer;">
            <i class="fa fa-paperclip mr-2"></i>2. Adjuntar
        </label>
        <input type="file" name="document_pdf" id="document_pdf"
            @change="callFunctionComponent('internal_ref', 'adjuntarDocumento', $event)" ref="adjuntarRef"
            class="btn btn-info" style="display: none;">
    </div>
</div>

<div id="page" style="width:28cm; height:35cm; position:relative; border:#000 solid 1px; background-repeat:repeat;" class="list">
   
    <vue-drag-resize :is-resizable="false" :is-draggable="true" :parent-w="1062" :parent-h="1322" :w="232" :x="815" :y="10" :parent-limitation="true">

        <div id="content-rotule" class="ui-widget-content" style="margin-top: 5px; width: 220px; border: 1px solid #000; border-radius: 10px; padding: 5px; background-color: #CCC; cursor: pointer; position: absolute; right: 8px;">

            <div style="font-family:Arial; font-size:11px; text-align:center; font-weight:bold; border-bottom:#000 solid 1px;">
                {{ config('app.name') }}
            </div>
    
            <div style="font-family:Arial; font-size:11px; text-align:center; font-weight:bold; border-bottom:#000 solid 1px;">
                Correspondencia Interna <br>@{{ dataShow.consecutive }}
            </div>

            <div style="font-family:Arial; font-size:11px;">
                Funcionario Radicador: <strong>@{{ dataShow.users_name }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Funcionario Remitente: <strong>@{{ dataShow.from }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Fecha: <strong>@{{ dataShow.created_at }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Asunto: <strong>@{{ dataShow.title }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Anexos: <strong>@{{ dataShow.annexes_description }}</strong>
            </div>
    
            <div style="font-family:Arial; font-size:11px;">
                Destinatarios: <br><strong v-html='dataShow.recipients'></strong>
            </div>
        
        </div>
        
    </vue-drag-resize>

</div>