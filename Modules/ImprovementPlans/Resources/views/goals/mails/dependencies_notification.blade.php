@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo {{ $data["name"] ? $data["name"] : 'funcionario' }},

El proceso {{ $data["name"] ? $data["name"] : 'funcionario' }}(concatenar el proceso responsable) ha incluidos en su plan de mejoramiento {{ $data["name"] ? $data["name"] : 'funcionario' }}(concatenar mejora o no conformidad) y requiere de su participación en este proceso de ejecución

<a href="{{ url('') }}">Acceder al sistema</a>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
