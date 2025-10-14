
<button type="{{ $type ?? 'button' }}" class="btn {{ $class ?? 'btn-light' }} btnPanel" onclick="{{ $onclick ?? '' }}">
   @if(isset($imagen))
      <img  src="{{ config('app.url') }}/adjunto/app/iconos/{{ $imagen }}"> 
   @else
      <i class="{{ $fontawesome ?? '' }}"></i>
   @endif
   <p>{{ $text }}</p>
</button>