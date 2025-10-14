@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   # Reciba un cordial saludo  {{ $data->name}}
   El proveedor {{$data['continue']['assigned_user_name']}} ha cambiado el estado de la solicitud con consecutivo {{$data['request']['expiration_date']}} a 'Pre-Cierre Adicionalmente, se ha registrado un mantenimiento asociado al activo correspondiente.   @component('mail::button', ['url' => config('app.url')])
   Ingrese aquí para gestionar la solicitud.
   @endcomponent
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
