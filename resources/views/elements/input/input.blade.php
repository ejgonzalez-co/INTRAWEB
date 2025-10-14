{{-- <div class="form-group row col-md-{{ $col_md ?? '12' }}" v-if="{{ $condicion ?? true }}">
   <label for="{{ $name ?? '' }}" class="col-sm-{{ $label_col_sm ?? '12' }}">{{ $titulo ?? '' }}</label>
   <div class="col-sm-{{ $input_col_sm ?? '12' }}">


      @if(!isset($v_model))
         <input type="{{ $type ?? 'text' }}" class="form-control @error($name) is-invalid @enderror"  aria-describedby="{{ $name ?? '' }}Help" placeholder="{{ $placeholder ?? '' }}" name="{{ $name ?? '' }}"  id="{{ $name ?? '' }}" value="{{ $value ?? '' }}" {{ $requerido ?? '' }} style="{{ $style ?? '' }}" v-if="{{ $condicion ?? '' }}">
      @else
         <input type="{{ $type ?? 'text' }}" class="form-control"  aria-describedby="{{ $name ?? '' }}Help" placeholder="{{ $placeholder ?? '' }}" name="{{ $name ?? '' }}" v-model="{{ $v_model }}"  id="{{ $name ?? '' }}" value="{{ $value ?? '' }}" max="{{ $max ?? '' }}" {{ $requerido ?? '' }} style="{{ $style ?? '' }}">
      @endif
      @error($name)
         <span class="invalid-feedback" role="alert">
               <strong>{{ $message }}</strong>
         </span>
      @enderror    
      <small id="{{ $name ?? '' }}Help" class="form-text text-muted">{{ $ayuda ?? '' }}</small>
    </div>
</div> --}}