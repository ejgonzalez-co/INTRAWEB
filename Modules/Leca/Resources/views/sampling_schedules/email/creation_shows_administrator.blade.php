@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    @php
        $fecha = date("Y-m-d", strtotime($data['offiialsSchedule']['sampling_date']));
    @endphp

    Cordial saludo señor (a): {{ $data['offiialsSchedule']['users']['name'] }}
    El administrador de LECA le ha asignado una toma de muestra para el día {{ $fecha }}, en el punto {{ $data['offiialsSchedule']['lcSamplePoints']['point_location'] }}.
    

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        Por favor haga caso omiso a éste mensaje, ya que el sistema de gestion de estudios del laboratorio LECA se encuentra en etapa de implementacíon y pruebas.
        Para cualquier información comuníquese al Tel: 7499121
        @endcomponent
    @endslot
@endcomponent
