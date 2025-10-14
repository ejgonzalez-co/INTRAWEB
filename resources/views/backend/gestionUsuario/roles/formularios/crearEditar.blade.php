@modalCrearEditar(['sizeModal'=>'lg','bgHeader'=>'bg-info','colorHeader'=>'text-white','idForm'=>'crearEditarRol'])
@input([
	'type' => 'text',
	'name' => 'name',
	'v_model' => 'datosRol.name',
	'titulo' => 'Nombre del permiso:*',
	'ayuda'=>'Ingrese el nombre del permiso',
	'placeholder'=>'Nombre',
	'requerido' => 'required'
])

@textarea([
	'name' => 'description',
	'v_model' => 'datosRol.description',
	'titulo' => 'Descripción:',
	'ayuda'=>'Ingrese una descripción',
	'placeholder'=>'Ingrese una descripción'
])

<hr>
<h6>Permiso especial</h6>
<div class="form-group">
	<label>{{ Form::radio('special', 'all-access') }} Acceso total</label>
	<label>{{ Form::radio('special', 'no-access') }} Ningún acceso</label>
</div>
<hr>
<h6>Permisos</h6>
<div class="form-group">
	<ul class="list-unstyled">
		<li v-for="permiso in listadoPermisos"  >
			<div class="form-check">
				<input v-model='permissions' class="form-check-input" name="permissions[]" type="checkbox" v-bind:value="permiso.id" v-bind:id="permiso.slug" :checked="retornarPermisosRoles">
				<label class="form-check-label" v-bind:for="permiso.slug">
					@{{ permiso.name }}
				</label>
				<em>(@{{ permiso.description }})</em>
			</div>
		</li>
	</ul>
</div>

@button([
	'type' => 'submit',
	'class' => 'primary',
	'fontawesome'=>'far fa-save',
	'v_text' => ' nombBtn',
])
@endmodalCrearEditar