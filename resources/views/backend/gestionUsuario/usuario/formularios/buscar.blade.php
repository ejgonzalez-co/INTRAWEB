<form id="buscarUsuarios" autocomplete="off" v-on:submit.prevent="listarUsuarios()">
@filtros(['buscarId' => 'buscarUsuario'])

   @select_array([
      'name' => 'tipoUsuario',
      'titulo' => 'Tipo de usuario:',
      'label_col_sm'=> '2',
      'select_col_sm'=> '10',
      'col_md'=> '12'
      ])
      <option value="">Seleccione</option>
      <option value="Funcionario">Funcionario</option>
      <option value="Ciudadano">Ciudadano</option>
   @endselect_array

  

      @input([
         'type' => 'text',
         'name' => 'name',
         'titulo' => 'Nombre:',
         'ayuda'=>'Ingrese el nombre de la persona',
         'label_col_sm'=> '2',
         'input_col_sm'=> '10',
         'col_md'=> '12'
      ])

      @input([
         'type' => 'text',
         'name' => 'username',
         'titulo' => 'Usuario:',
         'ayuda'=>'Ingrese el usuario',
         'label_col_sm'=> '2',
         'input_col_sm'=> '10',
         'col_md'=> '12'
      ])

      @input([
         'type' => 'email',
         'name' => 'email',
         'titulo' => 'Correo electrónico:',
         'ayuda'=>'Ingrese el correo electrónico',
         'label_col_sm'=> '2',
         'input_col_sm'=> '10',
         'col_md'=> '12'
      ])
      
	   @btnBuscar(['limpiarCampos' => 'limpiarCamposUsuarios()'])

@endfiltros
</form>
