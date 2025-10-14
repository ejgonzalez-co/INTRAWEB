<div class="form-group row col-md-{{ $col_md ?? '12' }}">
   <label for="{{ $name ?? '' }}" class="col-sm-{{ $label_col_sm ?? '12' }}">{{ $titulo ?? '' }}</label>
   <div class="col-sm-{{ $input_col_sm ?? '12' }}">

      
      <input type="file" name="file_{{ $name ?? '' }}"  @change="{{ $adjuntar ?? 'adjuntarActa' }}" id="file_{{ $name ?? '' }}" {{ $requerido ?? '' }} ref="fileInput" multiple>
      <input type="hidden" class="form-control" aria-describedby="{{ $name ?? '' }}" name="{{ $name ?? '' }}[]" id="{{ $name ?? '' }}" {{ $requerido ?? '' }}>

      <em v-if="{{$v_model}}" id="datosAdjunto_{{ $name ?? '' }}">
         <a  :href="'adjunto/'+{{ $v_model ?? '' }}" v-text="{{ $v_model ?? '' }}" target="_blank">
         </a>&nbsp;&nbsp;<a v-on:click="eliminarAdjunto({{ $v_model }}, '{{ $name}}', {{ $idRegistro ?? '\'\''}}, '{{ $funcionEjecutar ?? ''}}')" href="#" class="badge badge-danger"><i class="fa fa-trash"></i> Eliminar</a>
      </em>
   </div>
</div>