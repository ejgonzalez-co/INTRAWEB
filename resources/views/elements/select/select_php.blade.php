<div class="form-group row col-md-{{ $col_md ?? '12' }}" id="div_{{ $name ?? '' }}">
   <label class="col-sm-{{ $label_col_sm ?? '12' }}" for="{{ $name ?? '' }}">{{ $titulo ?? '' }}</label>
   <div class="col-sm-{{ $select_col_sm ?? '12' }}">
      <?php

         if (isset($option)) {
            
            $options = explode(",", $option);

            $opciones;

            foreach ( $options as $key => $opcion) {
               $opciones[$opcion] = $opcion;
            }
         }else{
            $opciones[] = ""; 
         }
      ?>
      {!! Form::select($name ?? '',
      $opciones,
      $value ?? '',
      ['class' =>  $class ?? 'custom-select',
      'placeholder' => 'Seleccione',
      'id' => $name ?? '',
      'required' => $requerido ?? false
      ]) !!}
          
      <small id="{{ $name ?? '' }}Help" class="form-text text-muted">{{ $ayuda ?? '' }}</small>
   </div>
</div>