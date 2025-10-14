@modalCrearEditar(['sizeModal'=>'lg','bgHeader'=>'bg-info','colorHeader'=>'text-white','idForm'=>'crearEditarPermiso'])
	@input([
		'type' => 'text',
		'name' => 'name',
		'v_model' => 'datosPermisos.name',
		'titulo' => 'Nombre del permiso:*',
		'ayuda'=>'Ingrese el nombre del permiso',
		'placeholder'=>'Nombre',
		'requerido' => 'required'
	])

	@input([
		'type' => 'text',
		'name' => 'slug',
		'v_model' => 'datosPermisos.slug',
		'titulo' => 'URL Amigable:',
		'ayuda'=>'Ingrese la url amigable',
		'placeholder'=>'Slug',
		'requerido' => 'required'
	])

   @textarea([
		'name' => 'description',
		'v_model' => 'datosPermisos.description',
		'titulo' => 'Descripción:',
		'ayuda'=>'Ingrese una descripción',
		'placeholder'=>'Ingrese una descripción'
	])

	@button([
		'type' => 'submit',
		'class' => 'primary',
		'fontawesome'=>'far fa-save',
		'v_text' => ' nombBtn',
	])
@endmodalCrearEditar