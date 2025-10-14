<div class="form-group row col-md-{{ $col_md ?? '12' }}">
   <label for="{{ $name ?? '' }}" class="col-sm-{{ $label_col_sm ?? '12' }}">{{ $titulo ?? '' }}</label>
   <div class="col-sm-{{ $input_col_sm ?? '12' }}">
      @if(!isset($v_model))
      <textarea class="form-control" id="{{ $name ?? '' }}" rows="3" name="{{ $name ?? '' }}" placeholder="{{ $placeholder ?? '' }}" {{ $requerido ?? '' }}  data-bv-trigger="change keyup"></textarea>
      @else
      <textarea class="form-control" id="{{ $name ?? '' }}" rows="3" name="{{ $name ?? '' }}" placeholder="{{ $placeholder ?? '' }}" v-model="{{ $v_model }}" {{ $requerido ?? '' }}  data-bv-trigger="change keyup"></textarea>
      @endif
      @error($name)
         <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
         </span>
      @enderror  
      <small id="{{ $name ?? '' }}Help" class="form-text text-muted">{{ $ayuda ?? '' }}</small>
   </div>
</div>