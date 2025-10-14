@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo,

El avance de la actividad denominada {{ $data["goal_name"] ? $data["goal_name"] : 'N/E' }}, correspondiente a la oportunidad
de mejora {{ $data["name_opportunity_improvement"] ? $data["name_opportunity_improvement"] : 'N/E' }}, del plan de mejoramiento {{ $data["name_improvement_plan"] ? $data["name_improvement_plan"] : 'N/E' }} con el
consecutivo {{ $data["no_improvement_plan"] ? $data["no_improvement_plan"] : 'N/E' }}, ha sido aprobado por el evaluador. Si desea revisar el
avance, ingrese al sistema con sus credenciales.

<a href="{{ url('/')}}">Ingresar al sistema</a>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
