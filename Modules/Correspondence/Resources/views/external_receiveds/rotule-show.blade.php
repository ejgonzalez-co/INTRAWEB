<div class="row">
    <div class="alert alert-success w-100" role="alert">
        Si desea editar la información que radicó, por favor cierre esta ventana y haga clic en la opción de editar en el listado.
    </div>
    <div class="mb-2 mr-3 ml-2" id="attach">
        <label for="document_pdf" class="btn btn-info" style="width: 100%; cursor: pointer;">
            </i>1. Adjuntar documento principal
        </label>
        <input type="file" name="document_pdf" id="document_pdf"
            @change="callFunctionComponent('received_ref', 'previewDocument', $event)" ref="adjuntarRef"
            class="btn btn-info" style="display: none;" accept=".pdf">
    </div>

    <div class="mb-2 mr-3" v-if="$refs.received_ref.dataForm.document_pdf" @click="callFunctionComponent('received_ref', 'saveDocumentWithRotule', $event)" >
        <label  class="btn btn-info" style="width: 100%; cursor: pointer;">
            </i>2. Rotular y guardar documento
        </label>
    </div>

    {{-- <p>@{{ $refs.received_ref.dataForm.document_pdf }} </p> --}}
    <div class="mb-2">
        <button id="btn-rotule-print" class="btn btn-warning" type="button" onclick="printContent('page');">
            <i class="fa fa-print mr-2"></i> Imprimir rótulo en papel
        </button>
    </div>

    <div class="mb-2 ml-3">
        <select name="" id="" class="form-control" v-model="dataForm.document_type">
            <option value="Carta">Carta</option>
            <option value="Oficio">Oficio</option>
        </select>
    </div>
</div>

<div id="page" class="page-rotule-show" v-if="dataForm.document_type == 'Carta'"
:style="'width: 216mm; height: 279mm; position:relative; border:rgb(0,0,0) solid 1px;background-size:contain;  background-repeat:no-repeat; background-image: url(\'' + dataShow.pdf_previous + '\')'"
class="list">

    <vue-drag-resize ref="vueDragResize" :is-resizable="false" :is-draggable="true"  :parent-w="782" :parent-h="1100" :w="232" :x="575" :y="10" :parent-limitation="true">
    
        <div id="content-rotule" class="ui-widget-content" style="width: 220px; height: auto; border: #000 solid 1px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding: 5px; background-color: #CCC; cursor: pointer; position: absolute; right: 8px;">

            <div style="width:100%; height:auto; font-family:Arial; text-align:center; font-weight:bold; border-bottom:#000 solid 1px; font-size:11px;">
                {{ config('app.name') }}
            </div>

            <div style="width:100%; height:auto; font-family:Arial; text-align:center; font-weight:bold; border-bottom:#000 solid 1px; font-size:11px;">
                Correspondencia recibida <br />@{{ dataShow.consecutive }}
            </div>
            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Ciudadano:&nbsp;</b>
                @{{ dataShow.citizen_name }} 
            </div>
            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Fecha:&nbsp;</b>
                @{{ dataShow.created_at }}
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Asunto:&nbsp;</b>
                <span {{-- v-if="dataShow.issue && dataShow.issue.trim() !== ''" --}}>@{{ shortText(dataShow.issue).text }}</span>
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Anexos:&nbsp;</b>
                @{{ dataShow.annexed }}
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Destinatario:&nbsp;</b>
                @{{ dataShow.functionary_name }}
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;PQRS asociado:&nbsp;</b>
                <span v-if="dataShow.pqr">@{{ dataShow.pqr }}</span>
                <span v-else>No aplica</span>
            </div>
        </div>
    </vue-drag-resize>
</div>

<div id="page"
    v-else
    :style="'width: 216mm; height: 356mm; position:relative; border:rgb(0,0,0) solid 1px;background-size:contain;  background-repeat:no-repeat; background-image: url(\'' + dataShow.pdf_previous + '\')'"
    class="list">
    <vue-drag-resize ref="vueDragResize" :is-resizable="false" :is-draggable="true"  :parent-w="782" :parent-h="1100" :w="232" :x="575" :y="10" :parent-limitation="true">
    
        <div id="content-rotule" class="ui-widget-content" style="width: 220px; height: auto; border: #000 solid 1px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding: 5px; background-color: #CCC; cursor: pointer; position: absolute; right: 8px;">

            <div style="width:100%; height:auto; font-family:Arial; text-align:center; font-weight:bold; border-bottom:#000 solid 1px; font-size:11px;">
                {{ config('app.name') }}
            </div>

            <div style="width:100%; height:auto; font-family:Arial; text-align:center; font-weight:bold; border-bottom:#000 solid 1px; font-size:11px;">
                Correspondencia recibida <br />@{{ dataShow.consecutive }}
            </div>
            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Ciudadano:&nbsp;</b>
                @{{ dataShow.citizen_name }} 
            </div>
            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Fecha:&nbsp;</b>
                @{{ dataShow.created_at }}
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Asunto:&nbsp;</b>
                <span {{-- v-if="dataShow.issue && dataShow.issue.trim() !== ''" --}}>@{{ shortText(dataShow.issue).text }}</span>
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Anexos:&nbsp;</b>
                @{{ dataShow.annexed }}
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;Destinatario:&nbsp;</b>
                @{{ dataShow.functionary_name }}
            </div>

            <div style="font-family:Arial; font-size:11px; width:100%;height:auto;"><b>&nbsp;PQRS asociado:&nbsp;</b>
                <span v-if="dataShow.pqr">@{{ dataShow.pqr }}</span>
                <span v-else>No aplica</span>
            </div>
        </div>
    </vue-drag-resize>
</div>

<br>
<viewer-attachement :link-file-name="true" :list="dataShow.document_pdf" :key="dataShow.document_pdf" :name="dataShow.consecutive"></viewer-attachement>