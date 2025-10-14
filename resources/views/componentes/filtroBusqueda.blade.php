
<div class="filtros ">
	<!--<div class="row no-gutters">
		<div class="col">
				<input class="form-control border-secondary border-right-0 rounded-0" type="search" value="search" id="example-search-input4">
		</div>
		<div class="col-auto">
				<button class="btn btn-outline-secondary border-left-0 rounded-0 rounded-right" type="button">
					<i class="fa fa-search"></i>
				</button>
		</div>
	</div>-->
	<!--
	<div class="row">
	
		<div class="input-group col-sm-4">
			<button type="submit" class="fa fa-search form-control-feedback"></button>
			
			<input type="text" class="form-control py-2 border-right-0 border" placeholder="buscar" name="nomb_categoria">
			<span class="input-group-append">
				<button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split border border-left-0 border-right-0 rounded-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="sr-only">Toggle Dropdown</span>
				</button>
        
   	 </span>
		</div>


	<div class="input-group col-sm-4">
	<div class="input-group-append" href="#filtros" data-toggle="collapse">
			<button type="submit" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split">
			</button>
		</div>
		<input type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="Buscar">
		<div class="input-group-append" href="#filtros" data-toggle="collapse">
			<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"   aria-expanded="false" aria-controls="filtros" data-toggle="tooltip" title="Ver filtros de búsqueda">
			</button>
		</div>
	</div>-->
	<a class="font-weight-bold text-dark" data-toggle="collapse" href="#{{ $buscarId ?? 'filtros' }}" role="button" aria-expanded="false" aria-controls="{{ $buscarId ?? 'filtros' }}">
		<i class="fas fa-chevron-circle-down"></i> 
		<span id="mostrarFiltros">Mostrar opciones de búsqueda</span>
  	</a>


	<div class="collapse" id="{{ $buscarId ?? 'filtros' }}">
		<div class="card-body">
			<div class="col-sm-7" role="alert">
				<small class="form-text text-muted">
					<b>Ayuda:</b> Los siguientes campos son opciones de búsqueda para encontrar información, puede buscar con un solo campo o combinarlos para ajustar el resultado esperado. Al final haga clic en el botón buscar.
				</small>
			</div>
			<br>
			{{ $slot }}
		</div>
	</div>
</div>
<br>