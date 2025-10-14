<div class="form-group row col-md-{{ $col_md ?? '12' }}">
   <label class="col-sm-{{ $label_col_sm ?? '12' }}" for="{{ $name ?? '' }}">{{ $titulo ?? '' }}</label>
   <div class="col-sm-{{ $select_col_sm ?? '12' }}">

      @if(!isset($v_model))
      <select id="{{ $name ?? '' }}" name="{{ $name ?? '' }}" class="{{ $class ?? 'custom-select' }}" {{ $requerido ?? '' }} @change="{{ $change ?? '' }}">
       {{ $slot}}
      </select>

      @else
      <select id="{{ $name ?? '' }}" name="{{ $name ?? '' }}" v-model="{{ $v_model }}" class="{{ $class ?? 'custom-select' }}" {{ $requerido ?? '' }} placeholder @change="{{ $change ?? '' }}">
      {{ $slot}}
      </select>
      @endif      
      <small id="{{ $name ?? '' }}Help" class="form-text text-muted">{{ $ayuda ?? '' }}</small>
   </div>	
</div>