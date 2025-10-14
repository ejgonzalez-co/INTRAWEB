<div class="form-group row col-md-{{ $col_md ?? '12' }}">
   <label for="{{ $name ?? '' }}" class="col-sm-{{ $label_col_sm ?? '12' }}">{{ $titulo ?? '' }}</label>
   <div class="col-sm-{{ $input_col_sm ?? '12' }}">
      <select ref="select" name="{{ $name ?? '' }}" name="{{ $name ?? '' }}" class="form-control selectpicker" title="{{ $tituloSelect ?? '' }}"  data-live-search="true" v-model="{{ $v_model ?? 'n_a' }}">
         {{ $slot }}
      </select>
      <small id="{{ $name ?? '' }}Help" class="form-text text-muted">{{ $ayuda ?? '' }}</small>
    </div>
</div>