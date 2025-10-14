@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   @php

   use Carbon\Carbon;

   $cuatomer =  DB:: table('lc_customers')->where('id',$data->lc_customers_id)->get()->first();
   $fecha = Carbon::parse($data->date_report)->format('d-m-Y')   ; 
                                     
@endphp 

Cordial saludo {{$data->name_customer }}

El informe de caracterización del agua que se tomó el día {{ $fecha }}, se encuentra en estado: Informe finalizado. Para consultar el informe generado por el Laboratorio de Calidad del Agua -LECA, lo puede realizar ingresando <a href="https://app.intraepa.gov.co/login-costumer">Aqui</a>.<br><br>
Su credencial de acceso es: <strong> {{ $cuatomer->pin }}</strong>

{{-- @foreach ( $attached as $attachment)

Descripción de adjuntos: {{ $attachment->comments }} <br>
Archivos adjuntos:<a href="{{ asset('storage') }}/{{ $attachment->attachment }}">Ver adjunto<br><br></a>

@endforeach --}}


   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent

