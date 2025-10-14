@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo {{ $data["name"] }},

El concepto técnico solicitado ya ha sido aprobado por la Secretaría TIC.  Para consultar lo puede realizar ingresando a la<a href="https://intranet.armenia.gov.co/"> Intranet Armenia</a> 
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
