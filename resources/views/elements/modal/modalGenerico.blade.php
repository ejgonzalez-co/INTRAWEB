<div class="modal fade" data-keyboard="false" data-backdrop="static" ref="{{ $modalId ?? ''}}" id="{{ $modalId ?? ''}}" tabindex="-1" role="dialog" aria-labelledby="createCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-{{ $sizeModal ?? 'dialog'}}" role="document">
      <div class="modal-content">
         <div class="modal-header {{ $colorHeader ?? '' }} {{ $bgHeader ?? '' }}">
            <h5 class="modal-title" id="createCenterTitle"  v-text="tituloModal"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            {{ $slot}}
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
         </div>
      </div>
   </div>
</div>