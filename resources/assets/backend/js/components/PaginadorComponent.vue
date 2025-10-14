<template>
	<nav>
		<ul class="pagination  justify-content-center">
			<li class="page-item" v-if="pagination.current_page > 1">
				<a href="javascript:void(0)"  v-on:click.prevent="changePage(1)">
					<span class="page-link">Inicio</span>
				</a>
			</li>
			<li class="page-item" v-if="pagination.current_page > 1">
				<a href="javascript:void(0)"  v-on:click.prevent="changePage(pagination.current_page - 1)">
					<span class="page-link"><i class="fas fa-angle-double-left"></i></span>
				</a>
			</li>
			<li class="page-item" v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
				<a href="javascript:void(0)"  v-on:click.prevent="changePage(page)">
					<span class="page-link">{{ page }}</span>
				</a>
			</li>
			<li class="page-item" v-if="pagination.current_page < pagination.last_page">
				<a href="javascript:void(0)"  v-on:click.prevent="changePage(pagination.current_page + 1)">
					<span class="page-link"><i class="fas fa-angle-double-right"></i></span>
				</a>
			</li>
			<li class="page-item" v-if="pagination.current_page < pagination.last_page">
				<a href="javascript:void(0)"  v-on:click.prevent="changePage(pagination.last_page)">
					<span class="page-link">Final</span>
				</a>
			</li>
		</ul>
		<div class="text-center text-dark">
			Página <span v-text="pagination.current_page"></span> de <span v-text="pagination.last_page"></span>
		</div>
	</nav>
</template>
<script>
	export default{
		props: {

			pagination: {
				type: Object,
				required: true
			},

			offset: {
				type: Number,
				default: 1
			}
		},
    computed: {
		 
		isActived: function() {
			return this.pagination.current_page;
		},

      pagesNumber() {
			if(!this.pagination.to){
				return [];
			}

			let from = this.pagination.current_page - this.offset; 
			if(from < 1){
				from = 1;
			}

			let to = from + (this.offset * 2); 
			if(to >= this.pagination.last_page){
				to = this.pagination.last_page;
			}

			let pagesArray = [];
			while(from <= to){
				pagesArray.push(from);
				from++;
			}

			return pagesArray;
      }
    },
    methods : {
      changePage(page) {
        this.pagination.current_page = page;
        this.$emit('paginate');
      }
    }
  }
</script>

<style>
	@media (max-width: 575.98px) {
		.pagination{
			font-size: 12px;
		}
	}
</style>