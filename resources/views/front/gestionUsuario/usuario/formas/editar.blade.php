<fieldset>
   <legend>Información general</legend>

   @input([
      'type' => 'text',
      'name' => 'nombre',
      'value'=> $usuario->nombre,
      'titulo' => 'Nombre:*',
      'ayuda'=>'Ingrese el nombre del usuario',
      'placeholder'=>'Nombre',
      'label_col_sm'=> '2',
      'input_col_sm'=> '10',
      'col_md'=> '12',
      'requerido' => 'required'
   ])


   @input([
      'type' => 'text',
      'name' => 'username',
      'value'=> $usuario->username,
      'titulo' => 'Usuario:*',
      'ayuda'=>'Ingrese el usuario',
      'placeholder'=>'Usuario',
      'label_col_sm'=> '2',
      'input_col_sm'=> '10',
      'col_md'=> '12',
      'requerido' => 'required'
   ])

   @input([
      'type' => 'email',
      'name' => 'email',
      'value'=> $usuario->email,
      'titulo' => 'Correo electrónico:*',
      'ayuda'=>'Ingrese el correo electrónico',
      'placeholder'=>'Correo electrónico',
      'label_col_sm'=> '2',
      'input_col_sm'=> '10',
      'col_md'=> '12',
      'requerido' => 'required'
   ])

   <div class="form-group row col-md-12">
      <div class="custom-control custom-radio">
         <input value="Activo" {{ ($usuario->estado=="Activo") ? "checked" : "" }} type="radio" id="estado" name="estado" class="custom-control-input">
         <label class="custom-control-label" for="estado">Estado activo</label>
      </div>
      <div class="custom-control custom-radio">
         <input value="Inactivo" {{ ($usuario->estado=="Inactivo") ? "checked" : "" }} type="radio" id="estado2" name="estado" class="custom-control-input">
         <label class="custom-control-label" for="estado2">Estado inactivo</label>
      </div>
   </div>
</fieldset>
<br>
<fieldset>
   <legend>Roles para asignar o asignados</legend>

   <div class="form-group">
   <ul class="list-unstyled">
      @foreach($roles as $role)
         <li>
            <label>
            {{ Form::checkbox('roles[]', $role->id, null) }}
            {{ $role->name }}
            <em>({{ $role->description }})</em>
            </label>
         </li>
         @endforeach
      </ul>
   </div>
</fieldset>
<br>
<div class="form-group">
	{{ Form::submit('Guardar', ['class' => 'btn btn-sm btn-primary']) }}
</div>