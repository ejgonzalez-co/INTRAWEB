<div class="form-group">
              <label for="name">Rol:</label>
              <input type="text" class="form-control" name="name" value="{{$role->name}}"/>
          </div>

<div class="form-group">
		<ul class="list-unstyled">
			@foreach($permissions as $permission)
			<li>
				<div class="form-check">
					<input class="form-check-input" name="permissions[]" type="checkbox" value="{{ $permission->id }}" id="{{ $permission->slug }}" @if($role->permissions->contains($permission)) checked @endif>
					<label class="form-check-label" for="{{ $permission->slug }}">
						{{ $permission->name }}
					</label>
					<em>({{ $permission->description }})</em>
				</div>
			</li>
			@endforeach
		</ul>
	</div>
<div class="form-group">
	
</div>