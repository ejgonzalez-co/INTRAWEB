@modalCrearEditar(['sizeModal'=>'xl','bgHeader'=>'bg-info','colorHeader'=>'text-white','idForm'=>'crearEditarUsuario'])

<fieldset>
	<legend> Datos del usuario</legend>
	<div class="row">

      @select([
         'name' => 'dependencia_id',
         'titulo' => 'Dependencia:',
         'label_col_sm'=> '3',
         'select_col_sm'=> '9',
         'col_md'=> '6',
         'requerido' => 'required',
         'ayuda'=>'Seleccione la dependencia que pertenece el usuario',
      ])
         <option value="">Seleccione</option>
         @foreach($dependencias as $dependencia)
         <option value="{{ $dependencia->id}}">{{ $dependencia->nombre}}</option>
         @endforeach
      @endselect

      @input([
         'type' => 'text',
         'name' => 'nombre',
         'titulo' => 'Nombre:*',
         'ayuda'=>'Ingrese el nombre',
         'label_col_sm'=>'3',
         'input_col_sm'=>'9',
         'col_md'=>'6',
         'requerido' => 'required'
      ])

      @input([
         'type' => 'text',
         'name' => 'username',
         'titulo' => 'Username:*',
         'ayuda'=>'Ingrese el username',
         'label_col_sm'=>'3',
         'input_col_sm'=>'9',
         'col_md'=>'6',
         'requerido' => 'required'
      ])

      @input([
         'type' => 'email',
         'name' => 'email',
         'titulo' => 'Correo electrónico:*',
         'ayuda'=>'Ingrese el correo electrónico',
         'label_col_sm'=>'3',
         'input_col_sm'=>'9',
         'col_md'=>'6',
         'requerido' => 'required'
      ])

      @input([
         'type' => 'password',
         'name' => 'password',
         'titulo' => 'Password:*',
         'ayuda'=>'Ingrese la contraseña',
         'label_col_sm'=>'3',
         'input_col_sm'=>'9',
         'col_md'=>'6',
         'requerido' => 'required'
      ])

      @select([
         'name' => 'estado',
         'titulo' => 'Estado:',
         'label_col_sm'=> '3',
         'select_col_sm'=> '9',
         'col_md'=> '6',
         'requerido' => 'required',
         'ayuda'=>'Seleccione el estado del usuario',
      ])
         <option value="">Seleccione</option>
         <option value="Activo">Activo</option>
         <option value="Inactivo">Inactivo</option>
      @endselect
	</div>
</fieldset>
<br>
<fieldset>
   <legend>Roles asignados</legend>

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
@button([
	'type' => 'submit',
	'class' => 'primary',
	'text'=> 'Guardar',
	'fontawesome'=>'fas fa-save'
])
@endmodalCrearEditar