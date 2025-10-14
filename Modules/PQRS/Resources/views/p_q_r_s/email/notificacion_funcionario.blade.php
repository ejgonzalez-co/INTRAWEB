@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot

   # Estimado/a {{ $data["name"] ?? 'Funcionario' }},


   {!! $data['mensaje'] !!}

   <p>Si desea marcar este correo como leído, puede hacerlo aquí: <br>👉<a style="text-align:center;font-size:15px;"href="{{ config('app.url') }}/pqrs/watch-archives?id={{ $data['id'] }}&id_mail={{ $data['trackingId'] }}"> Haga clic aquí para marcar este correo electrónico como leído.</a></p>

   Para más detalles, ingrese al sistema a través del siguiente enlace:

   👉<a href="{{ config('app.url') . ($data['link'] ?? '') }}" title="Haga clic aquí para gestionar su solicitud"> Gestionar mi solicitud</a>

   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es informativo. No responda a este mensaje. Si necesita ayuda, por favor contacte a nuestro soporte.
      @endcomponent
   @endslot
@endcomponent
