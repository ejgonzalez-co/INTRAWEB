<button type="{{ $type ?? 'button' }}" class="btn btn-{{ $class ?? 'secondary' }}"  data-toggle="{{ $data_toggle ?? '' }}" data-target="{{ $data_target ?? '' }}" {{ $onclick ?? '' }} @click="{{ $onclickVue ?? '' }}">
   @if(isset($imagen))
      <img  src="{{ config('app.url') }}/components/com_formasonline/css/images/48/{{ $imagen }}"> 
   @else
      <i class="{{ $fontawesome ?? '' }}"></i>
   @endif
   &nbsp;&nbsp;<span v-text="{{ $v_text ?? 'n_a' }}"></span>{{ $text ?? '' }} 
</button>