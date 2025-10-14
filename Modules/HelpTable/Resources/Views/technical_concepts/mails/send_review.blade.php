@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo {{ $data["name"] }},
Hay una solicitud de concepto técnico pendiente de su revisión, ingrese con usuario y contraseña a la <a href="https://intranet.armenia.gov.co/"> Intranet Armenia</a> 
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
