<button type="button" @click="{{ $click ?? '' }}" class="{{ $class ?? '' }}" data-toggle="tooltip" data-placement="top" title="{{ $titulo ?? '' }}">
   @if(isset($imagen))
      <img  src="{{ config('app.url') }}/components/com_formasonline/css/images/48/{{ $imagen }}"> 
   @else
      <i class="{{ $fontawesome ?? '' }}"></i>
   @endif
</button>