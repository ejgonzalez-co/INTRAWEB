@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot


El funcionario {{ $data["functionary_name"] ? $data["functionary_name"] : 'funcionario' }} le ha asignado una actividad denominada {{ $data["activity_name"] ? $data["activity_name"] : 'N/E' }} del Plan de Mejoramiento {{ $data["improvement_plan_name"] ? $data["improvement_plan_name"] : 'N/E' }},con consecutivo {{ $data["no_improvement_plan"] ? $data["no_improvement_plan"] : 'N/E' }}, en la cual usted es el responsable designado para su cumplimiento.Por esta razón,deberá ingresar al sistema con sus credenciales e iniciar
la carga de evidencias,las cuales serán revisadas por el evaluador del plan,{{ $data["evaluator_name"] ? $data["evaluator_name"] : 'N/E' }}.Esta actividad equivale al {{ $data["activity_percentage"] ? $data["activity_percentage"] : 'N/E' }}% del total necesario
para cumplir la meta {{ $data["goal_name"] ? $data["goal_name"] : 'N/E' }}.

Tenga encuenta que la fecha de inicio de esta actividad es el {{ $data["activity_start_date"] ? $data["activity_start_date"] : 'N/E' }} y la fecha final para presentar las evidencias al 100% es el {{ $data["activity_end_date"] ? $data["activity_end_date"] : 'N/E' }}.

<a href="{{ url('') }}">Acceder al sistema</a>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
