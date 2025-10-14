@component('mail::layout')
   {{-- Header --}}
   @slot('header')
      @component('mail::header', ['url' => config('app.url')])
         {{ config('app.name') }}
      @endcomponent
   @endslot
   @php

   use Carbon\Carbon;
   $attached = DB::table('lc_rm_attachment')->where("lc_rm_report_management_id", $data->id)
                                          ->where("status", 'Activo') 
                                          ->get();
                         
                            $fecha = Carbon::parse($data->date_report)->format('d-m-Y')         
                                     
@endphp 
Cordial saludo {{ $data->name_customer }}

El informe de caracterización del agua que se tomo el día {{ $fecha }}, se encuentra es estado Informe finalizado, el cual puede ser consultado en el documento adjunto. 

@foreach ( $attached as $attachment)

Descripción de adjuntos: {{ $attachment->comments }} <br>
Archivos adjuntos:<a href="{{ asset('storage') }}/{{ $attachment->attachment }}">Ver adjunto<br><br></a>

@endforeach


   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
