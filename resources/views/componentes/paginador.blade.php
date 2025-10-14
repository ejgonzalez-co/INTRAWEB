<div class="card">
      <div class="card-body">
			<div class="input-group col-sm-12 justify-content-center">
				<div class="input-group col-sm-3 mb-3">
					<div class="input-group-prepend">
						<label class="input-group-text" for="numero_registros">Cantidad a mostrar</label>
					</div>
					<select class="custom-select" id="numero_registros" v-model="registrosMostrar" @change="{{ $listarPaginador ??  n_a }} ">
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="15">15</option>
							<option value="20">20</option>
							<option value="25">25</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
					</select>
				</div>
			</div>
			{{ $slot }}
			<!--<nav>
				<ul class="pagination pagination  justify-content-center">
					<li class="page-item" v-if="pagination.current_page > 1">
						<a href="#" @click.prevent="changePage(1)">
							<span class="page-link">Inicio</span>
						</a>
					</li>
					<li class="page-item" v-if="pagination.current_page > 1">
						<a href="#" @click.prevent="changePage(pagination.current_page - 1)">
							<span class="page-link">Atras</span>
						</a>
					</li>
					<li class="page-item" v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
						<a href="#" @click.prevent="changePage(page)">
							<span class="page-link">@{{ page }}</span>
						</a>
					</li>
					<li class="page-item" v-if="pagination.current_page < pagination.last_page">
						<a href="#" @click.prevent="changePage(pagination.current_page + 1)">
							<span class="page-link">Siguiente</span>
						</a>
					</li>
					<li class="page-item" v-if="pagination.current_page < pagination.last_page">
						<a href="#" @click.prevent="changePage(pagination.last_page)">
							<span class="page-link">Final</span>
						</a>
					</li>
				</ul>
				<div class="text-center text-dark">
					Página <span v-text="pagination.current_page"></span> de <span v-text="pagination.last_page"></span>
				</div>
			</nav>-->
		</div>
</div>

