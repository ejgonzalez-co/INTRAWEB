<div class="form-group col-md-6">
   <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
   
   <button type="button" class="btn btn-success" @click="{{ $limpiarCampos ?? 'limpiarCampos()' }}" ><i class="fas fa-minus"></i> Limpiar los campos</button>
</div>