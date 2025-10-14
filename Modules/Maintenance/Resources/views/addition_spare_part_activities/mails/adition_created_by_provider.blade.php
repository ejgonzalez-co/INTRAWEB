@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

Cordial saludo, {{ $data["admin_name"] }},

El proveedor {{ $data["provider_name"] }} ha enviado una solicitud de adición relacionada con la identificación de necesidad N.° {{ $data["consecutive"] }}, la cual requiere su revisión y aprobación.

Por favor, ingrese al sistema con sus credenciales para gestionar esta solicitud.

🔗 Acceso al sistema: <a href="{{ $data["url_register"] }}">Ir a Intraweb</a>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Este correo es de tipo informativo y por lo tanto, le pedimos no responda a éste mensaje..
        @endcomponent
    @endslot
@endcomponent
