<li class="{{ Request::is('categories*') ? 'active' : '' }}">
   <a href="{{ route('categories.index') }}"><i class="fa fa-edit"></i><span>@lang('Categories')</span></a>
</li>

<li class="{{ Request::is('tags*') ? 'active' : '' }}">
   <a href="{{ route('tags.index') }}"><i class="fa fa-edit"></i><span>@lang('Tags')</span></a>
</li>

<li class="{{ Request::is('posts*') ? 'active' : '' }}">
   <a href="{{ route('posts.index') }}"><i class="fa fa-edit"></i><span>@lang('Posts')</span></a>
</li>
