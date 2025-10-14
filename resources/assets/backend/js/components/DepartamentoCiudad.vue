<template>
   <div class="form-group row">
		<label for="departamento" class="col-sm-3 col-form-label">Departamento: *</label>
		<div class="col-sm-9">
			<select class="form-control" id="departamento" name="departamento" required autofocus>
				<option value="">Seleccione</option>
				@foreach($departamentos as $departamento)
					<option value="{{$departamento->id}}" {{(old('departamento') == $departamento->id ? "selected":"") }}>{{ $departamento->nombreDpto }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label for="ciudad" class="col-sm-3 col-form-label">Ciudad principal: *</label>
		<div class="col-sm-9">
			<select class="form-control" id="ciudad" name="ciudad" required autofocus>
				<option value="">Seleccione</option>
				@foreach($ciudades as $ciudad)
					<option value="{{$ciudad->id}}" {{($usuario->ciudad_id == $ciudad->id ? "selected":"") }}>{{ $ciudad->nombreCiudad }}</option>
				@endforeach
			</select>
		</div>
	</div>
</template>