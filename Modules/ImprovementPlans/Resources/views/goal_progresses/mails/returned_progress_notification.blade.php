@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo,

El avance de la actividad denominada {{ $data["goal_activity_name"] ? $data["goal_activity_name"] : 'N/E' }}, correspondiente a la oportunidad
de mejora {{ $data["name_opportunity_improvement"] ? $data["name_opportunity_improvement"] : 'N/E' }} del plan de mejoramiento {{ $data["name_improvement_plan"] ? $data["name_improvement_plan"] : 'N/E' }} con el
consecutivo {{ $data["no_improvement_plan"] ? $data["no_improvement_plan"] : 'N/E' }}, ha sido devuelto por el evaluador con la siguiente
observación: {{ $data["observation"] ? $data["observation"] : 'N/E' }}.

<br><br>
Ingrese al aplicativo Planes de Mejoramiento Institucional con sus credenciales para realizar los
ajustes solicitados por el evaluador y envíe de nuevo el avance.

<a href="{{ url('/')}}">Ingresar al sistema</a>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
