@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    Cordial saludo señor (a): Administrador
    El funcionario {{ $data['offiialsSchedule']['users']['name'] }} ha agregado una toma de muestra especial asignada para el día {{ $data['offiialsSchedule']['sampling_date'] }},  en el punto {{ $data['offiialsSchedule']['lcSamplePoints']['point_location'] }} con la observación {{ $data['offiialsSchedule']['observation'] }}

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        Por favor haga caso omiso a éste mensaje, ya que el sistema de gestion de estudios del laboratorio LECA se encuentra en etapa de implementacíon y pruebas.
        Para cualquier información comuníquese al Tel: 7499121
        @endcomponent
    @endslot
@endcomponent
