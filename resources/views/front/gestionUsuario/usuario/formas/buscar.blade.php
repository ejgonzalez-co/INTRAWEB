<form id="buscarUsuarios" autocomplete="off" v-on:submit.prevent="listarUsuarios()">
@filtros(['buscarId' => 'buscarUsuario'])
  

      @input([
         'type' => 'text',
         'name' => 'nombreBuscar',
         'titulo' => 'Nombre:',
         'ayuda'=>'Ingrese el nombre de la persona',
         'label_col_sm'=> '2',
         'input_col_sm'=> '10',
         'col_md'=> '12'
      ])

      @input([
         'type' => 'text',
         'name' => 'usernameBusacar',
         'titulo' => 'Usuario:',
         'ayuda'=>'Ingrese el usuario',
         'label_col_sm'=> '2',
         'input_col_sm'=> '10',
         'col_md'=> '12'
      ])

      @input([
         'type' => 'email',
         'name' => 'emailBuscar',
         'titulo' => 'Correo electrónico:',
         'ayuda'=>'Ingrese el correo electrónico',
         'label_col_sm'=> '2',
         'input_col_sm'=> '10',
         'col_md'=> '12'
      ])
      
	   @btnBuscar(['limpiarCampos' => 'limpiarCamposUsuarios()'])

@endfiltros
</form>
