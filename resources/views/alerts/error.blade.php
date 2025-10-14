@if(Session::has('error'))
<div class="alert alert-error alert-dismissible fade show" role="alert">
	{{Session::get('error')}}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif