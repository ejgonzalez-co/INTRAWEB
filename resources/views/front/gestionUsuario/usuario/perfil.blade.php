@contenidoFront
<br>
@include('alerts.warning')
@include('alerts.success')
@include('alerts.error')
<div class="card">
   <div class="card-body">
      <h6 style="padding: 20px; border-radius: 5px;">
         <strong>Configuración general de la cuenta</strong>
      </h6>
      <hr>
      {!! Form::model(Auth::user(), ['route' => ['actualizar-perfil', Auth::user()->id], 'method' => 'PUT', 'files' => true, 'id' =>'perfil']) !!}
      <fieldset>
         <legend><h6><strong>Información general</strong></h6></legend>
         <div class="row">
            <div class="form-group row col-md-6">
               <label for="nombre" class="col-sm-4">Username:</label>
               <div class="col-sm-8">
                  <label disabled class="col-form-label">{{ Auth::user()->username}}</label>
               </div>
            </div>
         </div>
         <div class="row">
         
            @input([
               'type' => 'text',
               'name' => 'nombre',
               'titulo' => 'Nombre:*',
               'value'=>  Auth::user()->nombre,
               'ayuda'=>'Ingrese el nombre',
               'label_col_sm'=>'4',
               'input_col_sm'=>'8',
               'col_md'=>'6',
            ])

         </div>
         <div class="row">
            

            @input([
               'type' => 'email',
               'name' => 'email',
               'titulo' => 'Correo electrónico:*',
               'value'=>  Auth::user()->email,
               'ayuda'=>'Ingrese el correo electrónico',
               'label_col_sm'=>'4',
               'input_col_sm'=>'8',
               'col_md'=>'6',
               'requerido' => 'required'
            ])

            @input([
               'type' => 'email',
               'name' => 'email_confirmation',
               'titulo' => 'Confirmación del correo electrónico:',
               'ayuda'=>'Ingrese la confirmación del correo electrónico',
               'label_col_sm'=>'4',
               'input_col_sm'=>'8',
               'col_md'=>'6',
            ])

            @input([
               'type' => 'password',
               'name' => 'password',
               'titulo' => 'Password:',
               'ayuda'=>'Ingrese la contraseña',
               'label_col_sm'=>'4',
               'input_col_sm'=>'8',
               'col_md'=>'6',
            ])

            
            @input([
               'type' => 'password',
               'name' => 'password_confirmation',
               'titulo' => 'Confirmar contraseña:',
               'ayuda'=>'Ingrese la contraseña',
               'label_col_sm'=>'4',
               'input_col_sm'=>'8',
               'col_md'=>'6',
            ])
         </div>
      </fieldset>
      <br>
      @button([
         'type' => 'submit',
         'class' => 'primary',
         'text'=> 'Guardar',
         'fontawesome'=>'fas fa-save'
      ])
      {!! Form::close() !!}
   </div>
</div>
@endcontenidoFront