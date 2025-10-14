<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Información inicial</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="form-group row m-b-15">
         {!! Form::label('tipo_documento', 'Tipo de documento'.':', ['class' => 'col-form-label col-md-3 required']) !!}
         <div class="col-md-9">
             {!! Form::text('tipo_documento', null, ['class' => 'form-control', 'v-model' => 'dataForm.tipo_documento', 'required' => true]) !!}
             <small>Ingrese el Tipo de documento.</small>
         </div>
     </div>
      <div class="form-group row m-b-15">
         {!! Form::label(
               'estado',
               'Estado' . ':',
               ['class' => 'col-form-label col-md-3 required'],
         ) !!}
         <div class="col-md-9">
               <select class="form-control" name="estado"
                  id="estado" v-model="dataForm.estado" required>
                  <option value="Activo">Activo</option>
                  <option value="Inactivo">Inactivo</option>
               </select>
               <small>@lang('Select the') el estado del expediente</small>
               <div class="invalid-feedback" v-if="dataErrors.estado">
                  <p class="m-b-0" v-for="error in dataErrors.estado">
                     @{{ error }}</p>
               </div>
         </div>
      </div>
   </div>
</div>