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
Cordial saludo,<br> 
La credencial única asignada por el Laboratorio de Ensayo de Calidad del Agua - LECA es: <br> {{$data->pin}} 
   {{-- Footer --}}
   @slot('footer')
      @component('mail::footer')
         Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
      @endcomponent
   @endslot
@endcomponent
